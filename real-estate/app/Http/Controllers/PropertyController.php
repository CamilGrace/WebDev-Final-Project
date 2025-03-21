<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PropertyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $properties = Property::latest()->paginate(10);
        return view('properties.index', compact('properties'));
    }

    public function create()
    {
        return view('properties.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'location' => 'required',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('properties', 'public');
        }

        $validated['user_id'] = Auth::id();
        Property::create($validated);
        return redirect()->route('properties.index')->with('success', 'Property added!');
    }

    public function edit(Property $property)
    {
        return view('properties.edit', compact('property'));
    }

    public function update(Request $request, Property $property)
    {
        $this->authorize('update', $property);
        $validated = $request->validate([
            'title' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'location' => 'required',
            'image' => 'nullable|image',
        ]);

        if ($request->hasFile('image')) {
            Storage::delete($property->image);
            $validated['image'] = $request->file('image')->store('properties', 'public');
        }

        $property->update($validated);
        return redirect()->route('properties.index')->with('success', 'Property updated!');
    }

    public function destroy(Property $property)
    {
        $this->authorize('delete', $property);
        Storage::delete($property->image);
        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted!');
    }
}