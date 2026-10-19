<?php

namespace Mediconesystems\LivewireDatatables\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyModel;

class DummyModelFactory extends Factory
{
    protected $model = DummyModel::class;

    public function definition(): array
    {
        return [
            'subject' => fake()->sentence(),
            'category' => fake()->word(),
            'body' => fake()->paragraph(),
            'flag' => fake()->boolean(),
            'expires_at' => fake()->dateTimeBetween('now', '+ 4 weeks'),
        ];
    }
}
