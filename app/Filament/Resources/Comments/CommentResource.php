<?php

namespace App\Filament\Resources\Comments;

use App\Filament\Resources\Comments\Pages\CreateComment;
use App\Filament\Resources\Comments\Pages\EditComment;
use App\Filament\Resources\Comments\Pages\ListComments;
use App\Models\Comment;
use Filament\Actions\Action;
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
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CommentResource extends Resource
{
    protected static ?string $model = Comment::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-chat-bubble-left-right';

    protected static string|\UnitEnum|null $navigationGroup = 'Public';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Comment')->columns(2)->components([
                Select::make('post_id')->relationship('post', 'title')->searchable()->preload()->required(),
                Select::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'hidden' => 'Hidden'])->required()->default('pending'),
                TextInput::make('name')->required()->maxLength(80),
                TextInput::make('email')->email()->maxLength(120),
                TextInput::make('relation')->maxLength(120),
                Toggle::make('is_private')->label('Private message style')->default(false),
                Textarea::make('content')->required()->rows(5)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('post.title')->label('Memory')->searchable()->limit(30),
                TextColumn::make('name')->searchable(),
                TextColumn::make('relation')->badge()->toggleable(),
                TextColumn::make('content')->limit(55)->searchable(),
                TextColumn::make('is_private')->badge()->formatStateUsing(fn (bool $state): string => $state ? 'Private' : 'Public'),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([SelectFilter::make('status')->options(['pending' => 'Pending', 'approved' => 'Approved', 'hidden' => 'Hidden'])])
            ->recordActions([
                Action::make('approve')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn (Comment $record): bool => $record->status !== 'approved')
                    ->action(fn (Comment $record) => $record->update(['status' => 'approved'])),
                Action::make('hide')
                    ->icon('heroicon-o-eye-slash')
                    ->color('warning')
                    ->visible(fn (Comment $record): bool => $record->status !== 'hidden')
                    ->action(fn (Comment $record) => $record->update(['status' => 'hidden'])),
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListComments::route('/'),
            'create' => CreateComment::route('/create'),
            'edit' => EditComment::route('/{record}/edit'),
        ];
    }
}
