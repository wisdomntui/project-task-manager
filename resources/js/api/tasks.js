import $ from "jquery";
import Sortable from "sortablejs";

$(function () {
    initSort();
    bindCreateForm();
    bindDeleteButtons();
    bindProjectFilter();
});

// Sort tasks on page load
function initSort() {
    const taskList = document.getElementById("task-list");

    if (!taskList) {
        return;
    } else {
        new Sortable(taskList, {
            animation: 150,
            handle: ".drag-handle",
            onEnd: function () {
                reorderTasks();
            },
        });
    }
}

function bindCreateForm() {
    // Create tasks handler
    $("#create-task-form").on("submit", function (event) {
        event.preventDefault();

        const form = $(this);
        const button = form.find('[type="submit"]');

        button.prop("disabled", true);

        $.post(form.attr("action"), form.serialize())
            .done(function () {
                window.location.reload();
            })
            .fail(function (xhr) {
                showError(xhr, "#create-task-errors");
            })
            .always(function () {
                button.prop("disabled", false);
            });
    });
}

function bindDeleteButtons() {
    // Delete task handler
    $(document).on("click", ".delete-task-btn", function () {
        const taskId = $(this).data("task-id");

        if (!confirm("Are you sure you want to delete this task?")) {
            return;
        } else {
            $.ajax({
                url: `/tasks/${taskId}`,
                method: "DELETE",
            })
                .done(function () {
                    window.location.reload();
                })
                .fail(function () {
                    showAlert("Unable to delete the task.", "danger");
                });
        }
    });
}

function bindProjectFilter() {
    // Filter projects handler
    $("#project-filter").on("change", function () {
        const projectId = $(this).val();
        const url = new URL(window.location.href);

        if (projectId) {
            url.searchParams.set("project", projectId);
        } else {
            url.searchParams.delete("project");
        }

        window.location.href = url.toString();
    });
}

function reorderTasks() {
    const taskIds = $(".task-row")
        .map(function () {
            return $(this).data("task-id");
        })
        .get();

    // Send post request with new order after UI has been altered
    $.post("/tasks/reorder", {
        task_ids: taskIds,
    })
        .done(function () {
            updateVisiblePriorities();

            showAlert("Task order updated successfully.", "success");
        })
        .fail(function () {
            showAlert("Unable to save the new order.", "danger");

            window.location.reload();
        });
}

function updateVisiblePriorities() {
    $(".task-row").each(function (index) {
        $(this)
            .find(".task-priority")
            .text(index + 1);
    });
}

// Show alert messages
function showAlert(message, type) {
    $("#alert-container").html(`
        <div class="alert alert-${type}">
            ${message}
        </div>
    `);
}

// Show error messages
function showError(xhr, selector) {
    const container = $(selector);

    container.empty();

    if (xhr.status === 422 && xhr.responseJSON?.errors) {
        Object.values(xhr.responseJSON.errors)
            .flat()
            .forEach(function (message) {
                container.append(`<div>${message}</div>`);
            });

        container.removeClass("d-none");
        return;
    }

    container.text("Something went wrong.").removeClass("d-none");
}
