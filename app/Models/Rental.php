<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Rental extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE = 'active';
    public const STATUS_COMPLETED = 'completed';
    public const STATUS_LATE = 'late';
    public const STATUS_CANCELLED = 'cancelled';

    protected $fillable = [
        'rental_code', 'customer_id', 'user_id', 'rental_date', 'return_date',
        'duration_days', 'total_cost', 'rental_status', 'notes',
    ];

    protected $casts = [
        'rental_date' => 'date',
        'return_date' => 'date',
        'duration_days' => 'integer',
        'total_cost' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function staff(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function details(): HasMany
    {
        return $this->hasMany(RentalDetail::class);
    }

    public function returnTransaction(): HasOne
    {
        return $this->hasOne(ReturnTransaction::class);
    }

    public function damagedItems(): HasMany
    {
        return $this->hasMany(DamagedItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->whereIn('rental_status', [self::STATUS_ACTIVE, self::STATUS_LATE]);
    }

    public function scopeForMonth(Builder $query, Carbon $month): Builder
    {
        return $query->whereBetween('rental_date', [$month->copy()->startOfMonth(), $month->copy()->endOfMonth()]);
    }

    public function isLate(): bool
    {
        return in_array($this->rental_status, [self::STATUS_LATE], true)
            || ($this->rental_status === self::STATUS_ACTIVE && $this->return_date->isPast());
    }

    public function statusLabel(): string
    {
        return match ($this->rental_status) {
            self::STATUS_ACTIVE => 'Aktif',
            self::STATUS_COMPLETED => 'Selesai',
            self::STATUS_LATE => 'Terlambat',
            self::STATUS_CANCELLED => 'Dibatalkan',
            default => ucfirst($this->rental_status),
        };
    }

    public function statusBadgeClass(): string
    {
        return match ($this->rental_status) {
            self::STATUS_ACTIVE => 'bg-emerald-100 text-emerald-800 ring-emerald-600/20',
            self::STATUS_COMPLETED => 'bg-slate-100 text-slate-800 ring-slate-600/20',
            self::STATUS_LATE => 'bg-amber-100 text-amber-800 ring-amber-600/20',
            self::STATUS_CANCELLED => 'bg-rose-100 text-rose-800 ring-rose-600/20',
            default => 'bg-slate-100 text-slate-800',
        };
    }
}
