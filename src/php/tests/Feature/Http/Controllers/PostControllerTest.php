<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Carbon;
use App\Models\Comment;
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

    public function test_詳細画面が表示され、コメントが古い順に表示される(){
        $post = Post::factory()->create();
        [$comment1,$comment2,$comment3] = Comment::factory()->createMany([
            ['name' => 'firstCommentator','created_at' => now()->sub('5 days'),'post_id' => $post->id,],
            ['name' => 'secondCommentator','created_at' => now()->sub('3 days'),'post_id' => $post->id,],
            ['name' => 'thirdCommentator','created_at' => now()->sub('1 days'),'post_id' => $post->id,],
        ]);
       
        $this->get('posts/'.$post->id)
        ->assertOk()
        ->assertSee($post->title)
        ->assertSee($post->user->name)
        ->assertSeeInOrder(['firstCommentator','secondCommentator','thirdCommentator']);
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

    public function test_今日が12月25日だとメリークリスマスと表示される(){
        $post = Post::factory()->create();

        Carbon::setTestNow('2023-12-25');

        $this->get('posts/'.$post->id)
        ->assertOk()
        ->assertSee('メリークリスマス！');
    }

    public function test_今日が12月24日だとメリークリスマスと表示されない(){
        $post = Post::factory()->create();

        Carbon::setTestNow('2023-12-24');

        $this->get('posts/'.$post->id)
        ->assertOk()
        ->assertDontSee('メリークリスマス！');
    }
}
