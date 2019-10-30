@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="/user">用户管理</a></li>
    <li class="breadcrumb-item active">用户详情</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row">
    <div class="col-lg-4 card-wrapper ct-example">
      <div class="card" id="main_card" style="display:none">
          <div class="card-header">
            <h3 class="mb-0">用户详情</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="form-group">
              <label class="form-control-label">用户序号</label>
              <input class="form-control" type="text" value="{{ $user->user_id }}" readonly>
            </div>
            <div class="form-group">
              <label class="form-control-label">用户名称</label>
              <input class="form-control" type="text" value="{{ $user->user_name }}" readonly>
            </div>
            <div class="form-group">
              <label class="form-control-label">添加时间</label>
              <input class="form-control" type="text" value="{{ $user->user_createtime }}" readonly>
            </div>
            <a href="/user/{{ $user->user_id }}/edit"><button class="btn btn-warning">修改</button></a>
          </div>
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
