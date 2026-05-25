<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        return response()->json($request->user()->tasks()->orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate(\App\Http\Requests\AppRequest::taskStore());

        $task = $request->user()->tasks()->create($validated);
        return response()->json($task, 201);
    }

    public function update(Request $request, string $id)
    {
        $task = $request->user()->tasks()->findOrFail($id);

        $validated = $request->validate(\App\Http\Requests\AppRequest::taskUpdate());

        $task->update($validated);
        return response()->json($task);
    }

    public function destroy(Request $request, string $id)
    {
        $request->user()->tasks()->findOrFail($id)->delete();
        return response()->json(['message' => 'Task deleted']);
    }
}
