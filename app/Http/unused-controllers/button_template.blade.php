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
    <div class="container-fluid mt-6">
      <div class="row justify-content-center">
        <div class="col-8 card-wrapper ct-example">
          <div class="card">
            <div class="card-header">
              <div class="row">
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-neutral btn-icon">添加</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-warning">提交</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-primary">查看 / 下一步</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-outline-danger">删除</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-info">搜索</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-outline-primary">重置</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-default">Default</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-secondary">Secondary</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-success">Success</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-danger">Danger</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-outline-default">Default</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-outline-secondary">Secondary</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-outline-info">Info</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-outline-success">Success</button>
                </div>
                <div class="col-6 mb-2">
                  <button type="button" class="btn btn-block btn-outline-warning">Warning</button>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
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
