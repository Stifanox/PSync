<?php

namespace App\Http\Resources;

use App\Models\TeamClockCycle;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\MissingValue;

/**
 * @mixin TeamClockCycle
 */
class TeamClockResource extends JsonResource {
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array {
        return [
            'id' => $this->id,
            'teamConfigurationId' => $this->team_configuration_id,
            'startTime' => $this->start_time->setTimezone(1),
            'endWorkTime' => $this->end_work_time->setTimezone(1),
            'endFreeTime' => $this->end_free_time->setTimezone(1),
            'clockState' => $this->clock_state,
            'createdAt' => $this->created_at,
            'updatedAt' => $this->updated_at,
            'teamConfiguration' => $this->teamConfiguration ?? new MissingValue(),
            'team' => $this->team ?? new MissingValue(),
        ];
    }
}
