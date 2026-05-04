<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Destination;

class DestinationController extends Controller
{
    // Show all destinations (sorted by rating 🔥)
    public function index()
    {
        $destinations = Destination::orderBy('rating', 'desc')->get();
        return view('destinations.index', compact('destinations'));
    }

    // Search destinations
    public function search(Request $request)
    {
        $query = $request->input('query', '');
        $category = $request->input('category', '');

        $destinations = Destination::query();

        if (!empty($query)) {
            $destinations->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('category', 'LIKE', "%{$query}%");
            });
        }

        if (!empty($category)) {
            $destinations->where('category', 'LIKE', "%{$category}%");
        }

        // 🔥 Sort by rating
        $destinations = $destinations->orderBy('rating', 'desc')->get();

        return response()->json($destinations);
    }

    // Show single destination
    public function show($id)
    {
        $destination = Destination::findOrFail($id);

        // Related by category (top rated)
        $related = Destination::where('category', $destination->category)
            ->where('id', '!=', $destination->id)
            ->orderBy('rating', 'desc')
            ->take(4)
            ->get();

        return view('destinations.show', compact('destination', 'related'));
    }
}