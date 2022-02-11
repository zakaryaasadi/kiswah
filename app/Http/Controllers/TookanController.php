<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Services\Tookan;
use Illuminate\Http\Request;

class TookanController extends Controller
{
    public function taskUpdate(Request $request)
    {
        logs()->info('call from tookan', $request->all());
    }

    public function processOrders()
    {
        $tokan_order = (new Tookan())->getTaskDetailsFromOrders(['ORD581401'],1);
        return $this->respondWithSuccess($tokan_order);
    }
}
