<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property string $id
 * @property bool $isPublic
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property integer $numberOfDays
 * @property-read integer $numberOfNights
 * @property Collection<integer, string> $moods
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class Travel extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    use HasFactory;
    use HasUuids;

    protected $table = 'travels';

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'isPublic',
        'slug',
        'name',
        'description',
        'numberOfDays',
        'moods',
    ];
    /**
     * @var array<string, string>
     */
    protected $casts = [
        'isPublic' => 'boolean',
        'numberOfDays' => 'integer',
        'numberOfNights' => 'integer',
        'moods' => 'array',
    ];

    /**
     * Definition of the relationship joining a travel
     * with its tours
     */
    public function tours(): HasMany
    {
        return $this->hasMany(
            related: Tour::class,
            foreignKey: 'travelId',
        );
    }
}
