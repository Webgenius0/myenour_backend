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
    public function getAllSuppliers()
    {
        return Supplier::all();
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




}
