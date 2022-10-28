<?php

namespace App\Http\Controllers\API;

use App\Models\Department;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController as BaseController;
use App\Models\Product;
use Validator;
use App\Http\Resources\ProductResource;

class ProductController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $products = Product::all();

        return $this->sendResponse(ProductResource::collection($products), 'Products retrieved successfully.');
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
            'detail' => 'required',
            'department_id' => 'required',
            'category_id' => 'required',
        ]);

        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());
        }

        $product = Product::create($input);

        return $this->sendResponse(new ProductResource($product), 'Product created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (is_null($product)) {
            return $this->sendError('Product not found.');
        }

        return $this->sendResponse(new ProductResource($product), 'Product retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $input = $request->all();

        $product = Product::find($id);

        if (isset($input['name'])){
            $product->name = $input['name'];
        }
        if (isset($input['detail'])){
            $product->detail = $input['detail'];
        }
        if (isset($input['code'])) {
            $product->code = $input['code'];
        }
        if (isset($input['department_id'])) {
            $department = Department::find($input['department_id']);
            if ($department) {
                $product->department_id = $input['department_id'];
            }
        }
        if (isset($input['category_id'])) {
            $product->category_id = $input['category_id'];
        }
        if  (isset($input['image'])) {
            $product->image = $input['image'];
        }
        if (isset($input['price'])) {
            $product->price = $input['price'];
        }
        if (isset($input['status'])) {
            $product->status = $input['status'];
        }
        if (isset($input['taxes'])) {
            $product->taxes = $input['taxes'];
        }
        if (isset($input['maximum'])) {
            $product->maximum = $input['maximum'];
        }
        if (isset($input['minimum'])) {
            $product->minimum = $input['minimum'];
        }
        if (isset($input['unit'])) {
            $product->unit = $input['unit'];
        }
        if (isset($input['profit'])) {
            $product->profit = $input['profit'];
        }
        if (isset($input['sale_price'])) {
            $product->sale_price = $input['sale_price'];
        }
        if (isset($input['measurement'])) {
            $product->measurement = $input['measurement'];
        }
        if  (isset($input['description'])) {
            $product->description = $input['description'];
        }
        if (isset($input['profit'])) {
            $product->profit = $input['profit'];
        }
        if (isset($input['dealer_id'])) {
            $product->dealer_id = $input['dealer_id'];
        }

        $product->save();

        return $this->sendResponse(new ProductResource($product), 'Product updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {

        $product = Product::find($id);

        if  (is_null($product)) {
            return $this->sendError('Department not found.');
        }

        $product->delete();

        return $this->sendResponse([], 'Product deleted successfully.');
    }
}
