<?php

namespace App\Http\Controllers;

use App\Domain\Services\TeamService;
use App\Http\Resources\TeamDetailedResource;
use App\Http\Resources\TeamResource;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TeamController extends Controller {


    public function __construct(
        protected TeamService $teamService
    ) {
    }

    public function index(Request $request): View {
        $user = $request->user();
        $teams = $this->teamService->getTeams($user);

        return view('team.teams', ['teams' => TeamResource::collection($teams)]);
    }

    public function getAllUserTeams(Request $request): View {
        $user = $request->user();
        $teams = $this->teamService->getUserTeams($user);

        return view('dashboard', ['teams' => TeamResource::collection($teams)]);
    }

    public function show(Request $request, int $teamId): View {
        $user = $request->user();
        $team = $this->teamService->getTeam($user, $teamId);

        if (is_null($team)) {
            return view('errors.404');
        }

        return view('team.team', ['team' => new TeamDetailedResource($team)]);
    }

    public function store(Request $request): RedirectResponse {
        $user = $request->user();
        $request->validateWithBag('teamCreate', [
            'teamName' => 'string|required',
        ]);

        $team = $this->teamService->createTeam($user, $request->all());

        return redirect()->route('userTeams', ["teamId" => $team->id])
            ->with(['team' => new TeamDetailedResource($team)]);
    }

    public function destroy(Request $request): RedirectResponse {
        $request->validateWithBag('teamDelete', [
           'teamId'=> 'integer|required',
        ]);
        $this->teamService->deleteTeam($request->user(), $request->all()["teamId"]);
        return redirect()->route('dashboard');
    }

    public function joinTeam(Request $request): RedirectResponse {
        $request->validateWithBag('teamDelete', [
            'teamId'=> 'integer|required',
        ]);
        $team = $this->teamService->joinTeam($request->user(), $request->all()["teamId"]);
        return redirect()->route('userTeams', ["teamId" => $team->id])
            ->with(['team' => new TeamDetailedResource($team)]);
    }

    public function updateTeamConfig(Request $request, int $teamId):RedirectResponse {
        $request->validateWithBag('teamConfig', [
            'workTime'=> 'integer|required',
            'freeTime'=> 'integer|required',
        ]);

        $this->teamService->updateTeamConfiguration($request->user(), $teamId, $request->all());


        return back();
    }
}
