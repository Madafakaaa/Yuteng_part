@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">学生排课</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item active">学生排课</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">选择上课时间</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">选择课程信息</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">确认上课信息</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/operation/studentSchedule/createIrregular2" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">一、选择上课时间</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课校区</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <select class="form-control form-control-sm" name="input0" data-toggle="select" required>
                        <option value=''>请选择上课校区...</option>
                        @foreach ($departments as $department)
                          <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>上课日期</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <input class="form-control form-control-sm multidatepicker" name="input1" id="input1" type="text" placeholder="请选择上课日期..." autocomplete="off" onchange="updateDateNum();" required>
                    </div>
                    <div class="col-4">
                      <label class="form-control-label">已选<span id="date_num" style="color:red;">0</span>天</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>上课时间</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                        <option value=''>请选择上课时间...</option>
                        <option value='8:00'>8:00</option>
                        <option value='8:30'>8:30</option>
                        <option value='9:00'>9:00</option>
                        <option value='9:30'>9:30</option>
                        <option value='10:00'>10:00</option>
                        <option value='10:30'>10:30</option>
                        <option value='11:00'>11:00</option>
                        <option value='11:30'>11:30</option>
                        <option value='12:00'>12:00</option>
                        <option value='12:30'>12:30</option>
                        <option value='13:00'>13:00</option>
                        <option value='13:30'>13:30</option>
                        <option value='14:00'>14:00</option>
                        <option value='14:30'>14:30</option>
                        <option value='15:00'>15:00</option>
                        <option value='15:30'>15:30</option>
                        <option value='16:00'>16:00</option>
                        <option value='16:30'>16:30</option>
                        <option value='17:00'>17:00</option>
                        <option value='17:30'>17:30</option>
                        <option value='18:00'>18:00</option>
                        <option value='18:30'>18:30</option>
                        <option value='19:00'>19:00</option>
                        <option value='19:30'>19:30</option>
                        <option value='20:00'>20:00</option>
                        <option value='20:30'>20:30</option>
                        <option value='21:00'>21:00</option>
                        <option value='21:30'>21:30</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>下课时间</label>
              </div>
              <div class="col-10">
                <div class="form-group mb-1">
                  <div class="row">
                    <div class="col-6 pl-2 pr-2 mb-2">
                      <select class="form-control form-control-sm" name="input3" data-toggle="select" required>
                        <option value=''>请选择下课时间...</option>
                        <option value='8:30'>8:30</option>
                        <option value='9:00'>9:00</option>
                        <option value='9:30'>9:30</option>
                        <option value='10:00'>10:00</option>
                        <option value='10:30'>10:30</option>
                        <option value='11:00'>11:00</option>
                        <option value='11:30'>11:30</option>
                        <option value='12:00'>12:00</option>
                        <option value='12:30'>12:30</option>
                        <option value='13:00'>13:00</option>
                        <option value='13:30'>13:30</option>
                        <option value='14:00'>14:00</option>
                        <option value='14:30'>14:30</option>
                        <option value='15:00'>15:00</option>
                        <option value='15:30'>15:30</option>
                        <option value='16:00'>16:00</option>
                        <option value='16:30'>16:30</option>
                        <option value='17:00'>17:00</option>
                        <option value='17:30'>17:30</option>
                        <option value='18:00'>18:00</option>
                        <option value='18:30'>18:30</option>
                        <option value='19:00'>19:00</option>
                        <option value='19:30'>19:30</option>
                        <option value='20:00'>20:00</option>
                        <option value='20:30'>20:30</option>
                        <option value='21:00'>21:00</option>
                        <option value='21:30'>21:30</option>
                        <option value='22:00'>22:00</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-3">
                <a href="/operation/studentSchedule/create" ><button type="button" class="btn btn-outline-default btn-block">规律排课</button></a>
              </div>
              <div class="col-6"></div>
              <div class="col-3">
                <input type="submit" class="btn btn-primary btn-block" value="下一步">
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationStudentScheduleCreate');

  function updateDateNum(){
      var length = $("#input1").val().split(",").length;
      if(length!=1){
	      $("#date_num").text($("#input1").val().split(",").length);
      }else{
          if($("#input1").val()==""){
              $("#date_num").text(0);
          }else{
              $("#date_num").text(1);
          }
      }
	  console.log($("#input1").val().split(",").length);
  }
</script>
@endsection
