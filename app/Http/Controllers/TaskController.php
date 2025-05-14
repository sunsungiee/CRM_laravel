<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Priority;
use App\Models\Task;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class TaskController extends Controller
{
    public function index()
    {
        $search = request('search');
        $sort = request()->input('sort', 'id');
        $direction = request()->input('direction', 'asc');

        $user = Auth::user();
        $userId = Auth::id();

        $tasks = Task::where("user_id", $userId)
            ->orderBy($sort, $direction)
            ->join('priorities', 'tasks.priority_id', '=', 'priorities.id')
            ->join('contacts', 'tasks.contact_id', '=', 'contacts.id')
            ->when($search, function ($query) use ($search) {
                return $query->where('tasks.subject', 'like', "%$search%")
                    ->orWhere('tasks.description', 'like', "%$search%")
                    ->orWhere('tasks.date', 'like', "%$search%")
                    ->orWhere('tasks.time', 'like', "%$search%")
                    ->orWhere('contacts.contact', 'like', "%$search%")
                    ->orWhere('priorities.priority', 'like', "%$search%");
            })
            ->select('tasks.*')
            ->get();

        $priorities = Priority::all();
        $contacts = Contact::all();
        return view('task.main', [
            'tasks' => $tasks,
            'sort' => $sort,
            'direction' => $direction,
            'contacts' => $contacts,
            'priorities' => $priorities,
            'user' => $user,
        ]);
    }

    public function store()
    {
        $data = request()->validate([
            'contact_id' => 'nullable|integer|exists:contacts,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'priority_id' => 'required|integer|exists:priorities,id'
        ]);

        if (isset($data['contact_id']) && $data['contact_id'] == 0) {
            $data['contact_id'] = null;
        }

        $data['user_id'] = auth()->id();

        try {
            Task::create($data);
            return redirect()->route("task.main")->with('success', 'Задача успешно создана!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ошибка при создании задачи: ' . $e->getMessage());
        }
    }

    public function edit(Task $task)
    {
        return response()->json($task);
    }

    public function update(Task $task)
    {
        $data = request()->validate([
            'contact_id' => 'nullable|integer|exists:contacts,id',
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'date' => 'nullable|date',
            'time' => 'nullable|date_format:H:i',
            'priority_id' => 'required|integer|exists:priorities,id'
        ]);

        if (isset($data['contact_id']) && $data['contact_id'] == 0) {
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
        $sort = request()->input('sort', 'id');
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
        // Выполняем Artisan-команду
        Artisan::call('tasks:update-expired');

        return response()->json([
            'status' => 'success',
            'output' => Artisan::output(),
        ]);
    }
}
