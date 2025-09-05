@extends('layouts.app')

@section('content')
  <h3 class="mb-3">Edit Task</h3>

  <div class="card-soft p-4 mb-4">
    <form method="POST" action="{{ route('tasks.update', $task) }}">
      @csrf @method('PUT')
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Task Title *</label>
          <input name="title" class="form-control" value="{{ old('title',$task->title) }}" required minlength="3" maxlength="255" pattern="^[A-Za-z0-9\s\-\_.,]+$">
        </div>
        <div class="col-md-3">
          <label class="form-label">Priority</label>
          <select name="priority" class="form-select">
            @foreach(['Low','Medium','High'] as $p)
              <option value="{{ $p }}" @selected(old('priority',$task->priority)===$p)>{{ $p }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-md-3">
          <label class="form-label">Status</label>
          <select name="status" class="form-select">
            @foreach(['To-Do','In Progress','Done'] as $s)
              <option value="{{ $s }}" @selected(old('status',$task->status)===$s)>{{ $s }}</option>
            @endforeach
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" maxlength="1000">{{ old('description',$task->description) }}</textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Deadline</label>
          <input name="deadline" type="datetime-local" class="form-control"
                 value="{{ old('deadline', $task->deadline ? $task->deadline->format('Y-m-d\TH:i') : '') }}">
        </div>
        <div class="col-12 d-flex gap-2">
          <button class="btn btn-orange">Save</button>
          <a href="{{ route('tasks.index') }}" class="btn btn-secondary">Back</a>
        </div>
      </div>
    </form>
  </div>
@endsection
