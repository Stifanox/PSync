<?php

namespace App\Models;

use App\Enums\TeamConfigurationStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;

/**
 * @property int $id
 * @property int $team_id
 * @property int $work_time
 * @property int $free_time
 * @property TeamConfigurationStatus $status
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read Team $team
 * @property-read Collection<int, TeamClockCycle> $teamClockCycles
 */
class TeamConfiguration extends Model {
    /** @use HasFactory<\Database\Factories\TeamConfigurationFactory> */
    use HasFactory;
    const ATTRIBUTE_ID = 'id';
    const ATTRIBUTE_TEAM_ID = 'team_id';
    const ATTRIBUTE_WORK_TIME = 'work_time';
    const ATTRIBUTE_FREE_TIME = 'free_time';
    const ATTRIBUTE_STATUS = 'status';
    const ATTRIBUTE_CREATED_AT = 'created_at';
    const ATTRIBUTE_UPDATED_AT = 'updated_at';

    const RELATION_PARENT_TEAM = 'team';
    const RELATION_CHILD_TEAM_CLOCK_CYCLES = 'teamClockCycles';

    protected $casts = [
        self::ATTRIBUTE_STATUS => TeamConfigurationStatus::class,
    ];

    public function team(): BelongsTo {
        return $this->belongsTo(Team::class);
    }

    public function teamClockCycles(): HasMany {
        return $this->hasMany(TeamClockCycle::class, TeamClockCycle::ATTRIBUTE_TEAM_CONFIGURATION_ID);
    }

    public static function createDefaultTeamConfig(int $teamId): TeamConfiguration {
        $teamConfig = new static();
        $teamConfig->team_id = $teamId;
        $teamConfig->work_time = 45;
        $teamConfig->free_time = 15;
        $teamConfig->status = TeamConfigurationStatus::ACTIVE;
        $teamConfig->save();
        return $teamConfig;
    }
}
