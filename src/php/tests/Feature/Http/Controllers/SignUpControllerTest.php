<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class SignUpControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_ユーザー登録画面が表示できる(){
        $this->get('/signup')->assertOk();
    }

    public function test_ユーザー登録ができる(){
        $validData = [
            'name' => '太郎',
            'email' => 'aaa@example.com',
            'password' => 'hogehoge',
        ];
        $this->post('/signup',$validData)->assertOk();

        unset($validData['password']);

        $this->assertDatabaseHas('users',$validData);
    }
}
