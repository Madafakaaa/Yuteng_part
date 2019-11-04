@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="/user">用户管理</a></li>
    <li class="breadcrumb-item active">添加用户</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 card-wrapper ct-example">
      <div class="card" id="main_card" style="display:none">
        <form action="/user" method="post">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加用户</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="form-group">
              <label class="form-control-label">用户姓名</label>
              <input class="form-control" type="text" name="input1" placeholder="请输入用户姓名... (姓名长度小于10, 不能重名)" autocomplete='off' required>
            </div>
            <div class="form-group mb-2">
              <label class="form-control-label">用户性别</label>
            </div>
            <div class="form-group">
              <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                <input type="radio" id="input2_1" name="input2" class="custom-control-input" checked>
                <label class="custom-control-label" for="input2_1">男</label>
              </div>
              <div class="custom-control custom-radio custom-control-inline ml-2 mr-4">
                <input type="radio" id="input2_2" name="input2" class="custom-control-input">
                <label class="custom-control-label" for="input2_2">女</label>
              </div>
            </div>
            <div class="form-group">
              <label class="form-control-label">用户校区</label>
              <select class="form-control" name="input3" data-toggle="select">
                <option value=''>请选择校区...</option>
                @foreach ($departments as $department)
                  <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-control-label">用户岗位</label>
              <select class="form-control" name="input4" data-toggle="select">
                <option value=''>请选择岗位...</option>
                @foreach ($positions as $position)
                  <option value="{{ $position->position_id }}">{{ $position->position_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-control-label">入职日期</label>
              <div class="input-group">
                <div class="input-group-prepend">
                  <span class="input-group-text"><i class="ni ni-calendar-grid-58"></i></span>
                </div>
                <input class="form-control datepicker" placeholder="Select date" type="text" value="06/20/2019">
              </div>
              <input class="form-control datepicker" placeholder="Select date" type="text" value="{{ date('Y-m-d') }}">
            </div>
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="添加用户">
            </div>
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
  sidebarActive('user');
</script>
@endsection
