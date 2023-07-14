<?php

namespace Tests\Feature\Models;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\DataBase\Eloquent\Collection;
use Tests\TestCase;

class PostTest extends TestCase
{
    use RefreshDatabase;

    public function test_userリレーションが返る()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(User::class,$post->user);
    }

    public function test_commentsリレーションが返る()
    {
        $post = Post::factory()->create();

        $this->assertInstanceOf(Collection::class ,$post->comments);
    }

    public function test_scopeOnlyOpenのテスト()
    {
        $post1 = Post::factory()->create();
        $post2 = Post::factory()->closed()->create();

        $posts = Post::onlyOpen()->get();

        $this->assertTrue($posts->contains($post1));
        $this->assertFalse($posts->contains($post2));
    }

    public function test_isClosedのテスト(){
        $openPost = Post::factory()->make();
        $closedPost = Post::factory()->closed()->make();

        $this->assertFalse($openPost->isClosed());
        $this->assertTrue($closedPost->isClosed());
    }

}
