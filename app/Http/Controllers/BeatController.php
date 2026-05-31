<?php

namespace App\Http\Controllers;

use App\Models\Beat;
use App\Models\Category;
use Illuminate\Http\Request;

class BeatController extends Controller
{
    public function index(Request $request)
    {
        $query = Beat::with('category');
        
        // Search
        if ($request->has('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        
        // Filter by category
        if ($request->has('category')) {
            $query->where('category_id', $request->category);
        }
        
        $beats = $query->paginate(12);
        $categories = Category::all();
        
        return view('beats.index', compact('beats', 'categories'));
    }
    
    public function show(Beat $beat)
    {
        return view('beats.show', compact('beat'));
    }
}