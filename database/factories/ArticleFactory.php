<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    protected $model = Article::class;

    public function definition(): array
    {
        $categories = ['Game', 'Politik', 'Game Politik'];
        return [
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(6),
            'content' => $this->faker->paragraphs(4, true),
            'category' => $this->faker->randomElement($categories),
            'image_url' => 'https://picsum.photos/seed/'.strtolower($this->faker->word()).'/800/450',
        ];
    }
}
