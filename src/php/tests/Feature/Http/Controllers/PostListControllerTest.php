<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PostListControllerTest extends TestCase
{
    public function test_top画面にアクセスするとブログ一覧ページが表示される()
    {
        $this->get('/')->assertOk();
    }
}
