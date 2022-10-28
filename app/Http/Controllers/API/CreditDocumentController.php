<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\CreditDocumentResource;
use App\Models\CreditDocument;
use Illuminate\Http\Request;

class CreditDocumentController extends BaseController
{
    public function index(){

        $documents = CreditDocument::all();
        return $this->sendResponse(CreditDocumentResource::collection($documents), 'Documents retrieved successfully.');
    }

    public function store(Request $request){
        $input = $request->all();
        $document = CreditDocument::create($input);
        return $this->sendResponse(new CreditDocumentResource($document), 'Document created successfully.');
    }

    public function update(Request $request, CreditDocument $document){
        $input = $request->all();
        $document->update($input);
        return $this->sendResponse(new CreditDocumentResource($document), 'Document updated successfully.');
    }

    public function show($id){
        $document = CreditDocument::find($id);
        if (is_null($document)) {
            return $this->sendError('Document not found.');
        }
        return $this->sendResponse(new CreditDocumentResource($document), 'Document retrieved successfully.');
    }

    public function destroy($document){
        $document->delete();
        return $this->sendResponse([], 'Document deleted successfully.');
    }

}
