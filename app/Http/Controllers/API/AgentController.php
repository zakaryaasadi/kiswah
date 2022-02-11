<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\AgentPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Agent;

class AgentController extends Controller
{


    public function index()
    {
        return Agent::all();
    }

    public function show(Request $request, Agent $agent)
    {
        return $agent;
    }

    public function store(AgentPostRequest $request)
    {
        $data = $request->validated();
        $agent = Agent::create($data);
        return $agent;
    }

    public function update(AgentPostRequest $request, Agent $agent)
    {
        $data = $request->validated();
        $agent->fill($data);
        $agent->save();

        return $agent;
    }

    public function destroy(Request $request, Agent $agent)
    {
        $agent->delete();
        return $agent;
    }

}
