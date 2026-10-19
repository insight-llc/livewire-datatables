<?php

namespace Mediconesystems\LivewireDatatables\Tests\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Mediconesystems\LivewireDatatables\Tests\Models\DummyHasManyModel;

class DummyHasManyModelFactory extends Factory
{
    protected $model = DummyHasManyModel::class;

    public function definition(): array
    {
        return [
            'name' => fake()->word(),
        ];
    }
}
