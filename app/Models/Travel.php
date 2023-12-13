<?php

namespace App\Models;

use App\Models\Concerns\HasIdentifier;
use App\Models\Identifiers\TravelId;
use Database\Factories\TravelFactory;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

/**
 * @property string $id
 * @property bool $isPublic
 * @property string $slug
 * @property string $name
 * @property string $description
 * @property int $numberOfDays
 * @property-read int $numberOfNights
 * @property Collection<integer, string> $moods
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 * @method static TravelFactory factory
 */
class Travel extends Model
{
    use HasFactory;
    use HasIdentifier;
    use HasSlug;
    use HasUuids;

    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

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

    public static string $identifierClass = TravelId::class;

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

    /**
     * Defines the options for generating travel slug
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
