@extends('layout.app')

@section('content')
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="mb-1">Edit Task</h1>
                </div>

                <a href="{{ route('tasks.index') }}" class="btn btn-outline-secondary">
                    Back
                </a>
            </div>

            <div class="card">
                <div class="card-body">
                    <form action="{{ route('tasks.update', $task) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Task name --}}
                        <div class="mb-3">
                            <label for="task-name" class="form-label">
                                Task Name
                            </label>

                            <input type="text" id="task-name" name="name"
                                class="form-control @error('name') is-invalid @enderror"
                                value="{{ old('name', $task->name) }}" required maxlength="255">

                            @error('name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Project --}}
                        <div class="mb-3">
                            <label for="task-project" class="form-label">
                                Project
                            </label>

                            <select id="task-project" name="project_id"
                                class="form-select @error('project_id') is-invalid @enderror">
                                <option value="">
                                    No Project
                                </option>

                                @foreach ($projects as $project)
                                    <option value="{{ $project->id }}" @selected(old('project_id', $task->project_id) == $project->id)>
                                        {{ $project->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('project_id')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        {{-- Priority is read-only --}}
                        <div class="mb-4">
                            <label class="form-label">
                                Current Priority
                            </label>

                            <input type="text" class="form-control" value="{{ $task->priority }}" disabled>

                            <small class="text-muted">
                                Priority is controlled by task order.
                            </small>
                        </div>

                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                Save Changes
                            </button>

                            <a href="{{ route('tasks.index') }}" class="btn btn-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
