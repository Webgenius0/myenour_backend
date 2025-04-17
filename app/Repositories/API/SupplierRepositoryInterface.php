<?php

namespace App\Repositories\API;

use App\Models\Supplier;

interface SupplierRepositoryInterface
{
    // Define the methods your repository should implement
    public function storeSupplier(array $data): Supplier;
    public function getAllSuppliers(array $data);
    public function getSupplier($id);
    public function updateSupplier(Supplier $supplier, array $data):Supplier;
    public function deleteSupplier($id);
    public function searchSupplier($searchQuery);
    public function getAllSupplierList();
}
