<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Order;
use App\Models\Product;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\DB;

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
            $product = Product::findOrFail($data['product_id']);

            if ($product->quantity < $data['quantity']) {
                throw new \Exception("Not enough stock available.");
            }

            $product->decrement('quantity', $data['quantity']);

            return Order::create($data);
        });
    }
}
