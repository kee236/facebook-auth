<?php

namespace App\Http\Controllers\Facebook;

use Exception;
use App\Models\Post;
use App\Models\User;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Services\FacebookService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Two\InvalidStateException;
use Illuminate\Support\Facades\Crypt; // Add this line


class FacebookController extends Controller
{

    protected $facebookService;

    public function __construct(FacebookService $facebookService)
    {
        $this->facebookService = $facebookService;
    }

    public function redirectToFacebook()
    {
        return $this->facebookService->redirectToFacebook();
    }

    public function handleFacebookCallback(Request $request)
    {
        try {
               // Pass the required dependencies to handleFbCallback
               $authUser = Socialite::driver('facebook')->user();
               $user = $this->facebookService->handleFbCallback(
                $authUser,          // Use the user from Socialite
                new User(), // Create a new instance of User model
                app('filesystem')->disk('local') // Use the local filesystem disk (you can change it to the appropriate disk)
               );
            if ($user) {
                return redirect()->route('dashboard')->with('success', 'Logged in with Facebook');
            }

            return redirect()->route('login')->with('error', 'Failed to log in with Facebook');
        } catch (InvalidStateException $e) {
            return redirect()->route('login')->with('error', 'Facebook authentication failed. Please try again.');
        } catch (Exception $e) {
            return redirect()->route('login')->with('error', 'An error occurred.');
        }
    }


    public function createPost(Request $request){

        $post = new Post;
        $post->name = $request->name;
        $post->message = $request->message;

        if ($request->hasFile('image')) {
                $uploadedImages = [];
                foreach ($request->file('image') as $img) {
                    $fileName = time() . '_' . $img->getClientOriginalName();
                    $path = $img->storeAs('images', $fileName, 'public');
                    $uploadedImages[] = '/storage/' . $path;
                }
                $post->image = implode(',', $uploadedImages);
            }
            $post->save();
        $this->facebookService->publishToPage($request);
        return redirect()->back()->with('success', 'Created post successfully');
    }



    public function index(){
            $users = User::select(['name','status','facebook_page_name','created_at','updated_at'])->get();
            return view('facebook.index',compact('users'));
    }

    public function getPost(){
        return view('facebook.post');
    }


    // Route::get('facebook/{id}/reply', [FacebookController::class, 'keywordWithAutoReply'])->name('facebook.reply');

    public function  keywordWithAutoReply($id){
        $keywordId = Keyword::findOrFail($id);
        $reply = AutoReply::select(['message','type'])->get();
        $message = $reply->message;
        if($reply->type){
            $type = $reply->type;
        }


        $autoReply = $this->facebookService->associateKeywordWithAutoReply($keywordId, $message, $type);

        if ($autoReply) {
            // Successfully associated keyword with auto reply
            // You can handle the response as needed
        } else {
            // Failed to associate keyword with auto reply
            // Handle the error or log it
        }
    }



}
