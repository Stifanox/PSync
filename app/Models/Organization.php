<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property string $name
 * @property int $user_admin_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read User $userAdmin
 * @property-read Collection<int, Team> $teams
 * @property-read Collection<int, User> $users
 */
class Organization extends Model {

    use HasFactory;

    const ATTRIBUTE_ID = 'id';
    const ATTRIBUTE_NAME = 'name';
    const ATTRIBUTE_USER_ADMIN_ID = 'user_admin_id';
    const ATTRIBUTE_CREATED_AT = 'created_at';
    const ATTRIBUTE_UPDATED_AT = 'updated_at';

    const RELATION_PARENT_USER_ADMIN = 'userAdmin';
    const RELATION_CHILD_USER_ADMIN = 'teams';


    public function userAdmin(): BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function teams(): HasMany {
        return $this->hasMany(Team::class, Team::ATTRIBUTE_ORGANIZATION_ID);
    }

    public function users(): HasMany {
        return $this->hasMany(User::class, User::ATTRIBUTE_ORGANIZATION_ID);
    }
}
