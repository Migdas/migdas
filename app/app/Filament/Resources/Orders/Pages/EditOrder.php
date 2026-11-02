<?php

namespace App\Filament\Resources\Orders\Pages;

use App\Filament\Resources\Orders\OrderResource;
use App\Mail\OrderShipped;
use Filament\Actions\DeleteAction;
use Filament\Actions\ViewAction;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;
use Throwable;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            ViewAction::make(),
            DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        $order = $this->record;

        if ($order->status !== 'shipped' || $order->shipped_at !== null) {
            return;
        }

        // shipped_at ustawiamy dopiero po wysyłce - po błędzie poczty
        // ponowny zapis wyśle wiadomość jeszcze raz.
        try {
            Mail::to($order->email)->send(new OrderShipped($order));
        } catch (Throwable $e) {
            report($e);

            Notification::make()
                ->title('Nie udało się wysłać e-maila o wysyłce. Zapisz zamówienie ponownie.')
                ->danger()
                ->send();

            return;
        }

        $order->update(['shipped_at' => now()]);

        Notification::make()
            ->title('Klient dostał e-mail z numerem przesyłki.')
            ->success()
            ->send();
    }
}
