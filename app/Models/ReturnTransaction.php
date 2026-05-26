<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ReturnTransaction extends Model
{
    use HasFactory;

    protected $table = 'returns';

    protected $fillable = [
        'return_code', 'rental_id', 'user_id', 'actual_return_date',
        'late_days', 'late_fee', 'damage_fee', 'total_fine',
        'condition_check', 'payment_status',
    ];

    protected $casts = [
        'actual_return_date' => 'date',
        'late_days' => 'integer',
        'late_fee' => 'integer',
        'damage_fee' => 'integer',
        'total_fine' => 'integer',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function damagedItems(): HasMany
    {
        return $this->hasMany(DamagedItem::class, 'return_id');
    }
}
