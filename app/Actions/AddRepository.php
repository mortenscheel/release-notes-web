<?php

declare(strict_types=1);

namespace App\Actions;

use App\Github\GithubService;
use App\Models\Repository;
use RuntimeException;

use function count;
use function explode;
use function filter_var;
use function parse_url;
use function preg_match;

class AddRepository
{
    public function __invoke(string $name): Repository
    {
        if (filter_var($name, FILTER_VALIDATE_URL)) {
            if (parse_url($name, PHP_URL_HOST) !== 'github.com') {
                throw new RuntimeException('Only Github repositories are supported');
            }
            $path = parse_url($name, PHP_URL_PATH) ?: '';
            $segments = explode('/', $path);
            if (count($segments) < 3) {
                throw new RuntimeException('Invalid repository name');
            }
            [,$organization, $repository] = $segments;
        } else {
            if (in_array(preg_match('{^\w+/\w+$}', $name), [0, false], true)) {
                throw new RuntimeException('Invalid repository name');
            }
            [$organization, $repository] = explode('/', $name);
        }
        $githubRepo = app(GithubService::class)->getGithubRepository($organization, $repository);

        return Repository::create($githubRepo->toArray());
    }
}
