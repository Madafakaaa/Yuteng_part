@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">修改教室</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">公司管理</li>
    <li class="breadcrumb-item"><a href="/company/department">教室设置</a></li>
    <li class="breadcrumb-item active">修改教室</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <form action="/company/classroom/update?id={{encode($classroom->classroom_id, 'classroom_id')}}" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改教室</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">教室名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $classroom->classroom_name }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">所属校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}" @if($classroom->classroom_department==$department->department_id) selected @endif>{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">容纳人数<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input3" value="{{ $classroom->classroom_student_num }}" autocomplete='off' min="1" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">教室类型<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择教室类型...</option>
                    <option value="一对一教室" @if($classroom->classroom_type=="一对一教室") selected @endif>一对一教室</option>
                    <option value="小教室" @if($classroom->classroom_type=="小教室") selected @endif>小教室</option>
                    <option value="中教室" @if($classroom->classroom_type=="中教室") selected @endif>中教室</option>
                    <option value="大教室" @if($classroom->classroom_type=="大教室") selected @endif>大教室</option>
                    <option value="多媒体教室" @if($classroom->classroom_type=="多媒体教室") selected @endif>多媒体教室</option>
                  </select>
                </div>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-4 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-4 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-4 col-md-5 col-sm-12">
                <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="修改">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('companyClassroom');
</script>
@endsection
