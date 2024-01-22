<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use App\Models\User;
use BezhanSalleh\FilamentShield\Traits\HasWidgetShield;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends BaseWidget
{
    use HasWidgetShield;
    protected function getStats(): array
    {
        return [
            Stat::make('Total Books', Book::count())
                ->description('Total number of books in the database.')
                ->descriptionIcon('heroicon-o-book-open'),
            Stat::make('Total Categories', Category::count())
                ->description('Total number of categories in the database.')
                ->descriptionIcon('heroicon-o-book-open'),
            Stat::make('Total Tags', Tag::count())
                ->description('Total number of tags in the database.')
                ->descriptionIcon('heroicon-o-book-open'),
            Stat::make('Total Users', User::count())
                ->description('Total number of users in the database.')
                ->descriptionIcon('heroicon-o-book-open'),
        ];
    }
}
