<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Priority;
use App\Models\Task;
use App\Models\Contact;
use App\Models\Status;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;

file_put_contents(storage_path('logs/debug.txt'), 'Request received at TaskController@update' . PHP_EOL, FILE_APPEND);

class TaskController extends Controller
{
    public function index()
    {
        $search = request('search');
        $sort = request()->input('sort', 'subject');
        $direction = request()->input('direction', 'asc');

        $user = Auth::user();
        $userId = Auth::id();

        $tasks = Task::where("user_id", $userId)
            ->with('contact', 'priority', 'status') // Убедитесь, что эти отношения определены в модели Task
            ->orderBy($sort, $direction)
            ->leftJoin('contacts', 'tasks.contact_id', '=', 'contacts.id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.id')
            ->when($search, function ($query) use ($search) {
                return $query->where('tasks.subject', 'like', "%$search%")
                    ->orWhere('tasks.description', 'like', "%$search%")
                    ->orWhere('statuses.status', 'like', "%$search%")
                    ->orWhere('tasks.date', 'like', "%$search%")
                    ->orWhere('tasks.time', 'like', "%$search%")
                    ->orWhere('contacts.surname', 'like', "%$search%")
                    ->orWhere('contacts.name', 'like', "%$search%")
                    ->orWhere('priorities.priority', 'like', "%$search%");
            })
            ->select(
                'tasks.*',
                'contacts.surname as contact_surname',
                'contacts.name as contact_name',
                'priorities.priority as task_priority',
                'statuses.status as task_status'
            )
            ->withTrashed()->get();

        $priorities = Priority::all();
        $contacts = Contact::all();
        $statuses = Status::all();

        return view('task.main', [
            'tasks' => $tasks,
            'sort' => $sort,
            'direction' => $direction,
            'contacts' => $contacts,
            'priorities' => $priorities,
            'user' => $user,
            'statuses' => $statuses,
        ]);
    }

    public function store()
    {
        // Log::debug("Логи добавит yfxfk");

        $data = request()->validate([
            'contact_id' => 'nullable|integer',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'priority_id' => 'required|integer|exists:priorities,id',
        ]);

        // Log::debug("Логи добавит ", $data);

        if (isset($data['contact_id']) && $data['contact_id'] == 0) {
            $data['contact_id'] = null;
        }

        // Log::debug("Логи добавит  конец", $data);

        $data['user_id'] = auth()->id();

        Task::create($data);
        return redirect()->route("task.main");
        // dd($e->getMessage());
    }

    public function edit(Task $task)
    {
        return response()->json($task);
    }

    public function update(Task $task)
    {
        // Log::info('Update method called', ['task_id' => $task->id]);
        $data = request()->validate([
            'contact_id' => 'nullable|integer',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'priority_id' => 'required|integer|exists:priorities,id',
            'status_id' => 'required|integer|exists:statuses,id',
        ]);

        // Log::debug("Логи апдейт " . $data);

        if (!isset($data['contact_id']) || $data['contact_id'] === '' || $data['contact_id'] == 0) {
            $data['contact_id'] = null;
        }

        $data['user_id'] = auth()->id();

        $task->update($data);
        return redirect()->route("task.main");
    }

    public function destroy(Task $task)
    {
        $data = request()->validate([
            "status_id" => 'required'
        ]);

        $task->update($data);

        $task->delete();
        return redirect()->route("task.main");
    }

    public function showArchive()
    {
        $search = request('search');
        $sort = request()->input('sort', 'subject');
        $direction = request()->input('direction', 'asc');

        $user = Auth::user();
        $userId = Auth::id();

        $tasks = Task::where("user_id", $userId)
            ->where("status_id", "!=", 1)
            ->orderBy($sort, $direction)
            ->join('statuses', 'tasks.status_id', '=', 'statuses.id')
            ->join('priorities', 'tasks.priority_id', '=', 'priorities.id')
            ->when($search, function ($query) use ($search) {
                return $query->where('tasks.subject', 'like', "%$search%")
                    ->orWhere('tasks.description', 'like', "%$search%")
                    ->orWhere('tasks.date', 'like', "%$search%")
                    ->orWhere('tasks.time', 'like', "%$search%")
                    ->orWhere('statuses.status', 'like', "%$search%")
                    ->orWhere('priorities.priority', 'like', "%$search%");
            })
            ->select('tasks.*')
            ->onlyTrashed()->get();

        return view('task.archive', [
            'tasks' => $tasks,
            'sort' => $sort,
            'direction' => $direction,
            'user' => $user,
        ]);
    }

    public function runScheduler()
    {
        Artisan::call('tasks:update-expired');

        return response()->json([
            'status' => 'success',
            'output' => Artisan::output(),
        ]);
    }
}
