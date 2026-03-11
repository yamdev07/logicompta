<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\GeneralAccounting\JournalController;

Route::get('/', function () {
    return view('dashbord');
});

Route::prefix('accounting')->name('accounting.')->group(function () {
    Route::get('/journal', [JournalController::class, 'index'])->name('journal.index');
    Route::get('/journal/create', [JournalController::class, 'create'])->name('journal.create');
    Route::post('/journal/store', [JournalController::class, 'store'])->name('journal.store');
    Route::get('/journal/{id}', [JournalController::class, 'show'])->name('journal.show');
    Route::get('/ledger/{account_id?}', [JournalController::class, 'ledger'])->name('ledger');
    Route::get('/balance', [JournalController::class, 'balance'])->name('balance');
    Route::get('/bilan', [JournalController::class, 'bilan'])->name('bilan');
    Route::get('/resultat', [JournalController::class, 'resultat'])->name('resultat');
    Route::get('/help', [JournalController::class, 'help'])->name('help');
});
