@extends('layouts.app')
@section('page_title', 'Home')
@section('content')
    <div class="d-flex justify-content-end mb-4">
        <a href="" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#TaskManager" action="{{ route('store') }}">
            <i class="fa fa-plus-circle"></i> Add Task
        </a>
    </div>
    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>

            <tbody>
                @if ($tasks && !empty(get_object_vars($tasks)))
                    @foreach ($tasks as $task)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td class="task-title">{{ $task->title }}</td>
                            <td class="task-desc">{{ $task->description }}</td>
                            <td class="task-status" value={{ ($task->status == 'completed') ? 1 : 0  }}>
                                @if ($task->status == 'completed')
                                    <span class="badge bg-success">Completed</span>
                                @else
                                    <span class="badge bg-warning">Pending</span>
                                @endif
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-info btn-sm my-1" data-bs-toggle="modal" data-bs-target="#TaskManager" action="{{ route('update', $task->id) }}">
                                    <i class="fa fa-edit"></i> Edit
                                </a>

                                <a href="javascript:void(0);" class="btn btn-danger btn-sm my-1 confirm-action" path="{{ route('delete', $task->id) }}" message="Are you sure you want to delete {{ $task->title }}?" action="delete" action_text="Yes, Delete">
                                    <i class="fa fa-trash"></i> Delete
                                </a>

                                @if ($task->status == 'pending')
                                    <a href="javascript:void(0);" class="btn btn-success btn-sm my-1 confirm-action" path="{{ route('mark-as-completed', $task->id) }}" message="Are you sure you want to mark {{ $task->title }} as completed?" action="post" action_text="Yes, Complete">
                                        <i class="fa fa-check-circle"></i> Mark as Completed
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                       <td colspan="5">
                            <div class="text-center">
                                <p class="text-warning mb-1"><i class="fa fa-exclamation-triangle fa-2x" aria-hidden="true"></i></p>
                                <p class="font-monospace">No Task added yet</p>
                            </div>
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="TaskManager" tabindex="-1" aria-labelledby="TaskManagerLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="javascript:void(0);" method="POST" class="form" autocomplete="off">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="TaskManagerLabel">Add Task</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label for="task_name">Name</label>
                            <input type="text" class="form-control" name="task_name" id="task_name" required />
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="form-group mb-3">
                            <label for="task_desc">Description</label>
                            <textarea name="task_desc" id="task_desc" cols="30" rows="10" class="form-control" required></textarea>
                            <span class="invalid-feedback"></span>
                        </div>

                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" value="1" name="task_status" id="task_completed">
                            <label class="form-check-label" for="task_completed">
                                Completed
                            </label>
                            <span class="invalid-feedback"></span>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
