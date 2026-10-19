<?php

namespace Mediconesystems\LivewireDatatables\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyHasOneModel;

class DummyHasOneModelFactory extends Factory
{
    protected $model = DummyHasOneModel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
        ];
    }
}
