<?php

namespace App\Domain\Services;

use App\Domain\Repositories\TeamConfigurationModelQuery;
use App\Domain\Repositories\TeamModelQuery;
use App\Models\Team;
use App\Models\TeamClockCycle;
use App\Models\TeamConfiguration;
use App\Models\User;
use Illuminate\Support\Collection;

class TeamService {

    public function getTeams(User $currentUser): Collection {
        $organization = $currentUser->organization;
        return TeamModelQuery::getQuery()
            ->whereOrganization($organization)
            ->get();
    }

    public function getUserTeams(User $currentUser): Collection {
        return $currentUser->teams;
    }

    public function getTeam(User $currentUser, int $teamId): ?Team {
        return TeamModelQuery::getQuery()
            ->getModelById($teamId)
            ->withAllRelations()
            ->first();

    }


    public function createTeam(User $currentUser, array $data): Team {
        $team = new Team();
        $team->name = $data['teamName'];
        $team->organization_id = $currentUser->organization->id;
        $team->save();
        $team->users()->attach($currentUser->id);
        $config = TeamConfiguration::createDefaultTeamConfig($team->id);
        TeamClockCycle::createClockCycleBasedOnConfiguration($config);

        return $team;
    }

    public function deleteTeam(User $currentUser, int $teamId): void {
        $model = TeamModelQuery::getQuery()
            ->getModelById($teamId)
            ->first();

        $model->delete();
    }

    public function joinTeam(User $currentUser, int $teamId): Team {
        /** @var Team $team */
        $team = TeamModelQuery::getQuery()
            ->getModelById($teamId)
            ->withAllRelations()
            ->first();

        if(!$team->users->first(fn(User $user) => $user->id === $currentUser->id)) {
            $team->users()->attach($currentUser->id);
        }
        return $team;
    }

    public function updateTeamConfiguration(User $currentUser, int $teamId, array $data): void {
        /** @var TeamConfiguration $config */
        $config = TeamConfigurationModelQuery::getQuery()
            ->whereTeam($teamId)
            ->first();

        $config->work_time = $data['workTime'];
        $config->free_time = $data['freeTime'];
        $config->save();
    }
}
