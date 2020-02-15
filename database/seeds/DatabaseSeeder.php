<?php

use App\Airport;
use App\Customer;
use App\Flight;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        factory(Airport::class,5)->create();
        factory(Flight::class,10)->create()->each(function ($flight){
            factory(Customer::class,100)->make()->each(function ($customer) use ($flight){
                $flight->passengers()->save($customer);
            });
        });
    }
}
