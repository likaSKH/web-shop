<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\Action;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form->schema([
            Select::make('product_id')
                ->label('Product')
                ->relationship('product', 'name')
                ->searchable()
                ->preload()
                ->required(),

            TextInput::make('quantity')
                ->numeric()
                ->minValue(1)
                ->maxValue(function (callable $get, ?Order $record) {
                    $product = Product::find($get('product_id'));
                    $currentQuantity = $record?->product_id === $get('product_id') ? $record?->quantity : 0;

                    return optional($product)->quantity + $currentQuantity;
                })->default(0)
                ->required()
                ->reactive()
                ->afterStateUpdated(fn ($state, callable $set, $get) =>
                    $set('total', Product::find($get('product_id'))?->price * $state)
                ),

            TextInput::make('total')
                ->numeric()
                ->disabled()
                ->dehydrated()
                ->required(),

            Select::make('user_id')
                ->label('Customer')
                ->relationship(
                    'user',
                    'name',
                    modifyQueryUsing: fn ($query) => $query->where('is_admin', false)
                )
                ->searchable()
                ->preload()
                ->required()
                ->reactive()
                ->afterStateUpdated(function ($state, callable $set) {
                    $user = \App\Models\User::find($state);
                    if ($user) {
                        $set('user_name', $user->name);
                        $set('user_email', $user->email);
                    }
                }),

            TextInput::make('user_name')
                ->label('Customer Name')
                ->disabled()
                ->dehydrated(),

            TextInput::make('user_email')
                ->label('Customer Email')
                ->disabled()
                ->dehydrated(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')->label('Customer'),
                TextColumn::make('product.name'),
                TextColumn::make('quantity'),
                TextColumn::make('total')->money('usd'),
                TextColumn::make('user.email'),
                TextColumn::make('created_at')->dateTime(),
            ])
            ->filters([
                Filter::make('Archived')
                    ->query(fn (Builder $query) => $query->onlyTrashed())
                    ->label('Archived Orders'),
                SelectFilter::make('Customer')
                    ->relationship('user', 'name')
                    ->label('Customer'),
            ])
            ->actions([
                EditAction::make(),
                Action::make('Archive')
                    ->icon('heroicon-o-archive-box')
                    ->color('warning')
                    ->requiresConfirmation()
                    ->visible(fn ($record) => !$record->trashed())
                    ->action(fn ($record) => $record->delete()),
                Action::make('restore')
                    ->label('Restore')
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('success')
                    ->visible(fn ($record) => $record->trashed())
                    ->action(fn ($record) => $record->restore()),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);
    }
}
