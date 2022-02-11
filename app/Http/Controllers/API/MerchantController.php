<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests\MerchantPostRequest;
use App\Http\Controllers\Controller;
use App\Models\Merchant;

class MerchantController extends Controller
{


    public function index()
    {
        return Merchant::all();
    }

    public function show(Request $request, Merchant $merchant)
    {
        return $merchant;
    }

    public function store(MerchantPostRequest $request)
    {
        $data = $request->validated();
        $merchant = Merchant::create($data);
        return $merchant;
    }

    public function update(MerchantPostRequest $request, Merchant $merchant)
    {
        $data = $request->validated();
        $merchant->fill($data);
        $merchant->save();

        return $merchant;
    }

    public function destroy(Request $request, Merchant $merchant)
    {
        $merchant->delete();
        return $merchant;
    }

}
