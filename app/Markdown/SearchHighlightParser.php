<?php

declare(strict_types=1);

namespace App\Markdown;

use League\CommonMark\Extension\CommonMark\Node\Inline\HtmlInline;
use League\CommonMark\Parser\Inline\InlineParserInterface;
use League\CommonMark\Parser\Inline\InlineParserMatch;
use League\CommonMark\Parser\InlineParserContext;

use function strlen;

readonly class SearchHighlightParser implements InlineParserInterface
{
    public function __construct(private string $searchString) {}

    public function getMatchDefinition(): InlineParserMatch
    {
        return InlineParserMatch::regex($this->searchString);
    }

    public function parse(InlineParserContext $inlineContext): bool
    {
        $match = $inlineContext->getFullMatch();
        $inlineContext->getCursor()->advanceBy(strlen($match));
        $inlineContext->getContainer()->appendChild(new HtmlInline("<mark>$match</mark>"));

        return true;
    }
}
