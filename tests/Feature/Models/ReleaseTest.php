<?php

declare(strict_types=1);

use App\Models\Release;

test('version and stability attributes are generated correctly', function (): void {
    // Arrange
    $tags = [
        '1.0.0' => ['version' => '1.0.0.0', 'stability' => 'stable'],
        'v2.1.3' => ['version' => '2.1.3.0', 'stability' => 'stable'],
        '3.0.0-alpha' => ['version' => '3.0.0.0-alpha', 'stability' => 'alpha'],
        'v4.2.0-beta.1' => ['version' => '4.2.0.0-beta1', 'stability' => 'beta'],
        '5.0.0-RC1' => ['version' => '5.0.0.0-RC1', 'stability' => 'RC'],
    ];

    foreach ($tags as $tag => $expected) {
        // Act
        $release = Release::factory()->create(['tag' => $tag]);

        // Assert
        expect($release->version)->toBe($expected['version'])
            ->and($release->stability)->toBe($expected['stability']);
    }
});
