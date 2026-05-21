<?php

namespace App\Filament\Resources\PrivateMessages\Pages;

use App\Filament\Resources\PrivateMessages\PrivateMessageResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditPrivateMessage extends EditRecord
{
    protected static string $resource = PrivateMessageResource::class;

    protected function getHeaderActions(): array
    {
        return [DeleteAction::make()];
    }
}
