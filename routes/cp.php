<?php

use Teamnovu\Localize\Http\Controllers\DashboardController;

Route::get('localize', [DashboardController::class, 'index'])->name('localize.dashboard');
Route::post('localize', [DashboardController::class, 'update'])->name('localize.dashboard.update');
