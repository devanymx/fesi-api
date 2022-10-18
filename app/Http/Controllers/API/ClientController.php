<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ClientResource;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends BaseController
{
    public function index(){
        $clients = Client::all();
        return $this->sendResponse(ClientResource::collection($clients), 'Clients retrieved successfully.');
    }

    public function store(Request $request){
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'details' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $client = Client::create($input);

        return $this->sendResponse(new ClientResource($client), 'Client created successfully.');
    }

    public function show($id){
        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        return $this->sendResponse(new ClientResource($client), 'Client retrieved successfully.');
    }

    public function update(Request $request, int $id){
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'lastname' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'details' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        $client->name = $input['name'];
        $client->lastname = $input['lastname'];
        $client->email = $input['email'];
        $client->phone = $input['phone'];
        $client->details = $input['details'];
        $client->save();

        return $this->sendResponse(new ClientResource($client), 'Client updated successfully.');
    }

    public function destroy($id){
        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        $client->delete();

        return $this->sendResponse([], 'Client deleted successfully.');
    }


}
