<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\UserPostRequest;
use App\Http\Controllers\Controller;
use App\Models\User;
class UserController extends Controller
{


    public function index()
    {
        return $this->respondWithSuccess(['data' => User::latest()->paginate(19)]);
    }

    public function show($id, User $user)
    {
        $user = $user->findOrFail($id);
        return $this->respondWithSuccess(['data' => $user]);
    }

    public function store(UserPostRequest $request)
    {
        $data = $request->validated();
        $user = User::create($data);
        return $this->respondWithSuccess(['data' => $user]);
    }

    public function update(UserPostRequest $request, User $user)
    {
        $data = $request->validated();
        $user->fill($data);
        $user->save();

        return $user;
    }

    public function destroy($id, User $user)
    {
        $user = $user->findOrFail($id);
        $user->delete();
        return $this->respondWithSuccess('Successfully deleted');
    }
}
