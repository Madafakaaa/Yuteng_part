@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">班级详情</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">班级详情</li>
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
              <img src="{{ asset(_ASSETS_.'/avatar/class.png') }}" class="rounded-circle">
            </div>
          </div>
        </div>
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="text-center pb-2">
            <h1>{{ $class->class_name }}</h1>
            <div class="h5 font-weight-300">{{ $class->class_id }}</div>
            <hr>
            <div class="h5">
              {{ $class->department_name }}
            </div>
            <div class="h5">
              年级 - {{ $class->grade_name }}
            </div>
            <div class="h5">
              科目 - {{ $class->subject_name }}
            </div>
            <div class="h5">
              教师 - {{ $class->user_name }}
            </div>
            <div class="h5">
              规模 - {{ $class->class_current_num }} / {{ $class->class_max_num }} 人
            </div>
            <hr>
            <div class="h5">
              备注 - {{ $class->class_remark }}
            </div>
          </div>
        </div>
      </div>
      <div class="card mb-0">
        <div class="card-header">
          <h5 class="h3 mb-0">班级成员</h5>
        </div>
        <div class="card-body py-2">
          <ul class="list-group list-group-flush list my--1 px-1">
            @foreach ($members as $member)
              <li class="list-group-item px-0">
                <div class="row align-items-center">
                  <div class="col ml--2">
                    <h4 class="mb-0">
                      <a href="#!">{{ $member->student_name }}</a>
                    </h4>
                    <span class="text-success">●</span>
                    <small>{{ $member->student_id }}</small>
                  </div>
                  <div class="col-auto">
                    <a href="/student?id={{encode($member->student_id,'student_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>
                    <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/class/member/delete?input_class_id={{ encode($class->class_id, 'class_id') }}&input_student_id={{ encode($member->student_id, 'student_id') }}', '确认删除学生？')">删除</button>
                  </div>
                </div>
              </li>
            @endforeach
          </ul>
          @if($class->class_current_num<$class->class_max_num)
            <form action="/class/member/add" method="post" onsubmit="submitButtonDisable('submitButton1')">
              @csrf
              <div class="card-body p-0 mt-3">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-2">
                      <select class="form-control" name="input_student_id" data-toggle="select" required>
                        <option value=''>添加新成员...</option>
                        @if(count($same_grade_students)>0)
                          <optgroup label="{{$class->grade_name}}学生">
                            @foreach ($same_grade_students as $student)
                              <option value="{{ $student->student_id }}">[ {{ $student->grade_name }} ] {{ $student->student_name }}</option>
                            @endforeach
                          </optgroup>
                        @endif
                        @if(count($diff_grade_students)>0)
                          <optgroup label="其它年级">
                            @foreach ($diff_grade_students as $student)
                              <option value="{{ $student->student_id }}">[ {{ $student->grade_name }} ] {{ $student->student_name }}</option>
                            @endforeach
                          </optgroup>
                        @endif
                      </select>
                    </div>
                  </div>
                  <div class="col-12">
                    <input type="hidden" name="input_class_id" value="{{ $class->class_id }}">
                    <input type="submit" class="form-control btn btn-warning btn-block" value="添加" id="submitButton1">
                  </div>
                </div>
              </div>
            </form>
          @endif
        </div>
      </div>
    </div>
    <div class="col-lg-9 col-md-6 col-sm-12">
      <div class="nav-wrapper p-0">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-3 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-badge mr-2"></i>课程安排</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>上课记录</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>修改信息</a>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:40px;">校区</th>
                    <th style="width:90px;">教师</th>
                    <th style="width:40px;">科目</th>
                    <th style="width:40px;">年级</th>
                    <th style="width:70px;">日期</th>
                    <th style="width:70px;">时间</th>
                    <th style="width:60px;">地点</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($schedules as $schedule)
                    <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $schedule->department_name }}</td>
                      <td><a href="/user?id={{encode($schedule->user_id,'user_id')}}">{{ $schedule->user_name }}</a> [ {{ $schedule->position_name }} ]</td>
                      <td>{{ $schedule->subject_name }}</td>
                      <td>{{ $schedule->grade_name }}</td>
                      <td>{{ $schedule->schedule_date }}</td>
                      <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                      <td>{{ $schedule->classroom_name }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:40px;">校区</th>
                    <th style="width:90px;">教师</th>
                    <th style="width:40px;">科目</th>
                    <th style="width:40px;">年级</th>
                    <th style="width:70px;">日期</th>
                    <th style="width:70px;">时间</th>
                    <th style="width:60px;">教室</th>
                    <th style="width:50px;">操作</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($attended_schedules as $schedule)
                    <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $schedule->department_name }}</td>
                      <td><a href="/user?id={{encode($schedule->user_id,'user_id')}}">{{ $schedule->user_name }}</a> [ {{ $schedule->position_name }} ]</td>
                      <td>{{ $schedule->subject_name }}</td>
                      <td>{{ $schedule->grade_name }}</td>
                      <td>{{ $schedule->schedule_date }}</td>
                      <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                      <td>{{ $schedule->classroom_name }}</td>
                      <td>
                        <a href="/attendedSchedule?id={{encode($schedule->schedule_id,'schedule_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>&nbsp;
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
          <div class="card mb-4">
            <form action="/class/update?id={{ encode($class->class_id, 'class_id') }}" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
              @csrf
              <!-- Card body -->
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">班号<span style="color:red">*</span></label>
                      <input class="form-control" type="text" value="{{ $class->class_id }}" readonly>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">名称<span style="color:red">*</span></label>
                      <input class="form-control" type="text" name="input1" value="{{ $class->class_name }}" autocomplete='off' required maxlength="20">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">校区<span style="color:red">*</span></label>
                      <input class="form-control" type="text" value="{{ $class->department_name }}" readonly>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">年级<span style="color:red">*</span></label>
                      <select class="form-control" name="input2" data-toggle="select" required>
                        @foreach ($grades as $grade)
                          <option value="{{ $grade->grade_id }}" @if($class->class_grade==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">科目<span style="color:red">*</span></label>
                      <select class="form-control" name="input3" data-toggle="select" required>
                        @foreach ($subjects as $subject)
                          <option value="{{ $subject->subject_id }}" @if($class->class_subject==$subject->subject_id) selected @endif>{{ $subject->subject_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">负责教师<span style="color:red">*</span></label>
                      <select class="form-control" name="input4" data-toggle="select" required>
                        @if(count($same_department_users)>0)
                          <optgroup label="{{$class->department_name}}教师">
                            @foreach ($same_department_users as $user)
                              <option value="{{ $user->user_id }}" @if($class->class_teacher==$user->user_id) selected @endif>{{ $user->user_name }} [ {{ $user->position_name }} ]</option>
                            @endforeach
                          </optgroup>
                        @endif
                        @if(count($diff_department_users)>0)
                          <optgroup label="其它校区">
                            @foreach ($diff_department_users as $user)
                              <option value="{{ $user->user_id }}" @if($class->class_teacher==$user->user_id) selected @endif>{{ $user->user_name }} [ {{ $user->position_name }} ]</option>
                            @endforeach
                          </optgroup>
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">最大人数<span style="color:red">*</span></label>
                      <input class="form-control" type="number" name="input5" value="{{ $class->class_max_num }}" autocomplete='off' @if($class->class_current_num==0) min="1" @else min="{{ $class->class_current_num }}" @endif>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">当前人数</label>
                      <input class="form-control" type="number" value="{{ $class->class_current_num }}" readonly>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-12">
                    <div class="form-group">
                      <label class="form-control-label">备注</label>
                      <textarea class="form-control" name="input6" rows="3" resize="none" spellcheck="false" autocomplete='off' maxlength="140">{{ $class->class_remark }}</textarea>
                    </div>
                  </div>
                </div>
                <hr>
                <div class="row">
                  <div class="col-3">
                    <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
                  </div>
                  <div class="col-6"></div>
                  <div class="col-3">
                    <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="提交">
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
<script>
  linkActive('link-3');
  navbarActive('navbar-3');
</script>
@endsection
