<?php

namespace App\Repositories\API;

use App\Models\Supplier;
use Illuminate\Support\Facades\Log;

class SupplierRepository implements SupplierRepositoryInterface
{
      /**
     * Create a new supplier.
     *
     * @param  array  $data
     * @return Supplier
     */
    public function storeSupplier(array $data): Supplier
    {
        return Supplier::create($data);
    }

    /**
     * Get all suppliers.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getAllSuppliers(array $data)
    {
        try {
            $query = Supplier::query();

            // Apply filters
            if (!empty($data['supplier_name'])) {
                $query->where('supplier_name', 'like', '%' . $data['supplier_name'] . '%');
            }

            if (!empty($data['lead_time_days'])) {
                $query->where('lead_time_days', $data['lead_time_days']);
            }

            if (!empty($data['pack_size_constraint'])) {
                $query->where('pack_size_constraint', '=', $data['pack_size_constraint']);
            }

            // Always return paginated results
            return $query->paginate(10);

        } catch (\Exception $e) {
            Log::error('Inventory filtering failed: ' . $e->getMessage());
            return response()->json([
                'message' => 'Failed to fetch inventory.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    /**
     * Get a supplier by ID.
     *
     * @param  int  $id
     * @return Supplier|null
     */
    public function getSupplier($id)
    {
        return Supplier::where('supplier_id', $id)->first();
    }

    /**
     * Update a supplier.
     *
     * @param  Supplier  $supplier
     * @param  array  $data
     * @return Supplier
     */
    public function updateSupplier($id, array $data): Supplier
    {
        $supplier = Supplier::where('supplier_id', $id)->first();

        $supplier->update($data);
        return $supplier;
    }

    /**
     * Delete a supplier.
     *
     * @param  Supplier  $supplier
     * @return void
     */
    public function deleteSupplier($id)
    {
        Supplier::where('supplier_id', $id)->delete();
    }

    /**
     * Get suppliers by name.
     *
     * @param  string  $name
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function searchSupplier($searchQuery)
{

 try{
        return Supplier::where('supplier_name', 'like', '%' . $searchQuery . '%')->get();
    } catch (\Exception $e) {
        Log::error('SupplierRepository::searchSupplier', ['error' => $e->getMessage()]);
        throw $e;
    }

}

public function getAllSupplierList(){
    try {
        $suppliers = Supplier::select('supplier_id', 'supplier_name')->get();
        return $suppliers;

    } catch (\Exception $e) {
        Log::error("SupplierRepository::getAllSupplierList", ['error' => $e->getMessage()]);
        throw $e;
    }
}



}
