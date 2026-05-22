<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CommentController
{
    public function store(Request $request, Post $post): JsonResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:120'],
            'relation' => ['nullable', 'string', 'max:120'],
            'text' => ['required_without:content', 'nullable', 'string', 'max:1000'],
            'content' => ['required_without:text', 'nullable', 'string', 'max:1000'],
            'is_private' => ['sometimes', 'boolean'],
        ]);

        $comment = $post->comments()->create([
            'name' => $data['name'],
            'email' => $data['email'] ?? null,
            'relation' => $data['relation'] ?? null,
            'content' => $data['content'] ?? $data['text'],
            'is_private' => (bool) ($data['is_private'] ?? false),
            'status' => ($data['is_private'] ?? false) ? 'hidden' : 'pending',
        ]);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'name' => $comment->name,
                'relation' => $comment->relation,
                'content' => $comment->content,
                'is_private' => $comment->is_private,
                'status' => $comment->status,
            ],
        ], 201);
    }
}
