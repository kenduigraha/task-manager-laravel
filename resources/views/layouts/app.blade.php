<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Task Manager</title>
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

  <style>
    body { background: #fafafa; }
    .card-soft { background:#FFFBEB; border-radius:16px; box-shadow:0 4px 14px rgba(0,0,0,.05); }
    .btn-orange { background:#d97706; border:none; }
    .btn-orange:hover { background:#b45309; }
    .pill .nav-link { border-radius:999px; }
    .task-card { border-radius:12px; box-shadow:0 3px 10px rgba(0,0,0,.04); }
    .empty-soft { background:#FFFBEB; border-radius:16px; }
    .done { text-decoration: line-through; opacity: .65; }
  </style>
</head>
<body>
<div class="container py-5">
  @yield('content')
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@stack('scripts')
</body>
</html>
