<?php

namespace App\Http\Controllers;

use App\Models\Show;
use Illuminate\Http\Request;

class ShowController extends Controller
{
    public function index()
    {
        $shows = Show::where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return view('shows', compact('shows'));
    }
}
