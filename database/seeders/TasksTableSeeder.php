<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class TasksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create("ru_RU");

        foreach (range(1, 10) as $index) {

            $contact = $faker->optional()->numberBetween(1, 20);
            $user = $faker->numberBetween(1, 1);
            $priority = $faker->numberBetween(1, 3);
            $specificTasks = [
                'Составить отчет для клиента',
                'Подготовить презентацию',
                'Организовать воркшоп',
                'Провести оценку рисков',
                'Согласовать ТЗ',
                'Назначить ответственных',
                'Проверить выполнение этапа',
                'Обновить диаграмму Ганта',
                'Синхронизировать команды'
            ];

            $description = "Необходимо " . $faker->randomElement($specificTasks);

            DB::table('tasks')->insert([
                'subject' => $faker->randomElement($specificTasks),
                'description' => $description,
                'date' => $faker->dateTimeBetween('now', '+1 month')->format('Y-m-d'),
                'time' => $faker->dateTimeBetween('today 09:00', 'today 18:00')->format('H:i:s'),
                'contact_id' => $contact,
                'user_id' => $user,
                'priority_id' => $priority,
                'status_id' => 1,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
