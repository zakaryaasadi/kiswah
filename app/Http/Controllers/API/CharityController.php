<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\CharityPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Charity;
use Illuminate\Http\Request;


class CharityController extends Controller
{
    public function index(Request $request)
    {
        $data = Charity::latest();
        if ($request->search) {
            $data = $data->whereLike(['name', 'description',], $request->search);
        }
        $data = $data->paginate(10);
        return $this->respondWithSuccess($data);
    }

    public function show($id)
    {
        $data = Charity::findOrFail($id);
        return $this->respondWithSuccess(compact('data'));
    }

    public function store(CharityPostRequest $request)
    {
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $data['image'] = moveFile($request->file('image'));
        }
        $data = Charity::create($data);
        return $this->respondWithSuccess(compact('data'));;
    }

    public function update(CharityPostRequest $request, $id)
    {
        $data = $request->except(['image']);
        if ($request->hasFile('image')) {
            $data['image'] = moveFile($request->file('image'));
        }
        $charity = Charity::findOrFail($id);
        $data = $charity->update($data);
        return $this->respondWithSuccess(compact('data'));
    }

    public function destroy($id)
    {
        $data = Charity::findOrFail($id);
        $data->delete();
        return $this->respondWithSuccess('Deleted successfully');
    }
}
