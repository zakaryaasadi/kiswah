<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\HelpPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Help;

class HelpController extends Controller
{


    public function index()
    {
        $helps = Help::paginate(10);
        return $this->respondWithSuccess(['data' => $helps]);
    }

    public function show(Request $request, Help $help)
    {
        return $help;
    }

    public function store(HelpPostRequest $request)
    {
        $request->merge(['customer_id' => getUser()->id]);
        $help = Help::create($request->all());
        return $this->respondWithSuccess(['data', $help]);
    }

    public function update(HelpPostRequest $request, Help $help)
    {
        $data = $request->validated();
        $help->fill($data);
        $help->save();

        return $help;
    }

    public function destroy(Request $request, Help $help)
    {
        $help->delete();
        return $help;
    }

}
