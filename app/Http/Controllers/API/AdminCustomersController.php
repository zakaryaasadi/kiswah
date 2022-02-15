<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CustomerPostRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Customer;

class AdminCustomersController extends Controller
{


    public function index(Request $request)
    {
        $customers = Customer::latest()->with(['locations'])->paginate(10);
        if ($request->has('search')) {
            $customers = Customer::whereLike(['name', 'phone', 'username', 'email'], $request->search)
                ->latest()->with(['locations'])->paginate(10);
        }
        return $this->respondWithSuccess(['data' => $customers]);
    }

    public function show($id)
    {
        $customers = Customer::findOrFail($id);
        return $this->respondWithSuccess(['data' => $customers]);
    }

    public function showTasks($id)
    {
        $customers = Customer::findOrFail($id);
        $tasks = $customers->tasks()->paginate(10);
        return $this->respondWithSuccess(['data' => $tasks]);
    }

    public function showLocations($id)
    {
        $customers = Customer::findOrFail($id);
        $data = $customers->locations()->paginate(10);
        return $this->respondWithSuccess(compact('data'));
    }

    public function store(CustomerPostRequest $request)
    {
        $image = moveFile($request->image);
        $data = $request->all();
        $data['image'] = $image;
        $customers = Customer::create($data);
        return $this->respondWithSuccess(['data' => $customers]);
    }

    public function update(CustomerPostRequest $request, $id)
    {
        $customers = Customer::where('uuid', $id)->firstOrFail();
        $data = $request->all();
        if ($request->hasFile('image')) {
            $image = moveFile($request->image);
            $data['image'] = $image;
        }
        $customers->update($data);
        $customers->save();
        return $this->respondWithSuccess(['data' => $customers]);
    }

    public function destroy(Request $request, Customer $customers)
    {
        $customers->delete();
        return $this->respondWithSuccess(['data' => $customers, 'message' => 'Deleted successfully']);

    }

}
