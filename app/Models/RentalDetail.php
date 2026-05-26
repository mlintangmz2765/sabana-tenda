<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RentalDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'detail_code', 'rental_id', 'item_id', 'quantity',
        'price_per_day', 'subtotal',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price_per_day' => 'integer',
        'subtotal' => 'integer',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }
}
