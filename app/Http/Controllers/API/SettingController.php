<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\SettingPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Setting;

class SettingController extends Controller
{


    public function index()
    {
        $settings =  Setting::first();
        if (!$settings) {
            Setting::create(['whatsapp' => '', 'phone' => '', 'locations' => []]);
        }
        return $this->respondWithSuccess(['data' => $settings]);
    }

    public function show($setting)
    {
        $settings =  Setting::first();
        if (!$settings) {
            Setting::create();
        }
        return $this->respondWithSuccess(['data' => $settings]);
    }

    public function store(SettingPostRequest $request)
    {
        $setting = Setting::first();
        if (!$setting) {
            $setting  = Setting::firstOrCreate($request->all());
        }
        $setting->update($request->all());
        return $this->respondWithSuccess(['data' => $setting]);
    }

    public function update(SettingPostRequest $request, $id)
    {

        $setting = Setting::first();
        if (!$setting) {
            $setting  = Setting::firstOrCreate($request->all());
        }
        $setting->update($request->all());
        return $this->respondWithSuccess(['data' => $setting]);
    }
}
