<?php

namespace App\Http\Controllers\GeneralAccounting;

use App\Http\Controllers\Controller;
use App\Models\GeneralAccounting\Account;
use App\Models\GeneralAccounting\Journal;
use App\Models\GeneralAccounting\JournalEntry;
use App\Models\GeneralAccounting\JournalEntryLine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JournalController extends Controller
{
    public function index()
    {
        // Pour le moment, permettre l'accès sans authentification
        // TODO: Ajouter l'authentification plus tard
        $entries = JournalEntry::with(['journal', 'lines.account'])
            ->orderBy('date', 'desc')
            ->orderBy('id', 'desc')
            ->paginate(50);
            
        return view('accounting.journal.index', compact('entries'));
    }

    private function getUserFromToken()
    {
        // Pour les requêtes web, essayer de récupérer l'utilisateur depuis session
        // Sinon, essayer le token Sanctum
        try {
            // D'abord essayer via session Laravel standard
            if (session()->has('comptafriq_user_id')) {
                return \App\Models\User::find(session('comptafriq_user_id'));
            }
            
            // Ensuite essayer via token Sanctum
            $token = request()->bearerToken() ?: request()->header('X-Auth-Token');
            if (!$token) {
                $authHeader = request()->header('Authorization');
                if ($authHeader && str_starts_with($authHeader, 'Bearer ')) {
                    $token = substr($authHeader, 7);
                }
            }
            
            if ($token) {
                $model = \Laravel\Sanctum\PersonalAccessToken::findToken($token);
                return $model ? $model->tokenable : null;
            }
        } catch (\Exception $e) {
            // En cas d'erreur, retourner null
        }
        
        return null;
    }

    public function ledger(Request $request, $account_id = null)
    {
        $accounts = Account::orderBy('code_compte')->get()->groupBy('classe');
        $selectedAccount = $account_id ? Account::find($account_id) : null;
        $selectedClass = $request->query('class');
        $mode = $request->query('mode', 'single'); // 'single', 'class', 'all'
        
        $data = [];

        if ($mode === 'all') {
            $data = Account::with(['entryLines.entry.journal'])
                ->orderBy('code_compte')
                ->get()
                ->filter(fn($acc) => $acc->entryLines->count() > 0);
        } elseif ($mode === 'class' && $selectedClass) {
            $data = Account::with(['entryLines.entry.journal'])
                ->where('classe', $selectedClass)
                ->orderBy('code_compte')
                ->get()
                ->filter(fn($acc) => $acc->entryLines->count() > 0);
        } elseif ($selectedAccount) {
            $lines = JournalEntryLine::with('entry.journal')
                ->where('account_id', $selectedAccount->id)
                ->join('journal_entries', 'journal_entry_lines.journal_entry_id', '=', 'journal_entries.id')
                ->orderBy('journal_entries.date', 'asc')
                ->select('journal_entry_lines.*')
                ->get();
            $data = collect([
                (object)[
                    'id' => $selectedAccount->id,
                    'code_compte' => $selectedAccount->code_compte,
                    'libelle' => $selectedAccount->libelle,
                    'entryLines' => $lines
                ]
            ]);
        }

        return view('accounting.ledger', compact('accounts', 'selectedAccount', 'data', 'mode', 'selectedClass'));
    }

    public function create()
    {
        $user = $this->getUserFromToken();
        if (!$user) {
            return redirect()->route('accounting.ledger')->with('error', 'Vous devez être connecté pour créer des écritures.');
        }
        
        if (!$user->entreprise_id) {
            return redirect()->route('accounting.ledger')->with('error', 'Vous devez être associé à une entreprise pour créer des écritures. Utilisez le formulaire ci-dessous pour rejoindre une entreprise.');
        }
        
        $journals = Journal::all();
        $accounts = Account::orderBy('code_compte')->get()->groupBy('classe');
        
        // Prédiction du prochain numéro de pièce (format séquentiel simple)
        $latestEntry = JournalEntry::where('entreprise_id', $user->entreprise_id)->latest()->first();
        $nextNum = $latestEntry ? intval(preg_replace('/[^0-9]/', '', $latestEntry->numero_piece)) + 1 : 1;
        $nextPieceNumber = str_pad($nextNum, 6, '0', STR_PAD_LEFT);

        return view('accounting.journal.create', compact('journals', 'accounts', 'nextPieceNumber'));
    }

    public function store(Request $request)
    {
        $minDate = now()->subDays(5)->startOfDay();
        $maxDate = now()->endOfMonth();

        $request->validate([
            'journal_id' => 'required|exists:journals,id',
            'date' => [
                'required',
                'date',
                'after_or_equal:' . $minDate->format('Y-m-d'),
                'before_or_equal:' . $maxDate->format('Y-m-d'),
            ],
            'libelle' => 'required|string|max:255',
            'lines' => 'required|array|min:2',
            'lines.*.account_id' => 'required|exists:accounts,id',
            'lines.*.debit' => 'nullable|numeric|min:0',
            'lines.*.credit' => 'nullable|numeric|min:0',
        ], [
            'date.after_or_equal' => 'La date ne peut pas être antérieure à 5 jours.',
            'date.before_or_equal' => 'L\'écriture ne peut pas être enregistrée au-delà du mois en cours.',
            'lines.*.account_id.required' => 'Le compte est obligatoire pour chaque ligne.',
            'lines.*.account_id.exists' => 'Le compte sélectionné est invalide.',
            'lines.*.debit.numeric' => 'Le montant débité doit être un nombre.',
            'lines.*.debit.min' => 'Le montant au débit ne peut pas être négatif.',
            'lines.*.credit.numeric' => 'Le montant crédité doit être un nombre.',
            'lines.*.credit.min' => 'Le montant au crédit ne peut pas être négatif.',
        ]);

        $totalDebit = collect($request->lines)->sum('debit');
        $totalCredit = collect($request->lines)->sum('credit');

        if (abs($totalDebit - $totalCredit) > 0.001) {
            return back()->withErrors(['balance' => 'L\'écriture n\'est pas équilibrée (Total Débit: ' . $totalDebit . ', Total Crédit: ' . $totalCredit . ')'])->withInput();
        }

        if ($totalDebit <= 0) {
            return back()->withErrors(['amount' => 'Le montant de l\'écriture doit être supérieur à zéro.'])->withInput();
        }

        try {
            DB::beginTransaction();

            // Récupérer l'entreprise de l'utilisateur connecté via token
            $user = $this->getUserFromToken();
            if (!$user || !$user->entreprise_id) {
                throw new \Exception('Vous devez être associé à une entreprise pour créer des écritures.');
            }

            // Génération d'un numéro de pièce séquentiel automatique
            $latestEntry = JournalEntry::where('entreprise_id', $user->entreprise_id)->latest()->first();
            $nextNum = $latestEntry ? intval(preg_replace('/[^0-9]/', '', $latestEntry->numero_piece)) + 1 : 1;
            $numeroPiece = str_pad($nextNum, 6, '0', STR_PAD_LEFT);

            $entry = JournalEntry::create([
                'journal_id' => $request->journal_id,
                'numero_piece' => $numeroPiece,
                'date' => $request->date,
                'libelle' => $request->libelle,
                'entreprise_id' => $user->entreprise_id,
            ]);

            foreach ($request->lines as $line) {
                if (($line['debit'] ?? 0) > 0 || ($line['credit'] ?? 0) > 0) {
                    JournalEntryLine::create([
                        'journal_entry_id' => $entry->id,
                        'account_id' => $line['account_id'],
                        'debit' => $line['debit'] ?? 0,
                        'credit' => $line['credit'] ?? 0,
                        'libelle' => $line['libelle'] ?? $request->libelle,
                    ]);
                }
            }

            DB::commit();
            return redirect()->route('accounting.journal.create')->with('success', 'Écriture enregistrée avec succès ! (Pièce N° ' . $numeroPiece . ')');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['error' => 'Erreur lors de l\'enregistrement : ' . $e->getMessage()])->withInput();
        }
    }

    public function balance()
    {
        $accounts = Account::with(['entryLines'])->get();
        
        $balanceData = [];
        $grandTotal = [
            'mouv_debit' => 0, 'mouv_credit' => 0,
            'fin_debit' => 0, 'fin_credit' => 0
        ];

        // Groupement par classe (1 à 9)
        for ($c = 1; $c <= 9; $c++) {
            $classAccounts = $accounts->filter(fn($a) => $a->classe == $c);
            if ($classAccounts->isEmpty()) continue;

            $classData = [
                'label' => 'Total Classe ' . $c,
                'groups' => [], // Contiendra les sous-groupes (ex: 10, 11...)
                'class_totals' => ['mouv_debit' => 0, 'mouv_credit' => 0, 'fin_debit' => 0, 'fin_credit' => 0]
            ];

            // On groupe les comptes de la classe par les 2 premiers chiffres
            $groupedByPrefix = $classAccounts->groupBy(function($acc) {
                return substr(str_pad($acc->code_compte, 2, '0', STR_PAD_RIGHT), 0, 2);
            })->sortKeys();

            foreach ($groupedByPrefix as $prefix => $accs) {
                $groupData = [
                    'prefix' => str_pad($prefix, 6, '0', STR_PAD_RIGHT),
                    'accounts' => [],
                    'group_totals' => ['mouv_debit' => 0, 'mouv_credit' => 0, 'fin_debit' => 0, 'fin_credit' => 0]
                ];

                foreach ($accs as $account) {
                    $mouv_debit = $account->entryLines->sum('debit');
                    $mouv_credit = $account->entryLines->sum('credit');

                    if ($mouv_debit == 0 && $mouv_credit == 0) continue;

                    $solde = $mouv_debit - $mouv_credit;
                    $fin_debit = $solde > 0 ? $solde : 0;
                    $fin_credit = $solde < 0 ? abs($solde) : 0;

                    $accRow = [
                        'code' => str_pad($account->code_compte, 9, '0', STR_PAD_RIGHT),
                        'libelle' => $account->libelle,
                        'mouv_debit' => $mouv_debit,
                        'mouv_credit' => $mouv_credit,
                        'fin_debit' => $fin_debit,
                        'fin_credit' => $fin_credit,
                    ];

                    $groupData['accounts'][] = $accRow;

                    // Cumul Groupe
                    $groupData['group_totals']['mouv_debit'] += $mouv_debit;
                    $groupData['group_totals']['mouv_credit'] += $mouv_credit;
                    $groupData['group_totals']['fin_debit'] += $fin_debit;
                    $groupData['group_totals']['fin_credit'] += $fin_credit;
                }

                if (!empty($groupData['accounts'])) {
                    $classData['groups'][$prefix] = $groupData;
                    
                    // Cumul Classe
                    $classData['class_totals']['mouv_debit'] += $groupData['group_totals']['mouv_debit'];
                    $classData['class_totals']['mouv_credit'] += $groupData['group_totals']['mouv_credit'];
                    $classData['class_totals']['fin_debit'] += $groupData['group_totals']['fin_debit'];
                    $classData['class_totals']['fin_credit'] += $groupData['group_totals']['fin_credit'];
                }
            }

            if (!empty($classData['groups'])) {
                $balanceData[$c] = $classData;
                
                // Cumul Grand Total
                $grandTotal['mouv_debit'] += $classData['class_totals']['mouv_debit'];
                $grandTotal['mouv_credit'] += $classData['class_totals']['mouv_credit'];
                $grandTotal['fin_debit'] += $classData['class_totals']['fin_debit'];
                $grandTotal['fin_credit'] += $classData['class_totals']['fin_credit'];
            }
        }

        return view('accounting.balance', compact('balanceData', 'grandTotal'));
    }

    public function bilan()
    {
        $accounts = Account::with(['entryLines'])->get();
        
        $actif = collect();
        $passif = collect();

        foreach ($accounts as $acc) {
            $debit = $acc->entryLines->sum('debit');
            $credit = $acc->entryLines->sum('credit');
            $soldeDebit = $debit - $credit;
            
            if ($soldeDebit == 0) continue;

            // Classes de Bilan : 1, 2, 3, 4, 5
            if ($acc->classe == 2 || $acc->classe == 3) {
                // Toujours à l'Actif (même si négatif)
                $actif->push(['libelle' => $acc->libelle, 'solde' => $soldeDebit]);
            } elseif ($acc->classe == 1) {
                // Toujours au Passif (on inverse le signe car c'est une ressource)
                $passif->push(['libelle' => $acc->libelle, 'solde' => -$soldeDebit]);
            } elseif ($acc->classe == 4 || $acc->classe == 5) {
                // Comptes mixtes : on les place selon leur nature de solde
                if ($soldeDebit > 0) {
                    $actif->push(['libelle' => $acc->libelle, 'solde' => $soldeDebit]);
                } else {
                    $passif->push(['libelle' => $acc->libelle, 'solde' => abs($soldeDebit)]);
                }
            }
        }

        // Calcul du Résultat Net (Produits [7,8] - Charges [6,8])
        $totalCharges = $accounts->whereIn('classe', [6, 8])->sum(function($a) {
            $solde = $a->entryLines->sum('debit') - $a->entryLines->sum('credit');
            // En classe 8, seuls certains comptes sont des charges (81, 83, 85, 87, 89)
            if ($a->classe == 8 && !in_array(substr($a->code_compte, 0, 2), ['81', '83', '85', '87', '89'])) return 0;
            return $solde > 0 ? $solde : 0;
        });

        $totalProduits = $accounts->whereIn('classe', [7, 8])->sum(function($a) {
            $solde = $a->entryLines->sum('credit') - $a->entryLines->sum('debit');
            // En classe 8, seuls certains comptes sont des produits (82, 84, 86, 88)
            if ($a->classe == 8 && !in_array(substr($a->code_compte, 0, 2), ['82', '84', '86', '88'])) return 0;
            return $solde > 0 ? $solde : 0;
        });

        $resultatNet = $totalProduits - $totalCharges;

        // On ajoute le résultat au Passif pour équilibrer
        $passif->push([
            'libelle' => $resultatNet >= 0 ? 'RÉSULTAT NET (BÉNÉFICE)' : 'RÉSULTAT NET (PERTE)',
            'solde' => $resultatNet,
            'is_resultat' => true
        ]);

        return view('accounting.bilan', compact('actif', 'passif'));
    }

    public function resultat()
    {
        $accounts = Account::with(['entryLines'])->get();

        $data = [
            'charges' => ['total' => 0, 'groups' => []],
            'produits' => ['total' => 0, 'groups' => []]
        ];

        // Traitement des Charges (Classe 6 et comptes de charges Classe 8)
        $chargeAccounts = $accounts->filter(function($acc) {
            if ($acc->classe == 6) return true;
            if ($acc->classe == 8 && in_array(substr($acc->code_compte, 0, 2), ['81', '83', '85', '87', '89'])) return true;
            return false;
        });
        $groupedCharges = $chargeAccounts->groupBy(function($acc) {
            return substr(str_pad($acc->code_compte, 2, '0', STR_PAD_RIGHT), 0, 2);
        })->sortKeys();

        foreach ($groupedCharges as $prefix => $accs) {
            $groupTotal = 0;
            $accountsList = [];
            foreach ($accs as $acc) {
                $solde = $acc->entryLines->sum('debit') - $acc->entryLines->sum('credit');
                if ($solde != 0) {
                    $accountsList[] = [
                        'code' => str_pad($acc->code_compte, 9, '0', STR_PAD_RIGHT),
                        'libelle' => $acc->libelle,
                        'montant' => $solde
                    ];
                    $groupTotal += $solde;
                }
            }
            if (!empty($accountsList)) {
                $data['charges']['groups'][$prefix] = [
                    'prefix' => str_pad($prefix, 6, '0', STR_PAD_RIGHT),
                    'total' => $groupTotal,
                    'accounts' => $accountsList
                ];
                $data['charges']['total'] += $groupTotal;
            }
        }

        // Traitement des Produits (Classe 7 et comptes de produits Classe 8)
        $produitAccounts = $accounts->filter(function($acc) {
            if ($acc->classe == 7) return true;
            if ($acc->classe == 8 && in_array(substr($acc->code_compte, 0, 2), ['82', '84', '86', '88'])) return true;
            return false;
        });
        $groupedProduits = $produitAccounts->groupBy(function($acc) {
            return substr(str_pad($acc->code_compte, 2, '0', STR_PAD_RIGHT), 0, 2);
        })->sortKeys();

        foreach ($groupedProduits as $prefix => $accs) {
            $groupTotal = 0;
            $accountsList = [];
            foreach ($accs as $acc) {
                $solde = $acc->entryLines->sum('credit') - $acc->entryLines->sum('debit');
                if ($solde != 0) {
                    $accountsList[] = [
                        'code' => str_pad($acc->code_compte, 9, '0', STR_PAD_RIGHT),
                        'libelle' => $acc->libelle,
                        'montant' => $solde
                    ];
                    $groupTotal += $solde;
                }
            }
            if (!empty($accountsList)) {
                $data['produits']['groups'][$prefix] = [
                    'prefix' => str_pad($prefix, 6, '0', STR_PAD_RIGHT),
                    'total' => $groupTotal,
                    'accounts' => $accountsList
                ];
                $data['produits']['total'] += $groupTotal;
            }
        }

        $profit = $data['produits']['total'] - $data['charges']['total'];

        return view('accounting.resultat', [
            'charges' => $data['charges'],
            'produits' => $data['produits'],
            'totalCharges' => $data['charges']['total'],
            'totalProduits' => $data['produits']['total'],
            'profit' => $profit
        ]);
    }

    public function show($id)
    {
        $entry = JournalEntry::with(['lines.account', 'journal'])->findOrFail($id);
        return view('accounting.journal.show', compact('entry'));
    }

    public function help()
    {
        return view('accounting.help');
    }
}
