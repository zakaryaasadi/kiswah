<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\TeamPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Team;

class TeamController extends Controller
{


    public function index()
    {
        return Team::all();
    }

    public function show(Request $request, Team $team)
    {
        return $team;
    }

    public function store(TeamPostRequest $request)
    {
        $data = $request->validated();
        $team = Team::create($data);
        return $team;
    }

    public function update(TeamPostRequest $request, Team $team)
    {
        $data = $request->validated();
        $team->fill($data);
        $team->save();

        return $team;
    }

    public function destroy(Request $request, Team $team)
    {
        $team->delete();
        return $team;
    }

}
