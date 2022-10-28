<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CreditPaymentResource;
use App\Models\CreditPayment;
use Illuminate\Http\Request;

class CreditPaymentController extends BaseController
{
    public function index(){
        $payments = CreditPayment::all();
        return $this->sendResponse(CreditPaymentResource::collection($payments), 'Payments retrieved successfully.');
    }

    public function store(Request $request){
        $input = $request->all();
        $payment = CreditPayment::create($input);
        return $this->sendResponse(new CreditPaymentResource($payment), 'Payment created successfully.');
    }

    public function update(Request $request, CreditPayment $payment){
        $input = $request->all();
        $payment->update($input);
        return $this->sendResponse(new CreditPaymentResource($payment), 'Payment updated successfully.');
    }

    public function show($id){
        $payment = CreditPayment::find($id);
        if (is_null($payment)) {
            return $this->sendError('Payment not found.');
        }
        return $this->sendResponse(new CreditPaymentResource($payment), 'Payment retrieved successfully.');
    }

    public function destroy($payment){
        $payment->delete();
        return $this->sendResponse([], 'Payment deleted successfully.');
    }

    public function getCreditPayments($id){
        $payments = CreditPayment::find($id)->payments;
        return $this->sendResponse(CreditPaymentResource::collection($payments), 'Payments retrieved successfully.');
    }
}
