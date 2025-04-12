<?php

namespace App\Filament\Widgets;

use App\Models\User;
use App\Models\Post;
use App\Models\Comment;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsOverview extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users',    User::count()),
            Stat::make('Total Posts',    Post::count()),
            Stat::make('Total Comments', Comment::count()),
        ];
    }
}
