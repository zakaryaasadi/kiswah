<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\MissionPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Mission;

class MissionController extends Controller
{


    public function index()
    {
        return Mission::all();
    }

    public function show(Request $request, Mission $mission)
    {
        return $mission;
    }

    public function store(MissionPostRequest $request)
    {
        $data = $request->validated();
        $mission = Mission::create($data);
        return $mission;
    }

    public function update(MissionPostRequest $request, Mission $mission)
    {
        $data = $request->validated();
        $mission->fill($data);
        $mission->save();

        return $mission;
    }

    public function destroy(Request $request, Mission $mission)
    {
        $mission->delete();
        return $mission;
    }

}
