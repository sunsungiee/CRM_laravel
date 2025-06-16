<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Contact;
use App\Models\User;
use Faker\Factory as Faker;

class DealsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create("ru_RU");

        $userCount = User::count();
        $contactCount = Contact::count();

        foreach (range(1, 10) as $index) {

            $contact = $faker->numberBetween(1, $contactCount);
            $user = $faker->numberBetween(1, $userCount);
            $phase = $faker->numberBetween(1, 3);
            $dealNames = [
                'Партнерство с крупным клиентом',
                'Разработка корпоративного портала',
                'Внедрение CRM-системы',
                'Запуск рекламной кампании',
                'Обновление IT-инфраструктуры',
                'Консалтинг по цифровизации',
                'Аутсорсинг поддержки 1С',
                'Создание мобильного приложения',
                'Оптимизация бизнес-процессов',
                'Разработка маркетинговой стратегии'
            ];

            DB::table('deals')->insert([
                'subject' => $faker->randomElement($dealNames),
                'end_date' => $faker->dateTimeBetween('+2 day', '+3 month')->format('Y-m-d'),
                'end_time' => $faker->dateTimeBetween('today 09:00', 'today 18:00')->format('H:i:s'),
                'sum' => $faker->numberBetween(5000, 500000),
                'contact_id' => $contact,
                'user_id' => $user,
                'phase_id' => $phase,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
