<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            //
            'title' => $this->faker->sentence, // Titlu aleator
            'description' => $this->faker->paragraph, // Descriere aleatoare
            'category_id' => \App\Models\Category::factory(), // Leagă cu o categorie
        ];
    }
}
