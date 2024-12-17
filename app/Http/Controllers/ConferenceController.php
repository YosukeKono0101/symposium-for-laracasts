<?php

namespace App\Http\Controllers;

use App\Models\Conference;
use Illuminate\Http\Request;

class ConferenceController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $conferences = Conference::orderBy('created_at', 'desc')->get();

        return view('conferences.index', ['conferences' => $conferences]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|max:255',
            'location' => 'required',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'description' => '',
            'url' => '',
        ]);

        Conference::create($validated);

        return redirect()->route('conferences.index')->with('status', 'Conference created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Conference $conference)
    {
        return view('conferences.show', ['conference' => $conference]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Conference $conference)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Conference $conference)
    {
        $conference->update($request->validate([
            'title' => 'required|max:255',
            'location' => 'required',
            'starts_at' => 'required|date',
            'ends_at' => 'required|date',
            'cfp_starts_at' => '',
            'cfp_ends_at' => '',
            'description' => '',
            'url' => '',
        ]));

        return redirect()->back()->with('status', 'Conference updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Conference $conference)
    {
        $conference->delete();

        return redirect()->route('conferences.index')->with('status', 'Conference deleted successfully!');
    }
}
