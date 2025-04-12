<?php

namespace App\Filament\Resources;

use App\Models\User;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\Facades\Response;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Columns\ImageColumn;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use App\Filament\Resources\UserResource\Pages;

class UserResource extends Resource
{
    protected static ?string $model          = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('name')
                     ->required()
                     ->minLength(2)
                     ->maxLength(255),

            TextInput::make('email')
                     ->email()
                     ->required()
                     ->unique(ignoreRecord: true),

            Select::make('location')
                  ->required()
                  ->options([
                      'Room A'     => 'Room A',
                      'Zone 1'     => 'Zone 1',
                      'Hall 3'     => 'Hall 3',
                      'Library'    => 'Library',
                      'North Zone' => 'North Zone',
                  ]),

            FileUpload::make('avatar')
                      ->image()
                      ->nullable()
                      ->maxSize(512)
                      ->label('Avatar')
                      ->directory('avatars')
                      ->imagePreviewHeight('64')
                      ->acceptedFileTypes(['image/jpeg', 'image/png'])
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->sortable()->searchable(),
                TextColumn::make('email')->sortable()->searchable(),
                ImageColumn::make('avatar')->label('Avatar')->size(48)->circular()->defaultImageUrl(url('/images/default-avatar.png')),
                TextColumn::make('location')->sortable()->searchable(),
            ])

            ->filters([
                SelectFilter::make('location')
                            ->label('Location')
                            ->options([
                                'Room A'     => 'Room A',
                                'Zone 1'     => 'Zone 1',
                                'Hall 3'     => 'Hall 3',
                                'Library'    => 'Library',
                                'North Zone' => 'North Zone',
                            ]),

                TernaryFilter::make('has_posts')
                             ->label('Has posts')
                             ->placeholder('Any')
                             ->trueLabel('With posts')
                             ->falseLabel('Without posts')
                             ->queries(
                                 true:  fn ($query) => $query->whereHas('posts'),
                                 false: fn ($query) => $query->whereDoesntHave('posts'),
                                 blank: fn ($query) => $query
                             )
            ])

            ->headerActions([
                Action::make('Export CSV')
                      ->action(function () {
                          $csv   = implode(',', ['name', 'email', 'location']) . "\n";
                          $users = User::all(['name', 'email', 'location']);

                          foreach ($users as $user) {
                              $csv .= implode(',', [
                                      $user->name,
                                      $user->email,
                                      $user->location,
                                  ]) . "\n";
                          }

                          return Response::streamDownload(fn () => print($csv), 'users_export.csv');
                      })
                      ->label('Export CSV')
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
            'edit'   => Pages\EditUser::route('/{record}/edit'),
            'index'  => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
        ];
    }
}
