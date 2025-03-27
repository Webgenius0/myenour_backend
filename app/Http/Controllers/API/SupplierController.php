<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SupplierRequest;
use App\Models\Supplier;
use App\Services\API\SupplierService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SupplierController extends Controller
{
    protected SupplierService $supplierService;

    public function __construct(SupplierService $supplierService)
    {
        $this->supplierService = $supplierService;
    }

    public function getSuppliers(){
        try {
            $suppliers = $this->supplierService->getAllSuppliers();
            return response()->json(

                [
                    'success' => true,
                    'status' => 200,
                    'data' => $suppliers
                ]);
        } catch (\Exception $e) {
            Log::error('SupplierController::getSuppliers', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
 /**
     * Store a new supplier.
     */
    public function storeSupplier(SupplierRequest $supplierRequest): JsonResponse
    {
        try {
            $this->supplierService->storeSupplier($supplierRequest->validated());
            return response()->json([
                'success' => true,
                'status' => 201,
                'message' => 'Supplier created successfully'
            ], 201);
        } catch (\Exception $e) {
            Log::error('SupplierController::storeSupplier', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
   public function getSupplier($id){
    try {
        $supplier = $this->supplierService->getSupplier($id);
        return response()->json([
            'success' => true,
            'status' => 200,
            'data' => $supplier
        ]);
    } catch (\Exception $e) {
        Log::error('SupplierController::getSupplier', ['error' => $e->getMessage()]);
        throw $e;
    }
        }

    /**
     * Update a supplier.
     */
    public function updateSupplier(int $id, SupplierRequest $request): JsonResponse
    {

        try {

           $suppliers= $this->supplierService->updateSupplier($id, $request->validated());
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Supplier updated successfully',
                'data' =>$suppliers
            ]);
        } catch (\Exception $e) {
            Log::error('SupplierController::updateSupplier', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
     /**
     * Delete a supplier.
     */
    public function deleteSupplier(int $id): JsonResponse
    {
        try {
            $this->supplierService->deleteSupplier($id);
            return response()->json([
                'success' => true,
                'status' => 200,
                'message' => 'Supplier deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('SupplierController::deleteSupplier', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    public function searchSupplier(Request $request): JsonResponse
    {
        try {
            // dd($supplier);
            $searchQuery = $request->query('query');

            $suppliers = $this->supplierService->searchSupplier( $searchQuery);
            return response()->json([
                'success' => true,
                'status' => 200,
                'data' => $suppliers
            ]);
        } catch (\Exception $e) {
            Log::error('SupplierController::searchSupplier', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'status' => 500,
                'message' => 'Server Error',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    
}
