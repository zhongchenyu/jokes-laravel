<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateRSAKey extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:rsa';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
      echo getcwd() . "\n";
      chdir('sec');
      echo getcwd() . "\n";
      shell_exec('openssl genrsa -out rsa_private_key.pem 1024');
      shell_exec('openssl pkcs8 -topk8 -inform PEM -in rsa_private_key.pem -outform PEM -nocrypt -out private_key.pem');
      shell_exec('openssl rsa -in rsa_private_key.pem -pubout -out rsa_public_key.pem');
      chdir('..');
      echo getcwd() . "\n";
      return;
    }
}
