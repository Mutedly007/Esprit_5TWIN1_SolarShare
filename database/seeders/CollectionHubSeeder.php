<?php

namespace Database\Seeders;

use App\Models\CollectionHub;
use App\Models\OperatingSlot;
use Illuminate\Database\Seeder;

class CollectionHubSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $hubsData = [
            [
                'name' => 'Hub Solaire Central — Tunis Centre',
                'address' => '45 Avenue Habib Bourguiba (Près du Théâtre Municipal)',
                'city' => 'Tunis',
                'postal_code' => '1000',
                'contact_phone' => '+216 71 234 567',
                'max_capacity' => 35,
                'description' => 'Hub principal équipé de 30 casiers solaires sécurisés par code PIN, ouvert 6j/7 pour le dépôt et le retrait de batteries nomades et panneaux pliables.',
                'is_active' => true,
                'slots' => [
                    ['day_of_week' => 'lundi', 'open_time' => '08:00', 'close_time' => '19:00', 'notes' => 'Permanence continue & casiers 24/7'],
                    ['day_of_week' => 'mardi', 'open_time' => '08:00', 'close_time' => '19:00', 'notes' => 'Permanence continue & casiers 24/7'],
                    ['day_of_week' => 'mercredi', 'open_time' => '08:00', 'close_time' => '19:00', 'notes' => 'Permanence continue & casiers 24/7'],
                    ['day_of_week' => 'jeudi', 'open_time' => '08:00', 'close_time' => '19:00', 'notes' => 'Permanence continue & casiers 24/7'],
                    ['day_of_week' => 'vendredi', 'open_time' => '08:00', 'close_time' => '19:00', 'notes' => 'Permanence continue & casiers 24/7'],
                    ['day_of_week' => 'samedi', 'open_time' => '09:00', 'close_time' => '16:00', 'notes' => 'Créneau spécial départs week-end'],
                ],
            ],
            [
                'name' => 'Casier Solaire Connecté — La Marsa',
                'address' => '12 Rue Mongi Slim (Face au Marché Municipal)',
                'city' => 'La Marsa',
                'postal_code' => '2070',
                'contact_phone' => '+216 22 987 654',
                'max_capacity' => 20,
                'description' => 'Station de partage idéale pour les sorties nautiques, camping et vanlife sur les côtes nord de Tunis. Casiers solaires avec recharges d\'appoint.',
                'is_active' => true,
                'slots' => [
                    ['day_of_week' => 'lundi', 'open_time' => '09:00', 'close_time' => '18:30', 'notes' => 'Dépôt et retrait automatisé'],
                    ['day_of_week' => 'mardi', 'open_time' => '09:00', 'close_time' => '18:30', 'notes' => 'Dépôt et retrait automatisé'],
                    ['day_of_week' => 'mercredi', 'open_time' => '09:00', 'close_time' => '18:30', 'notes' => 'Dépôt et retrait automatisé'],
                    ['day_of_week' => 'jeudi', 'open_time' => '09:00', 'close_time' => '18:30', 'notes' => 'Dépôt et retrait automatisé'],
                    ['day_of_week' => 'vendredi', 'open_time' => '09:00', 'close_time' => '18:30', 'notes' => 'Dépôt et retrait automatisé'],
                    ['day_of_week' => 'samedi', 'open_time' => '08:30', 'close_time' => '17:00', 'notes' => 'Permanence sur place'],
                    ['day_of_week' => 'dimanche', 'open_time' => '10:00', 'close_time' => '14:00', 'notes' => 'Créneau retour express'],
                ],
            ],
            [
                'name' => 'Éco-Relais Ennasr — Ariana',
                'address' => '78 Avenue Hédi Nouira, Résidence Les Jardins',
                'city' => 'Ariana',
                'postal_code' => '2037',
                'contact_phone' => '+216 29 112 233',
                'max_capacity' => 25,
                'description' => 'Point relais partenaire chez notre artisan électricien solaire partenaire. Diagnostic rapide de l\'état de batterie à la restitution.',
                'is_active' => true,
                'slots' => [
                    ['day_of_week' => 'lundi', 'open_time' => '08:30', 'close_time' => '18:00', 'notes' => 'Accueil technicien disponible'],
                    ['day_of_week' => 'mardi', 'open_time' => '08:30', 'close_time' => '18:00', 'notes' => 'Accueil technicien disponible'],
                    ['day_of_week' => 'mercredi', 'open_time' => '08:30', 'close_time' => '18:00', 'notes' => 'Accueil technicien disponible'],
                    ['day_of_week' => 'jeudi', 'open_time' => '08:30', 'close_time' => '18:00', 'notes' => 'Accueil technicien disponible'],
                    ['day_of_week' => 'vendredi', 'open_time' => '08:30', 'close_time' => '18:00', 'notes' => 'Accueil technicien disponible'],
                    ['day_of_week' => 'samedi', 'open_time' => '09:00', 'close_time' => '14:00', 'notes' => 'Matinée uniquement'],
                ],
            ],
            [
                'name' => 'Hub Solaire Sahel — Sousse Ville',
                'address' => '14 Boulevard 14 Janvier (Zone Hôtelière & Port)',
                'city' => 'Sousse',
                'postal_code' => '4000',
                'contact_phone' => '+216 73 555 888',
                'max_capacity' => 30,
                'description' => 'Point d\'échange pour les usagers de la région du Sahel. Pratique pour les événements en plein air, campings et chantiers solaires.',
                'is_active' => true,
                'slots' => [
                    ['day_of_week' => 'lundi', 'open_time' => '08:30', 'close_time' => '18:30', 'notes' => 'Permanence relais'],
                    ['day_of_week' => 'mardi', 'open_time' => '08:30', 'close_time' => '18:30', 'notes' => 'Permanence relais'],
                    ['day_of_week' => 'mercredi', 'open_time' => '08:30', 'close_time' => '18:30', 'notes' => 'Permanence relais'],
                    ['day_of_week' => 'jeudi', 'open_time' => '08:30', 'close_time' => '18:30', 'notes' => 'Permanence relais'],
                    ['day_of_week' => 'vendredi', 'open_time' => '08:30', 'close_time' => '18:30', 'notes' => 'Permanence relais'],
                    ['day_of_week' => 'samedi', 'open_time' => '09:00', 'close_time' => '16:00', 'notes' => 'Retrait week-end'],
                ],
            ],
            [
                'name' => 'Point Relais Sfax Sud — Sfax',
                'address' => 'Route de Téniour Km 2, Cité El Habib',
                'city' => 'Sfax',
                'postal_code' => '3002',
                'contact_phone' => '+216 74 444 333',
                'max_capacity' => 28,
                'description' => 'Hub logistique pour le partage d\'équipements d\'énergie renouvelable, onduleurs et générateurs solaires de forte puissance.',
                'is_active' => true,
                'slots' => [
                    ['day_of_week' => 'lundi', 'open_time' => '08:00', 'close_time' => '17:30', 'notes' => 'Ouverture magasin'],
                    ['day_of_week' => 'mardi', 'open_time' => '08:00', 'close_time' => '17:30', 'notes' => 'Ouverture magasin'],
                    ['day_of_week' => 'mercredi', 'open_time' => '08:00', 'close_time' => '17:30', 'notes' => 'Ouverture magasin'],
                    ['day_of_week' => 'jeudi', 'open_time' => '08:00', 'close_time' => '17:30', 'notes' => 'Ouverture magasin'],
                    ['day_of_week' => 'vendredi', 'open_time' => '08:00', 'close_time' => '17:30', 'notes' => 'Ouverture magasin'],
                ],
            ],
            [
                'name' => 'Station Solaire Cap Bon — Nabeul',
                'address' => '25 Avenue Habib Bourguiba (Centre Ville)',
                'city' => 'Nabeul',
                'postal_code' => '8000',
                'contact_phone' => '+216 72 222 111',
                'max_capacity' => 15,
                'description' => 'Point relais convivial situé au cœur de Nabeul pour faciliter la location entre résidents et vacanciers du Cap Bon.',
                'is_active' => true,
                'slots' => [
                    ['day_of_week' => 'mardi', 'open_time' => '09:00', 'close_time' => '18:00', 'notes' => 'Casier disponible'],
                    ['day_of_week' => 'mercredi', 'open_time' => '09:00', 'close_time' => '18:00', 'notes' => 'Casier disponible'],
                    ['day_of_week' => 'jeudi', 'open_time' => '09:00', 'close_time' => '18:00', 'notes' => 'Casier disponible'],
                    ['day_of_week' => 'vendredi', 'open_time' => '09:00', 'close_time' => '18:00', 'notes' => 'Casier disponible'],
                    ['day_of_week' => 'samedi', 'open_time' => '09:00', 'close_time' => '15:00', 'notes' => 'Casier disponible'],
                ],
            ],
        ];

        foreach ($hubsData as $data) {
            $slots = $data['slots'];
            unset($data['slots']);

            $hub = CollectionHub::updateOrCreate(
                ['name' => $data['name']],
                $data
            );

            foreach ($slots as $slot) {
                OperatingSlot::updateOrCreate(
                    [
                        'collection_hub_id' => $hub->id,
                        'day_of_week' => $slot['day_of_week'],
                    ],
                    $slot
                );
            }
        }
    }
}
