<!-- HTML header content -->
@include('layout.header')
<body>
  <!-- Sidebar Content -->
  @include('layout.sidebar')
  <div class="main-content" id="panel">
    <!-- Top navigator -->
    @include('layout.topnav')
    <!-- Main content -->
    @section('content')

    @show
  </div>
  <!-- Script files -->
  @include('layout.scripts')
</body>
</html>

<!-- PHP/JS functions -->
@include('layout.functions')

<!-- Sidebar active status -->
@section('sidebar_status')
<script>
  linkActive('home');
</script>
@show
