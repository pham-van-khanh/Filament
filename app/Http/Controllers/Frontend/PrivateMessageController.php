<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PrivateMessageController
{
    public function store(Request $request, ?Post $post = null): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:80'],
            'email' => ['nullable', 'email', 'max:120'],
            'message' => ['required', 'string', 'min:2', 'max:1600'],
        ]);

        $post?->privateMessages()->create([
            ...$data,
            'status' => 'unread',
        ]);

        if (! $post) {
            \App\Models\PrivateMessage::query()->create([
                ...$data,
                'status' => 'unread',
            ]);
        }

        return back()->with('status', 'Tin nhan rieng da duoc gui.');
    }
}
