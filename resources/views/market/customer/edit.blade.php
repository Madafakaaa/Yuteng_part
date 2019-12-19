@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/customer">客户设置</a></li>
    <li class="breadcrumb-item active">修改客户</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/customer/{{ $customer->customer_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h3 class="mb-0">修改客户</h3>
          </div>
          <!-- Card body -->
          <div class="card-body mb-0 pb-0">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">客户姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $customer->customer_name }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">联系电话<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input2" value="{{ $customer->customer_phone }}" autocomplete='off' required maxlength="11">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">客户校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}" @if($customer->customer_department==$department->department_id) selected @endif>{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">来源类型<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    @foreach ($sources as $source)
                      <option value="{{ $source->source_id }}" @if($customer->customer_source==$source->source_id) selected @endif>{{ $source->source_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">微信号</label>
                  <input class="form-control" type="text" name="input5" value="{{ $customer->customer_wechat }}" autocomplete='off' maxlength="255">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">客户关系</label>
                  <select class="form-control" name="input6" data-toggle="select">
                    <option value=''>请选择关系...</option>
                    <option value='爸爸' @if($customer->customer_relationship=='爸爸') selected @endif>爸爸</option>
                    <option value='妈妈' @if($customer->customer_relationship=='妈妈') selected @endif>妈妈</option>
                    <option value='爷爷' @if($customer->customer_relationship=='爷爷') selected @endif>爷爷</option>
                    <option value='奶奶' @if($customer->customer_relationship=='奶奶') selected @endif>奶奶</option>
                    <option value='叔叔' @if($customer->customer_relationship=='叔叔') selected @endif>叔叔</option>
                    <option value='阿姨' @if($customer->customer_relationship=='阿姨') selected @endif>阿姨</option>
                    <option value='其他' @if($customer->customer_relationship=='其他') selected @endif>其他</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">意向课程</label>
                  <select class="form-control" name="input7" data-toggle="select">
                    <option value=''>请选择意向课程...</option>
                    @foreach ($courses as $course)
                      <option value="{{ $course->course_id }}" @if($customer->customer_course==$course->course_id) selected @endif>{{ $course->course_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">负责人</label>
                  <select class="form-control" name="input8" data-toggle="select">
                    <option value=''>请选择负责人...</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->user_id }}" @if($customer->customer_follower==$user->user_id) selected @endif>{{ $user->user_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body mb-0 pb-0">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <div class="accordion" id="accordion1">
                    <div class="card-header mt-0 pt-0" id="headingOne" data-toggle="collapse" data-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                      <h5 class="mb-0"><span style="color:grey">更多信息</span></h5>
                    </div>
                    <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion1">
                      <div class="card-body">
                        <div class="row">
                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-control-label">学生姓名</label>
                              <input class="form-control" type="text" name="input9" value="{{ $customer->customer_student_name }}" autocomplete='off' maxlength="10">
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-control-label">学生年级</label>
                              <select class="form-control" name="input10" data-toggle="select">
                                <option value=''>请选择学生年级...</option>
                                @foreach ($grades as $grade)
                                  <option value="{{ $grade->grade_id }}" @if($customer->customer_student_grade==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="form-control-label">备注</label>
                              <input class="form-control" type="text" name="input11" value="{{ $customer->customer_remark }}" autocomplete='off' maxlength="255">
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="card-body mt-0 pt-0">
            <div class="form-group">
              <input type="submit" class="btn btn-primary" value="添加客户">
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
  linkActive('link-2');
  navbarActive('navbar-2');
  linkActive('customer');
</script>
@endsection
