<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookResource\Pages;
use App\Filament\Resources\BookResource\RelationManagers;
use App\Models\Book;
use App\Models\Category;
use App\Models\Tag;
use BezhanSalleh\FilamentShield\Contracts\HasShieldPermissions;
use Filament\Forms;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Infolists\Components\Actions;
use Filament\Infolists\Components\Actions\Action;
use Filament\Infolists\Components\SpatieMediaLibraryImageEntry;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ViewAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
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
                    ]),
                // ->editOptionForm([
                //     TextInput::make('name')
                //         ->label(__('Name'))
                //         ->required()
                //         ->placeholder(__('Category Name')),
                // ]),
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
                    ->disableToolbarButtons([
                        'attachFiles',
                    ])
                    ->fileAttachmentsDirectory('attachments')
                    ->fileAttachmentsVisibility('public')
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
                TextColumn::make('description')
                    ->html()
                    ->limit(50)
                    ->wrap()
                    ->searchable()
                    ->sortable(),
                TextColumn::make('amount')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->searchable()
                    ->badge()
                    ->sortable(),
                TextColumn::make('tags.name')
                    ->badge()
                    ->searchable()
                    ->sortable(),
                SpatieMediaLibraryImageColumn::make('cover')
                    ->collection('covers'),
                TextColumn::make('user.email')
                    ->label(__('Created By'))
                    ->searchable()
                    ->sortable()
                    ->hidden(
                        fn () => !auth()->user()->hasRole('super_admin')
                    ),
            ])
            ->filters([
                SelectFilter::make('category_id')
                    ->label(__('Category'))
                    ->relationship('category', 'name')
                    ->options(Category::pluck('name', 'id')->toArray())
                    ->placeholder(__('Category')),
                SelectFilter::make('tags')
                    ->label(__('Tags'))
                    ->relationship('tags', 'name')
                    ->options(Tag::pluck('name', 'id')->toArray())
                    ->multiple()
                    ->searchable()
                    ->preload()
                    ->placeholder(__('Tags')),
            ])
            ->actions([
                ViewAction::make()
                    ->iconButton(),
                Tables\Actions\EditAction::make()
                    ->iconButton(),
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
            'view' => Pages\ViewBook::route('/{record}'),
        ];
    }

    //admin can view all books but user can view only his books
    public static function getEloquentQuery(): Builder
    {
        if (auth()->user()->hasRole('super_admin')) {
            return parent::getEloquentQuery();
        }
        // dd(auth()->user()->roles()->get());
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }
}
