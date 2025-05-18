<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Status;

class StatusesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Status::create([
            'status' => 'Активно'
        ]);

        Status::create([
            'status' => 'Выполнено',
        ]);

        Status::create([
            'status' => 'Просрочено',
        ]);

        Status::create([
            'status' => 'Отменено',
        ]);
    }
}
