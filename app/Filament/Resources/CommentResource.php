<?php

namespace App\Filament\Resources;

use App\Models\Comment;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\Filter;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Filters\SelectFilter;
use Filament\Resources\Pages\PageRegistration;
use App\Filament\Resources\CommentResource\Pages;

class CommentResource extends Resource
{
    protected static ?string $model          = Comment::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                  ->required()
                  ->relationship('user', 'name'),

            Select::make('post_id')
                  ->required()
                  ->relationship('post', 'content'),

            Textarea::make('body')
                    ->required()
                    ->minLength(1)
                    ->maxLength(500),
        ]);
    }

    /**
     * @param Table $table
     *
     * @return Table
     * @throws \Exception
     */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('body')->label('Comment body')->limit(40)->wrap()->searchable()->sortable(),
                TextColumn::make('user.name')->label('User')->searchable()->sortable(),
                TextColumn::make('post.content')->label('Post')->limit(40)->searchable()->sortable(),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])

            ->filters([
                Filter::make('created_at')
                      ->label('Last 7 Days')
                      ->query(fn ($query) => $query->where('created_at', '>=', now()->subDays(7))),

                SelectFilter::make('post_location')
                            ->label('Post Location')
                            ->options([
                                'Room A'     => 'Room A',
                                'Zone 1'     => 'Zone 1',
                                'Hall 3'     => 'Hall 3',
                                'Library'    => 'Library',
                                'North Zone' => 'North Zone',
                            ])
                            ->modifyQueryUsing(function ($query, $state) {
                                if ($state['value'] === null || $state['value'] === '') {
                                    return $query;
                                }

                                return $query->whereHas('post', fn ($q) => $q->where('location', $state));
                            })
            ]);
    }

    /**
     * @return array
     */
    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    /**
     * @return array|PageRegistration[]
     */
    public static function getPages(): array
    {
        return [
            'index'  => Pages\ListComments::route('/'),
            'edit'   => Pages\EditComment::route('/{record}/edit'),
            'create' => Pages\CreateComment::route('/create'),
        ];
    }
}
