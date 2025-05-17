<?php

declare(strict_types=1);

namespace App\Github;

use Spatie\LaravelData\Attributes\MapInputName;
use Spatie\LaravelData\Data;

class GithubRelease extends Data
{
    #[MapInputName('tag_name')]
    public string $tag;

    #[MapInputName('html_url')]
    public string $url;

    public ?string $body = null;

    public string $published_at;
}
