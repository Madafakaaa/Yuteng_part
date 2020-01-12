@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">财务中心</li>
    <li class="breadcrumb-item"><a href="/contract">学生购课</a></li>
    <li class="breadcrumb-item active">添加购课</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/contract/create/step2" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">添加购课</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">购课学生<span style="color:red">*</span></label>
                  <select class="form-control" name="input1" data-toggle="select" onChange="form_submit('form1')" required>
                    <option value=''>请选择学生...</option>
                    @foreach ($students as $student)
                      <option value="{{ $student->student_id }}">{{ $student->student_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <hr>
        <form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-4');
  navbarActive('navbar-4');
  linkActive('contract');
</script>
@endsection
