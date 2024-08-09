<?php

namespace Database\Seeders;

use App\Models\Comment;
use App\Models\User;
use App\Models\Post;
use App\Models\Tag;

use Illuminate\Database\Seeder;

class  DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        User::factory(5)->create()->each(function ($user) {
            $posts = Post::factory(10)->create(['user_id' => $user->id]);

            $posts->each(function ($post) use ($user) {
                Comment::factory(5)->create([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                ])->each(function ($comment) {
                    $tags = Tag::factory(2)->create();
                    $comment->tags()->attach($tags);
                });

                $tags = Tag::factory(2)->create();
                $post->tags()->attach($tags);
            });
        });
    }
}
