<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DamagedItem extends Model
{
    use HasFactory;

    public const LEVEL_MINOR = 'minor';
    public const LEVEL_HEAVY = 'heavy';
    public const LEVEL_LOST = 'lost';

    protected $fillable = [
        'damage_code', 'rental_id', 'item_id', 'return_id',
        'damage_level', 'description', 'repair_cost', 'photo_path',
    ];

    protected $casts = [
        'repair_cost' => 'integer',
    ];

    public function rental(): BelongsTo
    {
        return $this->belongsTo(Rental::class);
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class);
    }

    public function returnTransaction(): BelongsTo
    {
        return $this->belongsTo(ReturnTransaction::class, 'return_id');
    }

    public function levelLabel(): string
    {
        return match ($this->damage_level) {
            self::LEVEL_MINOR => 'Rusak Ringan',
            self::LEVEL_HEAVY => 'Rusak Berat',
            self::LEVEL_LOST => 'Hilang',
            default => ucfirst($this->damage_level),
        };
    }
}
