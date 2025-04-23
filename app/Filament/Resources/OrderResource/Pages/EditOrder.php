<?php

namespace App\Filament\Resources\OrderResource\Pages;

use App\Filament\Resources\OrderResource;
use App\Models\Product;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\DB;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }

    protected function handleRecordUpdate($record, array $data): \App\Models\Order
    {
        return DB::transaction(function () use ($record, $data) {
            $originalProduct = Product::findOrFail($record->product_id);
            $originalProduct->increment('quantity', $record->quantity);

            $newProduct = Product::findOrFail($data['product_id']);

            if ($newProduct->quantity < $data['quantity']) {
                throw new \Exception("Not enough stock available.");
            }

            $newProduct->decrement('quantity', $data['quantity']);

            $record->update($data);

            return $record;
        });
    }
}
