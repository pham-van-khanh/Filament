<?php

namespace App\Filament\Resources\Posts;

use App\Enums\PostStatus;
use App\Enums\PostVisibility;
use App\Filament\Resources\Posts\Pages\CreatePost;
use App\Filament\Resources\Posts\Pages\EditPost;
use App\Filament\Resources\Posts\Pages\ListPosts;
use App\Models\Post;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\URL;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;

    protected static ?string $modelLabel = 'Memory';

    protected static ?string $pluralModelLabel = 'Memories';

    protected static ?string $slug = 'memories';

    protected static ?string $navigationLabel = 'Memories';

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-book-open';

    protected static string|\UnitEnum|null $navigationGroup = 'Content';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Basic information')
                ->columns(2)
                ->components([
                    TextInput::make('title')->required()->maxLength(180)->columnSpan(1),
                    TextInput::make('slug')->required()->unique(ignoreRecord: true)->maxLength(200)->columnSpan(1),
                    Textarea::make('excerpt')->rows(3)->columnSpanFull(),
                    Select::make('template_id')->relationship('template', 'name')->searchable()->preload()->required(),
                    Select::make('category_id')->relationship('category', 'name')->searchable()->preload(),
                    Select::make('tags')->relationship('tags', 'name')->multiple()->preload(),
                    Select::make('cover_media_id')->relationship('coverMedia', 'original_name')->searchable()->preload(),
                    DatePicker::make('memory_date'),
                    TextInput::make('location_name')->maxLength(255),
                ]),

            Section::make('Visibility and SEO')
                ->columns(2)
                ->components([
                    Select::make('status')
                        ->options(collect(PostStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()]))
                        ->required()
                        ->default(PostStatus::Draft->value),
                    Select::make('visibility')
                        ->options(collect(PostVisibility::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()]))
                        ->required()
                        ->default(PostVisibility::Private->value),
                    TextInput::make('password')->password()->revealable()->dehydrated(fn ($state) => filled($state)),
                    DateTimePicker::make('published_at'),
                    Toggle::make('is_featured')->default(false),
                    TextInput::make('seo_title')->maxLength(180)->columnSpanFull(),
                    Textarea::make('seo_description')->rows(3)->maxLength(300)->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('coverMedia.display_url')->label('Cover')->square(),
                TextColumn::make('title')->searchable()->sortable()->description(fn (Post $record) => $record->excerpt),
                TextColumn::make('template.name')->badge()->sortable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('visibility')->badge()->sortable(),
                IconColumn::make('is_featured')->boolean(),
                TextColumn::make('memory_date')->date()->sortable(),
                TextColumn::make('published_at')->dateTime()->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(collect(PostStatus::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                SelectFilter::make('visibility')->options(collect(PostVisibility::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])),
                SelectFilter::make('template')->relationship('template', 'name'),
                SelectFilter::make('category')->relationship('category', 'name'),
            ])
            ->recordActions([
                Action::make('editor')
                    ->label('Open Editor')
                    ->icon('heroicon-o-sparkles')
                    ->url(fn (Post $record): string => route('admin.memories.editor', $record))
                    ->openUrlInNewTab(),
                Action::make('publish')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('success')
                    ->visible(fn (Post $record): bool => $record->status !== PostStatus::Published)
                    ->requiresConfirmation()
                    ->action(fn (Post $record) => $record->update([
                        'status' => PostStatus::Published,
                        'visibility' => PostVisibility::Public,
                        'published_at' => $record->published_at ?: now(),
                    ])),
                Action::make('hide')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->visible(fn (Post $record): bool => $record->status === PostStatus::Published)
                    ->requiresConfirmation()
                    ->action(fn (Post $record) => $record->update(['status' => PostStatus::Hidden])),
                EditAction::make(),
                Action::make('preview')
                    ->icon('heroicon-o-eye')
                    ->url(fn (Post $record): string => URL::temporarySignedRoute('memories.preview', now()->addHour(), $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->defaultSort('updated_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPosts::route('/'),
            'create' => CreatePost::route('/create'),
            'edit' => EditPost::route('/{record}/edit'),
        ];
    }
}
