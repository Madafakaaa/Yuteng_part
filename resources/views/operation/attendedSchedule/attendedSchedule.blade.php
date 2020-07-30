@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">上课记录</h2>
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
  <div class="row mb-3">
    <div class="col-auto">
      <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
        <span class="btn-inner--text">搜索</span>
      </a>
      <button class="btn btn-sm btn-outline-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="批量删除" onclick="batchDeleteConfirm('/operation/attendedSchedule/delete', '确认批量删除所选上课记录？')">
        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
        <span class="btn-inner--text">批量删除</span>
      </button>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="collapse @if($filter_status==1) show @endif" id="filter">
        <div class="card mb-4">
          <div class="card-body border-1 p-0 my-1">
            <form action="" method="get">
              <div class="row m-2">
                <div class="col-lg-8 col-md-8 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <input class="form-control" type="text" name="filter_name" placeholder="班级名称..." autocomplete="off" @if(isset($filters['filter_name']))) value="{{ $filters['filter_name'] }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter_teacher" data-toggle="select">
                        <option value=''>负责教师</option>
                        @foreach ($filter_users as $filter_user)
                          <option value="{{ $filter_user->user_id }}" @if($filters['filter_teacher']==$filter_user->user_id) selected @endif>{{$filter_user->department_name}} {{ $filter_user->user_name }}</option>
                        @endforeach
                      </select>
	                </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
                      <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
                      <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
                      <input type="hidden" name="filter_subject" value="{{$filters['filter_subject']}}">
                      <input type="submit" class="btn btn-primary btn-block" value="查询">
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
                      <a href="?"><button type="button" class="form-control btn btn-outline-primary btn-block" style="white-space:nowrap; overflow:hidden;">重置</button></a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-header py-3" style="border-bottom:0px;">
          <form action="" method="get" onsubmit="submitButtonDisable('submitButton1')">
            <div class="row">
              <div class="col-lg-6 col-md-6 col-sm-12">
                <a href="?filter_date={{$first_day_prev}}&@foreach($filters as $key => $value) @if($key!='filter_date') {{$key}}={{$value}}& @endif @endforeach"><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-left"></i></button></a>
                <a href="?filter_date={{$first_day_next}}&@foreach($filters as $key => $value) @if($key!='filter_date') {{$key}}={{$value}}& @endif @endforeach"><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-right"></i></button></a>
                <span style="vertical-align: middle; font-size:26px; font-family: 'Noto Sans', sans-serif;" class="ml-3">{{ date('Y.m.d', strtotime($first_day)) }} ~ {{ date('m.d', strtotime($last_day)) }}</span>
              </div>
              <div class="col-lg-3 col-md-1 col-sm-12">
              </div>
              <div class="col-lg-2 col-md-3 col-sm-8 text-right">
                <input class="form-control datepicker" name="filter_date" placeholder="选择日期" type="text" value="{{$first_day}}">
              </div>
              <div class="col-lg-1 col-md-2 col-sm-4 text-right">
                <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
                <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
                <input type="hidden" name="filter_subject" value="{{$filters['filter_subject']}}">
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
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">年级：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_grade'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_grades as $filter_grade)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach filter_grade={{$filter_grade->grade_id}}"><button type="button" @if($filters['filter_grade']==$filter_grade->grade_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_grade->grade_name}}</button></a>
          @endforeach
        </div>
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">科目：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_subject') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_subject'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_subjects as $filter_subject)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_subject') {{$key}}={{$value}}& @endif @endforeach filter_subject={{$filter_subject->subject_id}}"><button type="button" @if($filters['filter_subject']==$filter_subject->subject_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_subject->subject_name}}</button></a>
          @endforeach
        </div>
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:130px;'>班级</th>
                <th style='width:210px;'></th>
                <th style='width:100px;'>校区</th>
                <th style='width:160px;'>日期</th>
                <th style='width:110px;'>时间</th>
                <th style='width:110px;'>班级人数</th>
                <th style='width:100px;'>教师</th>
                <th style='width:70px;'>科目</th>
                <th style='width:70px;'>年级</th>
                <th style='width:110px;'>教室</th>
                <th style='width:170px;'>课程</th>
                <th style='width:100px;'>排课用户</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
                <tr class="text-center"><td colspan="12">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($row->schedule_id, 'schedule_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>
                  {{ $row->class_name }}
                </td>
                <td>
                  <a href="/attendedSchedule?id={{encode($row->schedule_id,'schedule_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>&nbsp;
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/operation/attendedSchedule/delete?id={{encode($row->schedule_id, 'schedule_id')}}', '确认删除上课记录？')">删除</button>
                </td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->schedule_date }}&nbsp;{{ dateToDay($row->schedule_date) }}</td>
                <td>{{ date('H:i', strtotime($row->schedule_start)) }} - {{ date('H:i', strtotime($row->schedule_end)) }}</td>
                <td>
                  @if($row->class_current_num==$row->class_max_num)
                    <span style="color:green;">{{ $row->class_current_num }} / {{ $row->class_max_num }} 人</span>
                  @else
                    <span style="color:red;">{{ $row->class_current_num }} / {{ $row->class_max_num }} 人</span>
                  @endif
                </td>
                <td>{{ $row->teacher_name }}</td>
                <td>{{ $row->subject_name }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->classroom_name }}</td>
                <td>{{ $row->course_name }}</td>
                <td>{{ $row->creator_name }}</td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      {{ pageLink($currentPage, $totalPage, $request, $totalNum) }}
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationAttendedSchedule');
</script>
@endsection
