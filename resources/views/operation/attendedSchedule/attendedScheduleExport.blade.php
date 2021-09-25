@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">上课记录 @if (Session::get('user_access_self')==1) （个人点名） @endif</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">上课记录</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
            <button type="button" class="btn btn-waring btn-block" onclick="table_export('table-1', '导出表格')">导出表格</button>
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover text-left table-bordered" id='table-1'>
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:200px;'>班级</th>
                <th style='width:90px;'>校区</th>
                <th style='width:130px;'>日期</th>
                <th style='width:110px;'>时间</th>
                <th style='width:120px;' class="text-left">实到/应到人数</th>
                <th style='width:140px;'>教师</th>
                <th style='width:60px;'>科目</th>
                <th style='width:60px;'>年级</th>
                <th style='width:100px;'>教室</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($schedules as $schedule)
              <tr>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td><a href="/class?id={{encode($schedule['class_id'] ,'class_id')}}">{{ $schedule['class_name'] }}</a> </td>
                <td>{{ $schedule['department_name'] }}</td>
                <td>{{ $schedule['schedule_date'] }}&nbsp;{{ dateToDay($schedule['schedule_date']) }}</td>
                <td>{{ date('H:i', strtotime($schedule['schedule_start'])) }} - {{ date('H:i', strtotime($schedule['schedule_end'])) }}</td>
                <td class="text-right">
                  @if($schedule['schedule_attended_num']==$schedule['schedule_student_num'])
                    <span style="color:green;">{{ $schedule['schedule_attended_num'] }} / {{ $schedule['schedule_student_num'] }} 人</span>
                  @else
                    <span style="color:red;">{{ $schedule['schedule_attended_num'] }} / {{ $schedule['schedule_student_num'] }} 人</span>
                  @endif
                </td>
                <td><a href="/user?id={{encode($schedule['user_id'] ,'user_id')}}">{{ $schedule['user_name'] }}</a> [ {{ $schedule['position_name'] }} ]</td>
                <td>{{ $schedule['subject_name'] }}</td>
                <td>{{ $schedule['grade_name'] }}</td>
                <td>{{ $schedule['classroom_name'] }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationHour');
</script>
@endsection
