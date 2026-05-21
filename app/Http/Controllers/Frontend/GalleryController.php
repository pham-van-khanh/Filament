<?php

namespace App\Http\Controllers\Frontend;

use App\Enums\MediaType;
use App\Models\Media;
use Illuminate\Contracts\View\View;

class GalleryController
{
    public function __invoke(): View
    {
        $media = Media::query()
            ->where('type', MediaType::Image->value)
            ->whereHas('posts', fn ($query) => $query->visibleToPublic())
            ->latest()
            ->paginate(30);

        return view('frontend.pages.gallery', compact('media'));
    }
}
