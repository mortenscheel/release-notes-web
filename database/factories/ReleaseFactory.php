<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Release;
use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

class ReleaseFactory extends Factory
{
    protected $model = Release::class;

    public function definition(): array
    {
        return [
            'repository_id' => Repository::factory(),
            'tag' => $this->faker->word(),
            'url' => $this->faker->url(),
            'body' => $this->faker->paragraphs(asText: true),
            'published_at' => now(),
        ];
    }
}
