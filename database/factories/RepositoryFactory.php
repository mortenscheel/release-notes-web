<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Repository;
use Illuminate\Database\Eloquent\Factories\Factory;

class RepositoryFactory extends Factory
{
    protected $model = Repository::class;

    public function definition(): array
    {
        return [
            'organization' => $this->faker->word(),
            'repository' => $this->faker->word(),
            'description' => $this->faker->paragraphs(asText: true),
            'stars' => $this->faker->numberBetween(0, 1000),
        ];
    }
}
