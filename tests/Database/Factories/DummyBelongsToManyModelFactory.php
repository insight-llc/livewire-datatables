<?php

namespace Mediconesystems\LivewireDatatables\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyBelongsToManyModel;

class DummyBelongsToManyModelFactory extends Factory
{
    protected $model = DummyBelongsToManyModel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
        ];
    }
}
