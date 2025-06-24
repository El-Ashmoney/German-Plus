<?php

namespace App\Http\Controllers;

use App\Models\Option;

class FrontendContoller extends Controller
{
    public function index()
    {
        return view('frontend.index');
    }
    public function privacy_policy()
    {
        $options = Option::where('name', '=', 'privacy_policy')->pluck('value', 'name')->all();
        return view('frontend.privacy_policy', compact('options'));
    }
    public function service_terms()
    {
        $options = Option::where('name','=', 'service_terms')->pluck('value', 'name')->all();
        return view('frontend.service_terms', compact('options'));
    }
}
