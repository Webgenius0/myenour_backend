<?php

namespace App\Services\API;

use App\Repositories\API\SupplierRepositoryInterface;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade as PDF;
class SupplierService
{
    protected SupplierRepositoryInterface $supplierRepository;

    public function __construct(SupplierRepositoryInterface $supplierRepository)
    {
        $this->supplierRepository = $supplierRepository;

    }

    public function getAllSuppliers(array $data){
        try{
            return $this->supplierRepository->getAllSuppliers($data);
        } catch (\Exception $e) {
            Log::error('SupplierService::getAllSuppliers', ['error' => $e->getMessage()]);
            throw $e;
        }
    }
    public function storeSupplier(array $data){
        try{
        return $this->supplierRepository->storeSupplier($data);
        } catch (\Exception $e) {
           Log::error('SupplierService::storeSupplier', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getSupplier($id){
        try{
            return $this->supplierRepository->getSupplier($id);
        } catch (\Exception $e) {
            Log::error('SupplierService::getSupplier', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function updateSupplier($id, array $data){
        try{

            return $this->supplierRepository->updateSupplier($id, $data);
        } catch (\Exception $e) {
            Log::error('SupplierService::updateSupplier', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function deleteSupplier($id){
        try{
            return $this->supplierRepository->deleteSupplier($id);
        } catch (\Exception $e) {
            Log::error('SupplierService::deleteSupplier', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function searchSupplier($searchQuery){

        try{
            return $this->supplierRepository->searchSupplier($searchQuery);
        } catch (\Exception $e) {
            Log::error('SupplierService::searchSupplier', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    public function getAllSupplierList(){
        try{
            return $this->supplierRepository->getAllSupplierList();
        } catch (\Exception $e) {
            Log::error('SupplierService::getAllSupplierList', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

}
