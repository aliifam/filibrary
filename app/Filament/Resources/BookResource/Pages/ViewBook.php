<?php

namespace App\Filament\Resources\BookResource\Pages;

use App\Filament\Resources\BookResource;
use App\Models\Book;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Pages\ViewRecord;

class ViewBook extends ViewRecord
{
    protected static string $resource = BookResource::class;

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->name('book')
            ->schema([
                Section::make()
                    ->columns([
                        'sm' => 1,
                        'xl' => 2,
                        '2xl' => 4,
                    ])
                    ->schema([
                        TextEntry::make('title')
                            ->label(__('Title'))
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 3,
                            ]),
                        TextEntry::make('amount')
                            ->label(__('Amount'))
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                            ]),
                        TextEntry::make('description')
                            ->html()
                            ->label(__('Description'))
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 3,
                            ]),
                        TextEntry::make('category.name')
                            ->badge()
                            ->label(__('Category'))
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 2,
                            ]),
                        TextEntry::make('tags.name')
                            ->badge()
                            ->label(__('Tags'))
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 2,
                            ]),
                        SpatieMediaLibraryImageEntry::make('cover')
                            ->hintAction(
                                Action::make('preview-cover')
                                    ->label(__('Preview Cover'))
                                    ->url(fn ($record) => $record->getMedia("covers")->last()->getUrl(), shouldOpenInNewTab: true)
                                    ->icon('heroicon-o-eye')
                            )
                            ->collection('covers')
                            ->columnSpan([
                                'sm' => 1,
                                'md' => 1,
                            ]),
                        Actions::make([
                            Action::make('back')
                                ->label(__('Back'))
                                ->url(fn ($record) => route('filament.admin.resources.books.index'))
                                ->icon('heroicon-o-arrow-left'),
                            Action::make('edit')
                                ->label(__('Edit'))
                                ->url(fn ($record) => route('filament.admin.resources.books.edit', $record->id))
                                ->icon('heroicon-o-pencil'),
                            Action::make('preview-book')
                                ->label(__('Read Book'))
                                ->url(fn ($record) => $record->getMedia("books")->last()->getUrl(), shouldOpenInNewTab: true)
                                ->icon('heroicon-o-eye'),
                        ])->columnSpan([
                            'sm' => 1,
                            'md' => 4,
                        ]),
                    ]),
            ]);
    }
}
