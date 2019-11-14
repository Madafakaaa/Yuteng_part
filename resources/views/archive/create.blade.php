@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="/archive">档案管理</a></li>
    <li class="breadcrumb-item active">添加档案</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/archive" method="post" id="form1" name="form1" enctype="multipart/form-data">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加档案</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="form-group">
              <label class="form-control-label">档案用户*</label>
              <select class="form-control" name="input1" data-toggle="select" required>
                <option value=''>请选择用户...</option>
                @foreach ($users as $user)
                  <option value="{{ $user->user_id }}" @if($user->user_id==$user_id) selected @endif>{{ $user->user_name }}</option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label class="form-control-label">档案名称*</label>
              <input class="form-control" type="text" name="input2" placeholder="请输入档案名称... " autocomplete='off' required maxlength="255">
            </div>
            <div class="form-group">
              <label class="form-control-label">档案文件*</label>
              <div class="input-group">
                <input id='location' class="form-control" disabled aria-describedby="button-addon">
                <div class="input-group-append">
                  <input type="button" id="i-check" value="浏览文件" class="btn btn-outline-primary" onClick="$('#i-file').click();" style="margin:0;" id="button-addon">
                  <input type="file" name='file' id='i-file' onChange="$('#location').val($('#i-file').val());" style="display: none">
                </div>
              </div>
            </div>
            <input type="submit" class="btn btn-primary" value="添加档案">
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
  sidebarActive('archive');
</script>
@endsection
