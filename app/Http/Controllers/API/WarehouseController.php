<?php

namespace App\Http\Controllers\API;

use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;
use App\Http\Resources\WarehouseResource;
use App\Models\Category;
use App\Models\Product;
use Validator;

class WarehouseController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::all();

        return $this->sendResponse(WarehouseResource::collection($warehouses), 'Warehouses retrieved successfully.');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->all();

        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'capacity' => 'required',
            'current_capacity' => 'required',
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $warehouse = Warehouse::create($input);

        return $this->sendResponse(new WarehouseResource($warehouse), 'Warehouse created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  Int $id
     * @return \Illuminate\Http\Response
     */
    public function show(int $id)
    {
        $warehouse = Warehouse::find($id);

        if (is_null($warehouse)) {
            return $this->sendError('Warehouse not found.');
        }

        return $this->sendResponse(new WarehouseResource($warehouse), 'Warehouse retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $input = $request->all();

        $warehouse = Warehouse::find($id);

        $validator = Validator::make($input, [
            'name' => 'required',
            'address' => 'required',
            'phone' => 'required',
            'status' => 'required',
            'capacity' => 'required',
            'current_capacity' => 'required',
            'user_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $warehouse->name = $input['name'];
        $warehouse->address = $input['address'];
        $warehouse->phone = $input['phone'];
        $warehouse->status = $input['status'];
        $warehouse->capacity = $input['capacity'];
        $warehouse->current_capacity = $input['current_capacity'];
        $warehouse->user_id = $input['user_id'];
        $warehouse->save();

        return $this->sendResponse(new WarehouseResource($warehouse), 'Warehouse updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Warehouse  $warehouse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Warehouse $warehouse)
    {
        $warehouse->delete();

        return $this->sendResponse([], 'Warehouse deleted successfully.');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getProducts(int $id){
        $warehouse = Warehouse::find($id);
        $products = $warehouse->products;
        return $this->sendResponse($products, 'Products retrieved successfully.');
    }
}
