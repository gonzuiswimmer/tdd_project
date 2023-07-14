<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(){
        $posts = Post::query()
        ->OnlyOpen()
        ->with(['user'])
        ->orderByDesc('comments_count')
        ->withCount('comments')
        ->get();

        return view('index',compact('posts'));
    }

    public function show(Post $post){
        if($post->isClosed()){
            abort(403);
        }
        return view('show',compact('post'));
    }
}
