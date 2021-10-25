<?php

namespace App\Console\Commands;

use App\Models\User;
use Faker\Factory;
use Illuminate\Auth\Events\Registered;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class CreateUserCommand extends Command
{
   /**
    * The name and signature of the console command.
    *
    * @var string
    */
   protected $signature = 'user:generate';

   /**
    * The console command description.
    *
    * @var string
    */
   protected $description = 'Creates new user';

   /**
    * Execute the console command.
    *
    * @return int
    */
   public function handle()
   {

      $user = User::create([
         'name' => 'Adedaramola Chamola',
         'username' => 'Abegatio',
         'email' => 'abegatio@gmail.com',
         'phone' => '08132642725',
         'password' => bcrypt('password'),
      ]);

      event(new Registered($user));

      $user->markEmailAsVerified();

      $this->info('Successfully created user');
   }
}
