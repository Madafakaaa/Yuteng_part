@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="/department">校区管理</a></li>
    <li class="breadcrumb-item active">校区详情</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row">
    <div class="col-lg-4 col-md-8 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <div class="card-header">
          <h3 class="mb-0">校区详情</h3>
        </div>
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">校区序号</label>
                <input class="form-control" type="text" value="{{ $department->department_id }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">校区名称</label>
                <input class="form-control" type="text" value="{{ $department->department_name }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">添加时间</label>
                <input class="form-control" type="text" value="{{ $department->department_createtime }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <a href="/department/{{ $department->department_id }}/edit"><button class="btn btn-block btn-warning">修改</button></a>
            </div>
          </div>
        </div>
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
