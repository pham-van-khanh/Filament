<?php

namespace App\Filament\Resources\PrivateMessages\Pages;

use App\Filament\Resources\PrivateMessages\PrivateMessageResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePrivateMessage extends CreateRecord
{
    protected static string $resource = PrivateMessageResource::class;
}
