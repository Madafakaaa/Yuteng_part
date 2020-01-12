@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/customer">客户设置</a></li>
    <li class="breadcrumb-item active">添加客户</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/customer" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">添加客户</h4>
          </div>
          <!-- Card body -->
          <div class="card-body mb-0 pb-0">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">客户姓名<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" placeholder="请输入客户姓名... " autocomplete='off' required maxlength="10">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">联系电话<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input2" placeholder="请输入联系电话..." autocomplete='off' required maxlength="11">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">客户校区<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    <option value=''>请选择校区...</option>
                    @foreach ($departments as $department)
                      <option value="{{ $department->department_id }}">{{ $department->department_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">来源类型<span style="color:red">*</span></label>
                  <select class="form-control" name="input4" data-toggle="select" required>
                    <option value=''>请选择来源...</option>
                    @foreach ($sources as $source)
                      <option value="{{ $source->source_id }}">{{ $source->source_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">微信号</label>
                  <input class="form-control" type="text" name="input5" placeholder="请输入微信号..." autocomplete='off' maxlength="255">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">负责人</label>
                  <select class="form-control" name="input8" data-toggle="select">
                    <option value=''>请选择负责人...</option>
                    @foreach ($users as $user)
                      <option value="{{ $user->user_id }}">{{ $user->user_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生姓名</label>
                  <input class="form-control" type="text" name="input9" placeholder="请输入学生姓名... " autocomplete='off' maxlength="10">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">学生年级</label>
                  <select class="form-control" name="input10" data-toggle="select">
                    <option value=''>请选择学生年级...</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}">{{ $grade->grade_name }}</option>
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
                              <label class="form-control-label">客户关系</label>
                              <select class="form-control" name="input6" data-toggle="select">
                                <option value=''>请选择关系...</option>
                                <option value='爸爸'>爸爸</option>
                                <option value='妈妈'>妈妈</option>
                                <option value='爷爷'>爷爷</option>
                                <option value='奶奶'>奶奶</option>
                                <option value='叔叔'>叔叔</option>
                                <option value='阿姨'>阿姨</option>
                                <option value='其他'>其他</option>
                              </select>
                            </div>
                          </div>
                          <div class="col-6">
                            <div class="form-group">
                              <label class="form-control-label">意向课程</label>
                              <select class="form-control" name="input7" data-toggle="select">
                                <option value=''>请选择意向课程...</option>
                                @foreach ($courses as $course)
                                  <option value="{{ $course->course_id }}">{{ $course->course_name }}</option>
                                @endforeach
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="row">
                          <div class="col-12">
                            <div class="form-group">
                              <label class="form-control-label">备注</label>
                              <input class="form-control" type="text" name="input11" placeholder="请输入备注..." autocomplete='off' maxlength="255">
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
            <div class="row">
              <div class="col-9"></div>
              <div class="col-3">
                <div class="form-group">
                  <input type="submit" class="btn btn-warning btn-block" value="添加">
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
  linkActive('link-2');
  navbarActive('navbar-2');
  linkActive('customer');
</script>
@endsection
