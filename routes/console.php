<?php

use App\Console\Commands\ExpiredReservationsCleanup;
use Illuminate\Foundation\Console\ClosureCommand;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

use App\Models\Reservation;

// Команда Artisan для вывода вдохновляющей цитаты
Artisan::command('inspire', function () {
    /** @var ClosureCommand $this */
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('schedule:run', function (Schedule $schedule) {
    $schedule->command(ExpiredReservationsCleanup::class)->everyMinute();
});
