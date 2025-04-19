<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\SettingRequest;
use App\Services\API\SettingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    protected SettingService $settingService;
    /**
     * SettingController constructor.
     *
     * @param SettingService $settingService
     */
    public function __construct(SettingService $settingService)
    {
        $this->settingService = $settingService;
    }
    /**
     * Update the setting image.
     *
     * @param SettingRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateImage(SettingRequest $request)
    {
        try {
            $imageUrl = $this->settingService->updateImage($request);
            return response()->json([
                'status' => true,
                'message' => 'Image updated successfully',
                'data' => [
                    'image' => $imageUrl
                ]
            ]);
        } catch (\Exception $e) {
            Log::error("SettingController:UpdateImage - " . $e->getMessage());
            return response()->json([
                'status' => false,
                'message' => 'Failed to update image',
            ], 500);
        }
    }

    /**
     * Get the setting image.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getImage(){
        try{
          $image=$this->settingService->getImage();
            return response()->json([
                'status' => true,
                'message' => 'Image retrieved successfully',
                'data' => [
                    'image' => $image
                ]
            ]);
        }catch (\Exception $e){
            Log::error("SettingController:GetImage - " . $e->getMessage());
            throw $e;
        }
    }

    public function deleteImage(){
        try{
            $deleteImage= $this->settingService->deleteImage();
            return response()->json([
                'status' => true,
                'message' => 'Image deleted successfully',
                'data' => $deleteImage
            ]);

        }catch (\Exception $e){
            Log::error("SettingController:DeleteImage - " . $e->getMessage());
            throw $e;
        }
    }


}
