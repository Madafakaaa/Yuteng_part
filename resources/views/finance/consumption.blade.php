@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">课消统计</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">统计中心</li>
    <li class="breadcrumb-item active">课消统计</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-3">
        <div class="col-3 text-left">
          <a href="?month={{ date('Y-m', strtotime ('-1 month', strtotime($month))) }}"><button type="button" class="btn btn-secondary btn-icon-only rounded-circle"><i class="fa fa-chevron-left"></i></button></a>
        </div>
        <div class="col-6 text-center">
          <h1 class="text-white mb-0">{{date('Y.m', strtotime($month))}}</h1>
        </div>
        <div class="col-3 text-right">
          <a href="?month={{ date('Y-m', strtotime ('+1 month', strtotime($month))) }}"><button type="button" class="btn btn-secondary btn-icon-only rounded-circle"><i class="fa fa-chevron-right"></i></button></a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h4 class="card-title text-uppercase mb-1 text-muted">排课数量</h4>
              <span class="h2 font-weight-bold mb-1 text-blue">{{$dashboard['total_lesson_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                <i class="ni ni-ruler-pencil"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            @if($dashboard['total_lesson_num_change']>0)
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $dashboard['total_lesson_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @elseif($dashboard['total_lesson_num_change']==0)
            <span class="text-info mr-2"><i class="fa fa-minus"></i> 0%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @else
            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ $dashboard['total_lesson_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @endif
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h4 class="card-title text-uppercase mb-1 text-muted">已上课数量</h4>
              <span class="h2 font-weight-bold mb-1 text-blue">{{$dashboard['total_attended_lesson_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                <i class="ni ni-single-copy-04"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            @if($dashboard['total_attended_lesson_num_change']>0)
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $dashboard['total_attended_lesson_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @elseif($dashboard['total_attended_lesson_num_change']==0)
            <span class="text-info mr-2"><i class="fa fa-minus"></i> 0%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @else
            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ $dashboard['total_attended_lesson_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @endif
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h4 class="card-title text-uppercase mb-1 text-muted">已上课人次</h4>
              <span class="h2 font-weight-bold mb-1 text-blue">{{$dashboard['total_attended_student_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                <i class="ni ni-single-02"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            @if($dashboard['total_attended_student_num_change']>0)
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $dashboard['total_attended_student_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @elseif($dashboard['total_attended_student_num_change']==0)
            <span class="text-info mr-2"><i class="fa fa-minus"></i> 0%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @else
            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ $dashboard['total_attended_student_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @endif
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h4 class="card-title text-uppercase mb-1 text-muted">消耗课时</h4>
              <span class="h2 font-weight-bold mb-1 text-blue">{{$dashboard['total_attended_hour_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                <i class="ni ni-money-coins"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            @if($dashboard['total_attended_hour_num_change']>0)
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $dashboard['total_attended_hour_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @elseif($dashboard['total_attended_hour_num_change']==0)
            <span class="text-info mr-2"><i class="fa fa-minus"></i> 0%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @else
            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ $dashboard['total_attended_hour_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @endif
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card my-1">
        <div class="card-header">
          <h3 class="mb-0">部门统计 <a href="/finance/consumption/department" class="text-blue"><small>查看明细</small></a></h3>
        </div>
        <div class="table-responsive py-4">
          <table class="table table-flush datatable-basic">
            <thead class="thead-light">
              <tr>
                <th>序号</th>
                <th>校区</th>
                <th>已上课总数</th>
                <th>消耗课时</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              @foreach($department_schedules as $department_schedule)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{$department_schedule->department_name}}</td>
                  <td>{{$department_schedule->attended_schedule_num}}</td>
                  <td>{{$department_schedule->total_hour_num}}</td>
                  <td>
                    <a href="/finance/consumption/department?filter_department={{$department_schedule->department_id}}&filter_month={{$month}}">
                      <button type="button" class="btn btn-outline-primary btn-sm">查看明细</button>
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
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card my-2">
        <div class="card-header">
          <h3 class="mb-0">个人统计 <a href="/finance/consumption/user" class="text-blue"><small>查看明细</small></a></h3>
        </div>
        <div class="table-responsive py-4">
          <table class="table table-flush datatable-basic">
            <thead class="thead-light">
              <tr>
                <th>序号</th>
                <th>用户</th>
                <th>校区</th>
                <th>已上课总数</th>
                <th>消耗课时</th>
                <th>操作</th>
              </tr>
            </thead>
            <tbody>
              @foreach($user_schedules as $user_schedule)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td><a href="/user?id={{encode($user_schedule->user_id,'user_id')}}">{{$user_schedule->user_name}}</a></td>
                  <td>{{$user_schedule->department_name}}</td>
                  <td>{{$user_schedule->attended_schedule_num}}</td>
                  <td>{{$user_schedule->total_hour_num}}</td>
                  <td>
                    <a href="/finance/consumption/user?filter_user={{$user_schedule->user_id}}&filter_month={{$month}}">
                      <button type="button" class="btn btn-outline-primary btn-sm">查看明细</button>
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
