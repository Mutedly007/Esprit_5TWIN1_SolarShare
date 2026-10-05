<?php

namespace Database\Seeders;

use App\Models\EnergyKit;
use App\Models\Equipment;
use App\Models\User;
use Illuminate\Database\Seeder;

class EnergyKitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('email', 'admin@solarshare.tn')->first();
        $lender = User::where('email', 'lender@solarshare.tn')->first();
        $creator = $admin ?? $lender;

        $equipments = Equipment::all();
        if ($equipments->isEmpty()) {
            return;
        }

        // Pack 1: Vanlife Autonome
        $kit1 = EnergyKit::firstOrCreate(
            ['slug' => 'pack-vanlife-autonome'],
            [
                'user_id' => $creator?->id,
                'title' => 'Pack Vanlife Autonome Pro',
                'description' => 'Combinaison idéale pour partir en road-trip ou vivre en van aménagé en totale autonomie électrique. Comprend une batterie nomade haute capacité et deux panneaux solaires pliables haute performance.',
                'target_use' => 'vanlife',
                'discount_percentage' => 20.00,
                'is_active' => true,
            ]
        );

        $eqDelta = $equipments->firstWhere('brand', 'EcoFlow') ?? $equipments->first();
        $eqPanel = $equipments->firstWhere('brand', 'Jackery') ?? $equipments->skip(1)->first();

        if ($eqDelta && $eqPanel) {
            $kit1->equipment()->syncWithoutDetaching([
                $eqDelta->id => ['quantity' => 1, 'options' => 'Housse de transport et câble allume-cigare 12V'],
                $eqPanel->id => ['quantity' => 2, 'options' => 'Trépieds réglables et rallonge MC4 10m'],
            ]);
        }

        // Pack 2: Télétravail & Nature
        $kit2 = EnergyKit::firstOrCreate(
            ['slug' => 'pack-teletravail-solaire'],
            [
                'user_id' => $creator?->id,
                'title' => 'Pack Télétravail & Éco-Camping',
                'description' => 'Pack léger et portable pour travailler sereinement en pleine nature ou alimenter un campement éco-responsable (PC portable, smartphones, éclairage LED autonome).',
                'target_use' => 'camping',
                'discount_percentage' => 15.00,
                'is_active' => true,
            ]
        );

        $eqLite = $equipments->firstWhere('brand', 'BioLite') ?? $equipments->skip(2)->first();
        if ($eqLite && $eqPanel) {
            $kit2->equipment()->syncWithoutDetaching([
                $eqLite->id => ['quantity' => 1, 'options' => 'Système d\'éclairage complet avec interrupteurs'],
                $eqPanel->id => ['quantity' => 1, 'options' => 'Adaptateur USB-C Power Delivery 65W'],
            ]);
        }

        // Pack 3: Chantier & Bricolage Autonome
        $kit3 = EnergyKit::firstOrCreate(
            ['slug' => 'pack-chantier-autonome'],
            [
                'user_id' => $creator?->id,
                'title' => 'Pack Chantier & Rénovation Autonome',
                'description' => 'Conçu pour les travaux hors-réseau et les chantiers isolés. Alimentation d\'outillage électroportatif, chargeurs de batteries et projecteurs puissants.',
                'target_use' => 'chantier',
                'discount_percentage' => 10.00,
                'is_active' => true,
            ]
        );

        $eqWind = $equipments->firstWhere('brand', 'Automaxx') ?? $equipments->skip(3)->first();
        if ($eqDelta && $eqWind) {
            $kit3->equipment()->syncWithoutDetaching([
                $eqDelta->id => ['quantity' => 1, 'options' => 'Prises 230V pures sinus protégées IP54'],
                $eqWind->id => ['quantity' => 1, 'options' => 'Mât de fixation rapide et régulateur hybride'],
            ]);
        }

        // Pack 4: Secours & Urgence Coupure
        $kit4 = EnergyKit::firstOrCreate(
            ['slug' => 'pack-secours-urgence'],
            [
                'user_id' => $creator?->id,
                'title' => 'Pack Urgence & Continuité Énergétique',
                'description' => 'Solution immédiate en cas de coupure de courant prolongée pour maintenir en fonctionnement le réfrigérateur, le matériel médical essentiel et les communications.',
                'target_use' => 'urgence',
                'discount_percentage' => 12.00,
                'is_active' => true,
            ]
        );

        if ($eqDelta && $eqLite) {
            $kit4->equipment()->syncWithoutDetaching([
                $eqDelta->id => ['quantity' => 1, 'options' => 'Câble de recharge rapide secteur + solaire'],
                $eqLite->id => ['quantity' => 2, 'options' => 'Lampes de secours rechargeables'],
            ]);
        }
    }
}
