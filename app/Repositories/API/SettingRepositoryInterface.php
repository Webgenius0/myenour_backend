<?php

namespace App\Repositories\API;

interface SettingRepositoryInterface
{
    // Define the methods your repository should implement
    public function updateImage($request);
    public function getImage();
    public function deleteImage();
}
