@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">财务中心</li>
    <li class="breadcrumb-item"><a href="/payment">学生购课</a></li>
    <li class="breadcrumb-item active">添加购课</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/payment" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">添加购课</h3>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">购课学生<span style="color:red">*</span><a href="/payment/create">重新选择</a></label>
                  <input class="form-control" type="text" value="{{ $student->student_name }}" readonly>
                  <input class="form-control" type="hidden" name="input1" value="{{ $student->student_id }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">校区<span style="color:red">*</span></label>
                  <input class="form-control" type="text" value="{{ $student->department_name }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">年级<span style="color:red">*</span></label>
                  <input class="form-control" type="text" value="{{ $student->grade_name }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">购买课程<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" id="input2" data-toggle="select" onChange="update()" required>
                    <option value=''>请选择课程...</option>
                    @foreach ($courses as $course)
                      <option value="{{ $course->course_id }},{{ $course->course_unit_price }}">{{ $course->course_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">课时单价<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input3" id='input3' readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">购买课时<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input4" id='input4' placeholder="请输入购买课时..." autocomplete='off' min="1" onInput="update()" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">应付金额<span style="color:red">*</span></label>
                  <input class="form-control" type="number" name="input5" id='input5' readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <label class="form-control-label">购课时间<span style="color:red">*</span></label>
                  <input class="form-control datepicker" name="input6" type="text" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2"></div>
              <div class="col-8">
                <div class="form-group">
                  <input type="submit" class="btn btn-primary btn-block" value="提交">
                </div>
              </div>
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
  linkActive('link-4');
  navbarActive('navbar-4');
  linkActive('payment');
</script>
<script type="text/javascript">
function update(){
    if(document.getElementById('input2').value!=''){
        document.getElementById('input3').value=document.getElementById('input2').value.split(",")[1];
        if(document.getElementById('input4').value!=''){
            document.getElementById('input5').value=parseInt(document.getElementById('input3').value)*parseInt(document.getElementById('input4').value);
        }
    }else{
        document.getElementById('input3').value="";
    }
}
</script>
@endsection
