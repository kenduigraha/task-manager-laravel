@extends('layouts.app')

@section('content')
  <h1 class="fw-bold">Task Manager</h1>
  <p class="text-muted mb-4">Organize your tasks efficiently</p>

  <div class="card-soft p-4 mb-4">
    <h5 class="mb-3">＋ Add New Task</h5>
    <form id="addForm">
      @csrf
      <div class="row g-3 align-items-end">
        <div class="col-md-6">
          <label class="form-label">Task Title *</label>
          <input name="title" type="text" class="form-control" placeholder="Enter task title"
                 required minlength="3" maxlength="255" pattern="^[A-Za-z0-9\s\-\_.,]+$">
        </div>
        <div class="col-md-3">
          <label class="form-label">Priority</label>
          <select name="priority" class="form-select">
            <option>Low</option>
            <option selected>Medium</option>
            <option>High</option>
          </select>
        </div>
        <div class="col-md-12">
          <label class="form-label">Description</label>
          <textarea name="description" class="form-control" placeholder="Enter task description (optional)" maxlength="1000"></textarea>
        </div>
        <div class="col-md-6">
          <label class="form-label">Deadline</label>
          <input name="deadline" type="datetime-local" class="form-control">
        </div>
        <div class="col-md-12">
          <input type="hidden" name="status" value="To-Do">
          <button class="btn btn-orange w-100 py-2" type="submit">＋ Add Task</button>
        </div>
      </div>
    </form>
  </div>

  <ul class="nav nav-pills pill mb-3" id="filterTabs">
    <li class="nav-item">
      <button class="nav-link {{ $filter==='all'?'active':'' }}" data-filter="all">All Tasks ({{ $total }})</button>
    </li>
    <li class="nav-item">
      <button class="nav-link {{ $filter==='pending'?'active':'' }}" data-filter="pending">Pending ({{ $pending }})</button>
    </li>
    <li class="nav-item">
      <button class="nav-link {{ $filter==='completed'?'active':'' }}" data-filter="completed">Completed ({{ $completed }})</button>
    </li>
  </ul>

  <div class="mb-4">
    <h5>Status Progress</h5>
    <div class="progress" style="height: 25px;">
        <div class="progress-bar bg-success" role="progressbar" 
             style="width: {{ $progress }}%" 
             aria-valuenow="{{ $progress }}" aria-valuemin="0" aria-valuemax="100">
            {{ $progress }}%
        </div>
    </div>

    <div class="mt-2">
        <span class="badge bg-secondary">Total: {{ $total }}</span>
        <span class="badge bg-danger">To-Do: {{ $todo }}</span>
        <span class="badge bg-warning text-dark">In Progress: {{ $inprogress }}</span>
        <span class="badge bg-success">Done: {{ $completed }}</span>
    </div>
  </div>

  <form method="GET" action="{{ route('tasks.index') }}" class="row g-2 mb-4">
    <div class="col-md-4">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
               placeholder="Search tasks...">
    </div>
    <div class="col-md-3">
        <select name="status" class="form-select">
            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
            <option value="To-Do" {{ request('status') == 'To-Do' ? 'selected' : '' }}>To-Do</option>
            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
            <option value="Done" {{ request('status') == 'Done' ? 'selected' : '' }}>Done</option>
        </select>
    </div>
    <div class="col-md-2">
        <button type="submit" class="btn btn-primary w-100">Filter</button>
    </div>
    <div class="col-md-2">
        <a href="{{ route('tasks.index') }}" class="btn btn-secondary w-100">Reset</a>
    </div>
  </form>

  <div id="taskList">
    @include('tasks._list', ['tasks' => $tasks])
  </div>
@endsection

@push('scripts')
<script>
  const csrf = $('meta[name="csrf-token"]').attr('content');
  let currentFilter = '{{ $filter }}';

  function reloadList() {
    $.get("{{ route('tasks.index') }}", { filter: currentFilter, ajax: 1 })
      .done(function (html) {
        $('#taskList').html(html);
      });
  }

  // Submit ADD via AJAX
  $('#addForm').on('submit', function(e){
    e.preventDefault();
    const form = $(this);

    $.ajax({
      url: "{{ route('tasks.store') }}",
      method: 'POST',
      data: form.serialize(),
      headers: {'X-CSRF-TOKEN': csrf}
    }).done(function(){
      form[0].reset();
      Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: 'Task added',
        showConfirmButton: false,
        timer: 1500
      });
      reloadList();
    }).fail(function(xhr){
      let msg = 'Failed to add task.';
      if (xhr.status === 422) {
        const errs = xhr.responseJSON.errors;
        msg = Object.values(errs).flat().join('\\n');
      }
      Swal.fire('Validation Error', msg, 'error');
    });
  });

  // Tabs click -> reload via AJAX
  $('#filterTabs').on('click', '.nav-link', function(){
    $('#filterTabs .nav-link').removeClass('active');
    $(this).addClass('active');
    currentFilter = $(this).data('filter');
    reloadList();
  });

  // Delegated delete button
  $('#taskList').on('click', '.btn-delete', function(e){
    e.preventDefault();
    const url = $(this).data('url');

    Swal.fire({title:'Delete this task?',icon:'warning',showCancelButton:true,confirmButtonText:'Delete'})
      .then((res)=>{
        if(!res.isConfirmed) return;
        $.ajax({
          url: url, method:'POST',
          data:{ _method:'DELETE', _token: csrf }
        }).done(function(){
          Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'success',
            title: 'Task deleted',
            showConfirmButton: false,
            timer: 1500
          });
          reloadList();
        }).fail(()=> Swal.fire('Error','Failed to delete','error'));
      });
  });
</script>
@endpush
