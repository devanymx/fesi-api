<?php

namespace App\Http\Controllers\API;

use App\Exports\ClientsExport;
use App\Http\Resources\ClientResource;
use App\Http\Controllers\API\BaseController;
use App\Models\Client;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Validator;

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
            return $this->sendError('Validation Error.', $validator->errors(), 400);
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

    //Disable client
    public function disable($id){
        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        $client->status = 0;
        $client->save();

        return $this->sendResponse(new ClientResource($client), 'Client disabled successfully.');
    }

    //Enable client
    public function enable($id){
        $client = Client::find($id);

        if (is_null($client)) {
            return $this->sendError('Client not found.');
        }

        $client->status = 1;
        $client->save();

        return $this->sendResponse(new ClientResource($client), 'Client enabled successfully.');
    }

    public function export()
    {
        return Excel::download(new ClientsExport, 'clients.xlsx');
    }

    public function import(Request $request){
        $input = $request->all();

        $clients = [];
        foreach($input as $clientData){
            $validator = Validator::make($clientData, [
                'name' => 'required',
                'lastname' => 'required',
                'email' => 'required',
                'phone' => 'required',
                'details' => 'required'
            ]);

            if ($validator->fails()) {
                return $this->sendError('Validation Error.', $validator->errors());
            }

            $client = Client::create($clientData);
            $clients[] = $client;
        }

        return $this->sendResponse(ClientResource::collection($clients), 'Clients created successfully.');
    }


}
