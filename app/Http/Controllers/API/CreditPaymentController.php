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
}
