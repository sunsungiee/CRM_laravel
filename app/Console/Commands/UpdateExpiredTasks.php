<?php

namespace App\Console\Commands;

use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Task;
use Illuminate\Console\Command;

class UpdateExpiredTasks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tasks:update-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Обновляет статус и удаляет(soft delete) просроченные задачи';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //текущее время
        $now = Carbon::now();

        $todayDate = $now->toDateString();
        $currentTime = $now->toTimeString();

        $expiredTasks = Task::where('date', '<=', $todayDate)
            ->whereTime('time', '<=', $currentTime)
            ->whereNull('deleted_at')
            ->get();

        Log::info("Команда запущена");

        foreach ($expiredTasks as $task) {
            Log::info("Обработка задачи ID: {$task->id}");
            // Обновляем статус на "Просрочено"
            $task->status_id = 3;
            $task->save();

            $task->delete();
        }
        return Command::SUCCESS;
    }
}
