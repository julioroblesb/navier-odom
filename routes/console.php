<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Enviar el Hash-Chain Checkpoint todos los días a las 23:59
use Illuminate\Support\Facades\Schedule;
Schedule::command('audit:checkpoint')->dailyAt('23:59');
