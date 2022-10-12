<?php

namespace App\Http\Controllers\API;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\DealerResource;
use App\Models\Dealer;
use Illuminate\Http\Request;
use Validator;

class DealerController extends BaseController
{
    public function index()
    {
        $dealers = Dealer::all();

        return $this->sendResponse(DealerResource::collection($dealers), 'Dealers retrieved successfully.');
    }

    public function show($id)
    {
        $dealer = Dealer::find($id);

        if (is_null($dealer)) {
            return $this->sendError('Dealer not found.','', 400);
        }

        return $this->sendResponse(new DealerResource($dealer), 'Dealer retrieved successfully.');
    }

    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'website' => 'required',
            'logo' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);
        }

        $dealer = Dealer::create($input);

        return $this->sendResponse(new DealerResource($dealer), 'Dealer created successfully.');
    }

    public function update(Request $request, $id)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'email' => 'required',
            'website' => 'required',
            'logo' => 'required',
            'status' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors(),400);
        }

        $dealer = Dealer::find($id);
        if (is_null($dealer)) {
            return $this->sendError('Dealer not found.','', 400);
        }

        $dealer->name = $input['name'];
        $dealer->address = $input['address'];
        $dealer->phone = $input['phone'];
        $dealer->email = $input['email'];
        $dealer->website = $input['website'];
        $dealer->logo = $input['logo'];
        $dealer->status = $input['status'];
        $dealer->save();

        return $this->sendResponse(new DealerResource($dealer), 'Dealer updated successfully.');
    }

    public function destroy($id)
    {
        $dealer = Dealer::find($id);
        if (is_null($dealer)) {
            return $this->sendError('Dealer not found.', '',400);
        }

        $dealer->delete();

        return $this->sendResponse([], 'Dealer deleted successfully.');
    }
}
