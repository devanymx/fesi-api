<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\ProductResource;
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

        //TODO: ADD PAGINATION TO THIS SECTION, PRODUCTS COULDN'T BE MORE THAN 50
        $warehouse = Warehouse::find($id);
        $products = $warehouse->products;
        return $this->sendResponse($products, 'Products retrieved successfully.');
    }

    /**
     * Add product to a warehouse
     *
     * @return \Illuminate\Http\Response
     */
    public function addProduct(Request $request, int $id){

        $input = $request->all();
        $product = $this->searchProduct($input['product_id']);
        $warehouse = Warehouse::find($id);

        $productInventory = $warehouse->products()->find($product->id);
        if ($productInventory) {
            $productInventory->pivot->quantity += $input['quantity'];
            $productInventory->pivot->save();
        }
        else {
            $productInventory = $warehouse->products()->attach($product, ['quantity' => $input['quantity']]);
        }

        return $this->sendResponse($productInventory, 'Product added successfully.');

    }

    /**
     * Substract a quantity of product from a warehouse
     *
     * @return \Illuminate\Http\Response
     */
    public function removeProduct(Request $request, int $id){

        $input = $request->all();
        $product = $this->searchProduct($input['product_id']);
        $warehouse = Warehouse::find($id);

        if ($input['quantity'] <= 0){
            return $this->sendError('Invalid quantity.', ['error' => 'Invalid number, should be more than 0.', 'subtract' => $input['quantity']], 400);
        }

        $productInventory = $warehouse->products()->find($product->id);
        if ($productInventory->pivot->quantity >= $input['quantity'] && $productInventory->pivot->quantity > 0) {
            $productInventory->pivot->quantity -= $input['quantity'];
            $productInventory->pivot->save();
        }
        else {
            return $this->sendError('Invalid quantity.', ['error' => 'There is not enough to subtract', 'current_quantity' => $productInventory->pivot->quantity, 'subtract' => $input['quantity']], 400);
        }

        return $this->sendResponse($productInventory, 'Product subtract successfully.');

    }

    /**
     * Transfer product from warehouse to warehouse
     *
     * @return \Illuminate\Http\Response
     */
    public function transferProduct(Request $request, int $id){

        $input = $request->all();
        $product = $this->searchProduct($input['product_id']);
        $originWarehouse = Warehouse::find($id);
        $destinyWarehouse = Warehouse::find($input['destiny_warehouse_id']);

        if(!$destinyWarehouse){
            return $this->sendError('Destiny warehouse not found.', ['error' => 'Destiny warehouse not found.'], 400);
        }
        if ($input['quantity'] <= 0){
            return $this->sendError('Invalid quantity.', ['error' => 'Invalid number, should be more than 0.', 'subtract' => $input['quantity']], 400);
        }
        if (!$product){
            return $this->sendError('Product not found.', ['error' => 'Product not found.'], 400);
        }
        if ($originWarehouse->id == $destinyWarehouse->id){
            return $this->sendError('Invalid warehouse.', ['error' => 'Origin and destiny warehouse are the same.'], 400);
        }

        //Substract product or delete product in origin warehouse.
        /* Checking if the product is already in the warehouse, if it is, it adds the quantity to the existing one, if it
        is not, it creates a new entry in the pivot table. */
        $productInventoryOrigin = $originWarehouse->products()->find($product->id);
        if ($productInventoryOrigin->pivot->quantity >= $input['quantity'] && $productInventoryOrigin->pivot->quantity > 0) {
            $productInventoryOrigin->pivot->quantity -= $input['quantity'];
            $productInventoryOrigin->pivot->save();
        }
        else {
            return $this->sendError('Invalid quantity.', ['error' => 'There is not enough to transfer product.', 'current_quantity' => $productInventoryOrigin->pivot->quantity, 'transfer_quantity' => $input['quantity']], 400);
        }

        //Add product or update quantity in destiny warehouse.
        /* Checking if the product is already in the warehouse, if it is, it adds the quantity to the existing one, if it
        is not, it creates a new entry in the pivot table. */
        $productInventoryDestiny = $destinyWarehouse->products()->find($product->id);
        if ($productInventoryDestiny){
            $productInventoryDestiny->pivot->quantity += $input['quantity'];
            $productInventoryDestiny->pivot->save();
        }
        else {
            $productInventoryDestiny = $destinyWarehouse->products()->attach($product, ['quantity' => $input['quantity']]);
        }

        $transferedProducts = [
            'origin_warehouse' => $originWarehouse->id,
            'destiny_warehouse' => $destinyWarehouse->id,
            'product' => $product->id,
            'quantity_transfered' => $input['quantity'],
            'product_inventory_origin' => $productInventoryOrigin->pivot->quantity,
            'product_inventory_destiny' => $productInventoryDestiny->pivot->quantity
        ];

        return $this->sendResponse($transferedProducts, 'Product transfer successfully.');

    }


    public function searchProduct($id){
        return Product::find($id);
    }
}
