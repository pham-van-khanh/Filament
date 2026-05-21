<?php

namespace App\Filament\Resources\Media\Pages;

use App\Enums\MediaType;
use App\Filament\Resources\Media\MediaResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Storage;

class CreateMedia extends CreateRecord
{
    protected static string $resource = MediaResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['user_id'] = auth()->id();
        $data['disk'] ??= 'public';
        $data['path'] ??= '';
        $data['filename'] = $data['filename'] ?: basename((string) $data['path']);
        $data['original_name'] = $data['original_name'] ?: $data['filename'];

        if ($data['path'] && Storage::disk($data['disk'])->exists($data['path'])) {
            $data['mime_type'] = $data['mime_type'] ?: Storage::disk($data['disk'])->mimeType($data['path']);
            $data['size'] = $data['size'] ?: Storage::disk($data['disk'])->size($data['path']);
        }

        $data['type'] = $data['type'] ?: MediaType::fromMime($data['mime_type'] ?? null)->value;

        return $data;
    }
}

