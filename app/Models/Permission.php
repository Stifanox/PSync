<?php

namespace App\Models;

use App\Enums\Permissions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Carbon;
use Ramsey\Collection\Collection;

/**
 * @property int $id
 * @property Permissions $name
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Collection<int,User> $users
 */
class Permission extends Model
{

    protected $casts = [
        'name' => Permissions::class,
    ];
    public function users(): BelongsToMany{
        return $this->belongsToMany(User::class,'user_has_permissions','permission_id','user_id');
    }
}
