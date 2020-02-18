@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">安排学生课程</li>
    <li class="breadcrumb-item active">选择上课时间</li>
@endsection

@section('content')
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
        <form action="/operation/studentSchedule/create2" method="post" id="form1" name="form1">
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
              <div class="col-4 pl-2 pr-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ Session::get('user_department_name') }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>上课日期</label>
              </div>
              <div class="col-10 px-2 mb-2">
                <div class="form-group mb-1">
                  <div class="row input-daterange datepicker align-items-center">
                    <div class="col-5">
                      <input class="form-control form-control-sm" type="text" name="input1" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-1 text-left">
                      <label class="form-control-label">起</label>
                    </div>
                    <div class="col-5">
                      <input class="form-control form-control-sm" type="text" name="input2" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="col-1 text-left">
                      <label class="form-control-label">止</label>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>排课规律</label>
              </div>
              <div class="col-8 px-4 mb-2">
                <div class="form-group mb-1">
                  <div class="custom-control custom-checkbox">
                    <div class="row">
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input type="checkbox" class="custom-control-input" id="checkAll" onchange="CheckAll();">
                        <label class="custom-control-label" for="checkAll">全选</label>
                      </div>
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input type="checkbox" class="custom-control-input checkbox" id="check0" name="input3[]" value="0" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check0">周日</label>
                      </div>
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input type="checkbox" class="custom-control-input checkbox" id="check6" name="input3[]" value="6" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check6">周六</label>
                      </div>
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input type="checkbox" class="custom-control-input checkbox" id="check5" name="input3[]" value="5" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check5">周五</label>
                      </div>
                    </div>
                    <div class="row">
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input type="checkbox" class="custom-control-input checkbox" id="check4" name="input3[]" value="4" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check4">周四</label>
                      </div>
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input type="checkbox" class="custom-control-input checkbox" id="check3" name="input3[]" value="3" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check3">周三</label>
                      </div>
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input type="checkbox" class="custom-control-input checkbox" id="check2" name="input3[]" value="2" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check2">周二</label>
                      </div>
                      <div class="col-3 pl-2 pr-2 mb-2">
                        <input type="checkbox" class="custom-control-input checkbox" id="check1" name="input3[]" value="1" onchange="updateCheckAll();">
                        <label class="custom-control-label" for="check1">周一</label>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>上课时间</label>
              </div>
              <div class="col-4 pl-2 pr-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input4" data-toggle="select" required>
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
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>下课时间</label>
              </div>
              <div class="col-4 pl-2 pr-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input5" data-toggle="select" required>
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
            <hr>
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="/operation/studentSchedule/createIrregular" ><button type="button" class="btn btn-outline-default btn-block">非规律排课</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
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

  function CheckAll(){
      // 判断是全选还是反选
      if($("#checkAll").is(':checked')){
          $(".checkbox").each(function(){
              $(this).prop('checked',true);
          });
      }else{
          $(".checkbox").each(function(){
              $(this).prop('checked',false);
          });
      }
  }

  function updateCheckAll(){
      // 判断是全选还是反选
      if($("#check0").is(':checked')
         &&$("#check1").is(':checked')
         &&$("#check2").is(':checked')
         &&$("#check3").is(':checked')
         &&$("#check4").is(':checked')
         &&$("#check5").is(':checked')
         &&$("#check6").is(':checked')){
          $("#checkAll").prop('checked',true);
      }else{
          $("#checkAll").prop('checked',false);
      }
  }
</script>
@endsection
