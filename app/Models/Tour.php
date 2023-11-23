<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $travelId
 * @property string $name
 * @property Carbon $startingDate
 * @property Carbon $endingDate
 * @property integer $price
 * @property float $formattedPrice
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class Tour extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    use HasFactory;
    use HasUuids;

    /**
     * @var array<integer, string>
     */
    protected $fillable = [
        'id',
        'travelId',
        'name',
        'startingDate',
        'endingDate',
        'price',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'startingDate' => 'date',
        'endingDate' => 'date',
        'price' => 'integer',
    ];

    /**
     * @var array<integer, string>
     */
    protected $appends = [
        'formattedPrice',
    ];

    /**
     * Relationship definition to get the travel associated to
     * current tour instance
     */
    public function travel(): BelongsTo {
        return $this->belongsTo(
            related: Travel::class,
            foreignKey: 'travelId',
        );
    }

    public function formattedPrice(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->price / 100,
        );
    }
}
