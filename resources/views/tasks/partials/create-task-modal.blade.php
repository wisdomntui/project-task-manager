<div class="modal fade" id="createTaskModal" tabindex="-1" aria-labelledby="createTaskModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="create-task-form" action="{{ route('tasks.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="createTaskModalLabel">
                        Add Task
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label for="task-name" class="form-label">
                            Task Name
                        </label>

                        <input type="text" id="task-name" name="name" class="form-control" required
                            maxlength="255">
                    </div>

                    <div class="mb-3">
                        <label for="task-project" class="form-label">
                            Project
                        </label>

                        <select id="task-project" name="project_id" class="form-select">
                            <option value="">
                                No Project
                            </option>

                            @foreach ($projects as $project)
                                <option value="{{ $project->id }}">
                                    {{ $project->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div id="create-task-errors" class="alert alert-danger d-none"></div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" class="btn btn-primary">
                        Create Task
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
