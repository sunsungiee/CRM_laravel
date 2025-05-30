<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'surname' => 'Иванов',
            'name' => 'Иван',
            'post' => 'Менеджер',
            'phone' => '791111111',
            'email' => 'user@tt',
            'password' => 'user1234',
        ]);
    }
}
