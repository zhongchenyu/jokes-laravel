<?php

use Illuminate\Database\Seeder;
use App\Note;
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
      factory(Note::class, 30)->create();

      $this->command->info('完成');
    }
}
