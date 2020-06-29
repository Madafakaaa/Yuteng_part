@extends('main')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">班级详情</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">班级详情</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
            <a href="/class/edit?id={{ encode($class->class_id, 'class_id') }}" class="btn btn-sm btn-primary mr-4">修改信息</a>
            <!-- <a href="/schedule/create"  target="_blank" class="btn btn-sm btn-warning float-right">新建排课</a> -->
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="text-center pb-2">
            <h1>{{ $class->class_name }}</h1>
            <div class="h5 font-weight-300">{{ $class->class_id }}</div>
            <hr>
            <div class="row text-left ml-2">
              <div class="col-6">
                <div class="h4">校区 - {{ $class->department_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">年级 - {{ $class->grade_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">科目 - {{ $class->subject_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">负责人 - {{ $class->user_name }}</div>
              </div>
              <div class="col-12">
                <div class="h4">班级规模 - {{ $class->class_current_num }} / {{ $class->class_max_num }} 人</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">班级成员 ( {{ $class->class_current_num }} / {{ $class->class_max_num }} 人 )</h5>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush list my--1">
            @foreach ($members as $member)
            <li class="list-group-item px-0">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <a href="#" class="avatar rounded-circle">
                      <img alt="Image placeholder" src="../../assets/img/theme/team-1.jpg">
                    </a>
                  </div>
                  <div class="col ml--2">
                    <h4 class="mb-0">
                      <a href="#!">{{ $member->student_name }}</a>
                    </h4>
                    <span class="text-success">●</span>
                    <small>{{ $member->student_id }}</small>
                  </div>
                  <div class="col-auto">
                    <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/class/memberDelete?class_id={{ encode($class->class_id, 'class_id') }}&student_id={{ encode($member->student_id, 'student_id') }}', '确认删除学生？')">删除</button>
                  </div>
                </div>
            </li>
            @endforeach
          </ul>
          <!--
            <form action="/class/memberAdd?id={{ encode($class->class_id, 'class_id') }}" method="post">
              @csrf
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-8">
                    <div class="form-group mb-2">
                      <select class="form-control" name="input1" data-toggle="select" required>
                        <option value=''>添加新学生</option>
                        @foreach ($students as $student)
                          <option value="{{ $student->student_id }}">{{ $student->student_name }} ({{ $student->student_id }})</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-4">
                    <input type="submit" class="btn btn-warning btn-block" value="添加">
                  </div>
                </div>
              </div>
            </form>
          -->
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">班级备注</h5>
        </div>
        <div class="card-body">
          <h5 class="h4 mb-0">{{ $class->class_remark }}</h4>
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-md-6 col-sm-12">
      <div class="nav-wrapper p-0">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-3 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-badge mr-2"></i>课程安排</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>上课记录</a>
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
                    <th>序号</th>
                    <th>校区</th>
                    <th>课程</th>
                    <th>教师</th>
                    <th>科目</th>
                    <th>年级</th>
                    <th>日期</th>
                    <th>时间</th>
                    <th>地点</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($schedules as $schedule)
                    <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $schedule->department_name }}</td>
                      <td>{{ $schedule->course_name }}</td>
                      <td>{{ $schedule->user_name }} ({{ $schedule->position_name }})</td>
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
                    <th>序号</th>
                    <th>校区</th>
                    <th>课程</th>
                    <th>教师</th>
                    <th>科目</th>
                    <th>年级</th>
                    <th>日期</th>
                    <th>时间</th>
                    <th>地点</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($attended_schedules as $schedule)
                    <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $schedule->department_name }}</td>
                      <td>{{ $schedule->course_name }}</td>
                      <td>{{ $schedule->user_name }} ({{ $schedule->position_name }})</td>
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
