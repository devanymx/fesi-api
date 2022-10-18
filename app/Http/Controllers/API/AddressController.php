<?php

/* Telling the compiler that this file is part of the `App\Http\Controllers` namespace. */
namespace App\Http\Controllers\API;
use App\Http\Resources\AddressResource;
use App\Models\Address;
use Illuminate\Http\Request;

class AddressController extends BaseController
{
    public function index(){
        /* Using the `Address` model to retrieve all the addresses from the database. */
        $addresses = Address::all();
        return $this->sendResponse(AddressResource::collection($addresses), 'Addresses retrieved successfully.');
    }

    public function store(Request $request){
        $input = $request->all();

        $validator = Validator::make($input, [
            'street' => 'required',
            'number' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'client_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $address = Address::create($input);

        return $this->sendResponse(new AddressResource($address), 'Address created successfully.');
    }

    public function show($id){
        $address = Address::find($id);

        if (is_null($address)) {
            return $this->sendError('Address not found.');
        }

        return $this->sendResponse(new AddressResource($address), 'Address retrieved successfully.');
    }

    public function update(Request $request, int $id){
        $input = $request->all();

        $validator = Validator::make($input, [
            'street' => 'required',
            'number' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip_code' => 'required',
            'country' => 'required',
            'phone' => 'required',
            'client_id' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $address = Address::find($id);

        $address->street = $input['street'];
        $address->number = $input['number'];
        $address->city = $input['city'];
        $address->state = $input['state'];
        $address->zip_code = $input['zip_code'];
        $address->country = $input['country'];
        $address->phone = $input['phone'];
        $address->client_id = $input['client_id'];
        $address->save();

        return $this->sendResponse(new AddressResource($address), 'Address updated successfully.');
    }

    public function destroy(Address $address){
        $address->delete();

        return $this->sendResponse([], 'Address deleted successfully.');
    }
}
