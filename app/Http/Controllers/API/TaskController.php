<?php

namespace App\Http\Controllers\API;

use App\Models\Locations;
use App\Models\Region;
use Illuminate\Http\Request;
use App\Http\Requests\TaskPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Services\Tookan;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use phpDocumentor\Reflection\Location;

use function GuzzleHttp\Promise\task;

class TaskController extends Controller
{


    public function index()
    {
        $tasks = getUser()->tasks()->latest()->get();
        return $this->respondWithSuccess($tasks);
    }

    public function show(Request $request, $task)
    {
        $tasks = getUser()->tasks()->findOrFail($task);
        return $this->respondWithSuccess($tasks);
    }

    public function create(TaskPostRequest $request)
    {
        $images = [];
        $data = $request->except(['images', 'donations', 'meta_data']);
        $location = Locations::find($request->location_id);
        if ($location)
            $data['region_id'] = $location->region_id;

        $data['job_status'] = 6;
        if ($file = $request->file('images')) {
            $images[] = moveFile($file);
            $data['ref_images'] = $images;
        }

        $task = getUser()->tasks()->create($data);
        if ($request->donations) {
            $task->donations = $request->donations;
            $task->save();
        }
        if ($request->meta_data) {
            $task->meta_data = $request->meta_data;
            $task->save();
        }
        $task->status()->create(['requested' => $task->created_at]);
        $tookan = (new Tookan())->createTask($task);
        if ($tookan->message === 'Successful') {
            $task->update(['tookan_id' => $tookan->data['appointments'][0]['job_id'] ?? '']);
        }
        return $this->respondWithSuccess($task->with(['status', 'location'])->first());
    }

    public function update(TaskPostRequest $request, $tasks)
    {
        $task = getUser()->locations()->findOrFail($tasks);
        $images = [];
        $data = $request->except('images');
        if ($file = $request->file('images')) {
            $images[] = moveFile($file);
            $data['ref_images'] = $images;
        }
        $task->update($data);
        return $this->respondWithSuccess($task);
    }

    public function destroy(Request $request, $tasks)
    {
        $task = getUser()->tasks()->findOrFail($tasks);
        $task->delete();
        return $this->respondWithSuccess(['message' => 'Deleted successfully']);
    }

    public function webTask(Request $request)
    {
        $validator = $this->getValidationFactory()->make(
            $request->all(),
            [
                "address" => 'required|string',
                "name" => 'string',
                'latitude' => 'required|numeric',
                'longitude' => 'required|numeric',
                'job_description' => 'required|string',
                'job_delivery_datetime' => 'required|date|after:yesterday',
                'phone' => 'required|string',
                'tags' => ['sometimes', 'array'],
                'tags.*' => ['string', 'min:3'],
            ]
        );
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        $tookan = (new Tookan())->createWebTask($request);
        return $this->respondWithSuccess((array)$tookan);
    }

    public function availableDates(Request $request)
    {
        // $validator = $this->getValidationFactory()->make($request->all(), [
        //     'lat' => ['required', 'numeric', 'max:90'],
        //     'lng' => ['required', 'numeric'],
        //     'date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:now'],
        // ]);
        // if ($validator->fails()) {
        //     return $this->respondWithErrors($validator->errors());
        // }
        // $array = array();
        // $location = $request->user('api')->locations()->whereLongitude($request->lng)
        //     ->whereLatitude($request->lat)->first();

        // if ($location) {
        //     $region = Region::latest()->where('id', $location->region_id)->first();
        // } else {
        //     $regions = (new Tookan())->findRegion($request->lat, $request->lng);
        //     $ids = [];
        //     foreach ($regions->data as $line) {
        //         $ids[] = $line['region_id'];
        //     }
        //     $region = Region::whereIn('tookan_id', $ids)->first();
        // }
        // $days = $region ? $region->days : [];
        // $now = $request->date ? Carbon::parse($request->date) : now();
        // $_14days = $request->date ? Carbon::parse($request->date)->addDays(7) : now()->addDays(7);
        // $dates = getDatesFromRange($now->format("Y-m-d"), $_14days->format("Y-m-d"), null);
        // foreach ($dates as $date) {
        //     $is_full = false;
        //     if (in_array(((int)$date->dayOfWeek + 1), $days)) {
        //         $count = DB::table('tasks')->whereIn('job_status', [0, 1, 4, 6, 7])
        //             ->whereDate('job_delivery_datetime', $date)->count();
        //     }
        //     $array[] = ['date' => $date->format('D, d/m/Y'), 'is_full' => $is_full,];
        // }
        // return $this->respondWithSuccess(['data' => $array]);


        $now = now();
        $_14days = now()->addDays(7);
        $dates = getDatesFromRange($now->format("Y-m-d"), $_14days->format("Y-m-d"), null);
        foreach ($dates as $date) {
            $array[] = ['date' => $date->format('D, d/m/Y'), 'is_full' => false,];
        }
        return $this->respondWithSuccess(['data' => $array]);
    }

