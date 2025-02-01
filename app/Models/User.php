<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Enums\Permissions;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $organization_id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $remember_token
 * @property boolean $has_onboarding
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Organization $organization
 * @property-read Collection<int, Team> $teams
 * @property-read Collection<int, Permission> $permissions
 */
class User extends Authenticatable {
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    const ATTRIBUTE_ID = 'ID';
    const ATTRIBUTE_ORGANIZATION_ID = 'organization_id';
    const ATTRIBUTE_NAME = 'name';
    const ATTRIBUTE_EMAIL = 'email';
    const ATTRIBUTE_PASSWORD = 'password';
    const ATTRIBUTE_REMEMBER_TOKEN = 'remember_token';
    const ATTRIBUTE_HAS_ONBOARDING = 'has_onboarding';
    const ATTRIBUTE_CREATED_AT = 'created_at';
    const ATTRIBUTE_UPDATED_AT = 'updated_at';

    const RELATION_PARENT_ORGANIZATION = 'organization';

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
    protected $casts = [
        self::ATTRIBUTE_HAS_ONBOARDING => 'boolean',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public static function boot() {
        parent::boot();
        static::deleting(function(User $user) {
            $user->teams()->detach();
            $user->permissions()->detach();
        });
    }

    public function organization(): BelongsTo {
        return $this->belongsTo(Organization::class);
    }

    public function teams(): BelongsToMany {
        return $this->belongsToMany(Team::class, 'user_teams', 'user_id', 'team_id');
    }

    public function permissions(): BelongsToMany {
        return $this->belongsToMany(Permission::class, 'user_has_permissions', 'user_id', 'permission_id');
    }

    public function hasPermission(Permissions $permission): bool {
        return $this->permissions->contains(fn(Permission $model) => $model->name === $permission);
    }

}
