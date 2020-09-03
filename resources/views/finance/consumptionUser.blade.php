@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">个人课消统计</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">统计中心</li>
    <li class="breadcrumb-item active">校区课消统计</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <form action="" method="get" onsubmit="submitButtonDisable('submitButton1')">
          <div class="card-header p-3" style="border-bottom:0px;">
            <div class="row">
              <div class="col-1 text-center">
                <small class="text-muted font-weight-bold px-2">起始日期</small>
              </div>
              <div class="col-2">
                <input class="form-control form-control-sm datepicker" name="filter_date_start" type="text" value="{{$filters['filter_date_start']}}" autocomplete="off">
              </div>
              <div class="col-1 text-center">
                <small class="text-muted font-weight-bold px-2">截止日期</small>
              </div>
              <div class="col-2">
                <input class="form-control form-control-sm datepicker" name="filter_date_end" type="text" value="{{$filters['filter_date_end']}}" autocomplete="off">
              </div>
              <div class="col-1 text-center">
                <small class="text-muted font-weight-bold px-2">校区</small>
              </div>
              <div class="col-2">
                <select class="form-control form-control-sm" name="filter_department" data-toggle="select">
                  <option value=''>全部教师</option>
                  @foreach ($filter_users as $user)
                    <option value="{{ $user->user_id }}" @if($filters['filter_user']==$user->user_id) selected @endif>[{{$user->department_name}}] {{ $user->user_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-1">
              </div>
              <div class="col-2">
                <input type="submit" id="submitButton1" class="btn btn-sm btn-primary btn-block" value="查询">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">上课总数</h5>
              <span class="h1 font-weight-bold mb-0 counter-value text-primary">{{$dashboard['dashboard_schedule_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                <i class="fa fa-chalkboard-teacher"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            <span class="text-nowrap">{{date('Y.m.d', strtotime($filters['filter_date_start']))}} ~ {{date('Y.m.d', strtotime($filters['filter_date_end']))}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">消耗课时</h5>
              <span class="h1 font-weight-bold mb-0 counter-value text-primary">{{$dashboard['dashboard_hour_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                <i class="fa fa-user-clock"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            <span class="text-nowrap">{{date('Y.m.d', strtotime($filters['filter_date_start']))}} ~ {{date('Y.m.d', strtotime($filters['filter_date_end']))}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">正常上课人次</h5>
              <span class="h1 font-weight-bold mb-0 counter-value text-green">{{$dashboard['dashboard_attended_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                <i class="fa fa-user"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            <span class="text-nowrap">{{date('Y.m.d', strtotime($filters['filter_date_start']))}} ~ {{date('Y.m.d', strtotime($filters['filter_date_end']))}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">请假/旷课人次</h5>
              <span class="h1 font-weight-bold mb-0 counter-value text-warning">{{$dashboard['dashboard_leave_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-warning text-white rounded-circle shadow">
                <i class="fa fa-user-alt-slash"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            <span class="text-nowrap">{{date('Y.m.d', strtotime($filters['filter_date_start']))}} ~ {{date('Y.m.d', strtotime($filters['filter_date_end']))}}</span>
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="table-responsive"  style="max-height:600px;">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'>序号</th>
                <th style='width:110px;'>班级</th>
                <th style='width:60px;'>班级校区</th>
                <th style='width:40px;'>上课人数</th>
                <th style='width:40px;'>请假人数</th>
                <th style='width:40px;'>旷课人数</th>
                <th style='width:40px;'>年级</th>
                <th style='width:40px;'>科目</th>
                <th style='width:60px;'>日期</th>
                <th style='width:60px;'>时间</th>
                <th style='width:60px;'>共计消耗课时</th>
                <th style='width:60px;'>授课教师</th>
                <th style='width:60px;'>操作</th>
              </tr>
            </thead>
            <tbody>
              @foreach($schedules as $schedule)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td><a href="/class?id={{encode($schedule['class_id'],'class_id')}}">{{ $schedule['class_name'] }}</a></td>
                <td>{{ $schedule['department_name'] }}</td>
                <td>
                  @if($schedule['schedule_attended_num']==0)
                    -
                  @else
                    {{ $schedule['schedule_attended_num'] }}
                  @endif
                </td>
                <td>
                  @if($schedule['schedule_leave_num']==0)
                    -
                  @else
                    {{ $schedule['schedule_leave_num'] }}
                  @endif
                </td>
                <td>
                  @if($schedule['schedule_absence_num']==0)
                    -
                  @else
                    {{ $schedule['schedule_absence_num'] }}
                  @endif
                </td>
                <td>{{ $schedule['grade_name'] }}</td>
                <td>{{ $schedule['subject_name'] }}</td>
                <td>{{ date('m-d', strtotime($schedule['schedule_date'])) }}&nbsp;{{ dateToDay($schedule['schedule_date']) }}</td>
                <td>{{ date('H:i', strtotime($schedule['schedule_start'])) }} - {{ date('H:i', strtotime($schedule['schedule_end'])) }}</td>
                <td>
                  @if($schedule['total_hour']==0)
                    -
                  @else
                    {{ $schedule['total_hour'] }}
                  @endif
                </td>
                <td><a href="/user?id={{encode($schedule['user_id'],'user_id')}}">{{ $schedule['user_name'] }}</a></td>
                <td>
                  <a href="/attendedSchedule?id={{encode($schedule['schedule_id'],'schedule_id')}}">
                    查看上课详情
                  </a>
                </td>
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
  linkActive('link-finance');
  navbarActive('navbar-finance');
  linkActive('financeConsumptionUser');
</script>
@endsection
