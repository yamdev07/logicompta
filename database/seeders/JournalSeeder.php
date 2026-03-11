<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\GeneralAccounting\Journal;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $journals = [
            ['code' => 'CAI', 'name' => 'Journal de Caisse', 'description' => 'Opérations en espèces'],
            ['code' => 'BNQ', 'name' => 'Journal de Banque', 'description' => 'Opérations bancaires'],
            ['code' => 'PAIE', 'name' => 'Journal de Paie', 'description' => 'Enregistrement de la paie'],
            ['code' => 'ACH', 'name' => 'Journal des Achats', 'description' => 'Enregistrement des factures fournisseurs'],
            ['code' => 'VEN', 'name' => 'Journal des Ventes', 'description' => 'Enregistrement des factures clients'],
            ['code' => 'SERV', 'name' => 'Journal des prestations de services', 'description' => 'Enregistrement des prestations effectuées'],
            ['code' => 'SAL', 'name' => 'Journal des salaires', 'description' => 'Enregistrement des salaires'],
            ['code' => 'OD', 'name' => 'Journal des Opérations diverses', 'description' => 'Écritures de régularisation et autres'],
        ];

        foreach ($journals as $journal) {
            Journal::updateOrCreate(['code' => $journal['code']], $journal);
        }

        $this->command->info(count($journals) . " journaux configurés conformément au cahier des charges !");
    }
}
