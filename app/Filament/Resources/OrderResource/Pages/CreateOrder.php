<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;

    public static function canAccess(array $parameters = []): bool
    {
        return auth()->user()->isAdmin();
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordCreation(array $data): Order
    {
        return DB::transaction(function () use ($data) {
            $user = User::findOrFail($data['user_id']);
            $rawOrderProducts = $data['orderProducts'] ?? [];

            $orderProducts = collect($rawOrderProducts)
                ->groupBy('product_id')
                ->map(function ($items) {
                    $first = $items->first();
                    $totalQuantity = $items->sum('quantity');
                    $total = $items->sum(fn($i) => $i['price'] * $i['quantity']);

                    return [
                        'product_id' => $first['product_id'],
                        'price' => $first['price'], // assuming same price per product
                        'quantity' => $totalQuantity,
                        'total' => $total,
                    ];
                })->values();

            $orderTotal = $orderProducts->sum('total');

            if ($user->balance < $orderTotal) {
                Notification::make()
                    ->title('Insufficient Balance')
                    ->body('The user does not have enough balance to place this order.')
                    ->danger()
                    ->send();

                throw ValidationException::withMessages([
                    'orderProducts' => 'The user does not have enough balance to place this order.',
                ]);
            }

            foreach ($orderProducts as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->quantity < $item['quantity']) {
                    Notification::make()
                        ->title('Insufficient Stock')
                        ->body("Not enough stock available for product: {$product->name}.")
                        ->danger()
                        ->send();

                    throw ValidationException::withMessages([
                        'orderProducts' => "Not enough stock available for product: {$product->name}.",
                    ]);
                }

                $product->decrement('quantity', $item['quantity']);
            }

            $user->decrement('balance', $orderTotal);

            $order = Order::create([
                'user_id' => $data['user_id'],
                'user_name' => $data['user_name'],
                'user_email' => $data['user_email'],
                'total' => $orderTotal,
            ]);

            return $order;
        });
    }
}
