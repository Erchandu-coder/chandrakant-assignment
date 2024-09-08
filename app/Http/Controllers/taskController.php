<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Task;


class taskController extends Controller
{
    public function Index()
    {
        return view('index');
    }
    public function taskAdd(Request $request)
    {
        $data = new Task;
        $data->taskName = $request->task_name_;
        if ($data->save()) {
            return response()->json(1); // Success
        } else {
            return response()->json(0); // Error
        }
    }
    public function showTask()
    {
        $showTask = Task::all();
        return response()->json($showTask);
    }
    public function taskdestroy($id)
    {
        $task = Task::findOrFail($id); // Find the task or fail if it doesn't exist
        $task->delete(); // Delete the task
        return response()->json(['success' => true, 'message' => 'Task deleted successfully']);
    }

    public function updateTask(Request $request, $id)
    {
        $task = Task::findOrFail($id); // Find the task or fail if it doesn't exist
        $task->status = $request->status; // Update the status from the request
        $task->save(); // Save the updated task to the database
        return response()->json(['success' => true, 'message' => 'Task status updated successfully']);
    }

}
