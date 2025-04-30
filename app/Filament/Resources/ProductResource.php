<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Illuminate\Database\Eloquent\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    /**
     * Restrict access to this resource based on user role.
     *
     * @param \Illuminate\Database\Eloquent\Model $record
     * @return bool
     */
    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('price')->numeric()->required(),
                TextInput::make('quantity')->numeric()->minValue(0)->required(),
                Select::make('categories')
                    ->multiple()
                    ->relationship('categories', 'name')
                    ->label('Categories')
                    ->preload()
                    ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Product Name'),
                TextColumn::make('price')->label('Price'),
                TextColumn::make('quantity')->label('Quantity'),
                TextColumn::make('categories.name')->label('Categories'),
                TextColumn::make('deleted_at')
                    ->label('Status')
                    ->formatStateUsing(fn ($record) => $record->deleted_at ? 'Inactive' : 'Active')
                    ->badge()
                    ->color(fn ($record) => $record->deleted_at ? 'danger' : 'success'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
    }
}