    public function webDates(Request $request)
    {
        // $validator = $this->getValidationFactory()->make($request->all(), [
        //     'lat' => ['required', 'numeric', 'max:90'],
        //     'lng' => ['required', 'numeric'],
        //     'date' => ['nullable', 'date', 'date_format:Y-m-d', 'after_or_equal:now'],
        // ]);
        // if ($validator->fails()) {
        //     return $this->respondWithErrors($validator->errors());
        // }
        // if ($validator->fails()) {
        //     return $this->respondWithErrors($validator->errors());
        // }
        // //Check for regions on Tookan
        // $array = array();

        // $regions = (new Tookan())->findRegion($request->lat, $request->lng);
        // //Find tookan ids of the regions
        // $ids = [];
        // foreach ($regions->data as $line) {
        //     $ids[] = $line['region_id'];
        // }
        // $region = Region::whereIn('tookan_id', $ids)->first();
        // $days = [];
        // if ($region) {
        //     $days = $region->days;
        // }

        // $now = $request->date ? Carbon::parse($request->date) : now();
        // $_14days = $request->date ? Carbon::parse($request->date)->addDays(7) : now()->addDays(7);
        // $dates = getDatesFromRange($now->format("Y-m-d"), $_14days->format("Y-m-d"), null);
        // foreach ($dates as $date) {
        //     $is_full = true;
        //     $message = '';
        //     if (!$region) {
        //         $message = 'The cordinates does not fall under our Geo Fence';
        //     }
        //     if (in_array(((int)$date->dayOfWeek + 1), $days)) {
        //         $count = DB::table('tasks')->whereIn('job_status', [0, 1, 4, 6, 7])
        //             ->whereDate('job_delivery_datetime', $date)->count();
        //         if ($region) {
        //             $is_full = true;
        //             $message = 'The day is not available under this Geo Fence';

        //             if ($region->capacity >= $count) {
        //                 $is_full = false;
        //                 $message = 'The cordinates is available';
        //             } else {
        //                 $message = 'There are more orders  under this Geo Fence at the moment';
        //             }
        //         }
        //     }
        //     $array[] = ['date' => $date->format('D, d/m/Y'), 'is_available' => !$is_full, 'reason' => $message];
        // }
        // return $this->respondWithSuccess(['data' => $array]);


        $now = now();
        $_14days = now()->addDays(7);
        $dates = getDatesFromRange($now->format("Y-m-d"), $_14days->format("Y-m-d"), null);
        foreach ($dates as $date) {
            $array[] = ['date' => $date->format('D, d/m/Y'), 'is_available' => true, 'reason' => 'The cordinates is available'];
        }
        return $this->respondWithSuccess(['data' => $array]);
    }

    public function availableHours(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'date' => ['required', 'date', 'date_format:Y-m-d', 'after_or_equal:now'],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        $start = new \DateTime('00:00');
        $times = 24;
        $result[] = ['time' => $start->format('H:i A'), 'is_available' => false];
        for ($i = 0; $i < $times - 1; $i++) {
            $time = $start->add(new \DateInterval('PT1H'));
            $result[] = [
                'time' => $time->format('H:i A'),
                'is_available' => ($i >= 4 && $i < 18)
            ];
        }
        return $this->respondWithSuccess(['data' => $result,]);
    }

    public function availableDays(Request $request)
    {
        $validator = $this->getValidationFactory()->make($request->all(), [
            'lat' => ['required', 'numeric', 'max:90'],
            'lng' => ['required', 'numeric'],
        ]);
        if ($validator->fails()) {
            return $this->respondWithErrors($validator->errors());
        }
        //Check for regions on Tookan
        $regions = (new Tookan())->findRegion($request->lat, $request->lng);
        //Find tookan ids of the regions
        $ids = [];
        foreach ($regions->data as $line) {
            $ids[] = $line['region_id'];
        }
        $region = Region::whereIn('tookan_id', $ids)->first();
        $days = [];
        if ($region) {
            $days = $region->days;
        }
        $now = now();
        $_14days = now()->addDays(7);
        $dates = getDatesFromRange($now->format("Y-m-d"), $_14days->format("Y-m-d"), null);
        $array = array();
        foreach ($dates as $date) {
            $array[] = ['date' => $date->format('D, d/m/Y'), 'is_full' => in_array(((int)$date->day + 1), $days)];
        }
        return $this->respondWithSuccess(['data' => $array,]);
    }
}
