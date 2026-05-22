<?php

namespace App\Filament\Resources\Posts\Pages;

use App\Enums\PostStatus;
use App\Filament\Resources\Posts\PostResource;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\URL;

class EditPost extends EditRecord
{
    protected static string $resource = PostResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('editor')
                ->label('Open Editor')
                ->icon('heroicon-o-sparkles')
                ->url(fn () => route('admin.memories.editor', $this->record))
                ->openUrlInNewTab(),
            Action::make('preview')
                ->label('Preview')
                ->icon('heroicon-o-eye')
                ->url(fn () => URL::temporarySignedRoute('memories.preview', now()->addHour(), $this->record))
                ->openUrlInNewTab(),
            DeleteAction::make(),
        ];
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        if (($data['status'] ?? null) === PostStatus::Published->value && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }

        return $data;
    }
}
