<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostStoreRequest;
use App\Mail\ReviewPost;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::paginate(10)->get();

        return view('post.index', compact('posts'));
    }

    public function create(Request $request): View
    {
        return view('post.create');
    }

    public function store(PostStoreRequest $request): RedirectResponse
    {
        $post = Post::create($request->validated());

        Mail::to($post->author)->send(new ReviewPost($post));

        return redirect()->route('post.create');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        $post->delete();

        $request->session()->flash('post.message', $post->message);

        return redirect()->route('post.create');
    }
}
