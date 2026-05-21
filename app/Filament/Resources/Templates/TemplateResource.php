<?php

namespace App\Filament\Resources\Templates;

use App\Filament\Resources\Templates\Pages\CreateTemplate;
use App\Filament\Resources\Templates\Pages\EditTemplate;
use App\Filament\Resources\Templates\Pages\ListTemplates;
use App\Models\Template;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class TemplateResource extends Resource
{
    protected static ?string $model = Template::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-swatch';

    protected static string|\UnitEnum|null $navigationGroup = 'Builder';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Template')
                ->columns(2)
                ->components([
                    TextInput::make('name')->required()->maxLength(120),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(140),
                    Textarea::make('description')->rows(3)->columnSpanFull(),
                    Select::make('preview_media_id')->relationship('previewMedia', 'original_name')->searchable()->preload(),
                    TextInput::make('category')->maxLength(80),
                    TextInput::make('mood')->maxLength(80),
                    Toggle::make('is_active')->default(true),
                    Toggle::make('is_default')->default(false),
                    TextInput::make('sort_order')->numeric()->default(0),
                ]),
            Section::make('Design configuration')
                ->components([
                    Textarea::make('design_tokens')
                        ->rows(14)
                        ->formatStateUsing(fn ($state) => self::jsonForForm($state ?: []))
                        ->dehydrateStateUsing(fn ($state) => self::jsonFromForm($state, []))
                        ->required(),
                    Textarea::make('layout_config')
                        ->rows(8)
                        ->formatStateUsing(fn ($state) => self::jsonForForm($state ?: []))
                        ->dehydrateStateUsing(fn ($state) => self::jsonFromForm($state, []))
                        ->required(),
                    Textarea::make('supported_section_types')
                        ->rows(5)
                        ->formatStateUsing(fn ($state) => self::jsonForForm($state ?: []))
                        ->dehydrateStateUsing(fn ($state) => self::jsonFromForm($state, [])),
                    Textarea::make('settings')
                        ->rows(5)
                        ->formatStateUsing(fn ($state) => self::jsonForForm($state ?: []))
                        ->dehydrateStateUsing(fn ($state) => self::jsonFromForm($state, [])),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->searchable()->sortable(),
                TextColumn::make('slug')->searchable(),
                TextColumn::make('category')->badge(),
                TextColumn::make('mood')->badge(),
                IconColumn::make('is_active')->boolean(),
                IconColumn::make('is_default')->boolean(),
                TextColumn::make('sort_order')->sortable(),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('sort_order');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListTemplates::route('/'),
            'create' => CreateTemplate::route('/create'),
            'edit' => EditTemplate::route('/{record}/edit'),
        ];
    }

    protected static function jsonForForm(mixed $state): string
    {
        return is_string($state) ? $state : json_encode($state ?: [], JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    }

    protected static function jsonFromForm(mixed $state, array $fallback): array
    {
        $decoded = is_array($state) ? $state : json_decode((string) $state, true);

        return is_array($decoded) ? $decoded : $fallback;
    }
}

