<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $tags = \App\Models\Tag::factory(10)->create(); // Creează 10 etichete

        // Leagă sarcinile cu etichetele
        \App\Models\Task::all()->each(function ($task) use ($tags) {
            $task->tags()->attach(
                $tags->random(rand(1, 3))->pluck('id')->toArray()
            );
        });
    }
}
