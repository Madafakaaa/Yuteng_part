@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">校区课消</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">统计中心</li>
    <li class="breadcrumb-item"><a href="/finance/consumption">课消统计</a></li>
    <li class="breadcrumb-item active">校区课消</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card main_card mb-4" style="display:none">
        <div class="card-header py-3" style="border-bottom:0px;">
          <form action="" method="get" onsubmit="submitButtonDisable('submitButton1')">
            <div class="row">
              <div class="col-6">
                <a href="?filter_month={{ date('Y-m', strtotime ('-1 month', strtotime($filters['filter_month']))) }}&@foreach($filters as $key => $value) @if($key!='filter_month') {{$key}}={{$value}}& @endif @endforeach"><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-left"></i></button></a>
                <a href="?filter_month={{ date('Y-m', strtotime ('+1 month', strtotime($filters['filter_month']))) }}&@foreach($filters as $key => $value) @if($key!='filter_month') {{$key}}={{$value}}& @endif @endforeach"><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-right"></i></button></a>
                <span style="vertical-align: middle; font-size:26px; font-family: 'Noto Sans', sans-serif;" class="ml-3">{{ date('Y.m', strtotime($filters['filter_month'])) }}</span>
              </div>
              <div class="col-3">
              </div>
              <div class="col-2 text-right">
                <input class="form-control monthpicker" name="filter_month" placeholder="选择月份" type="text" value="{{$filters['filter_month']}}" autocomplete="off">
              </div>
              <div class="col-1 text-right">
                <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
                <input type="submit" id="submitButton1" class="btn btn-primary btn-block" value="查询">
              </div>
            </div>
          </form>
        </div>
        <hr class="mb-1 mt-0">
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">校区：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_department'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_departments as $filter_department)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach &filter_department={{$filter_department->department_id}}"><button type="button" @if($filters['filter_department']==$filter_department->department_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_department->department_name}}</button></a>
          @endforeach
        </div>
        <div class="table-responsive"  style="max-height:600px;">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'>序号</th>
                <th style='width:90px;'>班级</th>
                <th style='width:60px;'>班级校区</th>
                <th style='width:45px;'>上课人数</th>
                <th style='width:45px;'>请假人数</th>
                <th style='width:45px;'>旷课人数</th>
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
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="13">当前没有记录</td></tr>
              @endif
              @foreach($rows as $row)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td><a href="/class?id={{encode($row->class_id,'class_id')}}">{{ $row->class_name }}</a></td>
                <td>{{ $row->department_name }}</td>
                <td>
                  @if($row->schedule_attended_num==0)
                    -
                  @else
                    {{ $row->schedule_attended_num }}
                  @endif
                </td>
                <td>
                  @if($row->schedule_leave_num==0)
                    -
                  @else
                    {{ $row->schedule_leave_num }}
                  @endif
                </td>
                <td>
                  @if($row->schedule_absence_num==0)
                    -
                  @else
                    {{ $row->schedule_absence_num }}
                  @endif
                </td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->subject_name }}</td>
                <td>{{ date('m-d', strtotime($row->schedule_date)) }}&nbsp;{{ dateToDay($row->schedule_date) }}</td>
                <td>{{ date('H:i', strtotime($row->schedule_start)) }} - {{ date('H:i', strtotime($row->schedule_end)) }}</td>
                <td>
                  @if($row->total_hour==0)
                    -
                  @else
                    {{ $row->total_hour }}
                  @endif
                </td>
                <td><a href="/user?id={{encode($row->user_id,'user_id')}}">{{ $row->user_name }}</a></td>
                <td>
                  <a href="/attendedSchedule?id={{encode($row->schedule_id,'schedule_id')}}">
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
  linkActive('financeConsumption');
</script>
@endsection
