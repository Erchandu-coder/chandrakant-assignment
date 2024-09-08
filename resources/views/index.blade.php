<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</head>

<body>
    <div class="container">
        <!-- Content here -->
        <div class="row d-grid gap-3 mx-auto p-2">
            <center>
                <h1 class="text-primary">PHP - Simple To Do List App</h1><br>
                <div class="alert alert-success" role="alert" id="success" style="display:none"></div>
                <div class="alert alert-danger" role="alert" id="error" style="display:none">

                </div>
                <form id="insert-form">
                    <div class="row text-center">
                        <div class="col-sm-4">
                            
                        </div>
                        <div class="col-sm-4">
                            <input type="text" class="form-control" id="task_name" placeholder="Enter Task">
                        </div>
                        <div class="col-sm-4">
                            
                        </div>
                    </div><br>
                    <button type="submit" class="btn btn-primary" id="save-button">Submit</button>
                </form>
                <div id="table-load">
                    <table class="table" id="usersTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Task</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        $(document).ready(function () {
            function loadTable() {
                $.ajax({
                    url: "{{ route('showTask') }}", // retrive data from the route
                    method: 'GET',
                    success: function (response) {
                        $('#usersTable tbody').empty();
                        $.each(response, function (index, user) {
                            console.log(user.status);
                            let taskStatus = user.status === 1 ? 'Done' : '';
                            $('#usersTable tbody').append(`
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.taskName}</td>
                            <td>${taskStatus}</td>
                            <td> 
                                <input class="form-check-input task-status-checkbox" type="checkbox" data-id="${user.id}" ${user.status === 1 ? 'checked' : ''}>
                                 | <a class="btn btn-danger btn-sm delete-btn" data-id="${user.id}" role="button">x</a>
                            </td>
                        </tr>
                    `);
                        });
                    }
                });
            }
            loadTable();
            $('#save-button').on('click', function (e) {
                e.preventDefault(); //this function stop type submit action
                var task_name = $('#task_name').val();
                if (task_name == "") {
                    $('#error').html('All field is required').slideDown();
                    $('#success').slideUp();
                } else {
                    $.ajax({
                        url: "{{ route('taskAdd') }}",
                        type: "Post",
                        data: {
                            task_name_: task_name,
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (data) {
                            if (data == 1) //get response from controller
                            {
                                loadTable();
                                //reset form after submit
                                $('#insert-form').trigger('reset');
                                $('#success').html("Record Inserted").slideDown();
                                $('#error').slideUp();
                            } else {

                                $('#error').html("Record Can't insert").slideDown();
                                $('#success').slideUp();
                            }

                        }
                    })
                }
            })
            loadTable();
            $(document).on('click', '.delete-btn', function (e) {
                e.preventDefault();

                let taskId = $(this).data('id');
                let url = `/remove-tasks/${taskId}`;
                if (confirm('Are you sure you want to delete this task?')) {
                    $.ajax({
                        url: url,
                        type: 'DELETE',
                        data: {
                            _token: "{{ csrf_token() }}"
                        },
                        success: function (response) {
                            if (response.success) {
                                // Remove the row from the table
                                $(`#row-${taskId}`).remove();
                                alert(response.message);
                            }
                            loadTable();
                        },
                        error: function (xhr) {
                            alert('Error deleting the task. Please try again.');
                        }
                    });
                }
            });
            loadTable();
            $(document).on('change', '.task-status-checkbox', function () {
                let taskId = $(this).data('id');
                let isChecked = $(this).is(':checked');
                let status = isChecked ? 1 : 0;
                loadTable();
                $.ajax({
                    url: `/tasks/${taskId}/status`,
                    type: 'PATCH',
                    data: {
                        _token: "{{ csrf_token() }}",
                        status: status
                    },
                    success: function (response) {
                        if (response.success) {
                            console.log(response.message);
                        }
                        loadTable();
                    },
                    error: function (xhr) {
                        alert('Error updating the task status. Please try again.');
                    }
                });
            });
            loadTable();
        })
    </script>
</body>

</html>