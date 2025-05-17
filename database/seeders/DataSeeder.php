<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Github\GithubService;
use App\Jobs\SyncRepositoryReleasesJob;
use App\Models\Repository;
use Illuminate\Database\Seeder;

class DataSeeder extends Seeder
{
    private array $repos = [
        'laravel/framework',
        'larastan/larastan',
        'rectorphp/rector',
        'mkocansey/bladewind',
        'spatie/laravel-data',
        'spatie/laravel-permission',
        'spatie/laravel-medialibrary',
        'spatie/laravel-backup',
        'spatie/laravel-activitylog',
        'spatie/laravel-query-builder',
        'spatie/laravel-analytics',
        'spatie/laravel-sitemap',
        'spatie/laravel-translatable',
        'spatie/laravel-fractal',
        'spatie/laravel-tags',
        'spatie/laravel-newsletter',
    ];

    public function run(): void
    {
        foreach ($this->repos as $name) {
            [$org, $repo] = explode('/', (string) $name);
            $githubRepo = app(GithubService::class)->getGithubRepository($org, $repo);
            $repository = Repository::firstOrCreate($githubRepo->toArray());
            SyncRepositoryReleasesJob::dispatchSync($repository);
        }
    }
}
