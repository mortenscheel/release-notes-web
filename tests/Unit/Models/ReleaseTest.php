<?php

declare(strict_types=1);

use App\Models\Release;

test('Release model exists', function (): void {
    expect(class_exists(Release::class))->toBeTrue();
});

test('Release model has required properties', function (): void {
    $release = new Release;

    expect($release->getFillable())->toContain('tag')
        ->and($release->getFillable())->toContain('url')
        ->and($release->getFillable())->toContain('body')
        ->and($release->getFillable())->toContain('published_at');
});
