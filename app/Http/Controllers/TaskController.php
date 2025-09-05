<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'all');

        $query = Task::query();

        if ($filter === 'pending') {
            $query->whereIn('status', ['To-Do', 'In Progress']);
        } elseif ($filter === 'completed') {
            $query->where('status', 'Done');
        }

        $query->orderByRaw('deadline IS NULL, deadline ASC');

        $tasks = $query->get();

        $total     = Task::count();
        $pending   = Task::whereIn('status', ['To-Do','In Progress'])->count();
        $completed = Task::where('status','Done')->count();

        if ($request->ajax()) {
            return view('tasks._list', compact('tasks'))->render();
        }

        return view('tasks.index', compact('tasks','total','pending','completed','filter'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => ['required','string','min:3','max:255','regex:/^[A-Za-z0-9\s\-\_.,]+$/'],
            'description' => ['nullable','string','max:1000'],
            'status'      => ['required','in:To-Do,In Progress,Done'],
            'priority'    => ['required','in:Low,Medium,High'],
            'deadline'    => ['nullable','date','after_or_equal:now'],
        ]);

        $task = Task::create($data);

        if ($request->ajax()) {
            return response()->json(['message' => 'Task added', 'id' => $task->id], 201);
        }

        return redirect()->route('tasks.index')->with('success','Task added');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $data = $request->validate([
            'title'       => ['required','string','min:3','max:255','regex:/^[A-Za-z0-9\s\-\_.,]+$/'],
            'description' => ['nullable','string','max:1000'],
            'status'      => ['required','in:To-Do,In Progress,Done'],
            'priority'    => ['required','in:Low,Medium,High'],
            'deadline'    => ['nullable','date','after_or_equal:now'],
        ]);

        $task->update($data);

        if ($request->ajax()) {
            return response()->json(['message' => 'Task updated']);
        }

        return redirect()->route('tasks.index')->with('success','Task updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Task $task)
    {
        $task->delete();

        if ($request->ajax()) {
            return response()->json(['message' => 'Task deleted']);
        }

        return redirect()->route('tasks.index')->with('success','Task deleted');
    }
}
