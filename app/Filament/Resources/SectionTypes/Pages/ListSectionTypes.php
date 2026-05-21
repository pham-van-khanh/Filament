<?php

namespace App\Filament\Resources\SectionTypes\Pages;

use App\Filament\Resources\SectionTypes\SectionTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSectionTypes extends ListRecords
{
    protected static string $resource = SectionTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}

