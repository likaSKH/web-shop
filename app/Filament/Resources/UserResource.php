<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkActionGroup;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\TextInput;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function canAccess(): bool
    {
        return auth()->user()->is_admin;
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')->required(),
                TextInput::make('email')->required()->email(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name'),
                TextColumn::make('email'),
                TextColumn::make('balance')->money('usd')->label('Balance'),
                TextColumn::make('orders_count')->label('Number of Orders'),
            ])
            ->actions([
                Action::make('View Orders')
                    ->label('Orders')
                    ->icon('heroicon-o-eye')
                    ->url(fn (User $record) => route('filament.admin.resources.orders.index', [
                        'tableFilters[Customer][value]' => $record->id,
                    ]))
                    ->color('primary'),

                Action::make('editBalance')
                    ->label('Edit Balance')
                    ->icon('heroicon-o-pencil')
                    //ToDo adding to balance feature is for testing
                    // purposes, since this feature would need additional steps in reeal app
                    ->form(fn (User $record) => [
                        TextInput::make('balance')
                            ->numeric()
                            ->label('New Balance')
                            ->required()
                            ->default($record->balance),
                    ])
                    ->action(function (array $data, User $record) {
                        $record->update(['balance' => $data['balance']]);
                    })
                    ->modalHeading('Edit User Balance')
                    ->color('warning'),
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
            'index' => Pages\ListUsers::route('/'),
            //ToDo Create user feature needs deeper development,
            // with security features like email verification
            // and sending link where user can set their own password
            'create' => Pages\CreateUser::route('/create'),
        ];
    }

    public static function getNavigationLabel(): string
    {
        return 'Customers';
    }
}
