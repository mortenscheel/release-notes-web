<?php

declare(strict_types=1);

namespace App\Github;

use Github\ResultPager;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Support\Collection;

class GithubService
{
    public function __construct(private readonly GitHubManager $github) {}

    /** @return Collection<int, GithubRelease> */
    public function getGithubReleases(string $organization, string $repository): Collection
    {
        $client = $this->github->connection();
        $pager = new ResultPager($client);
        $api = $this->github->repo()->releases();
        $data = $pager->fetchAll($api, 'all', [$organization, $repository]);
        // $data = $this->github->repo()->releases()->all($organization, $repository);

        return collect(GithubRelease::collect($data));
    }

    public function getGithubRepository(string $organization, string $repository): GithubRepository
    {
        return GithubRepository::from($this->github->repo()->show($organization, $repository));
    }
}
