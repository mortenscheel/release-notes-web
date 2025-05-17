<?php

declare(strict_types=1);

use App\Http\Controllers\IndexRepositoriesController;
use App\Http\Controllers\ShowRepositoryController;
use App\Jobs\SyncRepositoryReleasesJob;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Models\Repository;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexRepositoriesController::class)
    ->name('repositories.index');
Route::get('/{organization}/{repository}', ShowRepositoryController::class)->name('repositories.show');
Route::get('/sync-all', function () {
    ini_set('max_execution_time', 0);
    Repository::all()->each(fn (Repository $repo) => SyncRepositoryReleasesJob::dispatchSync($repo));

    return redirect()->route('repositories.index');
});

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// require __DIR__.'/auth.php';
