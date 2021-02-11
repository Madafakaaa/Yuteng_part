@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">上课记录 @if (Session::get('user_access_self')==1) （个人） @endif</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教学中心</li>
    <li class="breadcrumb-item active">上课记录</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="?@foreach($filters as $key => $value) @if($key!='filter_teacher') {{$key}}={{$value}}& @endif @endforeach&filter_teacher={{Session::get('user_id')}}" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加客户">
        <span class="btn-inner--icon"><i class="fas fa-user-circle"></i></span>
        <span class="btn-inner--text">我的记录</span>
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
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
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
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($schedule['schedule_id'], 'schedule_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
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
                  <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modal-{{$loop->iteration}}-1">查看</button>&nbsp;
                  <div class="modal fade" id="modal-{{$loop->iteration}}-1" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h6 class="modal-title">{{ $schedule['class_name'] }}</h6>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <ul class="list-group list-group-flush list my--3">
                            @if(count($schedule['participants'])==0)
                              <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                  <div class="col ml--2">
                                    <h4 class="mb-0">
                                      <a href="#!">无</a>
                                    </h4>
                                  </div>
                                </div>
                              </li>
                            @endif
                            @foreach ($schedule['participants'] as $participant)
                              <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <a href="#" class="avatar rounded-circle">
                                      <img alt="..." src="{{ asset(_ASSETS_.'/avatar/student.png') }}">
                                    </a>
                                  </div>
                                  <div class="col ml--2">
                                    <h4 class="mb-1">
                                      <a href="/student?id={{encode($participant['student_id'], 'student_id')}}">{{ $participant['student_name'] }}</a>
                                    </h4>
                                    @if($participant['participant_attend_status']==1)
                                      <span class="text-success">● <small>正常</small></span>
                                      <small>[ 扣除： {{ $participant['course_name'] }} - {{ $participant['participant_amount'] }} 课时 ]</small>
                                    @elseif($participant['participant_attend_status']==2)
                                      <span class="text-warning">● <small>请假</small></span>
                                    @else
                                      <span class="text-danger">● <small>旷课</small></span>
                                      <small>[ 扣除： {{ $participant['course_name'] }} - {{ $participant['participant_amount'] }} 课时 ]</small>
                                    @endif
                                  </div>
                                  <div class="col-auto">
                                    <a href="/student?id={{encode($participant['student_id'], 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>
                                  </div>
                                </div>
                              </li>
                            @endforeach
                          </ul>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-link  ml-auto" data-dismiss="modal">关闭</button>
                        </div>
                      </div>
                    </div>
                  </div>
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
      {{ pageLink($currentPage, $totalPage, $request, $totalNum) }}
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationAttendedSchedule');
</script>
@endsection
