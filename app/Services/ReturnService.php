<?php

namespace App\Services;

use App\Models\DamagedItem;
use App\Models\Item;
use App\Models\Rental;
use App\Models\ReturnTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class ReturnService
{
    public function processReturn(Rental $rental, array $payload): ReturnTransaction
    {
        return DB::transaction(function () use ($rental, $payload) {
            if ($rental->returnTransaction()->exists()) {
                throw ValidationException::withMessages([
                    'rental_id' => 'Transaksi ini sudah memiliki catatan pengembalian.',
                ]);
            }

            $actualReturnDate = Carbon::parse($payload['actual_return_date'])->startOfDay();
            $dueDate = $rental->return_date->copy()->startOfDay();

            $lateDays = max(0, (int) $dueDate->diffInDays($actualReturnDate, false));
            $dailyPenalty = (int) config('sabana.daily_penalty');
            $totalItemsCount = $rental->details->sum('quantity');
            $lateFee = $lateDays * $dailyPenalty * $totalItemsCount;

            $damageFee = 0;
            $damageRows = $payload['damages'] ?? [];
            $damageSequence = (DamagedItem::max('id') ?? 0) + 1;

            foreach ($damageRows as $damage) {
                $cost = (int) ($damage['repair_cost'] ?? 0);
                $damageFee += $cost;
            }

            $totalFine = $lateFee + $damageFee;

            $returnTx = ReturnTransaction::create([
                'return_code' => sprintf('RET-%05d', (ReturnTransaction::max('id') ?? 0) + 1),
                'rental_id' => $rental->id,
                'user_id' => $payload['user_id'] ?? null,
                'actual_return_date' => $actualReturnDate,
                'late_days' => $lateDays,
                'late_fee' => $lateFee,
                'damage_fee' => $damageFee,
                'total_fine' => $totalFine,
                'condition_check' => $payload['condition_check'] ?? null,
                'payment_status' => $payload['payment_status'] ?? 'paid',
            ]);

            foreach ($damageRows as $damage) {
                if (empty($damage['item_id']) || empty($damage['damage_level'])) {
                    continue;
                }

                DamagedItem::create([
                    'damage_code' => sprintf('DMG-%05d', $damageSequence++),
                    'rental_id' => $rental->id,
                    'return_id' => $returnTx->id,
                    'item_id' => $damage['item_id'],
                    'damage_level' => $damage['damage_level'],
                    'description' => $damage['description'] ?? 'Tidak ada deskripsi',
                    'repair_cost' => (int) ($damage['repair_cost'] ?? 0),
                    'photo_path' => $damage['photo_path'] ?? null,
                ]);

                if ($damage['damage_level'] === DamagedItem::LEVEL_HEAVY || $damage['damage_level'] === DamagedItem::LEVEL_LOST) {
                    Item::where('id', $damage['item_id'])->update([
                        'condition' => $damage['damage_level'] === DamagedItem::LEVEL_LOST
                            ? Item::CONDITION_HEAVY
                            : Item::CONDITION_HEAVY,
                    ]);
                }
            }

            foreach ($rental->details as $detail) {
                $isLost = collect($damageRows)
                    ->where('item_id', $detail->item_id)
                    ->where('damage_level', DamagedItem::LEVEL_LOST)
                    ->isNotEmpty();

                if ($isLost) {
                    Item::where('id', $detail->item_id)->update([
                        'stock' => DB::raw("GREATEST(stock - {$detail->quantity}, 0)"),
                    ]);
                    continue;
                }

                $item = Item::find($detail->item_id);
                $item->available_stock = min(
                    $item->stock,
                    $item->available_stock + $detail->quantity
                );
                $item->status = $item->available_stock > 0
                    ? Item::STATUS_AVAILABLE
                    : Item::STATUS_UNAVAILABLE;
                $item->save();
            }

            $rental->rental_status = Rental::STATUS_COMPLETED;
            $rental->save();

            return $returnTx->fresh(['rental.details.item', 'damagedItems.item']);
        });
    }
}
