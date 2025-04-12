<?php

namespace App\Http\Controllers;

use App\Helpers\Helper;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

class ResetController extends Controller
{

    public function RunMigrations()
    {

        Artisan::call('migrate:fresh --seed');
        Artisan::call('optimize:clear');
        // return Helper::jsonResponse(true, 'System Reset Successfully.', 200);
        return "Okay";
    }
    // public function stroageLink(){
    //     Artisan::call('storage:link');
    //     return "Storage Link Created Successfully";
    // }

}
