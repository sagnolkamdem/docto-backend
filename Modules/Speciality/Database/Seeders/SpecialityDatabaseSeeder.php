<?php

namespace Modules\Speciality\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Modules\Speciality\Entities\Speciality;

class SpecialityDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Speciality::truncate();

        $specialities = [
            "Anatomie Pathologique",
            "Anesthésie Réanimation",
            "Biologie Clinique",
            "Cardiologie",
            "Chirurgie Cardio Vasculaire",
            "Chirurgie dentaire",
            "Chirurgie générale",
            "Chirurgie Maxillo- Faciale",
            "Chirurgie Orthopédique",
            "Chirurgie Pédiatrique",
            "Chirurgie Urologique",
            "Dermatologie",
            "Endocrinologie",
            "Epidémiologie",
            "Gastro Entérologie",
            "Gynéco Obstétrique",
            "Hématologie",
            "Hémobiologie",
            "Immunologie",
            "Maladies Infectieuses",
            "Médecine du sport",
            "Médecine du Travail",
            "Médecine Générale",
            "Médecine Interne",
            "Médecine Légale",
            "Médecine Nucléaire",
            "Microbiologie",
            "Néphrologie",
            "Neuro Chirurgie",
            "Neurologie",
            "Oncologie Médicale",
            "Ophtalmologie",
            "ORL",
            "Orthopédie",
            "Pédiatrie",
            "Pharmacologie",
            "Physiologie",
            "Pneumo Phtisiologie",
            "Psychiatrie",
            "Radiologie",
            "Rééducation et réadaptation fonctionnelle",
            "Rhumatologie",
            "Urologie"
        ];

        foreach ($specialities as $value) {
            Speciality::create([
                'name' => $value,
                'code' => Str::snake($value),
            ]);
        }
    }
}
