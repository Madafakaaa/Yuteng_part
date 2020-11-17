@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">校区课消统计</h2>
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
                  <option value=''>全部校区</option>
                  @foreach ($filter_departments as $department)
                    <option value="{{ $department->department_id }}" @if($filters['filter_department']==$department->department_id) selected @endif>{{ $department->department_name }}</option>
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
          <button type="button" class="btn btn-waring btn-block" onclick="table_export('table-1', '课消统计-{{$dashboard['dashboard_department_name']}} ({{date('Y.m.d', strtotime($filters['filter_date_start']))}}-{{date('Y.m.d', strtotime($filters['filter_date_end']))}})')">导出表格</button>
          <table class="table align-items-center table-hover table-bordered text-center" id="table-1">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'>序号</th>
                <th style='width:60px;'>校区</th>
                <th style='width:110px;'>班级</th>
                <th style='width:40px;'>年级</th>
                <th style='width:40px;'>科目</th>
                <th style='width:60px;'>班级规模</th>
                <th style='width:60px;'>应到/实到</th>
                <th style='width:65px;'>学生</th>
                <th style='width:50px;'>签到</th>
                <th style='width:110px;'>扣除课时</th>
                <th style='width:40px;'>数量</th>
                <th style='width:60px;' class="text-right">课时价值</th>
                <th style='width:60px;'>转化小时</th>
                <th style='width:60px;'>班主任</th>
                <th style='width:85px;'>日期</th>
                <th style='width:85px;'>时间</th>
                <th style='width:60px;'>教师</th>
                <th style='width:60px;'>工资小时</th>
                <th style='width:70px;' class="text-right">合计消耗</th>
              </tr>
            </thead>
            <tbody>
              @foreach($schedules as $schedule)
                <tr>
                  <td rowspan="{{ $schedule['student_num'] }}">{{ $loop->iteration }}</td>
                  <td rowspan="{{ $schedule['student_num'] }}">{{ $schedule['department_name'] }}</td>
                  <td rowspan="{{ $schedule['student_num'] }}"><a href="/class?id={{encode($schedule['class_id'],'class_id')}}">{{ $schedule['class_name'] }}</a></td>
                  <td rowspan="{{ $schedule['student_num'] }}">{{ $schedule['grade_name'] }}</td>
                  <td rowspan="{{ $schedule['student_num'] }}">{{ $schedule['subject_name'] }}</td>
                  <td rowspan="{{ $schedule['student_num'] }}">{{ $schedule['class_max_num'] }} 人班</td>
                  @if($schedule['schedule_attended_num']==0)
                    <td rowspan="{{ $schedule['student_num'] }}" class="text-danger">
                      {{ $schedule['schedule_attended_num'] }} / {{ $schedule['schedule_attended_num']+$schedule['schedule_leave_num']+$schedule['schedule_absence_num'] }} 人
                    </td>
                  @elseif($schedule['schedule_attended_num']==$schedule['schedule_attended_num']+$schedule['schedule_leave_num']+$schedule['schedule_absence_num'])
                    <td rowspan="{{ $schedule['student_num'] }}" class="text-success">
                      {{ $schedule['schedule_attended_num'] }} / {{ $schedule['schedule_attended_num']+$schedule['schedule_leave_num']+$schedule['schedule_absence_num'] }} 人
                    </td>
                  @else
                    <td rowspan="{{ $schedule['student_num'] }}" class="text-warning">
                      {{ $schedule['schedule_attended_num'] }} / {{ $schedule['schedule_attended_num']+$schedule['schedule_leave_num']+$schedule['schedule_absence_num'] }} 人
                    </td>
                  @endif
                  <td>{{ $schedule['participants'][0]['student_name'] }}</td>
                  @if($schedule['participants'][0]['participant_attend_status']==1)
                    <td><span class="text-success">正常</span></td>
                  @elseif($schedule['participants'][0]['participant_attend_status']==2)
                    <td><span class="text-warning">请假</span></td>
                  @else
                    <td><span class="text-danger">旷课</span></td>
                  @endif
                  <td>{{ $schedule['participants'][0]['course_name'] }}</td>
                  <td>
                    @if($schedule['participants'][0]['participant_amount']>0)
                      {{ $schedule['participants'][0]['participant_amount'] }}
                    @endif
                  </td>
                  <td class="text-right">
                    @if($schedule['participants'][0]['participant_consumption_price']>0)
                      {{ $schedule['participants'][0]['participant_consumption_price'] }}
                    @endif
                  </td>
                  <td>
                    @if($schedule['participants'][0]['participant_hour']>0)
                      {{ $schedule['participants'][0]['participant_hour'] }}
                    @endif
                  </td>
                  <td>
                      {{ $schedule['participants'][0]['user_name'] }}
                  </td>
                  <td rowspan="{{ $schedule['student_num'] }}">{{ date('m-d', strtotime($schedule['schedule_date'])) }}&nbsp;{{ dateToDay($schedule['schedule_date']) }}</td>
                  <td rowspan="{{ $schedule['student_num'] }}">{{ date('H:i', strtotime($schedule['schedule_start'])) }} - {{ date('H:i', strtotime($schedule['schedule_end'])) }}</td>
                  <td rowspan="{{ $schedule['student_num'] }}"><a href="/user?id={{encode($schedule['user_id'],'user_id')}}">{{ $schedule['user_name'] }}</a></td>
                  <td rowspan="{{ $schedule['student_num'] }}">
                    @if($schedule['schedule_attended_num']>0)
                      {{ $schedule['duration'] }}
                    @endif
                  </td>
                  <td rowspan="{{ $schedule['student_num'] }}" class="text-right">
                    @if($schedule['consumption_price']>0)
                      {{ $schedule['consumption_price'] }}
                    @endif
                  </td>
                </tr>
                @for ($i = 1; $i < $schedule['student_num']; $i++)
                  <tr>
                    <td>{{ $schedule['participants'][$i]['student_name'] }}</td>
                    @if($schedule['participants'][$i]['participant_attend_status']==1)
                      <td><span class="text-success">正常</span></td>
                    @elseif($schedule['participants'][$i]['participant_attend_status']==2)
                      <td><span class="text-warning">请假</span></td>
                    @else
                      <td><span class="text-danger">旷课</span></td>
                    @endif
                    <td>{{ $schedule['participants'][$i]['course_name'] }}</td>
                    <td>
                      @if($schedule['participants'][$i]['participant_amount']>0)
                        {{ $schedule['participants'][$i]['participant_amount'] }}
                      @endif
                    </td>
                    <td class="text-right">
                      @if($schedule['participants'][$i]['participant_consumption_price']>0)
                        {{ $schedule['participants'][$i]['participant_consumption_price'] }}
                      @endif
                    </td>
                    <td>
                      @if($schedule['participants'][$i]['participant_hour']>0)
                        {{ $schedule['participants'][$i]['participant_hour'] }}
                      @endif
                    </td>
                    <td>
                      {{ $schedule['participants'][$i]['user_name'] }}
                    </td>
                  </tr>
                @endfor
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
  linkActive('financeConsumptionDepartment');
</script>
@endsection
