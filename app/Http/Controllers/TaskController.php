<?php

namespace App\Http\Controllers;

use App\Models\Priority;
use App\Models\Task;
use App\Models\Contact;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();
        $priorities = Priority::all();
        $contacts = Contact::all();
        return view('task.main', compact("tasks", "priorities", "contacts"));
    }

    public function store(Request $request)
    {
        dd($request->all()); // Посмотрите, какие данные действительно приходят
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

        $validated['user_id'] = auth()->id();

        try {
            Task::create($data);
            return redirect()->route("task.main")->with('success', 'Задача успешно создана!');
        } catch (\Exception $e) {
            return back()->withInput()->with('error', 'Ошибка при создании задачи: ' . $e->getMessage());
        }
    }
}
