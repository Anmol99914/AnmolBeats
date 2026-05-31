<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Beat;
use App\Models\Category;
use Illuminate\Http\Request;

class BeatController extends Controller
{
    public function index()
    {
        $beats = Beat::with('category')->latest()->get();
        return view('admin.beats.index', compact('beats'));
    }
    
    public function create()
    {
        $categories = Category::all();
        return view('admin.beats.create', compact('categories'));
    }
    
    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'audio_file' => 'required|mimes:mp3,wav|max:10240'
        ]);
        
        $imagePath = $request->file('image')->store('beats/images', 'public');
        $audioPath = $request->file('audio_file')->store('beats/audio', 'public');
        
        Beat::create([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
            'audio_file' => $audioPath
        ]);
        
        return redirect()->route('admin.beats.index')->with('success', 'Beat created successfully!');
    }
    
    public function edit(Beat $beat)
    {
        $categories = Category::all();
        return view('admin.beats.edit', compact('beat', 'categories'));
    }
    
    public function update(Request $request, Beat $beat)
    {
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'audio_file' => 'nullable|mimes:mp3,wav|max:10240'
        ]);
        
        $data = $request->except(['image', 'audio_file']);
        
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('beats/images', 'public');
        }
        
        if ($request->hasFile('audio_file')) {
            $data['audio_file'] = $request->file('audio_file')->store('beats/audio', 'public');
        }
        
        $beat->update($data);
        return redirect()->route('admin.beats.index')->with('success', 'Beat updated successfully!');
    }
    
    public function destroy(Beat $beat)
    {
        $beat->delete();
        return redirect()->route('admin.beats.index')->with('success', 'Beat deleted successfully!');
    }
}