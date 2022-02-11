<?php

namespace App\Http\Controllers\API;

use App\Models\Region;
use App\Services\Tookan;
use Illuminate\Http\Request;
use App\Http\Requests\LocationsPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Locations;
use Laravel\Passport\Token;

class LocationsController extends Controller
{


    public function index()
    {
        $locations = getUser()->locations;
        return $this->respondWithSuccess($locations);
    }

    public function show(Request $request, $locations)
    {
        $location = getUser()->locations()->findOrFail($locations);
        return $this->respondWithSuccess($location);
    }

    public function store(LocationsPostRequest $request)
    {
        if ($request->is_default) {
            getUser()->locations()->where('is_default', true)->update(['is_default' => false]);
        }
        $regions = (new Tookan())->findRegion($request->latitude, $request->longitude);
        //Find tookan ids of the regions
        $ids = [];
        foreach ($regions->data as $line) {
            $ids[] = $line['region_id'];
        }
        $region = Region::whereIn('tookan_id', $ids)->first();
        if ($region)
            $request->merge(['region_id' => $region->id]);

        $locations = getUser()->locations()->create($request->all());
        return $this->respondWithSuccess($locations);
    }

    public function update(LocationsPostRequest $request, $locations)
    {
        $location = getUser()->locations()->findOrFail($locations);
        if ($request->is_default) {
            getUser()->locations()->where('is_default', true)->update(['is_default' => false]);
        }
        $regions = (new Tookan())->findRegion($request->latitude, $request->longitude);
        $ids = [];
        foreach ($regions->data as $line) {
            $ids[] = $line['region_id'];
        }
        $region = Region::whereIn('tookan_id', $ids)->first();
        if ($region){
            $request->merge(['region_id' => $region->id]);
        }
        $location->update($request->all());
        return $this->respondWithSuccess($location);
    }

    public function destroy(Request $request, $locations)
    {
        $location = getUser()->locations()->findOrFail($locations);
        $location->delete();
        return $this->respondWithSuccess(['message' => 'Deleted successfully']);
    }
}
