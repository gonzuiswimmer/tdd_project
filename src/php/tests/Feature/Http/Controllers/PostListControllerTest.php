<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Post;
use App\Models\User;
use Tests\TestCase;

class PostListControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_top画面にアクセスするとブログ一覧とユーザーが表示される()
    {
        // 準備
        $post1 = Post::factory()->create([
            'title' => 'テストタイトル1'
        ]);
        $post2 = Post::factory()->create([
            'title' => 'テストタイトル2'
        ]);
        // 実行
        // 検証
        $this->get('/')
        ->assertOk()
        ->assertSee('テストタイトル1')
        ->assertSee('テストタイトル2')
        ->assertSee($post1->user->name)
        ->assertSee($post2->user->name);
    }
}
