<?php

namespace App\Http\Controllers\Facebook;

use App\Models\Comment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CommentController extends Controller
{
    public function addComments(Request $request){
        $comment = new Comment;
        $comment->commentable_id = $request->commentable_id;
        $comment->commentable_type = $request->commentable_type;
        $comment->save();
        return redirect()->back()->with('success', 'Created post successfully');
    }

    // public function getKeyword($id){
    //     $comment = Comment::findOrFail($id);
    //     return $comment;
    //     // return view('facebook.rule',compact('comment'));
    // }


}





