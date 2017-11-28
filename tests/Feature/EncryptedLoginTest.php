<?php
/**
 * Created by PhpStorm.
 * User: chenyu
 * Date: 2017/11/28
 * Time: 11:48
 */

namespace Tests\Feature;

use App\Tools\RsaUtils;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class EncryptedLoginTest extends TestCase {
  use DatabaseTransactions;

  public function testEncryptedLogin()
  {
    $user              = User::create([
      'name'     => 'TestUser12345',
      'email'    => 'TestUser12345@TestUser.com',
      'password' => bcrypt('123456'),
    ]);
    $userId            = $user->id;
    $encryptedPassword = RsaUtils::enPublic('123456');
    $response          = $this->json('POST', '/api/encrypted_login', [
      'name'     => 'TestUser12345',
      'email'    => 'TestUser12345@TestUser.com',
      'password' => $encryptedPassword
    ]);
    $response->assertStatus(200)->assertJsonStructure(['token'])
    ->assertJson(['user' => ['id'=>$userId, 'name'=>'TestUser12345', 'email' =>'TestUser12345@TestUser.com']]);
  }
}