<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Reservation;

class ExpiredReservationsCleanup extends Command
{
    protected $signature = 'reservations:cleanup';
    protected $description = 'Удаляет просроченные бронирования';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expiredReservations = Reservation::where('status', 'active')
            ->where('expires_at', '<', now())
            ->get();

        foreach ($expiredReservations as $reservation) {
            // Освобождаем книгу
            $reservation->book->update([
                'status' => 'available',
                'reservation_expires_at' => null
            ]);

            // Удаляем бронирование
            $reservation->delete();
        }

        if ($expiredReservations->count() > 0) {
            $this->info("Удалено просроченных бронирований: {$expiredReservations->count()}");
        }
    }
}
