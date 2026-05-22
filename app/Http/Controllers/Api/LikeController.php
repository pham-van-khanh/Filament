<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LikeController
{
    public function toggle(Request $request, Post $post): JsonResponse
    {
        $sessionId = $request->session()->getId();

        $reaction = $post->reactions()
            ->where('session_id', $sessionId)
            ->where('reaction_type', 'like')
            ->first();

        if ($reaction) {
            $reaction->delete();

            return response()->json([
                'liked' => false,
                'count' => $post->reactions()->where('reaction_type', 'like')->count(),
            ]);
        }

        $post->reactions()->create([
            'session_id' => $sessionId,
            'ip_address' => $request->ip(),
            'reaction_type' => 'like',
        ]);

        return response()->json([
            'liked' => true,
            'count' => $post->reactions()->where('reaction_type', 'like')->count(),
        ]);
    }
}
