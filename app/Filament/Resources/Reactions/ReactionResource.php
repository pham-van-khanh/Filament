<?php

namespace App\Filament\Resources\Reactions;

use App\Filament\Resources\Reactions\Pages\CreateReaction;
use App\Filament\Resources\Reactions\Pages\EditReaction;
use App\Filament\Resources\Reactions\Pages\ListReactions;
use App\Models\Reaction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ReactionResource extends Resource
{
    protected static ?string $model = Reaction::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-heart';

    protected static string|\UnitEnum|null $navigationGroup = 'Public';

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Reaction')->columns(2)->components([
                Select::make('post_id')->relationship('post', 'title')->searchable()->preload()->required(),
                Select::make('reaction_type')->options(['like' => 'Like', 'love' => 'Love', 'wow' => 'Wow'])->required()->default('love'),
                TextInput::make('ip_address')->maxLength(45),
                TextInput::make('session_id')->maxLength(255),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('post.title')->label('Memory')->limit(35),
                TextColumn::make('reaction_type')->badge()->sortable(),
                TextColumn::make('ip_address')->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([SelectFilter::make('reaction_type')->options(['like' => 'Like', 'love' => 'Love', 'wow' => 'Wow'])])
            ->recordActions([EditAction::make()])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReactions::route('/'),
            'create' => CreateReaction::route('/create'),
            'edit' => EditReaction::route('/{record}/edit'),
        ];
    }
}
