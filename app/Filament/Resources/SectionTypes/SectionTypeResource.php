<?php

namespace App\Filament\Resources\SectionTypes;

use App\Filament\Resources\SectionTypes\Pages\CreateSectionType;
use App\Filament\Resources\SectionTypes\Pages\EditSectionType;
use App\Filament\Resources\SectionTypes\Pages\ListSectionTypes;
use App\Models\SectionType;
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
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class SectionTypeResource extends Resource
{
    protected static ?string $model = SectionType::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-squares-2x2';

    protected static string|\UnitEnum|null $navigationGroup = 'Builder';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Definition')
                ->columns(2)
                ->components([
                    TextInput::make('name')->required()->maxLength(120),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(140),
                    Select::make('category')->options([
                        'text' => 'Text',
                        'image' => 'Image',
                        'gallery' => 'Gallery',
                        'video' => 'Video',
                        'timeline' => 'Timeline',
                        'metadata' => 'Metadata',
                        'decorative' => 'Decorative',
                        'interactive' => 'Interactive',
                        'visual' => 'Visual',
                    ])->required()->searchable(),
                    TextInput::make('icon')->placeholder('heroicon-o-photo'),
                    Select::make('preview_media_id')->relationship('previewMedia', 'original_name')->searchable()->preload(),
                    Toggle::make('is_active')->default(true),
                    TextInput::make('sort_order')->numeric()->default(0),
                    Textarea::make('description')->rows(3)->columnSpanFull(),
                ]),
            Section::make('Schemas')
                ->components([
                    Textarea::make('default_data_schema')
                        ->rows(10)
                        ->formatStateUsing(fn ($state) => self::jsonForForm($state ?: []))
                        ->dehydrateStateUsing(fn ($state) => self::jsonFromForm($state, []))
                        ->required(),
                    Textarea::make('default_style_schema')
                        ->rows(6)
                        ->formatStateUsing(fn ($state) => self::jsonForForm($state ?: []))
                        ->dehydrateStateUsing(fn ($state) => self::jsonFromForm($state, [])),
                    Textarea::make('available_variants')
                        ->rows(8)
                        ->formatStateUsing(fn ($state) => self::jsonForForm($state ?: []))
                        ->dehydrateStateUsing(fn ($state) => self::jsonFromForm($state, []))
                        ->required(),
                    Textarea::make('supported_templates')
                        ->rows(4)
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
                TextColumn::make('category')->badge()->sortable(),
                IconColumn::make('is_active')->boolean(),
                TextColumn::make('sort_order')->sortable(),
            ])
            ->filters([
                SelectFilter::make('category')->options([
                    'text' => 'Text',
                    'image' => 'Image',
                    'gallery' => 'Gallery',
                    'video' => 'Video',
                    'timeline' => 'Timeline',
                    'visual' => 'Visual',
                ]),
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
            'index' => ListSectionTypes::route('/'),
            'create' => CreateSectionType::route('/create'),
            'edit' => EditSectionType::route('/{record}/edit'),
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

