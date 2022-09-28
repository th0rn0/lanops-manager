<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use Faker\Factory as Faker;
use App\User;
use Illuminate\Support\Facades\Hash;


class TestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        $usercount = 50;
        $i = 1;
        while ($i <= $usercount) {
            $user 						= new User;
            $user->email          		= "test".$i."@test.de";
            $user->admin 				= false;
            $user->password       		= Hash::make("test".$i."test".$i);
            $user->firstname        	= "test".$i;
            $user->surname          	= "test".$i;
            $user->username         	= "test".$i;
            $user->username_nice    	= strtolower(str_replace(' ', '-', "test".$i));
            $user->email_verified_at	= new \DateTime('NOW');
            $user->save();
            $i++;
        }

        $user 						= new User;
            $user->email          		= "Administrator1@Administrator.de";
            $user->admin 				= true;
            $user->password       		= Hash::make("Administrator1Administrator1");
            $user->firstname        	= "Administrator1";
            $user->surname          	= "Administrator1";
            $user->username         	= "Administrator1";
            $user->username_nice    	= strtolower(str_replace(' ', '-', "Administrator1"));
            $user->email_verified_at	= new \DateTime('NOW');
            $user->save();

    }
}
