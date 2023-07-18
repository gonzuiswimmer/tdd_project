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

        // ユーザー情報を持たせて/signupにpostすると指定のページにリダイレクトされる
        $this->post('/signup',$validData)->assertRedirect('/mypage/posts');

        // 作成したユーザーのパスワードはハッシュ化されてDBに保存されている
        unset($validData['password']);
        $this->assertDatabaseHas('users',$validData);
        $user = User::firstWhere($validData);
        $this->assertTrue(Hash::check('hogehoge', $user->password));

        // 作成したユーザーはログイン状態である
        $this->assertAuthenticatedAs($user);
    }

    public function test_不正なデータではユーザー登録ができない(){
        $url = '/signup';
        $user = User::factory()->create();

        // パラメータがない場合のチェック
        $this->from('/signup')->post($url,[])->assertRedirect('/signup');

        // nameのチェック
        $this->post($url,['name' => ''])->assertInvalid(['name'=>'指定']); // $this->post($url,['name' => ''])->assertSessionHasErrors(['name'=>'nameは必ず指定してください。']);
        $this->post($url,['name'=>str_repeat('a',26)])->assertInvalid(['name'=>'25文字以下']);
        $this->post($url,['name'=>str_repeat('a',25)])->assertValid('name');

        // emailのチェック
        $this->post($url,['email' => ''])->assertInvalid(['email' => '指定']);
        $this->post($url,['email' => 'aa@bb@cc'])->assertInvalid(['email'=>'有効なメールアドレス']);
        $this->post($url,['email' => 'あああああ@cc'])->assertInvalid(['email'=>'有効なメールアドレス']);
        $this->post($url,['email' => $user->email])->assertInvalid('email');

        // passwordのチェック
        $this->post($url,['password' => ''])->assertInvalid(['password'=>'指定']);
        $this->post($url,['password' => '1234567'])->assertInvalid(['password'=>'8文字以上']);
        $this->post($url,['password' => '12345678'])->assertValid('password');

    }
}
