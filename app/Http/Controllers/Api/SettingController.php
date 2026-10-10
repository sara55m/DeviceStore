<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AboutResource;
use App\Http\Resources\ContactResource;
use App\Models\Setting;

class SettingController extends Controller
{
    public function about()
    {
        return response()->json([
            'messages' => __("user.about_retrieved_successfully"),
            'about'    => new AboutResource(Setting::forGroup('about')),
        ]);
    }

    public function contact()
    {
        return response()->json([
            'messages' => __("user.contact_retrieved_successfully"),
            'contact'  => new ContactResource(Setting::forGroup('contact')),
        ]);
    }
}
