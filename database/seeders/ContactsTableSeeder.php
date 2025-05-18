<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class ContactsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create("ru_RU");

        foreach (range(1, 20) as $index) {
            DB::table('contacts')->insert([
                'surname' => $faker->lastName("male"),
                'name' => $faker->firstName("male"),
                'email' => $faker->unique()->email(),
                'phone' => $faker->unique()->phoneNumber(),
                'firm' => $faker->optional()->company(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
