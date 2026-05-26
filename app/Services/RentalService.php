<?php

namespace App\Services;

use App\Models\Customer;
use App\Models\Item;
use App\Models\Rental;
use App\Models\RentalDetail;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class RentalService
{
    public function createRental(array $payload): Rental
    {
        return DB::transaction(function () use ($payload) {
            $customer = Customer::lockForUpdate()->findOrFail($payload['customer_id']);
            $rentalDate = Carbon::parse($payload['rental_date'])->startOfDay();
            $returnDate = Carbon::parse($payload['return_date'])->startOfDay();

            $this->validateDates($rentalDate, $returnDate);

            $duration = (int) $rentalDate->diffInDays($returnDate);
            $maxDays = (int) config('sabana.max_rental_days');
            if ($duration > $maxDays && empty($payload['owner_approved'])) {
                throw ValidationException::withMessages([
                    'return_date' => "Durasi sewa melebihi batas maksimal {$maxDays} hari. Diperlukan persetujuan owner.",
                ]);
            }

            $totalCost = 0;
            $itemRows = [];
            foreach ($payload['items'] as $row) {
                $item = Item::lockForUpdate()->findOrFail($row['item_id']);
                $qty = (int) $row['quantity'];

                if ($qty < 1) {
                    throw ValidationException::withMessages([
                        "items.{$row['item_id']}" => 'Jumlah minimal 1 unit.',
                    ]);
                }

                if ($qty > $item->available_stock) {
                    throw ValidationException::withMessages([
                        "items.{$row['item_id']}" => "Stok {$item->name} tidak cukup. Tersedia: {$item->available_stock}",
                    ]);
                }

                $subtotal = $item->price_per_day * $qty * $duration;
                $totalCost += $subtotal;
                $itemRows[] = [
                    'item' => $item,
                    'quantity' => $qty,
                    'subtotal' => $subtotal,
                ];
            }

            $rental = Rental::create([
                'rental_code' => $this->nextRentalCode($rentalDate),
                'customer_id' => $customer->id,
                'user_id' => $payload['user_id'] ?? null,
                'rental_date' => $rentalDate,
                'return_date' => $returnDate,
                'duration_days' => $duration,
                'total_cost' => $totalCost,
                'rental_status' => Rental::STATUS_ACTIVE,
                'notes' => $payload['notes'] ?? null,
            ]);

            $detailSequence = (RentalDetail::max('id') ?? 0) + 1;
            foreach ($itemRows as $row) {
                /** @var Item $item */
                $item = $row['item'];
                RentalDetail::create([
                    'detail_code' => sprintf('RD-%05d', $detailSequence++),
                    'rental_id' => $rental->id,
                    'item_id' => $item->id,
                    'quantity' => $row['quantity'],
                    'price_per_day' => $item->price_per_day,
                    'subtotal' => $row['subtotal'],
                ]);

                $item->decrement('available_stock', $row['quantity']);
                $item->refresh();
                $item->status = $item->available_stock > 0
                    ? Item::STATUS_AVAILABLE
                    : Item::STATUS_UNAVAILABLE;
                $item->save();
            }

            return $rental->fresh(['details.item', 'customer']);
        });
    }

    public function nextRentalCode(Carbon $rentalDate): string
    {
        $year = $rentalDate->year;
        $count = Rental::whereYear('rental_date', $year)->count() + 1;

        return sprintf('RNT-%d-%03d', $year, $count);
    }

    protected function validateDates(Carbon $rentalDate, Carbon $returnDate): void
    {
        if ($returnDate->lessThanOrEqualTo($rentalDate)) {
            throw ValidationException::withMessages([
                'return_date' => 'Tanggal kembali harus lebih besar dari tanggal sewa.',
            ]);
        }
    }
}
