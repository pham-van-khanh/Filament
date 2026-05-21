<?php

namespace App\Filament\Resources\Media;

use App\Enums\MediaType;
use App\Filament\Resources\Media\Pages\CreateMedia;
use App\Filament\Resources\Media\Pages\EditMedia;
use App\Filament\Resources\Media\Pages\ListMedia;
use App\Models\Media;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class MediaResource extends Resource
{
    protected static ?string $model = Media::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-photo';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Upload')
                ->columns(2)
                ->components([
                    Select::make('disk')->options(['public' => 'Public'])->default('public')->required(),
                    Select::make('type')->options(collect(MediaType::cases())->mapWithKeys(fn ($case) => [$case->value => str($case->value)->headline()->toString()]))->required()->default(MediaType::Image->value),
                    FileUpload::make('path')->disk('public')->directory('media')->visibility('public')->columnSpanFull(),
                    TextInput::make('url')->url()->helperText('Optional external URL. Useful for demos or remote media.')->columnSpanFull(),
                    TextInput::make('original_name')->maxLength(255),
                    TextInput::make('filename')->maxLength(255),
                    TextInput::make('mime_type')->maxLength(120),
                    TextInput::make('alt')->maxLength(255)->columnSpanFull(),
                    Textarea::make('caption')->rows(3)->columnSpanFull(),
                    TextInput::make('width')->numeric(),
                    TextInput::make('height')->numeric(),
                    TextInput::make('duration')->numeric(),
                    TextInput::make('size')->numeric(),
                    Textarea::make('metadata')
                        ->rows(4)
                        ->formatStateUsing(fn ($state) => is_string($state) ? $state : json_encode($state ?: [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES))
                        ->dehydrateStateUsing(fn ($state) => is_array($state) ? $state : (json_decode((string) $state, true) ?: []))
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('display_url')->label('Preview')->square(),
                TextColumn::make('original_name')->searchable()->sortable(),
                TextColumn::make('type')->badge()->sortable(),
                TextColumn::make('mime_type')->toggleable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')->options(collect(MediaType::cases())->mapWithKeys(fn ($case) => [$case->value => str($case->value)->headline()->toString()])),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedia::route('/'),
            'create' => CreateMedia::route('/create'),
            'edit' => EditMedia::route('/{record}/edit'),
        ];
    }
}

