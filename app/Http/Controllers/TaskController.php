<?php

namespace App\Http\Controllers;

use App\Models\Tag;

use App\Models\Task;

use Inertia\Inertia;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Requests\CreateTaskRequest;

class TaskController extends Controller
{
    //
    // Display a list of tasks
    public function index()
    {
        $tasks = Task::with('category', 'tags')
            ->orderBy('created_at', 'desc')
            ->paginate(10); // Show 10 items per page

        return inertia('Tasks/Index', [
            'tasks' => $tasks,
        ]);
    }
    // Show a form for creating a new task
    public function create()
    {
        return Inertia::render('Tasks/Create', [
            'categories' => Category::all(),
            'tags' => Tag::all(),
        ]);
    }

    // Save a new task
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'title' => [
    //             'required',
    //             'string',
    //             'max:255',
    //             'min:3',
    //             'regex:/^[a-zA-Z\s]+$/'
    //         ],
    //         'description' => 'nullable|string',
    //         'category_id' => 'nullable|exists:categories,id',
    //         'tags' => 'nullable|array',
    //         'tags.*' => 'exists:tags,id',
    //     ]);

    //     $task = Task::create($validated);

    //     if (isset($validated['tags'])) {
    //         $task->tags()->attach($validated['tags']);
    //     }

    //     return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    // }
    public function store(CreateTaskRequest $request)
    {
        $validated = $request->validated();

        $task = Task::create($validated);

        if (isset($validated['tags'])) {
            $task->tags()->attach($validated['tags']);
        }

        return redirect()->route('tasks.index')->with('success', 'Task created successfully!');
    }

    // Display a single task
    public function show(Task $task)
    {
        $task->load(['category', 'tags']); // Eager load the category and tags relationships 
        return Inertia::render('Tasks/Show', ['task' => $task,]);
    }

    // Show a form to edit a task
    public function edit(Task $task)
    {
        return Inertia::render('Tasks/Edit', [
            'task' => $task->load(['tags', 'category']),
            'categories' => Category::all(),
            'allTags' => Tag::all(),
        ]);
    }

    // Update the specified task
    public function update(Request $request, Task $task)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category_id' => 'nullable|exists:categories,id',
            'tags' => 'nullable|array',
            'tags.*' => 'exists:tags,id',
        ]);

        // Update the task
        $task->update([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'category_id' => $validated['category_id'],
        ]);

        // Sync tags
        if (isset($validated['tags'])) {
            $task->tags()->sync($validated['tags']);
        }

        return redirect()->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }
    // Delete the specified task
    public function destroy(Task $task)
    {
        $task->delete();

        return redirect()->route('tasks.index')->with('success', 'Task deleted successfully.');
    }
}
