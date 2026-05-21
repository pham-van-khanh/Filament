<?php

namespace App\Policies;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use App\Models\Post;
use App\Models\User;

class PostPolicy
{
    public function before(?User $user, string $ability): ?bool
    {
        return $user?->is_admin ? true : null;
    }

    public function viewAny(?User $user): bool
    {
        return true;
    }

    public function view(?User $user, Post $post): bool
    {
        if ($post->status !== PostStatus::Published) {
            return false;
        }

        return match ($post->visibility) {
            PostVisibility::Public,
            PostVisibility::Unlisted => true,
            PostVisibility::Password => session()->has("post_password_{$post->id}"),
            PostVisibility::Private => false,
        };
    }

    public function create(User $user): bool
    {
        return $user->is_admin;
    }

    public function update(User $user, Post $post): bool
    {
        return $user->is_admin;
    }

    public function delete(User $user, Post $post): bool
    {
        return $user->is_admin;
    }

    public function restore(User $user, Post $post): bool
    {
        return $user->is_admin;
    }

    public function forceDelete(User $user, Post $post): bool
    {
        return $user->is_admin;
    }
}

