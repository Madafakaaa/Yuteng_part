@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">课程安排 @if (Session::get('user_access_self')==1) （个人） @endif</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教学中心</li>
    <li class="breadcrumb-item active">课程安排</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="?@foreach($filters as $key => $value) @if($key!='filter_teacher') {{$key}}={{$value}}& @endif @endforeach&filter_teacher={{Session::get('user_id')}}" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加客户">
        <span class="btn-inner--icon"><i class="fas fa-user-circle"></i></span>
        <span class="btn-inner--text">我的安排</span>
      </a>
      <a href="?">
        <button class="btn btn-sm btn-outline-primary btn-round btn-icon">
          <span class="btn-inner--icon"><i class="fas fa-redo"></i></span>
          <span class="btn-inner--text">重置搜索</span>
        </button>
      </a>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header p-1" style="border-bottom:0px;">
          <form action="" method="get" id="filterForm">
            <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
            <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
            <input type="hidden" name="filter_subject" value="{{$filters['filter_subject']}}">
            <div class="row m-2">
              <div class="col-12">
                <small class="text-muted font-weight-bold px-2">校区：</small>
                <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach">
                  <button type="button" @if(!isset($filters['filter_department'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
                </a>
                @foreach($filter_departments as $filter_department)
                  <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach &filter_department={{$filter_department->department_id}}"><button type="button" @if($filters['filter_department']==$filter_department->department_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_department->department_name}}</button></a>
                @endforeach
              </div>
            </div>
            <div class="row m-2">
              <div class="col-12">
                <small class="text-muted font-weight-bold px-2">年级：</small>
                <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach">
                  <button type="button" @if(!isset($filters['filter_grade'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
                </a>
                @foreach($filter_grades as $filter_grade)
                  <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach filter_grade={{$filter_grade->grade_id}}"><button type="button" @if($filters['filter_grade']==$filter_grade->grade_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_grade->grade_name}}</button></a>
                @endforeach
              </div>
            </div>
            <div class="row m-2">
              <div class="col-12">
                <small class="text-muted font-weight-bold px-2">科目：</small>
                <a href="?@foreach($filters as $key => $value) @if($key!='filter_subject') {{$key}}={{$value}}& @endif @endforeach">
                  <button type="button" @if(!isset($filters['filter_subject'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
                </a>
                @foreach($filter_subjects as $filter_subject)
                  <a href="?@foreach($filters as $key => $value) @if($key!='filter_subject') {{$key}}={{$value}}& @endif @endforeach filter_subject={{$filter_subject->subject_id}}"><button type="button" @if($filters['filter_subject']==$filter_subject->subject_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_subject->subject_name}}</button></a>
                @endforeach
              </div>
            </div>
            <hr>
            <div class="row m-2">
              <div class="col-3">
                <select class="form-control form-control-sm" name="filter_class" data-toggle="select" onChange="form_submit('filterForm')">
                  <option value=''>搜素班级...</option>
                  @foreach ($filter_classes as $filter_class)
                    <option value="{{ $filter_class->class_id }}" @if($filters['filter_class']==$filter_class->class_id) selected @endif>[ {{ $filter_class->department_name }} ] {{ $filter_class->class_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-3">
                <select class="form-control form-control-sm" name="filter_teacher" data-toggle="select" onChange="form_submit('filterForm')">
                  <option value=''>搜索教师...</option>
                  @foreach ($filter_users as $filter_user)
                    <option value="{{ $filter_user->user_id }}" @if($filters['filter_teacher']==$filter_user->user_id) selected @endif>[ {{$filter_user->department_name}} ] {{ $filter_user->user_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-3">
                <input class="form-control form-control-sm datepicker" name="filter_date" placeholder="搜索日期..." autocomplete="off" type="text" @if(isset($filters['filter_date']))) value="{{ $filters['filter_date'] }}" @endif onChange="form_submit('filterForm')">
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:60px;'>序号</th>
                <th style='width:190px;'>班级</th>
                <th style='width:90px;'>校区</th>
                <th style='width:130px;'>日期</th>
                <th style='width:100px;'>时间</th>
                <th style='width:90px;'>班级人数</th>
                <th style='width:90px;'>教师</th>
                <th style='width:70px;'>年级</th>
                <th style='width:70px;'>科目</th>
                <th style='width:90px;'>教室</th>
                <th style='width:90px;'>排课用户</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
                <tr class="text-center"><td colspan="13">当前没有记录</td></tr>
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
                <td><a href="/class?id={{encode($row->class_id ,'class_id')}}">{{ $row->class_name }}</a> </td>
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
                <td><a href="/user?id={{encode($row->teacher_id ,'user_id')}}">{{ $row->teacher_name }}</a></td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->subject_name }}</td>
                <td>{{ $row->classroom_name }}</td>
                <td><a href="/user?id={{encode($row->creator_id ,'user_id')}}">{{ $row->creator_name }}</a></td>
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
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationSchedule');
</script>
@endsection
