<?php

namespace App\Models;

use App\Constants\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property string $id
 * @property string $name
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class Role extends Model
{
    const CREATED_AT = 'createdAt';
    const UPDATED_AT = 'updatedAt';

    use HasFactory;
    use HasUuids;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'id',
        'name',
    ];

    public static function admin(): Role
    {
        return static::query()
            ->where('name', '=', UserRole::ADMIN->value)
            ->firstOrFail();
    }

    public static function editor(): Role
    {
        return static::query()
            ->where('name', '=', UserRole::EDITOR->value)
            ->firstOrFail();
    }
}
