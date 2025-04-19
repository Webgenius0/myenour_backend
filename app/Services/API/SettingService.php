<?php

namespace App\Services\API;

use App\Repositories\API\SettingRepositoryInterface;
use Illuminate\Support\Facades\Log;

class SettingService
{
    protected SettingRepositoryInterface $settingRepository;
    /**
     * SettingService constructor.
     *
     * @param SettingRepositoryInterface $settingRepository
     */
    public function __construct(SettingRepositoryInterface $settingRepository)
    {
        $this->settingRepository = $settingRepository;
    }
    /**
     * Update the setting image.
     *
     * @param $request
     * @return mixed
     */

    public function updateImage($request)
    {
        try{
            return $this->settingRepository->updateImage($request);
        }catch (\Exception $e){
            Log::error("SettingService:UpdateImage - " . $e->getMessage());
            throw $e;
        }
    }
    /**
     * Get the setting image.
     *
     * @return mixed
     */
    public function getImage()
    {
        try{
            return $this->settingRepository->getImage();
        }catch (\Exception $e){
            Log::error("SettingService:GetImage - " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteImage(){
        try{
            return $this->settingRepository->deleteImage();
        }catch (\Exception $e){
            Log::error("SettingService:DeleteImage - " . $e->getMessage());
            throw $e;
        }
    }
}
