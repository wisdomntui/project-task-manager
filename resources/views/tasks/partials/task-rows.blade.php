<div class="list-group-item task-row" data-task-id="{{ $task->id }}">
    <div class="d-flex align-items-center gap-3">
        {{-- Drag handle --}}
        <button type="button" class="btn btn-light btn-sm drag-handle" title="Drag to reorder" aria-label="Drag task">
            =
        </button>

        {{-- Priority --}}
        <div class="task-priority fw-semibold">
            {{ $task->priority }}
        </div>

        {{-- Task details --}}
        <div class="flex-grow-1">
            <div class="fw-semibold">
                {{ $task->name }}
            </div>

            @if ($task->project)
                <small class="text-muted">
                    {{ $task->project->name }}
                </small>
            @else
                <small class="text-muted">
                    No project
                </small>
            @endif
        </div>

        {{-- Actions --}}
        <div class="d-flex gap-2">
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-secondary">
                Edit
            </a>

            <button type="button" class="btn btn-sm btn-outline-danger delete-task-btn"
                data-task-id="{{ $task->id }}">
                Delete
            </button>
        </div>
    </div>
</div>
