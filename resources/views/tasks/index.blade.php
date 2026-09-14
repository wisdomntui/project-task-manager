@extends('layout.app')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Task Manager</h1>
        </div>

        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTaskModal">
            Create Task
        </button>
    </div>

    <hr>

    {{-- Project filter --}}
    <div class="mb-5 col-2">
        <select id="project-filter" class="form-select">
            <option value="">All Projects</option>
            @foreach ($projects as $project)
                <option value="{{ $project->id }}" @selected((string) request('project') === (string) $project->id)>
                    {{ $project->name }}
                </option>
            @endforeach
        </select>
    </div>

    {{-- Flash messages --}}
    <div id="alert-container">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>

    {{-- Task list --}}
    <div class="card">
        <div class="card-header">
            <strong>Tasks</strong>
        </div>

        <div id="task-list" class="list-group list-group-flush">
            @forelse ($tasks as $task)
                @include('tasks.partials.task-rows', [
                    'task' => $task,
                ])

            @empty
                <div id="empty-state" class="p-4 text-center text-muted">
                    No tasks found.
                </div>
            @endforelse
        </div>
    </div>

    {{-- Create task modal --}}
    @include('tasks.partials.create-task-modal')
@endsection
