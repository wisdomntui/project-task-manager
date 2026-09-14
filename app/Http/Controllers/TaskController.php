<?php

namespace App\Http\Controllers;

use App\Http\Requests\ReorderTaskRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $projectId = $request->project;

        // Eager load the project relationship
        $tasks = Task::when($projectId, fn($query) => $query->where('project_id', $projectId))->with('project')->orderBy('priority')->get();

        // Get the projects
        $projects = Project::orderBy('name')->get();

        return view('tasks.index', [
            'tasks' => $tasks,
            'projects' => $projects,
        ]);
    }

    public function store(StoreTaskRequest $request): JsonResponse
    {
        $projectId = $request->validated('project_id');

        // Determine the priority for the new task
        $priority = $this->getNextPriority($projectId);

        Task::create([
            'name' => $request->input('name'),
            'project_id' => $projectId,
            'priority' => $priority,
        ]);


        return response()->json([
            'message' => 'Task created successfully.',
        ], 201);
    }

    private function getNextPriority(?int $projectId): int
    {
        $maxPriority = Task::query()
            ->where('project_id', $projectId)
            ->max('priority') ?? 0;

        return $maxPriority + 1;
    }

    public function edit(Task $task): View
    {
        $projects = Project::orderBy('name')
            ->get();

        return view('tasks.edit', [
            'task' => $task,
            'projects' => $projects,
        ]);
    }

    public function update(
        UpdateTaskRequest $request,
        Task $task
    ): RedirectResponse {
        $oldProjectId = $task->project_id;
        $newProjectId = $request->validated('project_id');

        // Update based on the presence or absence of a new ly selected project
        if ($oldProjectId !== $newProjectId) {
            $task->update([
                'name' => $request->input('name'),
                'project_id' => $newProjectId,
            ]);
        } else {
            $task->update([
                'name' => $request->input('name'),
            ]);
        }

        return redirect()
            ->route('tasks.index')
            ->with('success', 'Task updated successfully.');
    }

    public function delete(Task $task): JsonResponse
    {
        $projectId = $task->project_id;

        // This transaction block will ensure that the deletion and priorities of a task is complete
        DB::transaction(function () use ($task, $projectId) {
            $task->delete();

            $this->resolvePriorities($projectId);
        });

        return response()->json([
            'message' => 'Task deleted successfully.',
        ]);
    }

    private function resolvePriorities(?int $projectId): void
    {
        $tasks = Task::query()
            ->where('project_id', $projectId)
            ->orderBy('priority')
            ->orderBy('id')
            ->get();

        foreach ($tasks as $index => $task) {
            $task->update([
                'priority' => $index + 1,
            ]);
        }
    }


    public function reorder(ReorderTaskRequest $request): JsonResponse
    {
        $taskIds = $request->validated('task_ids');
        $tasks = Task::whereIn('id', $taskIds)->get();

        // Ensure that tasks ordered are same as existing tasks
        if ($tasks->count() !== count($taskIds)) {
            return response()->json([
                'message' => 'Invalid task order.',
            ], 422);
        }

        // Safely update the priorities in this transaction
        DB::transaction(function () use ($taskIds) {
            foreach ($taskIds as $index => $taskId) {
                Task::whereKey($taskId)->update([
                    'priority' => $index + 1,
                ]);
            }
        });

        return response()->json([
            'message' => 'Task order updated successfully.',
        ]);
    }
}
