<?php

use App\Models\Project;
use App\Models\Task;
use Illuminate\Foundation\Testing\RefreshDatabase;

// Prepare the database for testing
uses(RefreshDatabase::class);

it('can create a task', function () {
    $response = $this->postJson(route('tasks.store'), [
        'name' => 'Build dashboard',
    ]);

    $response
        ->assertCreated()
        ->assertJson([
            'message' => 'Task created successfully.',
        ]);

    $task = Task::first();

    expect($task)->not->toBeNull()
        ->and($task->name)->toBe('Build dashboard')
        ->and($task->priority)->toBe(1);
});

it('can list tasks', function () {
    $task = Task::factory()->create([
        'name' => 'Fix payment issue',
        'priority' => 1,
    ]);

    $this->get(route('tasks.index'))
        ->assertOk()
        ->assertSee($task->name);
});

it('can update a task', function () {
    $task = Task::factory()->create([
        'name' => 'Old task name',
    ]);

    $this->put(route('tasks.update', $task), [
        'name' => 'Updated task name',
        'project_id' => $task->project_id,
    ])
        ->assertRedirect(route('tasks.index'));

    expect($task->fresh()->name)
        ->toBe('Updated task name');
});

it('can delete a task', function () {
    $task = Task::factory()->create();

    $this->delete(route('tasks.destroy', $task))
        ->assertStatus(200);

    expect(Task::find($task->id))->toBeNull();
});

it('can reorder tasks and update their priorities', function () {
    $task1 = Task::factory()->create(['priority' => 1]);
    $task2 = Task::factory()->create(['priority' => 2]);
    $task3 = Task::factory()->create(['priority' => 3]);

    $this->postJson(route('tasks.reorder'), [
        'task_ids' => [
            $task3->id,
            $task1->id,
            $task2->id,
        ],
    ])->assertOk();

    expect($task3->fresh()->priority)->toBe(1)
        ->and($task1->fresh()->priority)->toBe(2)
        ->and($task2->fresh()->priority)->toBe(3);
});
