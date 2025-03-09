<?php

use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schedule;

use App\Models\Book;
Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $books = Book::where('status', 'reserved')
        ->where('reservation_expires_at', '<', now());

    // Обновление статуса всех найденных книг
    $updatedCount = $books->update([
        'status' => 'available',
        'reservation_expires_at' => null
    ]);

    if ($updatedCount > 0) {
        echo ("Освобождено книг: {$updatedCount}");
    }
})->everyMinute(); // Запуск раз в минуту
