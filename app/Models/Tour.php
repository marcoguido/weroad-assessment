<?php

namespace App\Models;

use App\Models\Concerns\HasIdentifier;
use App\Models\Identifiers\TourId;
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
 * @property int $price
 * @property float $formattedPrice
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class Tour extends Model
{
    use HasFactory;
    use HasIdentifier;
    use HasUuids;

    const DATE_FORMAT = 'Y-m-d';

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

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

    public static string $identifierClass = TourId::class;

    /**
     * Relationship definition to get the travel associated to
     * current tour instance
     */
    public function travel(): BelongsTo
    {
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
