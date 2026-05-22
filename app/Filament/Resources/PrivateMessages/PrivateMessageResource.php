<?php

namespace App\Filament\Resources\PrivateMessages;

use App\Filament\Resources\PrivateMessages\Pages\CreatePrivateMessage;
use App\Filament\Resources\PrivateMessages\Pages\EditPrivateMessage;
use App\Filament\Resources\PrivateMessages\Pages\ListPrivateMessages;
use App\Models\PrivateMessage;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class PrivateMessageResource extends Resource
{
    protected static ?string $model = PrivateMessage::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-envelope';

    protected static string|\UnitEnum|null $navigationGroup = 'Public';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Private message')->columns(2)->components([
                Select::make('post_id')->relationship('post', 'title')->searchable()->preload(),
                Select::make('status')->options(['unread' => 'Unread', 'read' => 'Read', 'archived' => 'Archived'])->required()->default('unread'),
                TextInput::make('name')->required()->maxLength(80),
                TextInput::make('email')->email()->maxLength(120),
                Textarea::make('message')->required()->rows(6)->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('post.title')->label('Memory')->limit(30),
                TextColumn::make('name')->searchable(),
                TextColumn::make('message')->limit(60)->searchable(),
                TextColumn::make('status')->badge()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])
            ->filters([SelectFilter::make('status')->options(['unread' => 'Unread', 'read' => 'Read', 'archived' => 'Archived'])])
            ->recordActions([
                Action::make('mark_read')
                    ->label('Mark read')
                    ->icon('heroicon-o-envelope-open')
                    ->color('success')
                    ->visible(fn (PrivateMessage $record): bool => $record->status === 'unread')
                    ->action(fn (PrivateMessage $record) => $record->update(['status' => 'read'])),
                Action::make('archive')
                    ->icon('heroicon-o-archive-box')
                    ->color('gray')
                    ->visible(fn (PrivateMessage $record): bool => $record->status !== 'archived')
                    ->action(fn (PrivateMessage $record) => $record->update(['status' => 'archived'])),
                EditAction::make(),
            ])
            ->toolbarActions([BulkActionGroup::make([DeleteBulkAction::make()])])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPrivateMessages::route('/'),
            'create' => CreatePrivateMessage::route('/create'),
            'edit' => EditPrivateMessage::route('/{record}/edit'),
        ];
    }
}
