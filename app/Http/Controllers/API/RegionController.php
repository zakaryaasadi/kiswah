<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\RegionPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Region;
use App\Services\Tookan;

class RegionController extends Controller
{


    public function index(Request $request)
    {
        $y = (new \App\Services\Tookan());
        $regions = $y->viewRegions();
        foreach ($regions->data as $tookan_region) {
            $new_region = Region::where('tookan_id', $tookan_region['region_id'])->first();
            if (!$new_region) {
                $re = '';
                foreach ($tookan_region['region_data'] as $index => $i) {
                    $re .= $i['x'] . ' ' . $i['y'] . ((count($tookan_region['region_data']) == ($index + 1)) ? '' : ', ');
                }
                $data = [
                    'name' => $tookan_region['region_name'],
                    'description' => $tookan_region['region_description'],
                    'data' => $re,
                    'tookan_id' => $tookan_region['region_id'],
                ];
                Region::create($data);
            }
        }
        $data = Region::query();
        if ($request->search) {
            $data = $data->whereLike(['name'], $request->search);
        }
        $data = $data->latest()->get();
        return $this->respondWithSuccess($data);
    }

    public function show($id)
    {
        $data = Region::findOrFail($id);
        return $this->respondWithSuccess($data);
    }

    public function store(RegionPostRequest $request)
    {
        $data = $request->merge(['added_by' => getUser()->id])->all();
//        $region = Region::create($data);
//        $y = (new \App\Services\Tookan());
//        $uploaded_region = $y->addRegion($region);
//        logs()->info('Create Region ' . $uploaded_region->message);
//        $regions = $y->viewRegions()->data;
//        foreach ($regions as $tookan_region) {
//            if ($tookan_region['region_name'] == $region->name) {
//                $region->tookan_id = $tookan_region['region_id'];
//                $region->fleet_id = $tookan_region['fleet_id'];
//                $region->save();
//            }
//        }
        return $this->respondWithSuccess($data);
    }

    public function update(Request $request, $id)
    {
        $data = \request()->except([ 'data']);
        $region = Region::findOrFail($id);
        $region->update($data);
        (new Tookan())->editRegion($region);
        return $this->respondWithSuccess($region);
    }

    public function destroy($id)
    {
        $region = Region::findOrFail($id);
        (new Tookan())->deleteRegion($region);
        $region->delete();
        return $this->respondWithSuccess('Deleted Successfully');
    }

    public function syncData()
    {
        $y = (new \App\Services\Tookan());
        $regions = $y->viewRegions();
        foreach ($regions->data as $tookan_region) {
            $new_region = Region::where('tookan_id', $tookan_region['region_id'])->first();
            if (!$new_region) {
                $re = '';
                foreach ($tookan_region['region_data'] as $index => $i) {
                    $re .= $i['x'] . ' ' . $i['y'] . ((count($tookan_region['region_data']) == ($index + 1)) ? '' : ', ');
                }
                $data = [
                    'name' => $tookan_region['region_name'],
                    'description' => $tookan_region['region_description'],
                    'data' => $re,
                    'tookan_id' => $tookan_region['region_id'],
                ];
                Region::create($data);
            }
        }
        return $this->respondWithSuccess(Region::all());
    }

    public function addRegion(Request $request)
    {
        return $this->respondWithSuccess((new Tookan())->makeRequest('add_region', 'post', \request()->all(), true));
    }
}
