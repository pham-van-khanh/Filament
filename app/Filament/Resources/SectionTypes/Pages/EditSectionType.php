<?php

namespace App\Filament\Resources\SectionTypes\Pages;

use App\Filament\Resources\SectionTypes\SectionTypeResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditSectionType extends EditRecord
{
    protected static string $resource = SectionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}

