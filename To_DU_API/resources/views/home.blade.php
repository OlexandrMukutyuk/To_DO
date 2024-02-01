@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>{{ __('Dashboard') }}</span>
                        <a href="{{ route('create.task') }}" class="btn btn-primary">Створити завдання</a>
                    </div>
                </div>

                <div class="card-body">
                    @foreach ($tasks as $task)
                        <div class="task-item mb-3 mx-2">
                            <form action="{{ route('update.task', ['id' => $task->id]) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="d-flex justify-content-between align-items-center">
                                    <input type="text" name="task" class="form-control" value="{{ $task->task }}" required>
                                    <div class="form-check mx-2">
                                        <input type="hidden" name="completed" value="0">
                                        <input type="checkbox" name="completed" id="completed" class="form-check-input" {{ $task->is_completed ? 'checked' : '' }} value="1">
                                        <label for="completed" class="form-check-label">Виконано</label>
                                    </div>
                                    <button type="submit" class="btn btn-success mx-2">Змінити</button>
                                </div>
                            </form>
                        </div>
                    @endforeach
                </div>

            </div>
        </div>
    </div>
</div>
@endsection
