<?php

namespace App\Http\Controllers\Facebook;

use App\Models\Post;
use App\Models\User;
use App\Models\FbRule;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{

    public function index(){
        $users = User::select('facebook_page_name')->get();
        return view('facebook.rule',compact('users'));
    }

    public function getPage(){
        $users = User::select(['status','facebook_page_name'])->get();
        $rules = FbRule::select(['rule_name','page_name'])->get();
        return view('facebook.page',compact('users','rules'));
    }


    public function add(Request $request) {
        $validatedData = $request->validate([
            'rule_name' => 'required|max:100',
            'page_name' => 'required',
            'location' => 'required',
            'currency' => 'required',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $rule = new FbRule;
        $rule->fill($validatedData);
        $rule->rule_name = $request->rule_name;
        $rule->page_name = $request->page_name;
        $rule->location = $request->location;
        $rule->currency = $request->currency;

        if ($request->hasFile('images')) {
            $uploadedImages = [];
            foreach ($request->file('images') as $image) {
                $fileName = time() . '_' . $image->getClientOriginalName();
                $path = $image->storeAs('images', $fileName, 'public');
                $uploadedImages[] = '/storage/' . $path;
            }
            $rule->images = implode(',', $uploadedImages);
        }

        $rule->save();

        return redirect()->route('user.page')->with('success', 'Created rule successfully');
    }




}
