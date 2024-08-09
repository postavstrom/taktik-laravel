<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $title = $this->faker->word(4, true);

        return [
            'user_id' => 1,
            'title' => ucfirst($title),
            'text' => $this->faker->paragraph(5, true),
            'slug' => str_slug($title)
        ];
    }
}
