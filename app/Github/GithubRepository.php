<?php

declare(strict_types=1);

namespace App\Github;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class GithubRepository extends Data
{
    #[MapInputName('owner.login')]
    public string $organization;

    #[MapInputName('name')]
    public string $repository;

    public string $description;

    #[MapInputName('stargazers_count')]
    public int $stars;
}
