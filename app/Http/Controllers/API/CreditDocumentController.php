<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CreditDocumentResource;
use App\Models\CreditDocument;
use Illuminate\Http\Request;

class CreditDocumentController extends BaseController
{
    public function index(){
        /* Returning a collection of documents. */
        $documents = CreditDocument::all();
        return $this->sendResponse(CreditDocumentResource::collection($documents), 'Documents retrieved successfully.');
    }
}
