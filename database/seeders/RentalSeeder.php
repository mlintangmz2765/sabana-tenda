<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\DamagedItem;
use App\Models\Item;
use App\Models\Rental;
use App\Models\RentalDetail;
use App\Models\ReturnTransaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RentalSeeder extends Seeder
{
    public function run(): void
    {
        $staff = User::whereIn('role', [User::ROLE_STAFF, User::ROLE_ADMIN])->get();
        $customers = Customer::all();
        $items = Item::all()->keyBy('item_code');
        $dailyPenalty = (int) config('sabana.daily_penalty');

        $scenarios = $this->buildScenarios();

        $rentalCounter = 1;
        $detailCounter = 1;
        $returnCounter = 1;
        $damageCounter = 1;

        foreach ($scenarios as $idx => $scenario) {
            $customer = $customers->firstWhere('customer_code', $scenario['customer']);
            if (! $customer) {
                continue;
            }

            $rentalDate = Carbon::parse($scenario['rental_date']);
            $returnDate = Carbon::parse($scenario['return_date']);
            $duration = (int) $rentalDate->diffInDays($returnDate);

            $rentalLineItems = [];
            $totalCost = 0;

            foreach ($scenario['items'] as $itemRow) {
                $item = $items[$itemRow['code']] ?? null;
                if (! $item) continue;

                $subtotal = $item->price_per_day * $itemRow['qty'] * $duration;
                $totalCost += $subtotal;

                $rentalLineItems[] = [
                    'item' => $item,
                    'qty' => $itemRow['qty'],
                    'subtotal' => $subtotal,
                ];
            }

            $rental = Rental::create([
                'rental_code' => sprintf('RNT-%d-%03d', $rentalDate->year, $rentalCounter++),
                'customer_id' => $customer->id,
                'user_id' => $staff->random()->id,
                'rental_date' => $rentalDate,
                'return_date' => $returnDate,
                'duration_days' => $duration,
                'total_cost' => $totalCost,
                'rental_status' => $scenario['status'],
                'notes' => $scenario['notes'] ?? null,
                'created_at' => $rentalDate->copy()->addHours(rand(8, 17)),
                'updated_at' => $rentalDate->copy()->addHours(rand(8, 17)),
            ]);

            foreach ($rentalLineItems as $line) {
                $item = $line['item'];
                RentalDetail::create([
                    'detail_code' => sprintf('RD-%05d', $detailCounter++),
                    'rental_id' => $rental->id,
                    'item_id' => $item->id,
                    'quantity' => $line['qty'],
                    'price_per_day' => $item->price_per_day,
                    'subtotal' => $line['subtotal'],
                ]);

                if ($scenario['status'] === Rental::STATUS_ACTIVE || $scenario['status'] === Rental::STATUS_LATE) {
                    Item::where('id', $item->id)->update([
                        'available_stock' => DB::raw("GREATEST(available_stock - {$line['qty']}, 0)"),
                    ]);
                }
            }

            // Create return transaction for completed/late rentals
            if (in_array($scenario['status'], [Rental::STATUS_COMPLETED, Rental::STATUS_LATE])) {
                $actualReturnDate = isset($scenario['actual_return_date'])
                    ? Carbon::parse($scenario['actual_return_date'])
                    : $returnDate;
                $lateDays = max(0, (int) $returnDate->diffInDays($actualReturnDate, false));
                $totalItemCount = array_sum(array_column($scenario['items'], 'qty'));
                $lateFee = $lateDays * $dailyPenalty * $totalItemCount;
                $damageFee = $scenario['damage_fee'] ?? 0;

                $returnTx = ReturnTransaction::create([
                    'return_code' => sprintf('RET-%05d', $returnCounter++),
                    'rental_id' => $rental->id,
                    'user_id' => $staff->random()->id,
                    'actual_return_date' => $actualReturnDate,
                    'late_days' => $lateDays,
                    'late_fee' => $lateFee,
                    'damage_fee' => $damageFee,
                    'total_fine' => $lateFee + $damageFee,
                    'condition_check' => $scenario['condition_check'] ?? 'Lengkap, kondisi baik',
                    'payment_status' => $scenario['payment_status'] ?? 'paid',
                    'created_at' => $actualReturnDate,
                    'updated_at' => $actualReturnDate,
                ]);

                if (! empty($scenario['damages'])) {
                    foreach ($scenario['damages'] as $damage) {
                        $damagedItem = $items[$damage['code']] ?? null;
                        if (! $damagedItem) continue;

                        DamagedItem::create([
                            'damage_code' => sprintf('DMG-%05d', $damageCounter++),
                            'rental_id' => $rental->id,
                            'item_id' => $damagedItem->id,
                            'return_id' => $returnTx->id,
                            'damage_level' => $damage['level'],
                            'description' => $damage['desc'],
                            'repair_cost' => $damage['cost'],
                            'created_at' => $actualReturnDate,
                            'updated_at' => $actualReturnDate,
                        ]);
                    }
                }
            }
        }
    }

    protected function buildScenarios(): array
    {
        $today = Carbon::today();
        return [
            // Completed transactions (past)
            [
                'customer' => 'CUST-001', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(45)->toDateString(),
                'return_date' => $today->copy()->subDays(42)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(42)->toDateString(),
                'items' => [
                    ['code' => 'ITM-001', 'qty' => 1],
                    ['code' => 'ITM-008', 'qty' => 2],
                    ['code' => 'ITM-012', 'qty' => 1],
                ],
                'condition_check' => 'Semua barang kembali dalam kondisi baik. Tenda sudah dilipat rapi.',
                'notes' => 'Camping di Merapi 3 hari 2 malam',
            ],
            [
                'customer' => 'CUST-002', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(38)->toDateString(),
                'return_date' => $today->copy()->subDays(35)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(35)->toDateString(),
                'items' => [
                    ['code' => 'ITM-005', 'qty' => 2],
                    ['code' => 'ITM-008', 'qty' => 2],
                    ['code' => 'ITM-018', 'qty' => 2],
                ],
                'notes' => 'Pendakian Gunung Merbabu',
            ],
            [
                'customer' => 'CUST-006', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(30)->toDateString(),
                'return_date' => $today->copy()->subDays(28)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(27)->toDateString(),
                'items' => [
                    ['code' => 'ITM-003', 'qty' => 1],
                    ['code' => 'ITM-010', 'qty' => 6],
                    ['code' => 'ITM-013', 'qty' => 1],
                    ['code' => 'ITM-015', 'qty' => 2],
                ],
                'damage_fee' => 0,
                'condition_check' => 'Terlambat 1 hari karena hujan deras. Barang kondisi baik.',
            ],
            // Late return with damage
            [
                'customer' => 'CUST-003', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(25)->toDateString(),
                'return_date' => $today->copy()->subDays(22)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(20)->toDateString(),
                'items' => [
                    ['code' => 'ITM-001', 'qty' => 1],
                    ['code' => 'ITM-008', 'qty' => 3],
                    ['code' => 'ITM-016', 'qty' => 2],
                ],
                'damage_fee' => 50000,
                'damages' => [
                    ['code' => 'ITM-001', 'level' => 'minor', 'desc' => 'Resleting tenda agak rusak, perlu reparasi.', 'cost' => 50000],
                ],
                'condition_check' => 'Terlambat 2 hari. Tenda resleting rusak, dikenakan biaya perbaikan.',
            ],
            [
                'customer' => 'CUST-007', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(20)->toDateString(),
                'return_date' => $today->copy()->subDays(18)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(18)->toDateString(),
                'items' => [
                    ['code' => 'ITM-002', 'qty' => 1],
                    ['code' => 'ITM-010', 'qty' => 2],
                    ['code' => 'ITM-012', 'qty' => 1],
                ],
            ],
            [
                'customer' => 'CUST-008', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(15)->toDateString(),
                'return_date' => $today->copy()->subDays(12)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(12)->toDateString(),
                'items' => [
                    ['code' => 'ITM-006', 'qty' => 1],
                    ['code' => 'ITM-009', 'qty' => 2],
                    ['code' => 'ITM-014', 'qty' => 1],
                    ['code' => 'ITM-017', 'qty' => 1],
                ],
                'notes' => 'Pendakian Semeru 3 hari',
            ],
            [
                'customer' => 'CUST-004', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(12)->toDateString(),
                'return_date' => $today->copy()->subDays(10)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(10)->toDateString(),
                'items' => [
                    ['code' => 'ITM-007', 'qty' => 2],
                    ['code' => 'ITM-011', 'qty' => 2],
                    ['code' => 'ITM-015', 'qty' => 1],
                ],
            ],
            [
                'customer' => 'CUST-009', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(10)->toDateString(),
                'return_date' => $today->copy()->subDays(7)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(7)->toDateString(),
                'items' => [
                    ['code' => 'ITM-001', 'qty' => 2],
                    ['code' => 'ITM-008', 'qty' => 4],
                    ['code' => 'ITM-013', 'qty' => 1],
                    ['code' => 'ITM-015', 'qty' => 1],
                ],
                'notes' => 'Camping ground keluarga di Kaliurang',
            ],
            [
                'customer' => 'CUST-005', 'status' => Rental::STATUS_COMPLETED,
                'rental_date' => $today->copy()->subDays(8)->toDateString(),
                'return_date' => $today->copy()->subDays(6)->toDateString(),
                'actual_return_date' => $today->copy()->subDays(6)->toDateString(),
                'items' => [
                    ['code' => 'ITM-005', 'qty' => 1],
                    ['code' => 'ITM-008', 'qty' => 1],
                    ['code' => 'ITM-018', 'qty' => 1],
                    ['code' => 'ITM-019', 'qty' => 1],
                ],
            ],
            // Active rentals
            [
                'customer' => 'CUST-001', 'status' => Rental::STATUS_ACTIVE,
                'rental_date' => $today->copy()->subDays(2)->toDateString(),
                'return_date' => $today->copy()->addDays(1)->toDateString(),
                'items' => [
                    ['code' => 'ITM-001', 'qty' => 1],
                    ['code' => 'ITM-008', 'qty' => 2],
                    ['code' => 'ITM-015', 'qty' => 1],
                ],
                'notes' => 'Camping akhir pekan di Kaliurang',
            ],
            [
                'customer' => 'CUST-010', 'status' => Rental::STATUS_ACTIVE,
                'rental_date' => $today->copy()->subDays(1)->toDateString(),
                'return_date' => $today->copy()->addDays(2)->toDateString(),
                'items' => [
                    ['code' => 'ITM-003', 'qty' => 1],
                    ['code' => 'ITM-008', 'qty' => 6],
                    ['code' => 'ITM-013', 'qty' => 1],
                ],
                'notes' => 'Acara family gathering kantor',
            ],
            [
                'customer' => 'CUST-011', 'status' => Rental::STATUS_ACTIVE,
                'rental_date' => $today->copy()->toDateString(),
                'return_date' => $today->copy()->addDays(2)->toDateString(),
                'items' => [
                    ['code' => 'ITM-002', 'qty' => 1],
                    ['code' => 'ITM-010', 'qty' => 2],
                    ['code' => 'ITM-016', 'qty' => 1],
                ],
            ],
            // Late (overdue active)
            [
                'customer' => 'CUST-012', 'status' => Rental::STATUS_LATE,
                'rental_date' => $today->copy()->subDays(6)->toDateString(),
                'return_date' => $today->copy()->subDays(2)->toDateString(),
                'items' => [
                    ['code' => 'ITM-005', 'qty' => 1],
                    ['code' => 'ITM-008', 'qty' => 1],
                ],
                'notes' => 'Belum dikembalikan, follow-up ke pelanggan',
            ],
            // Recent active
            [
                'customer' => 'CUST-002', 'status' => Rental::STATUS_ACTIVE,
                'rental_date' => $today->copy()->toDateString(),
                'return_date' => $today->copy()->addDays(3)->toDateString(),
                'items' => [
                    ['code' => 'ITM-001', 'qty' => 1],
                    ['code' => 'ITM-008', 'qty' => 2],
                    ['code' => 'ITM-018', 'qty' => 1],
                ],
                'notes' => 'Camping di Goa Pindul',
            ],
        ];
    }
}
