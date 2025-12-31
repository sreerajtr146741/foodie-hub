<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AboutController extends Controller
{
    public function index()
    {
        try {
            return view('about');
        } catch (\Exception $e) {
            Log::error('About Page Error: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Unable to load about page.');
        }
    }
}
