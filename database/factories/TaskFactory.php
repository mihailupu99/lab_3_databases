<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Category;
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
        // return [
        //     //
        //     'title' => $this->faker->sentence, // Titlu aleator
        //     'description' => $this->faker->paragraph, // Descriere aleatoare
        //     'category_id' => \App\Models\Category::factory(), // Leagă cu o categorie
        // ];
        return [
            'title' => $this->faker->sentence(),
            'description' => $this->faker->paragraph(),
            'user_id' => User::factory(), // This creates a new user if none exists
            'category_id' => Category::factory(), // Assumes you have a Category factory
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
