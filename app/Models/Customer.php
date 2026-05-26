<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_code', 'user_id', 'name', 'phone', 'email', 'address',
        'id_card_type', 'id_card_number',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function rentals(): HasMany
    {
        return $this->hasMany(Rental::class);
    }

    public function totalSpent(): int
    {
        return (int) $this->rentals()->sum('total_cost');
    }

    public function activeRentalsCount(): int
    {
        return $this->rentals()->whereIn('rental_status', ['active', 'late'])->count();
    }
}
