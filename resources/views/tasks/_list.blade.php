@if($tasks->isEmpty())
  <div class="empty-soft p-5 text-center text-muted">
    No tasks found. Add your first task above!
  </div>
@else
  <div class="row g-3">
    @foreach($tasks as $task)
      <div class="col-md-6 col-lg-4">
        <div class="p-3 task-card bg-white">
          <div class="d-flex justify-content-between align-items-start">
            <h5 class="mb-1 {{ $task->status === 'Done' ? 'done' : '' }}">{{ $task->title }}</h5>
            <span class="badge
              @if($task->priority==='High') bg-danger
              @elseif($task->priority==='Medium') bg-warning text-dark
              @else bg-secondary @endif">
              {{ $task->priority }}
            </span>
          </div>

          <div class="small text-muted mb-2">
            Status: {{ $task->status }}
            @if($task->deadline)
              • Due: {{ $task->deadline->format('d M Y H:i') }}
            @endif
          </div>

          @if($task->description)
            <p class="mb-2">{{ $task->description }}</p>
          @endif

          <div class="d-flex gap-2">
            <a href="{{ route('tasks.edit', $task) }}" class="btn btn-sm btn-outline-primary">Edit</a>
            <button class="btn btn-sm btn-outline-danger btn-delete" data-url="{{ route('tasks.destroy', $task) }}">Delete</button>
          </div>
        </div>
      </div>
    @endforeach
  </div>
@endif
