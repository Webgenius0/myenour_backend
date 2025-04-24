<?php

use App\Http\Controllers\Api\DailyTrackingController;
use App\Http\Controllers\API\DashboardController;
use App\Http\Controllers\API\EventCategoryController;
use App\Http\Controllers\Api\EventController;
use App\Http\Controllers\API\EventInventoryController;
use App\Http\Controllers\API\HistoricalDataController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\API\SettingController;
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
        //all supplier list
        Route::get('/all-supplier', 'getAllSupplierList');
    });

    //group routes for Inventory routes
    Route::controller(InventoryController::class)->group(function () {
        Route::post('/inventory', 'storeInventory');
        Route::get('/inventory', 'filteringData'); // both list and search
        Route::get('/inventory/{id}', 'getInventoryById');
        Route::put('/inventory/{id}', 'updateInventory');
        Route::delete('/inventory/{id}', 'deleteInventory');
        Route::get('/all-inventory', 'getAllInventoryList');
    });


      //group routes for event routes
      Route::controller(EventController::class)->group(function () {
        Route::post('/event', 'storeEvent');
        Route::get('/event', 'getEvents');
        Route::get('/event/{id}', 'getEventById');
        Route::put('/event/{id}', 'updateEvent');
        Route::delete('/event/{id}', 'deleteEvent');
        Route::get('/search-event', 'searchEvent');
        Route::get('/all-event', 'getAllEventList');

    });


    //group routes for Daily Tracking routes
    Route::controller(DailyTrackingController::class)->group(function () {
        Route::post('/daily-tracking/return', 'returnToInventory');
        Route::get('/daily-tracking', 'index');
        Route::post('/daily-tracking', 'updateTracking');
    });
    //group routes for Order routes
    Route::controller(OrderController::class)->group(function () {
        Route::post('/order', 'storeOrder');
        Route::get('/order', 'getOrders');
        Route::get('/order/{id}', 'getOrderById');
        Route::put('/order/{id}', 'updateOrder');
        Route::delete('/order/{id}', 'deleteOrder');
        Route::post('/order/status-update/{id}', 'statusUpdate');

    });


    //Group routes for report routes
    Route::controller(ReportController::class)->group(function () {
        Route::get('/inventory-report', 'inventoryReport');
        Route::get('/event-report', 'eventReport');
        Route::get('/order-report', 'orderReport');
    });

    Route::controller(EventInventoryController::class)->group(function () {
        Route::post('/assign-inventory', 'assignInventory');
        Route::get('/assigned-inventory/{eventId}', 'getAssignedItems');
        Route::put('/assigned-inventory/{id}', 'updateAssignedInventory');
        Route::delete('/assigned-inventory/{id}', 'deleteAssignedInventory');
        Route::get('/assigned-inventory', 'getAssignedInventory');
    });

    Route::controller(DashboardController::class)->group(function () {
        Route::get('/dashboard-item', 'getAllItems');
        Route::get('/dashboard-upcoming-event', 'upcomingEvents');
        Route::get('/dashboard-upcoming-order', 'upcomingOrders');
    });

    Route::controller(SettingController::class)->group(function () {
        Route::post('/setting-image', 'updateImage');
        Route::get('/setting-image', 'getImage');
        Route::delete('/setting-image', 'deleteImage');
        // Route::put('/settings', 'updateSettings');
    });

    Route::controller(HistoricalDataController::class)->group(function () {
        Route::get('/historical-data', 'getAllHistoricalData');

    });

    //Event Category routes
    Route::controller(EventCategoryController::class)->group(function () {
        // Route::post('/event-category', 'getEventCategory');
        Route::get('/event-category-list', 'getEventCategoryList');
        Route::post('/event-category', 'storeEventCategory');
        Route::get('/event-category', 'getEventCategories');
        Route::get('/event-category/{id}', 'getEventCategoryById');
        Route::put('/event-category/{id}', 'updateEventCategory');
        Route::delete('/event-category/{id}', 'deleteEventCategory');
    }    );

    // Route::prefix('events')->group(function () {
    //     Route::post('assign-inventory', [EventInventoryController::class, 'assignInventory']);
    //     Route::get('{eventId}/assigned-inventory', [EventInventoryController::class, 'getAssignedItems']);
    // });
});
