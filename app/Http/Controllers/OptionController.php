<?php

namespace App\Http\Controllers;

use App\Models\Option;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OptionController extends Controller
{
    public function edit(Request $request)
    {
        $options = Option::all()->pluck('value', 'name')->all();
        return view('option.edit', compact('options'));
    }
    public function update(Request $request)
    {
        $privacy_policy = Option::updateOrCreate(
            ['name' => 'privacy_policy', 'user_id'=>$request->user()->id],
            ['value' => empty($request->privacy_policy)?"Privacy Policy":$request->privacy_policy]
        );
        $privacy_policy->save();

        $service_terms = Option::updateOrCreate(
            ['name' => 'service_terms', 'user_id'=>$request->user()->id],
            ['value' => empty($request->service_terms)?"Service of terms":$request->service_terms]
        );
        $service_terms->save();
        return redirect()->back()->with('success', 'Options was updated');
    }
}
