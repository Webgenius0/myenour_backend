<?php
namespace App\Repositories\API;

use App\Models\Setting;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use LDAP\Result;

class SettingRepository implements SettingRepositoryInterface
{
    public function updateImage($request)
{
    try {
        // Find or create the setting record
        $setting = Setting::firstOrNew(); // Same as updateOrCreate([], [])
// dd($setting);
        // Delete old image if it exists
        if ($setting->image && File::exists(public_path($setting->image))) {
            File::delete(public_path($setting->image));
        }
// dd($setting->image);
        // Upload new image
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->move(public_path('uploads/settings'), $filename);
            $setting->image = 'uploads/settings/' . $filename;
        }
        // dd($setting->image);

        $setting->save();

        return  $setting->image ? url($setting->image) : null;

    } catch (\Exception $e) {
        Log::error("SettingRepository:UpdateImage - " . $e->getMessage());
        throw $e;
    }
}
    public function getImage()
{
    try {
        $setting = Setting::first();
       return $setting ? url($setting->image) : null;
    } catch (\Exception $e) {
        Log::error("SettingRepository:GetImage - " . $e->getMessage());
        throw $e;
    }
}
public function deleteImage()
{
    try {
        $setting = Setting::first();
        if ($setting && $setting->image && File::exists(public_path($setting->image))) {
            File::delete(public_path($setting->image));
            $setting->image = null;
            $setting->save();
        }
        return $setting ? url($setting->image) : null;
    } catch (\Exception $e) {
        Log::error("SettingRepository:DeleteImage - " . $e->getMessage());
        throw $e;
    }
}

}
