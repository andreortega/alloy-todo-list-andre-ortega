<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Task;

class TaskFactory extends Factory
{
    protected $model = Task::class;

    public function definition(): array
    {
        return [
            'nome' => $this->faker->sentence(3),
            'descricao' => $this->faker->optional()->paragraph(),
            'finalizado' => false,
            'data_limite' => null,
        ];
    }
}
