<?php

namespace App\Filament\Widgets;

use App\Models\Post;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Widgets\TableWidget as BaseWidget;

class RecentPosts extends BaseWidget
{
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Post::query()
                    ->where('created_at', '>=', now()->subDay())
                    ->latest()
            )
            ->columns([
                TextColumn::make('content')->limit(50)->wrap(),
                TextColumn::make('user.name')->label('Author'),
                TextColumn::make('created_at')->since(),
            ]);
    }

    public static function canView(): bool
    {
        return true;
    }

    public function getColumnSpan(): int|string
    {
        return 'full';
    }
}
