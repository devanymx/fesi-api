<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CreditMakePaymentResource;
use App\Http\Resources\CreditProfileResource;
use App\Models\Client;
use App\Models\CreditProfile;
use Illuminate\Http\Request;

class CreditProfileController extends BaseController
{

    public function index(){
        $credits = CreditProfile::all();
        return $this->sendResponse(CreditProfileResource::collection($credits), 'Credits retrieved successfully.');
    }

    public function store(Request $request){
        $input = $request->all();
        $credit = CreditProfile::create($input);
        return $this->sendResponse(new CreditProfileResource($credit), 'Credit created successfully.');
    }

    public function show($id){
        $credit = CreditProfile::find($id);
        if (is_null($credit)) {
            return $this->sendError('Credit not found.');
        }
        return $this->sendResponse(new CreditProfileResource($credit), 'Credit retrieved successfully.');
    }

    public function update(Request $request, CreditProfile $credit){
        $input = $request->all();
        $credit->update($input);
        return $this->sendResponse(new CreditProfileResource($credit), 'Credit updated successfully.');
    }

    public function destroy(CreditProfile $credit){
        $credit->delete();
        return $this->sendResponse([], 'Credit deleted successfully.');
    }

    public function makePayment(Request $request, $id){
        $input = $request->all();

        $credit = Client::find($id)->creditProfile;

        $payment = $credit->payments()->create($input);

        return $this->sendResponse(new CreditMakePaymentResource($payment), 'Payment created successfully.');
    }

    public function getPayments($id){
        $payments = Client::find($id)->creditPayments;

        return $this->sendResponse(CreditMakePaymentResource::collection($payments), 'Payments retrieved successfully.');
    }

    public function getCreditDocuments($id){
        $documents = Client::find($id)->creditProfile->documents;

        return $this->sendResponse(CreditMakePaymentResource::collection($documents), 'Documents retrieved successfully.');
    }

    public function addDocument(Request $request, $id){
        $input = $request->all();

        $credit = Client::find($id)->creditProfile;

        if (is_null($credit)) {
            return $this->sendError('Credit not found.',400);
        }

        $document = $credit->documents()->create($input);

        return $this->sendResponse(new CreditMakePaymentResource($document), 'Document created successfully.');
    }

}
