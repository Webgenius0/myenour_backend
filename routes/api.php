<?php

use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\SupplierController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    // Load the user along with their profile
    $user = $request->user()->load('profile'); // Assuming 'profile' is the relation name
    return response()->json($user);
})->middleware('auth:api');

//group routes for Supplier routes
Route::middleware('auth:api')->group(function () {
    //Group routes for Supplier routes
    Route::controller(SupplierController::class)->group(function () {
        Route::post('/supplier', 'storeSupplier');
        Route::get('/supplier', 'getSuppliers');
        Route::get('/supplier/{id}', 'getSupplier');
        Route::put('/supplier/{id}', 'updateSupplier');
        Route::delete('/supplier/{id}', 'deleteSupplier');
        //search supplier
        Route::get('/search-supplier', 'searchSupplier');
        //download supplier
        // Route::get('/download-supplier', 'downloadSupplier');
    });

    //group routes for Inventory routes
    Route::controller(InventoryController::class)->group(function () {
        Route::post('/inventory', 'storeInventory');
        Route::get('/inventory', 'getInventory');
        Route::get('/inventory/{id}', 'getInventoryById');
        Route::put('/inventory/{id}', 'updateInventory');
        Route::delete('/inventory/{id}', 'deleteInventory');
        //search inventory
        Route::get('/search-inventory', 'searchInventory');

    });

      //group routes for event routes
      Route::controller(EventController::class)->group(function () {
        Route::post('/event', 'storeEvent');
        Route::get('/event', 'getEvents');
        Route::get('/event/{id}', 'getEventById');
        Route::put('/event/{id}', 'updateEvent');
        Route::delete('/event/{id}', 'deleteEvent');
        //search event
        Route::get('/search-event', 'searchEvent');

    });
});
