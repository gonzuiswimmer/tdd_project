<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Comment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class PostControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_top画面にアクセスするとブログ一覧とユーザーが表示される()
    {
        // 準備
        $post1 = Post::factory()->has(Comment::factory()->count(3))->create([
            'title' => 'テストタイトル1'
        ]);
        $post2 = Post::factory()->has(Comment::factory()->count(4))->create([
            'title' => 'テストタイトル2'
        ]);
        $post3 = Post::factory()->has(Comment::factory()->count(6))->create();
        // 実行
        // 検証
        $this->get('/')
        ->assertOk()
        ->assertSee('テストタイトル1')
        ->assertSee('テストタイトル2')
        ->assertSee($post1->user->name)
        ->assertSee($post2->user->name)
        ->assertSee('(3件のコメント)')
        ->assertSee('(4件のコメント)')
        ->assertSeeInOrder([
            '(6件のコメント)',
            '(4件のコメント)',
            '(3件のコメント)',
        ]);
    }

    public function test_詳細画面が表示される(){
        $post = Post::factory()->create();
        $this->get('posts/'.$post->id)
        ->assertOk()
        ->assertSee($post->title)
        ->assertSee($post->user->name);
    }

    public function test_非公開のブログは詳細画面が表示されない(){
        $post = Post::factory()->closed()->create();
        $this->get('posts/'.$post->id)
        ->assertForbidden();
    }

    public function test_公開済みのブログのみ一覧表示される(){
        $post1 = Post::factory()->create([
            'title' => 'これは公開済みのブログです',
        ]);
        $post2 = Post::factory()->closed()->create([
            'title' => 'これは非公開のブログです',
        ]);

        $this->get('/')
        ->assertDontSee('これは非公開のブログです')
        ->assertSee('これは公開済みのブログです');
    }
}