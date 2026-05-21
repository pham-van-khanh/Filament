<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:120'],
            'content' => ['required', 'string', 'min:2', 'max:1000'],
        ]);

        $post->comments()->create([
            ...$data,
            'status' => 'pending',
        ]);

        return back()->with('status', 'Binh luan da duoc gui va dang cho duyet.');
    }
}
