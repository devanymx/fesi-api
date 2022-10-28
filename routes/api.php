<?php

use App\Http\Controllers\API\AddressController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ClientController;
use App\Http\Controllers\API\DealerController;
use App\Http\Controllers\API\DepartmentController;
use App\Http\Controllers\API\WarehouseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\API\RegisterController;
use App\Http\Controllers\API\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::controller(RegisterController::class)->group(function(){
    Route::post('register', 'register');
    Route::post('login', 'login')->name('login');
    Route::get('no-logged', function () {
        $response = [
            'success' => false,
            'message' => 'Unauthenticated.',
        ];
        return response()->json($response, 401);
    })->name('no-logged');
});

Route::middleware('auth:sanctum')->group( function () {

    //Products
    Route::resource('products', ProductController::class);

    //Departments
    Route::resource('departments', DepartmentController::class);

    //Categories
    Route::resource('categories', CategoryController::class);

    //Dealers
    Route::resource('dealers', DealerController::class);

    //Addresses
    Route::resource('addresses', AddressController::class);

    //Clients
    //Export clients
    Route::get('clients/export', [ClientController::class, 'export']);
    //Import clients
    Route::post('clients/import', [ClientController::class, 'import']);
    //Clients Resource
    Route::resource('clients', ClientController::class);
    //Disable client
    Route::get('clients/{id}/disable', [ClientController::class, 'disable']);
    //Enable client
    Route::get('clients/{id}/enable', [ClientController::class, 'enable']);
    //Export clients


    //Warehouse
    Route::resource('warehouse', WarehouseController::class);
    //Inventory from warehouse
    Route::get('warehouse/{id}/inventory', [WarehouseController::class, 'getProducts']);
    //Add product to warehouse
    Route::post('warehouse/{id}/add', [WarehouseController::class, 'addProduct']);
    //Remove product from warehouse
    Route::post('warehouse/{id}/remove', [WarehouseController::class, 'removeProduct']);
    //Transfer product from warehouse
    Route::post('warehouse/{id}/transfer', [WarehouseController::class, 'transferProduct']);


    //Getting user info
    Route::get('user', [RegisterController::class, 'user'])->name('profile');


});
