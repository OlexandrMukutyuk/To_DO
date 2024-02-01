@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Create Task') }}</div>

                <div class="card-body">
                    <form action="{{ route('store.task') }}" method="post">
                        @csrf
                        <div class="form-group">
                            <label for="task">Завдання:</label>
                            <input type="text" name="task" class="form-control" id="task" required>
                        </div>
                        <div class="mb-3"></div>
                        <!-- Додайте інші поля, якщо потрібно -->

                        <button type="submit" class="btn btn-primary">Створити завдання</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
