<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;
use Illuminate\Support\Facades\DB;
class GetJokesTest extends TestCase
{

  //use DatabaseTransactions;
    /**
     * A basic test example.
     *
     *
     */


  public function  testLogin()
    {

        $user = User::where('email', 'AutoTestUser@AutoTestUser.com');
        if($user) {
          $user->delete();
        }
        User::create([
          'name' => 'AutoTestUser',
          'email' => 'AutoTestUser@AutoTestUser.com',
          'password' => bcrypt('123456'),
        ]);

      $response = $this->json('GET', '/api/login', [
        'name'     => 'AutoTestUser',
        'email'    => 'AutoTestUser@AutoTestUser.com',
        'password' => '123456'
      ]);
      $response->assertStatus(200)
        ->assertJsonStructure(['token']);

      $token = $response->original['token'];
      //$response2 = $this->json('GET', '/api/user',[], ['Authorization' => 'Bearer ' . $token]);
      //print_r($response2->original);print("\n\n");
      //print($token); print("\n\n");
      return $token;
    }

  /**
   * @param $token
   * @depends testLogin
   */
    public function testGetUser($token) {

      //print( "\n\n" . $token);print("\n\n");
      $response = $this->json('GET', '/api/user',[], ['Authorization' => 'Bearer ' . $token]);

      //print_r( $response2->original);
      $response->assertStatus(200);
    }
}
