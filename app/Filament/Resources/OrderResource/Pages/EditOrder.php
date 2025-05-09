<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Forms\Form;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Actions\Action;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->isAdmin();
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('user_name')
                    ->label('User Name')
                    ->disabled(),

                TextInput::make('user_email')
                    ->label('User Email')
                    ->disabled(),

                TextInput::make('total')
                    ->label('Total')
                    ->disabled(),

                Repeater::make('orderProducts')
                    ->relationship()
                    ->label('Ordered Products')
                    ->schema([
                        Select::make('product_id')
                            ->relationship('product', 'name')
                            ->label('Product')
                            ->disabled(),

                        TextInput::make('price')->disabled(),
                        TextInput::make('quantity')->disabled(),
                        TextInput::make('total')->disabled(),
                    ])
                    ->disableItemCreation()
                    ->disableItemDeletion()
                    ->disableItemMovement()
                    ->columns(4),
            ]);
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        abort(403, 'This form is read-only.');
    }

    protected function getFormActions(): array
    {
        return [
            Action::make('cancel')
                ->label('Back To Orders')
                ->url($this->getResource()::getUrl('index')),
        ];
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

}
