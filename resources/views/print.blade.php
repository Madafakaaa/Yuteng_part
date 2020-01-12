<!-- HTML header content -->
@include('layout.header')
<body>
  <div class="main-content">
    <!-- Main content -->
    @section('content')
    <div class="row justify-content-center">
      <div class="col-11">
        <div class="card m-4">
          <div class="card-header m-4">
            header
          </div>
          <div class="card-body m-4">
            body
          </div>
        </div>
      </div>
    </div>
    @show
  </div>
</body>
</html>
