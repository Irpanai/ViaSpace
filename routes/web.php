<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Intern\DashboardController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('intern.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Admin Routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Calendar
    Route::get('/calendar', [\App\Http\Controllers\Admin\CalendarController::class, 'index'])->name('calendar');
    // History
    Route::get('/history', [\App\Http\Controllers\Admin\HistoryController::class, 'index'])->name('history');

    // Interns
    Route::get('/interns', [\App\Http\Controllers\Admin\InternController::class, 'index'])->name('interns.index');
    Route::post('/interns', [\App\Http\Controllers\Admin\InternController::class, 'store'])->name('interns.store');
    Route::delete('/interns/{id}', [\App\Http\Controllers\Admin\InternController::class, 'destroy'])->name('interns.destroy');
    
    // Time Settings
    Route::get('/time', [\App\Http\Controllers\Admin\SettingsController::class, 'time'])->name('time');
    Route::post('/time', [\App\Http\Controllers\Admin\SettingsController::class, 'updateTime'])->name('time.update');
    
    // Location Settings
    Route::get('/location', [\App\Http\Controllers\Admin\SettingsController::class, 'location'])->name('location');
    Route::post('/location', [\App\Http\Controllers\Admin\SettingsController::class, 'updateLocation'])->name('location.update');
    
    // Schedule Settings
    Route::get('/schedule', [\App\Http\Controllers\Admin\SettingsController::class, 'schedule'])->name('schedule');
    Route::post('/schedule/store', [\App\Http\Controllers\Admin\SettingsController::class, 'storeSchedule'])->name('schedule.store');
    Route::post('/schedule/generate', [\App\Http\Controllers\Admin\SettingsController::class, 'generateSchedule'])->name('schedule.generate');
    Route::post('/schedule/manual-add', [\App\Http\Controllers\ScheduleController::class, 'manualAdd'])->name('schedule.manualAdd');
    Route::post('/swap', [\App\Http\Controllers\ScheduleController::class, 'swap'])->name('swap');
    
    // Reminders
    Route::post('/calendar/reminder', [\App\Http\Controllers\Admin\CalendarController::class, 'sendReminder'])->name('calendar.reminder');
});

// Intern Routes
Route::middleware(['auth', \App\Http\Middleware\ForcePasswordChange::class])->prefix('intern')->name('intern.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Intern\DashboardController::class, 'index'])->name('dashboard');
    Route::post('/check-in', [\App\Http\Controllers\Intern\DashboardController::class, 'checkIn'])->name('checkin');
    Route::post('/check-out', [\App\Http\Controllers\Intern\DashboardController::class, 'checkOut'])->name('checkout');
    
    // Schedule
    Route::get('/schedule', [\App\Http\Controllers\Intern\ScheduleController::class, 'index'])->name('schedule');
    
    // History & Export
    Route::get('/history', [\App\Http\Controllers\Intern\HistoryController::class, 'index'])->name('history');
    Route::get('/history/export/excel', [\App\Http\Controllers\Intern\HistoryController::class, 'exportExcel'])->name('history.export.excel');
    Route::get('/history/export/pdf', [\App\Http\Controllers\Intern\HistoryController::class, 'exportPdf'])->name('history.export.pdf');
    
    // Profile
    Route::get('/profile', [\App\Http\Controllers\Intern\ProfileController::class, 'index'])->name('profile');
    Route::post('/profile', [\App\Http\Controllers\Intern\ProfileController::class, 'update'])->name('profile.update');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
