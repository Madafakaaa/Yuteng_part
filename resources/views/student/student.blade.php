@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">学生详情</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学生详情</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-profile mt-6">
        <div class="row justify-content-center">
          <div class="col-lg-3 order-lg-2">
            <div class="card-profile-image">
              <img src="{{ asset(_ASSETS_.'/avatar/student.png') }}" class="rounded-circle">
            </div>
          </div>
        </div>
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="text-center pb-2">
            <h1>
              {{ $student->student_name }}
              @if($student->student_gender=="男")
                <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
              @else
                <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
              @endif
            </h1>
            <div class="h5 font-weight-300">{{ $student->student_id }}</div>
            <hr>
            <div class="h5">{{ $student->department_name }}</div>
            <div class="h5">年级 - {{ $student->grade_name }}</div>
            <div class="h5">家长 - {{ $student->student_guardian_relationship }} {{ $student->student_guardian }}</div>
            <div class="h5">电话 - {{ $student->student_phone }}</div>
            <hr>
            <div class="row text-center ml-2">
              <div class="col-12">
                <div class="h5">课程顾问 - @if($student->consultant_name=="") <span style="color:red;">无</span> @else {{ $student->consultant_name }} [ {{ $student->consultant_position_name }} ] @endif</div>
              </div>
            </div>
            <hr>
            <div class="row text-center ml-2">
              <div class="col-12">
                <div class="h5">班主任 - @if($student->class_adviser_name=="") <span style="color:red;">无</span> @else {{ $student->class_adviser_name }} [ {{ $student->class_adviser_position_name }} ] @endif</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">班级列表</h5>
        </div>
        <div class="card-body py-2">
          <ul class="list-group list-group-flush list my--1 px-1">
            @foreach ($classes as $class)
              <li class="list-group-item px-0">
                <div class="row align-items-center">
                  <div class="col ml--2">
                    <h4 class="mb-0">
                      <a href="/class?id={{encode($class->class_id,'class_id')}}">{{ $class->class_name }}</a>
                    </h4>
                    <span class="text-success">●</span>
                    <small>{{ $class->subject_name }} | 教师：<a href="/user?id={{encode($class->user_id,'user_id')}}">{{ $class->user_name }}</a></small>
                  </div>
                  <div class="col-auto">
                    <a href="/class?id={{encode($class->class_id,'class_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>
                    <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/student/member/delete?input_class_id={{ encode($class->class_id, 'class_id') }}&input_student_id={{ encode($student->student_id, 'student_id') }}', '确认退出班级？')">退出</button>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
          <form action="/student/member/add" method="post" onsubmit="submitButtonDisable('submitButton3')">
            @csrf
            <div class="card-body p-0 mt-3">
              <div class="row">
                <div class="col-12">
                  <div class="form-group mb-2">
                    <select class="form-control" name="input_class_id" data-toggle="select" required>
                      <option value=''>加入班级...</option>
                      @if(count($same_grade_classes)>0)
                        <optgroup label="{{$student->grade_name}}班级">
                          @foreach ($same_grade_classes as $class)
                            <option value="{{ $class->class_id }}">[ {{ $class->grade_name }} ] {{ $class->class_name }} - {{$class->class_current_num}}/{{$class->class_max_num}}人</option>
                          @endforeach
                        </optgroup>
                      @endif
                      @if(count($diff_grade_classes)>0)
                        <optgroup label="其它年级">
                          @foreach ($diff_grade_classes as $class)
                            <option value="{{ $class->class_id }}">[ {{ $class->grade_name }} ] {{ $class->class_name }} - {{$class->class_current_num}}/{{$class->class_max_num}}人</option>
                          @endforeach
                        </optgroup>
                      @endif
                    </select>
                  </div>
                </div>
                <div class="col-12">
                  <input type="hidden" name="input_student_id" value="{{ $student->student_id }}">
                  <input type="submit" class="form-control btn btn-warning btn-block" value="加入班级" id="submitButton3">
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div class="col-lg-9 col-md-6 col-sm-12">
      <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-3 active" id="schedule-tab" data-toggle="tab" href="#schedule-card" role="tab" aria-selected="true"><i class="ni ni-badge mr-2"></i>课程安排</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="attended-schedule-tab" data-toggle="tab" href="#attended-schedule-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>上课记录</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="hour-tab" data-toggle="tab" href="#hour-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>剩余课时</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="hour-update-record-tab" data-toggle="tab" href="#hour-update-record-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>课时修改记录</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="contract-tab" data-toggle="tab" href="#contract-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>学生合同</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="record-tab" data-toggle="tab" href="#record-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>学生动态</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="edit-tab" data-toggle="tab" href="#edit-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>修改信息</a>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="schedule-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:170px;">班级</th>
                    <th style="width:100px;">教师</th>
                    <th style="width:30px;">科目</th>
                    <th style="width:90px;">日期</th>
                    <th style="width:60px;">时间</th>
                    <th style="width:60px;">地点</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($schedules as $schedule)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="/class?id={{encode($schedule->class_id,'class_id')}}">{{ $schedule->class_name }} [ {{ $schedule->class_id }} ]</a></td>
                      <td><a href="/user?id={{encode($schedule->user_id,'user_id')}}">{{ $schedule->user_name }}</a> [ {{ $schedule->position_name }} ]</td>
                      <td>{{ $schedule->subject_name }}</td>
                      <td>{{ $schedule->schedule_date }}&nbsp;{{ dateToDay($schedule->schedule_date) }}</td>
                      <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                      <td>{{ $schedule->classroom_name }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="attended-schedule-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:170px;">班级</th>
                    <th style="width:30px;">状态</th>
                    <th style="width:150px;">使用课时</th>
                    <th style="width:90px;">教师</th>
                    <th style="width:30px;">科目</th>
                    <th style="width:100px;">日期</th>
                    <th style="width:60px;">时间</th>
                    <th style="width:60px;">地点</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($attended_schedules as $schedule)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="/class?id={{encode($schedule->class_id,'class_id')}}">{{ $schedule->class_name }} [ {{ $schedule->class_id }} ]</a></td>
                      @if($schedule->participant_attend_status==1)
                      <td><span class="text-success">正常</span></td>
                      @elseif($schedule->participant_attend_status==2)
                      <td><span class="text-warning">请假</span></td>
                      @else
                      <td><span class="text-danger">旷课</span></td>
                      @endif
                      <td>[ {{ $schedule->participant_amount }}课时 ] {{ $schedule->course_name }}</td>
                      <td><a href="/user?id={{encode($schedule->user_id,'user_id')}}">{{ $schedule->user_name }}</a> [ {{ $schedule->position_name }} ]</td>
                      <td>{{ $schedule->subject_name }}</td>
                      <td>{{ $schedule->schedule_date }}&nbsp;{{ dateToDay($schedule->schedule_date) }}</td>
                      <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                      <td>{{ $schedule->classroom_name }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="hour-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>课程</th>
                    <th>剩余课时</th>
                    <th>已用课时</th>
                    <th>课时单价</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($hours as $hour)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $hour->course_name }}</td>
                      <td>{{ $hour->hour_remain }} 课时</td>
                      <td>{{ $hour->hour_used }} 课时</td>
                      <td>{{ $hour->hour_average_price }} 元/课时</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="hour-update-record-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:90px;">课程</th>
                    <th style="width:45px;">修改前剩余</th>
                    <th style="width:45px;">修改后剩余</th>
                    <th style="width:200px;">修改备注</th>
                    <th style="width:70px;">修改用户</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($hour_update_records as $hour_update_record)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $hour_update_record->course_name }}</td>
                      <td>{{ $hour_update_record->hour_update_record_remain_before }} 课时</td>
                      <td>{{ $hour_update_record->hour_update_record_remain_after }} 课时</td>
                      <td>{{ $hour_update_record->hour_update_record_remark }}</td>
                      <td><a href="/user?id={{encode($hour_update_record->user_id,'user_id')}}">{{ $hour_update_record->user_name }}</a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="contract-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:60px;">类型</th>
                    <th style="width:70px;">合计课时</th>
                    <th style="width:80px;">实付金额</th>
                    <th style="width:90px;">签约人</th>
                    <th style="width:40px;">支付方式</th>
                    <th style="width:90px;">购课日期</th>
                    <th style="width:90px;">操作</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($contracts as $contract)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      @if($contract->contract_type==0)
                        <td><span style="color:red;">首签</span></td>
                      @else
                        <td><span style="color:green;">续签</span></td>
                      @endif
                      <td><strong>{{ $contract->contract_total_hour }} 课时</strong></td>
                      <td><strong>{{ number_format($contract->contract_total_price, 1) }} 元</strong></td>
                      <td><a href="/user?id={{encode($contract->user_id,'user_id')}}">{{ $contract->user_name }}</a> [ {{ $contract->position_name }} ]</td>
                      <td>{{ $contract->contract_payment_method }}</td>
                      <td>{{ $contract->contract_date }}</td>
                      <td><a href="/contract?id={{encode($contract->contract_id, 'contract_id')}}" target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="record-card" role="tabpanel">
          <div class="card">
            <div class="card-header">
              <h5 class="h3 mb-0">添加跟进记录</h5>
            </div>
            <form action="/student/record?id={{encode($student->student_id,'student_id')}}" method="post" onsubmit="submitButtonDisable('submitButton2')">
              @csrf
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-2">
                      <textarea class="form-control" name="input1" rows="2" resize="none" spellcheck="false" autocomplete='off' maxlength="140" placeholder="请输入跟进内容..." required></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-2 text-center">
                    <label class="form-control-label">跟进方式</label>
                  </div>
                  <div class="col-2 px-2 mb-2">
                    <div class="form-group mb-1">
                      <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                        <option value=''>请选择...</option>
                        <option value='电话'>电话</option>
                        <option value='上门'>上门</option>
                        <option value='微信'>微信</option>
                        <option value='短信'>短信</option>
                        <option value='其它'>其它</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-2 text-center">
                    <label class="form-control-label">跟进日期</label>
                  </div>
                  <div class="col-2 px-2 mb-2">
                    <div class="form-group mb-1">
                      <input class="form-control form-control-sm datepicker" name="input3" type="text" autocomplete="off" value="{{ date('Y-m-d') }}" required>
                    </div>
                  </div>
                  <div class="col-1"></div>
                  <div class="col-3">
                    <input type="submit" id="submitButton2" class="btn btn-sm btn-warning btn-block" value="保存">
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="list-group list-group-flush" style="max-height:990px; overflow:auto;">
            @foreach ($student_records as $student_record)
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start py-4 px-4">
              <div class="d-flex w-100 justify-content-between">
                <div>
                  <div class="d-flex w-100 align-items-center">
                    <img src="{{ asset(_ASSETS_.'/avatar/male.png') }}" class="avatar avatar-xs mr-2" />
                    <h5 class="mb-1">{{ $student_record->user_name }}</h5>
                  </div>
                </div>
                <small>{{ $student_record->student_record_createtime }}</small>
              </div>
              <h4 class="mt-3 mb-1">{{ $student_record->student_record_type }}</h4>
              <p class="text-sm mb-0">{!! $student_record->student_record_content !!}</p>
            </a>
            @endforeach
          </div>
        </div>

        <div class="tab-pane fade" id="edit-card" role="tabpanel">
          <div class="card">
            <form action="/student/update" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton2')">
              @csrf
              <!-- Card body -->
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">学生姓名<span style="color:red">*</span></label>
                      <input class="form-control" type="text" name="input1" value="{{ $student->student_name }}" autocomplete='off' maxlength="5" required>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">学生性别<span style="color:red">*</span></label>
                      <select class="form-control" name="input3" data-toggle="select" required>
                        <option value=''>请选择性别...</option>
                        <option value='男' @if($student->student_gender=="男") selected @endif>男</option>
                        <option value='女' @if($student->student_gender=="女") selected @endif>女</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">学生年级<span style="color:red">*</span></label>
                      <select class="form-control" name="input4" data-toggle="select" required>
                        <option value=''>请选择学生年级...</option>
                        @foreach ($grades as $grade)
                          <option value="{{ $grade->grade_id }}" @if($student->student_grade==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">所属大区</label>
                      <select class="form-control" name="input5" data-toggle="select">
                        <option value='0'>请选择大区...</option>
                        @foreach ($schools as $school)
                          <option value="{{ $school->school_id }}" @if($student->student_school==$school->school_id) selected @endif>{{ $school->school_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">监护人姓名<span style="color:red">*</span></label>
                      <input class="form-control" type="text" name="input6" value="{{ $student->student_guardian }}" autocomplete='off' required maxlength="5">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">监护人关系<span style="color:red">*</span></label>
                      <select class="form-control" name="input7" data-toggle="select" required>
                        <option value=''>请选择监护人关系...</option>
                        <option value='爸爸' @if($student->student_guardian_relationship=="爸爸") selected @endif>爸爸</option>
                        <option value='妈妈' @if($student->student_guardian_relationship=="妈妈") selected @endif>妈妈</option>
                        <option value='爷爷' @if($student->student_guardian_relationship=="爷爷") selected @endif>爷爷</option>
                        <option value='奶奶' @if($student->student_guardian_relationship=="奶奶") selected @endif>奶奶</option>
                        <option value='叔叔' @if($student->student_guardian_relationship=="叔叔") selected @endif>叔叔</option>
                        <option value='阿姨' @if($student->student_guardian_relationship=="阿姨") selected @endif>阿姨</option>
                        <option value='其他' @if($student->student_guardian_relationship=="其他") selected @endif>其他</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">联系电话<span style="color:red">*</span></label>
                      <input class="form-control" type="text" name="input8" value="{{ $student->student_phone }}" autocomplete='off' required maxlength="11">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">微信号</label>
                      <input class="form-control" type="text" name="input9" value="{{ $student->student_wechat }}" autocomplete='off' maxlength="20">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">来源类型<span style="color:red">*</span></label>
                      <select class="form-control" name="input10" data-toggle="select" required>
                        <option value=''>请选择来源...</option>
                        @foreach ($sources as $source)
                          <option value="{{ $source->source_name }}" @if($student->student_source==$source->source_name) selected @endif>{{ $source->source_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">学生生日<span style="color:red">*</span></label>
                      <input class="form-control datepicker" name="input11" type="text" value="{{ $student->student_birthday }}" required>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">课程顾问<span style="color:red">*</span></label>
                      <select class="form-control" name="input12" data-toggle="select" required>
                        <option value=''>请选择课程顾问...</option>
                        @foreach ($users as $user)
                          <option value="{{ $user->user_id }}" @if($student->student_consultant==$user->user_id) selected @endif>{{ $user->user_name }} [ {{$user->position_name}} ]</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">班主任<span style="color:red">*</span></label>
                      <select class="form-control" name="input13" data-toggle="select" required>
                        <option value=''>请选择班主任...</option>
                        @foreach ($users as $user)
                          <option value="{{ $user->user_id }}" @if($student->student_class_adviser==$user->user_id) selected @endif>{{ $user->user_name }} [ {{$user->position_name}} ]</option>
                        @endforeach
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
                    <input name="id" type="hidden" value="{{ $student->student_id }}">
                    <input type="submit" id="submitButton2" class="btn btn-warning btn-block" value="修改">
                  </div>
                </div>
              </div>
            <form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
@endsection
