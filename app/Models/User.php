<?php

namespace App\Models;

use App\Constants\UserRole;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property null|Collection<Role> $roles
 * @property Carbon $createdAt
 * @property Carbon $updatedAt
 */
class User extends Authenticatable
{
    const CREATED_AT = 'createdAt';

    const UPDATED_AT = 'updatedAt';

    const MINIMUM_PASSWORD_LENGTH = 8;

    use HasApiTokens;
    use HasFactory;
    use HasUuids;
    use Notifiable;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'password' => 'hashed',
    ];

    /**
     * Defines the relationship which binds users to
     * their roles
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            related: Role::class,
            foreignPivotKey: 'userId',
            relatedPivotKey: 'roleId',
        );
    }

    /**
     * Helper method to check whether an user has
     * administrative grants.
     *
     * @see UserRole
     */
    public function isAdmin(): bool
    {
        return $this->hasRole(UserRole::ADMIN);
    }

    /**
     * Helper method to check whether an user has
     * entity editing capabilities.
     *
     * @see UserRole
     */
    public function isEditor(): bool
    {
        return $this->hasRole(UserRole::EDITOR);
    }

    public function hasRole(UserRole $role): bool
    {
        if ($this->relationLoaded('roles')) {
            return $this->roles
                ->first(
                    fn (Role $roleModel) => $roleModel->name === $role->value,
                ) !== null;
        }

        return $this
            ->roles()
            ->where('name', '=', $role->value)
            ->exists();
    }
}
