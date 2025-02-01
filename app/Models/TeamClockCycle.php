<?php

namespace App\Models;

use App\Enums\ClockState;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property int $team_configuration_id
 * @property int $team_id
 * @property Carbon $start_time
 * @property Carbon $end_work_time
 * @property Carbon $end_free_time
 * @property ClockState $clock_state
 * @property Carbon $created_at
 * @property Carbon $updated_at
 * @property-read TeamConfiguration $teamConfiguration
 * @property-read Team $team
 */
class TeamClockCycle extends Model {
    const ATTRIBUTE_ID = 'id';
    const ATTRIBUTE_TEAM_CONFIGURATION_ID = 'team_configuration_id';
    const ATTRIBUTE_TEAM_ID = 'team_id';
    const ATTRIBUTE_START_TIME = 'start_time';
    const ATTRIBUTE_END_WORK_TIME = 'end_work_time';
    const ATTRIBUTE_END_FREE_TIME = 'end_free_time';
    const ATTRIBUTE_CLOCK_STATE = 'clock_state';
    const ATTRIBUTE_CREATED_AT = 'created_at';
    const ATTRIBUTE_UPDATED_AT = 'updated_at';

    const RELATION_PARENT_TEAM_CONFIGURATION = 'teamConfiguration';
    const RELATION_PARENT_TEAM = 'team';

    protected $casts = [
        self::ATTRIBUTE_CLOCK_STATE => ClockState::class,
        self::ATTRIBUTE_START_TIME => 'datetime',
        self::ATTRIBUTE_END_WORK_TIME => 'datetime',
        self::ATTRIBUTE_END_FREE_TIME => 'datetime',
    ];

    public function teamConfiguration(): BelongsTo {
        return $this->belongsTo(TeamConfiguration::class);
    }

    public function team(): BelongsTo {
        return $this->belongsTo(Team::class);
    }


    public function shouldMoveToNextState(): bool {
        $timeToCompare = match ($this->clock_state) {
            ClockState::WORK => $this->end_work_time,
            ClockState::FREE_TIME, ClockState::ENDED => $this->end_free_time,
        };
        return $timeToCompare->lessThanOrEqualTo(Carbon::now());
    }

    public static function createClockCycleBasedOnConfiguration(TeamConfiguration $teamConfiguration): static {
        $model = new static();
        $model->clock_state = ClockState::WORK;
        $model->start_time = Carbon::now();
        $model->end_work_time = $model->start_time->addMinutes($teamConfiguration->work_time);
        $model->end_free_time = $model->end_work_time->addMinutes($teamConfiguration->free_time);
        $model->team_configuration_id = $teamConfiguration->id;
        $model->team_id = $teamConfiguration->team_id;
        $model->save();
        return $model;
    }
}
