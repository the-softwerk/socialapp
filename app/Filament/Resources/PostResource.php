<?php

namespace App\Filament\Resources;

use App\Models\Post;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\PostResource\Pages;

class PostResource extends Resource
{
    protected static ?string $model          = Post::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('user_id')
                  ->required()
                  ->relationship('user', 'name'),

            Textarea::make('content')
                    ->required()
                    ->minLength(1)
                    ->maxLength(500),

            Select::make('location')
                  ->required()
                  ->options([
                      'Room A'     => 'Room A',
                      'Zone 1'     => 'Zone 1',
                      'Hall 3'     => 'Hall 3',
                      'Library'    => 'Library',
                      'North Zone' => 'North Zone',
                  ]),

            FileUpload::make('image')
                      ->label('Image')
                      ->image()
                      ->directory('post-images')
                      ->acceptedFileTypes(['image/jpeg', 'image/png'])
                      ->maxSize(1024)
                      ->nullable(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->query(
                fn () => Post::query()->withCount('comments')
            )

            ->defaultSort(
                'comments_count', 'desc'
            )

            ->columns([
                TextColumn::make('content')->limit(50)->wrap()->sortable()->searchable(),
                TextColumn::make('user.name')->label('Author')->sortable()->searchable(),
                ImageColumn::make('image')->label('Image')->size(48)->circular()->default(null)->sortable(),
                TextColumn::make('location')->sortable()->searchable(),
                TextColumn::make('created_at')->dateTime(),
                TextColumn::make('comments_count')->label('Comments')->sortable(),
                ToggleColumn::make('approved')->label('Approved')->sortable()
            ])

            ->filters([
                TernaryFilter::make('image')
                             ->label('Image')
                             ->placeholder('Any')
                             ->trueLabel('With image')
                             ->falseLabel('Without image')
                             ->queries(
                                 true:  fn ($query) => $query->whereNotNull('image'),
                                 false: fn ($query) => $query->whereNull('image'),
                                 blank: fn ($query) => $query
                             ),

                SelectFilter::make('location')->options([
                    'Room A'     => 'Room A',
                    'Zone 1'     => 'Zone 1',
                    'Hall 3'     => 'Hall 3',
                    'Library'    => 'Library',
                    'North Zone' => 'North Zone',
                ]),

                SelectFilter::make('user_id')
                            ->relationship('user', 'name')
                            ->label('User'),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'edit'   => Pages\EditPost::route('/{record}/edit'),
            'index'  => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
        ];
    }
}
