<?php

namespace App\Http\Controllers\API;

use App\Http\Requests\DonationTypePatchRequest;
use Illuminate\Http\Request;
use App\Http\Requests\DonationTypePostRequest;
use App\Http\Controllers\Controller;
use App\Models\DonationType;

class DonationTypeController extends Controller
{


    public function index(Request $request)
    {
        $donations = DonationType::query();
//        $donations = $donations->where('is_acceptable', $request->accept ? 1 : 2);
//        $donations = $donations->where('is_acceptable', $request->available ? 1 : 3);
        return $this->respondWithSuccess(['data' => $donations->paginate(10)]);
    }

    public function unaccept()
    {
        $donations = DonationType::where('is_acceptable', 2)->paginate(10);
        return $this->respondWithSuccess(['data' => $donations]);
    }

    public function show(Request $request, DonationType $donation_type)
    {
        return $donation_type;
    }

    public function store(DonationTypePostRequest $request)
    {
        $image = moveFile($request->icon);
        $data = $request->all();
        $data['icon'] = $image;
        $donation_type = DonationType::create($data);
        return $this->respondWithSuccess(['data' => $donation_type]);
    }

    public function update(DonationTypePatchRequest $request, $id)
    {
        $data = $request->all();
        if ($request->hasFile('icon')) {
            $image = moveFile($request->icon);
            $data['icon'] = $image;
        }
        $donation_type = DonationType::findOrFail($id);
        $donation_type->update($data);
        return $this->respondWithSuccess(['data' => $donation_type]);
    }

    public function destroy($id)
    {
        $donation_type = DonationType::findOrFail($id);
        $donation_type->delete();
        return $this->respondWithSuccess('Deleted Successfully');
    }
}
