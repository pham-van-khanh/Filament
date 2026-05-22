<?php

namespace App\Http\Controllers\Admin;

use App\Enums\MediaType;
use App\Http\Controllers\Controller;
use App\Models\Media;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class MediaUploadController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $data = $request->validate([
            'file' => [
                'required',
                'file',
                'max:102400',
                'mimetypes:image/jpeg,image/png,image/webp,image/gif,video/mp4,video/webm,video/quicktime,audio/mpeg,audio/mp4,audio/wav,audio/ogg,application/pdf',
            ],
            'alt' => ['nullable', 'string', 'max:255'],
            'caption' => ['nullable', 'string', 'max:1000'],
        ]);

        /** @var UploadedFile $file */
        $file = $data['file'];
        $extension = $file->guessExtension() ?: $file->extension() ?: 'bin';
        $filename = Str::uuid().'.'.$extension;
        $path = $file->storeAs('media/'.now()->format('Y/m'), $filename, 'public');

        if (! $path) {
            throw ValidationException::withMessages([
                'file' => 'Khong the luu tep upload. Vui long thu lai.',
            ]);
        }

        $mimeType = $file->getMimeType() ?: $file->getClientMimeType() ?: Storage::disk('public')->mimeType($path);
        [$width, $height] = $this->imageDimensions($file, $mimeType);

        $media = Media::query()->create([
            'user_id' => $request->user()?->id,
            'disk' => 'public',
            'type' => MediaType::fromMime($mimeType),
            'mime_type' => $mimeType,
            'filename' => $filename,
            'original_name' => $file->getClientOriginalName(),
            'path' => $path,
            'alt' => $data['alt'] ?? pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'caption' => $data['caption'] ?? null,
            'width' => $width,
            'height' => $height,
            'size' => $file->getSize(),
            'metadata' => [
                'source' => 'memory-editor-upload',
            ],
        ]);

        return response()->json([
            'media' => [
                'id' => $media->id,
                'type' => $media->type->value,
                'mime_type' => $media->mime_type,
                'name' => $media->original_name,
                'original_name' => $media->original_name,
                'filename' => $media->filename,
                'url' => $media->display_url,
                'display_url' => $media->display_url,
                'alt' => $media->alt,
                'caption' => $media->caption,
                'width' => $media->width,
                'height' => $media->height,
                'size' => $media->size,
            ],
        ], 201);
    }

    protected function imageDimensions(UploadedFile $file, ?string $mimeType): array
    {
        if (! str_starts_with((string) $mimeType, 'image/')) {
            return [null, null];
        }

        $dimensions = @getimagesize($file->getRealPath());

        if (! is_array($dimensions)) {
            return [null, null];
        }

        return [$dimensions[0] ?? null, $dimensions[1] ?? null];
    }
}
