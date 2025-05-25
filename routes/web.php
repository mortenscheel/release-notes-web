<?php

declare(strict_types=1);

use App\Livewire\IndexReleases;
use App\Livewire\IndexRepositories;
use App\Livewire\Settings\Appearance;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use Illuminate\Support\Facades\Route;

Route::get('/', IndexRepositories::class)->name('repositories.index');
Route::get('/{name}', IndexReleases::class)
    ->where('name', '[a-z0-9_.-]+/[a-z0-9_.-]+')
    ->name('repositories.show');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('settings.profile');
    Route::get('settings/password', Password::class)->name('settings.password');
    Route::get('settings/appearance', Appearance::class)->name('settings.appearance');
});

// require __DIR__.'/auth.php';
