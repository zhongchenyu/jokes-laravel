<?php

namespace Tests\Feature;

use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RegisterTest extends TestCase {
  use DatabaseTransactions;

  /**
   * A basic test example.
   *
   * @return void
   */
  public function testRegister()
  {
    $response = $this->json('POST', '/api/register', [
      'name'     => 'TestUser123',
      'email'    => 'TestUser123@TestUser.com',
      'password' => '123456'
    ]);


    $response->assertStatus(200)
    ->assertJsonStructure(['token']);
  }

  public function testRegisterInvalidEmail()
  {
    $response = $this->json('POST', '/api/register', [
      'name'     => 'TestUser1234',
      'email'    => 'TestUser123TestUser.com',
      'password' => '123456'
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

    $response = $this->json('POST', '/api/register', [
      'name'     => 'TestUser12345',
      'email'    => 'TestUser12345@TestUser.com',
      'password' => '123456'
    ]);


    $response->assertStatus(500)
      ->assertJson(['message' => 'The given data failed to pass validation.']);
  }
}
