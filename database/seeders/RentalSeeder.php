<?php

namespace Database\Seeders;

use App\Models\Equipment;
use App\Models\InspectionReport;
use App\Models\Rental;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class RentalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $renter = User::where('email', 'renter@solarshare.tn')->first();
        $lender = User::where('email', 'lender@solarshare.tn')->first();
        $admin = User::where('email', 'admin@solarshare.tn')->first();

        if (! $renter || ! $lender) {
            return;
        }

        $equipments = Equipment::where('user_id', $lender->id)->get();
        if ($equipments->isEmpty()) {
            return;
        }

        // 1. Pending rental (waiting for lender confirmation)
        $eq1 = $equipments->get(0);
        if ($eq1) {
            Rental::firstOrCreate(
                [
                    'user_id' => $renter->id,
                    'equipment_id' => $eq1->id,
                    'status' => 'pending',
                ],
                [
                    'start_date' => Carbon::today()->addDays(2),
                    'end_date' => Carbon::today()->addDays(5),
                    'total_price' => Rental::calculatePrice((float) $eq1->daily_rate, Carbon::today()->addDays(2), Carbon::today()->addDays(5)),
                    'renter_notes' => 'Location pour un week-end éco-camping en pleine nature.',
                ]
            );
        }

        // 2. Confirmed rental (ready for check-in)
        $eq2 = $equipments->get(1);
        if ($eq2) {
            Rental::firstOrCreate(
                [
                    'user_id' => $renter->id,
                    'equipment_id' => $eq2->id,
                    'status' => 'confirmed',
                ],
                [
                    'start_date' => Carbon::today()->addDays(1),
                    'end_date' => Carbon::today()->addDays(3),
                    'total_price' => Rental::calculatePrice((float) $eq2->daily_rate, Carbon::today()->addDays(1), Carbon::today()->addDays(3)),
                    'renter_notes' => 'Besoin d\'une batterie de secours pour télétravail nomade.',
                ]
            );
        }

        // 3. Active rental (Check-in validated, rental in progress)
        $eq3 = $equipments->get(2);
        if ($eq3) {
            $activeRental = Rental::firstOrCreate(
                [
                    'user_id' => $renter->id,
                    'equipment_id' => $eq3->id,
                    'status' => 'active',
                ],
                [
                    'start_date' => Carbon::today()->subDay(),
                    'end_date' => Carbon::today()->addDays(3),
                    'total_price' => Rental::calculatePrice((float) $eq3->daily_rate, Carbon::today()->subDay(), Carbon::today()->addDays(3)),
                    'renter_notes' => 'Alimentation pour petit chantier de rénovation autonome.',
                ]
            );

            // Add validated Check-in report
            InspectionReport::firstOrCreate(
                [
                    'rental_id' => $activeRental->id,
                    'type' => 'check_in',
                ],
                [
                    'battery_level_percent' => 100,
                    'physical_condition' => 'excellent',
                    'has_all_cables' => true,
                    'notes' => 'Matériel remis en parfait état de propreté avec la housse de protection et les câbles MC4.',
                    'photos' => [],
                    'inspector_id' => $lender->id,
                    'is_validated' => true,
                    'validated_at' => Carbon::now()->subDay(),
                ]
            );
        }

        // 4. Completed rental (Check-in AND Check-out completed and validated)
        $eq4 = $equipments->get(3) ?? $equipments->first();
        if ($eq4) {
            $completedRental = Rental::firstOrCreate(
                [
                    'user_id' => $renter->id,
                    'equipment_id' => $eq4->id,
                    'status' => 'completed',
                ],
                [
                    'start_date' => Carbon::today()->subDays(6),
                    'end_date' => Carbon::today()->subDays(2),
                    'total_price' => Rental::calculatePrice((float) $eq4->daily_rate, Carbon::today()->subDays(6), Carbon::today()->subDays(2)),
                    'renter_notes' => 'Utilisation pour un festival zéro déchet.',
                ]
            );

            // Check-in
            InspectionReport::firstOrCreate(
                [
                    'rental_id' => $completedRental->id,
                    'type' => 'check_in',
                ],
                [
                    'battery_level_percent' => 100,
                    'physical_condition' => 'excellent',
                    'has_all_cables' => true,
                    'notes' => 'Remis avec batterie pleine, tous les câbles d\'alimentation et adaptateurs.',
                    'photos' => [],
                    'inspector_id' => $lender->id,
                    'is_validated' => true,
                    'validated_at' => Carbon::now()->subDays(6),
                ]
            );

            // Check-out
            InspectionReport::firstOrCreate(
                [
                    'rental_id' => $completedRental->id,
                    'type' => 'check_out',
                ],
                [
                    'battery_level_percent' => 65,
                    'physical_condition' => 'good',
                    'has_all_cables' => true,
                    'notes' => 'Restitué propre, batterie restante à 65%, aucun choc ni rayure. Caution restituée.',
                    'photos' => [],
                    'inspector_id' => $lender->id,
                    'is_validated' => true,
                    'validated_at' => Carbon::now()->subDays(2),
                ]
            );
        }
    }
}
