<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\PostVisibility;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class MemoryPasswordController
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'string'],
        ]);

        if ($post->visibility !== PostVisibility::Password || ! $post->password || ! Hash::check($request->string('password'), $post->password)) {
            throw ValidationException::withMessages([
                'password' => 'The memory password is incorrect.',
            ]);
        }

        session()->put("post_password_{$post->id}", true);

        return redirect()->route('memories.show', $post->slug);
    }
}

