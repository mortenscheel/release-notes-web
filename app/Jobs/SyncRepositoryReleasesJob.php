<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Github\GithubRelease;
use App\Github\GithubService;
use App\Models\Repository;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncRepositoryReleasesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly Repository $repository) {}

    public function handle(): void
    {
        $github = app(GithubService::class);
        $githubRepo = $github->getGithubRepository($this->repository->organization, $this->repository->repository);
        $this->repository->update($githubRepo->toArray());
        $releases = $github->getGithubReleases(
            $this->repository->organization,
            $this->repository->repository
        );
        $this->repository->releases()->whereNotIn('tag', $releases->pluck('tag'))->delete();
        $releases->each(fn (GithubRelease $release) => $this->repository->releases()->updateOrCreate([
            'tag' => $release->tag,
        ], $release->toArray()));
        $this->repository->update(['synced_at' => now()]);
    }
}
