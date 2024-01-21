<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookResource extends Resource
{
    protected static ?string $model = Book::class;

    protected static ?string $navigationIcon = 'heroicon-o-book-open';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('title')
                    ->label(__('Book Title'))
                    ->autofocus()
                    ->required()
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 3,
                    ])
                    ->placeholder(__('Title')),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->inputMode('decimal')
                    ->minValue(1)
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 1,
                    ])
                    ->placeholder(__('Amount')),
                Select::make('category_id')
                    ->relationship('category', 'name')
                    ->label(__('Category'))
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->required()
                    ->searchable()
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->placeholder(__('Category'))
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->placeholder(__('Category Name')),
                    ])
                    ->editOptionForm([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->placeholder(__('Category Name')),
                    ]),
                Select::make('tags')
                    ->relationship('tags', 'name')
                    ->options(Tag::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->searchable()
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->placeholder(__('Tags'))
                    ->createOptionForm([
                        TextInput::make('name')
                            ->label(__('Name'))
                            ->required()
                            ->placeholder(__('Tag Name')),
                    ]),
                SpatieMediaLibraryFileUpload::make('file')
                    ->required()
                    ->acceptedFileTypes(['application/pdf'])
                    ->collection('books')
                    ->maxSize(1024 * 10)
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->placeholder(__('Book File')),
                SpatieMediaLibraryFileUpload::make('cover')
                    ->required()
                    ->maxSize(1024 * 2)
                    ->acceptedFileTypes(['image/*'])
                    ->collection('covers')
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 2,
                    ])
                    ->placeholder(__('Book Cover')),
                RichEditor::make('description')
                    ->required()
                    ->columnSpan([
                        'sm' => 1,
                        'md' => 4,
                    ])
                    ->placeholder(__('Description')),
            ])->columns([
                'sm' => 1,
                'md' => 2,
                'lg' => 4,
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('tags')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('file')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('cover')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('description')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
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
            'index' => Pages\ListBooks::route('/'),
            'create' => Pages\CreateBook::route('/create'),
            'edit' => Pages\EditBook::route('/{record}/edit'),
        ];
    }
}
