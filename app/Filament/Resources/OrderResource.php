<?php

namespace App\Filament\Resources;

use App\Enums\OrderStatus;
use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use App\Models\Product;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Forms\Set;
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

    public static function canCreate(array $parameters = []): bool
    {
        return auth()->user()->isAdmin();
    }
    public static function form(Form $form): Form
    {
        return $form->schema([
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
                ->afterStateUpdated(function ($state, Set $set) {
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

            TextInput::make('total')
                ->label('Total')
                ->disabled()
                ->dehydrated(),

            Repeater::make('orderProducts')
                ->relationship()
                ->label('Products')
                ->schema([
                    Select::make('product_id')
                        ->label('Product')
                        ->options(fn () => Product::where('quantity', '>', 0)->pluck('name', 'id')->toArray())
                        ->searchable()
                        ->preload()
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Get $get, Set $set, $state) {
                            $product = Product::find($state);
                            if ($product) {
                                $quantity = $get('quantity') ?? 1;
                                $set('price', $product->price);
                                $set('total', $product->price * $quantity);
                                $set('max_quantity', $product->quantity);
                            }
                        }),

                    TextInput::make('quantity')
                        ->numeric()
                        ->minValue(1)
                        ->required()
                        ->reactive()
                        ->afterStateUpdated(function (Set $set, Get $get, $state) {
                            $price = $get('price') ?? 0;
                            $set('total', $state * $price);
                        })
                        ->maxValue(fn (Get $get) => $get('max_quantity')),

                    TextInput::make('max_quantity')
                        ->hidden()
                        ->dehydrated(false),

                    TextInput::make('price')
                        ->numeric()
                        ->disabled()
                        ->required()
                        ->dehydrated(true),

                    TextInput::make('total')
                        ->numeric()
                        ->disabled()
                        ->required()
                        ->dehydrated(true),
                ])
                ->defaultItems(1)
                ->minItems(1)
                ->columns(2)
                ->dehydrated(true)
                ->afterStateHydrated(function (Get $get, Set $set) {
                    $orderProducts = $get('orderProducts') ?? [];
                    foreach ($orderProducts as $key => $orderProduct) {
                        $product = Product::find($orderProduct['product_id']);
                        if ($product) {
                            $set("orderProducts.{$key}.price", $product->price);
                            $set("orderProducts.{$key}.total", $product->price * $orderProduct['quantity']);
                        }
                    }
                })
                ->statePath('orderProducts')
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->label('Order ID'),
                TextColumn::make('user.name')->label('Customer'),
                TextColumn::make('user.email'),
                TextColumn::make('total')->money('usd'),
                TextColumn::make('status')->badge()->color(fn (string $state) => match ($state) {
                    'pending' => 'warning',
                    'canceled' => 'danger',
                    'completed' => 'success',
                    default => 'gray',
                }),
                TextColumn::make('created_at')->dateTime(),

                TextColumn::make('orderProducts')
                    ->label('Products')
                    ->getStateUsing(fn ($record) => $record->orderProducts
                        ->map(fn ($item) => "{$item->product->name} × {$item->quantity}")
                        ->implode(', ')
                    )->wrap(),
            ])
            ->filters([
                Filter::make('Canceled')
                    ->query(fn (Builder $query) => $query->onlyTrashed())
                    ->label('Canceled Orders'),
                SelectFilter::make('Customer')
                    ->relationship('user', 'name')
                    ->label('Customer'),
            ])
            ->actions([
                EditAction::make()
                    ->label('Show')
                    ->icon('heroicon-o-eye')
                    ->visible(fn ($record) => auth()->user()->isAdmin()),

                Action::make('cancel')
                    ->label('Cancel')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->visible(function ($record) {
                        $user = auth()->user();

                        return !$record->trashed() && $record->status === OrderStatus::Pending->value && (
                            $user->isAdmin() || $record->user_id === $user->id
                        );
                    })
                    ->action(function (Order $record) {
                        foreach ($record->orderProducts as $item) {
                            $item->product?->increment('quantity', $item->quantity);
                        }

                        $record->user?->increment('balance', $record->orderProducts->sum(
                            fn ($item) => $item->price * $item->quantity
                        ));

                        $record->update(['status' => OrderStatus::Canceled->value]);

                        $record->delete();
                    }),
                Action::make('complete')
                    ->label('Complete')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->visible(function (Order $record) {
                        return auth()->user()->isAdmin() &&
                            !$record->trashed() &&
                            $record->status === OrderStatus::Pending->value;
                    })
                    ->action(function (Order $record) {
                        $record->update(['status' => OrderStatus::Completed->value]);
                    }),
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
        $query = parent::getEloquentQuery()->withoutGlobalScopes([
            \Illuminate\Database\Eloquent\SoftDeletingScope::class,
        ]);

        $user = auth()->user();

        if (!$user->isAdmin()) {
            $query->where('user_id', $user->id);
        }

        return $query;
    }
}
