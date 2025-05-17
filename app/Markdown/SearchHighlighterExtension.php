<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\ExtensionInterface;

readonly class SearchHighlighterExtension implements ExtensionInterface
{
    public function __construct(private string $searchString) {}

    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addInlineParser(new SearchHighlightParser($this->searchString));
    }
}
