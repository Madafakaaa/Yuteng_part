@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="/department">校区管理</a></li>
    <li class="breadcrumb-item active">修改校区</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 card-wrapper ct-example">
      <div class="card" id="main_card" style="display:none">
        <form action="/department/{{ $department->department_id }}" method="post">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改校区</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="form-group">
              <label class="form-control-label">校区序号</label>
              <input class="form-control" type="text" name="input1" value="{{ $department->department_id }}" readonly>
            </div>
            <div class="form-group">
              <label class="form-control-label">校区名称</label>
              <input class="form-control" type="text" name="input2" value="{{ $department->department_name }}" autocomplete='off' required>
            </div>
            <div class="form-group">
              <label class="form-control-label">添加时间</label>
              <input class="form-control" type="text" value="{{ $department->department_createtime }}" readonly>
            </div>
            <input type="submit" class="btn btn-warning" value="修改">
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  sidebarActive('section1');
  sidebarActive('department');
</script>
@endsection
