<?php

namespace App\Filament\Resources\Reactions\Pages;

use App\Filament\Resources\Reactions\ReactionResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReactions extends ListRecords
{
    protected static string $resource = ReactionResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
