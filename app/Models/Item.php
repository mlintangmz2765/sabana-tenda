<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Item extends Model
{
    use HasFactory;

    public const CONDITION_GOOD = 'good';
    public const CONDITION_MINOR = 'minor_damage';
    public const CONDITION_HEAVY = 'heavy_damage';

    public const STATUS_AVAILABLE = 'available';
    public const STATUS_UNAVAILABLE = 'unavailable';

    protected $fillable = [
        'item_code', 'category_id', 'name', 'slug', 'description', 'specifications',
        'stock', 'available_stock', 'condition', 'status', 'price_per_day',
        'image_path', 'is_active',
    ];

    protected $casts = [
        'stock' => 'integer',
        'available_stock' => 'integer',
        'price_per_day' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::creating(function (Item $item) {
            if (empty($item->slug)) {
                $item->slug = Str::slug($item->name . '-' . $item->item_code);
            }
        });

        static::saving(function (Item $item) {
            $item->status = $item->available_stock > 0
                ? self::STATUS_AVAILABLE
                : self::STATUS_UNAVAILABLE;
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function rentalDetails(): HasMany
    {
        return $this->hasMany(RentalDetail::class);
    }

    public function damagedRecords(): HasMany
    {
        return $this->hasMany(DamagedItem::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_AVAILABLE)->where('available_stock', '>', 0);
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (! $term) {
            return $query;
        }

        return $query->where(function (Builder $q) use ($term) {
            $q->where('name', 'like', "%{$term}%")
                ->orWhere('item_code', 'like', "%{$term}%")
                ->orWhere('description', 'like', "%{$term}%");
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function conditionLabel(): string
    {
        return match ($this->condition) {
            self::CONDITION_GOOD => 'Baik',
            self::CONDITION_MINOR => 'Rusak Ringan',
            self::CONDITION_HEAVY => 'Rusak Berat',
            default => $this->condition,
        };
    }

    public function rentedQuantity(): int
    {
        return max(0, $this->stock - $this->available_stock);
    }
}
