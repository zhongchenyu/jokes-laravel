<?php

namespace Tests\Feature;

use App\Tools\RsaUtils;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class EncryptedRegisterTest extends TestCase {
  use DatabaseTransactions;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function testRegister()
  {
    $encryptedPassword = RsaUtils::enPublic('123456');
    $response = $this->json('POST', '/api/encrypted_register', [
      'name'     => 'TestUser123',
      'email'    => 'TestUser123@TestUser.com',
      'password' => $encryptedPassword
    ]);


    $response->assertStatus(200)
    ->assertJsonStructure(['token']);
  }

  public function testRegisterInvalidEmail()
  {
    $encryptedPassword = RsaUtils::enPublic('123456');
    $response = $this->json('POST', '/api/encrypted_register', [
      'name'     => 'TestUser1234',
      'email'    => 'TestUser123TestUser.com',
      'password' => $encryptedPassword
    ]);


    $response->assertStatus(500)
      ->assertJson(['message' => 'The given data failed to pass validation.']);
  }

  public function testRegisterRepeatEmail()
  {
    User::create([
      'name' => 'TestUser12345',
      'email' => 'TestUser12345@TestUser.com',
      'password' => bcrypt('123456'),
    ]);
    $encryptedPassword = RsaUtils::enPublic('123456');
    $response = $this->json('POST', '/api/encrypted_register', [
      'name'     => 'TestUser12345',
      'email'    => 'TestUser12345@TestUser.com',
      'password' => $encryptedPassword
    ]);


    $response->assertStatus(500)
      ->assertJson(['message' => 'The given data failed to pass validation.']);
  }

  public function testEncryptionError() {
    $response = $this->json('POST', '/api/encrypted_register', [
      'name'     => 'TestUser12345',
      'email'    => 'TestUser12345@TestUser.com',
      'password' => '123456'
    ]);
    $response->assertStatus(400)
      ->assertJson(['message' => 'Encryption error']);
  }
}
