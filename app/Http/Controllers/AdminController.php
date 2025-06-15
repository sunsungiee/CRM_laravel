<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Task;
use App\Models\Priority;
use App\Models\Contact;
use App\Models\Status;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function index()
    {
        $search = request('search');

        $sort = request()->input('sort', 'users.surname'); // По умолчанию сортировка по ID
        $direction = request()->input('direction', 'asc'); // Направление по умолчанию

        $tasks = Task::orderBy($sort, $direction)
            ->with('contact', 'priority', 'status')
            ->leftJoin('contacts', 'tasks.contact_id', '=', 'contacts.id')
            ->leftJoin('priorities', 'tasks.priority_id', '=', 'priorities.id')
            ->leftJoin('statuses', 'tasks.status_id', '=', 'statuses.id')
            ->leftJoin('users', 'tasks.user_id', '=', 'users.id')
            ->when($search, function ($query) use ($search) {
                return $query->where('tasks.subject', 'like', "%$search%")
                    ->orWhere('users.surname', 'like', "%$search%")
                    ->orWhere('users.name', 'like', "%$search%")
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
                'statuses.status as task_status',
                'users.surname as user_surname',
                'users.name as user_name',
            )
            ->get();

        $priorities = Priority::all();
        $contacts = Contact::all();
        $statuses = Status::all();

        $users = User::all();

        $current_user = Auth::user();


        return view("admin.main", [
            'tasks' => $tasks,
            'sort' => $sort,
            'direction' => $direction,
            'contacts' => $contacts,
            'priorities' => $priorities,
            'statuses' => $statuses,
            'users' => $users,
            'user' => $current_user
        ]);
    }
}
