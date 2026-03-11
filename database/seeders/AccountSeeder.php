<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralAccounting\Account;
use Illuminate\Support\Facades\File;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $filePath = base_path('modelsPlanSYSCOHADA.txt');
        
        if (!File::exists($filePath)) {
            $this->command->error("Le fichier $filePath est introuvable.");
            return;
        }

        $content = File::get($filePath);
        $lines = explode("\n", $content);
        
        $currentClasse = null;
        $count = 0;

        foreach ($lines as $line) {
            $line = trim($line);
            
            // Détection de la classe (ex: CLASSE 1 : COMPTES DE RESSOURCES DURABLES)
            if (preg_match('/CLASSE\s+(\d+)/i', $line, $matches)) {
                $currentClasse = $matches[1];
                continue;
            }

            // Détection d'un compte (ex: COMPTE 101 : Capital social)
            if (preg_match('/COMPTE\s+([\d0-9]+)\s*:\s*(.*)/i', $line, $matches)) {
                $code = $matches[1];
                $libelle = trim($matches[2]);

                if ($currentClasse) {
                    Account::updateOrCreate(
                        ['code_compte' => $code],
                        [
                            'classe' => $currentClasse,
                            'libelle' => $libelle
                        ]
                    );
                    $count++;
                }
            }
        }

        $this->command->info("$count comptes importés avec succès !");
    }
}
