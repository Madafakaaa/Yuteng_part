<!-- HTML header content -->
@include('layout2.header')
<body>
  <!-- Sidebar Content -->
  @include('layout2.sidebar')
  <div class="main-content" id="panel">
    <!-- Top navigator -->
    @include('layout2.topnav')
    <!-- Main content -->
    @section('content')

    @show
  </div>
  <!-- Script files -->
  @include('layout2.scripts')
</body>
</html>

<!-- PHP/JS functions -->
@include('layout2.functions')

<!-- Sidebar active status -->
@section('sidebar_status')
<script>
  sidebarActive('home');
</script>
@show
