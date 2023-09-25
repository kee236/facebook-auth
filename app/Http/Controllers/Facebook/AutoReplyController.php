<?php

namespace App\Http\Controllers\Facebook;

use App\Models\Keyword;
use App\Models\AutoReply;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AutoReplyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('facebook.auto-reply.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'keyword' => 'required|string|max:255',
            'message' => 'required|string',
            'type' => 'required|in:post,comment', // Indicates if it's for a post or comment
        ]);

        // Create a new keyword if it doesn't exist, or find an existing one
        $keyword = Keyword::firstOrCreate(['body' => $request->input('keyword')]);

	    $postId = null;
        $commentId = null;

        // Determine the type (post or comment) and associate the keyword accordingly
        if ($request->input('type') === 'post') {
            $keyword->posts()->attach($postId);
        } else {
            $keyword->comments()->attach($commentId);
        }

        // Store the auto-reply message in the database
        AutoReply::create([
            'keyword_id' => $keyword->id,
            'message' => $request->input('message'),
        ]);

        return redirect()->back()->with('success', 'Auto-reply rule created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }


}
