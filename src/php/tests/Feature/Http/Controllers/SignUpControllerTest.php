<?php

namespace Tests\Feature\Http\Controllers;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;
use app\Models\User;

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

        $user = User::firstWhere($validData);

        $this->assertTrue(Hash::check('hogehoge', $user->password));
    }

    public function test_不正なデータではユーザー登録ができない(){
        $url = '/signup';
        $this->post($url,[])->assertRedirect();


        $this->post($url,['name' => ''])->assertInvalid(['name'=>'指定']);
        // $this->post($url,['name' => ''])->assertSessionHasErrors(['name'=>'nameは必ず指定してください。']);

        $this->post($url,['name'=>str_repeat('a',26)])->assertInvalid(['name'=>'25文字以下']);
        $this->post($url,['name'=>str_repeat('a',25)])->assertValid('name');

    }
}
