<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;


/**
 * @property int $id
 * @property string $name
 * @property int $organization_id
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Organization $organization
 * @property-read Collection<int, User> $users
 * @property-read TeamConfiguration $teamConfiguration
 * @property-read Collection<int,TeamClockCycle> $teamClocks
 */
class Team extends Model {
    /** @use HasFactory<\Database\Factories\TeamFactory> */
    use HasFactory;
    const ATTRIBUTE_ID = 'id';
    const ATTRIBUTE_NAME = 'name';
    const ATTRIBUTE_ORGANIZATION_ID = 'organization_id';
    const ATTRIBUTE_CREATED_AT = 'created_at';
    const ATTRIBUTE_UPDATED_AT = 'updated_at';
    const RELATION_PARENT_ORGANIZATION = 'organization';
    const RELATION_ATTACH_USERS = 'users';
    const RELATION_CHILD_CONFIGURATION = 'teamConfiguration';
    const RELATION_CHILD_TEAM_CLOCKS = 'teamClocks';

    protected static function boot() {
        parent::boot();

        static::deleting(function(Team $model) {
            $model->users()->detach();
            $model->teamConfiguration->delete();
            $model->teamClocks->each(fn(TeamClockCycle $clockCycle) =>$clockCycle->delete());
        });
    }

    public function organization(): BelongsTo {
        return $this->belongsTo(Organization::class);
    }

    public function users(): BelongsToMany {
        return $this->belongsToMany(User::class, 'user_teams', 'team_id', 'user_id');
    }

    public function teamConfiguration(): hasOne {
        return $this->hasOne(TeamConfiguration::class, TeamConfiguration::ATTRIBUTE_TEAM_ID);
    }

    public function teamClocks(): HasMany {
        return $this->hasMany(TeamClockCycle::class, TeamClockCycle::ATTRIBUTE_TEAM_ID);
    }
}
