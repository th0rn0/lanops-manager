<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Settings;
use Artisan;

use Faker\Factory as Faker;
use App\User;
use Illuminate\Support\Facades\Hash;


class DefaultInstallationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Create User
        $user 						= New User;
     	$user->email          		= "Administrator@Administrator.de";
     	$user->admin 				= true;
        $user->password       		= Hash::make("AdministratorAdministrator");
        $user->firstname        	= "Administrator";
        $user->surname          	= "Administrator";
        $user->username         	= "Administrator";
        $user->username_nice    	= strtolower(str_replace(' ', '-', "Administrator"));
        $user->email_verified_at	= new \DateTime('NOW');
        $user->save();

        // Set Org Details
        Settings::SetOrgName("eventulaOrg");
        Settings::SetOrgTagline("eventulaTagline");

        // Set Payment Gateways
		Settings::enablePaymentGateway('free');
		Settings::enablePaymentGateway('onsite');

        // Clear Cache
        Artisan::call('config:clear');

        // Set Installed
        Settings::setInstalled();

    }
}
