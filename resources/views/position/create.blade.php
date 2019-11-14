@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="/position">岗位管理</a></li>
    <li class="breadcrumb-item active">添加岗位</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/position" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加岗位</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="form-group">
              <label class="form-control-label">岗位名称*</label>
              <input class="form-control" type="text" name="input1" placeholder="请输入岗位名称... (长度小于10, 且不能重复)" autocomplete='off' required maxlength="10">
            </div>
            <input type="submit" class="btn btn-primary" value="添加岗位">
          </div>
        <form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  sidebarActive('section1');
  sidebarActive('position');
</script>
@endsection
