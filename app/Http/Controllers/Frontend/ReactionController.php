<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ReactionController
{
    public function store(Request $request, Post $post): RedirectResponse
    {
        $data = $request->validate([
            'reaction_type' => ['required', 'in:like,love,wow'],
        ]);

        $post->reactions()->updateOrCreate(
            [
                'session_id' => $request->session()->getId(),
                'reaction_type' => $data['reaction_type'],
            ],
            [
                'ip_address' => $request->ip(),
            ],
        );

        return back()->with('status', 'Da luu cam xuc cua ban.');
    }
}
