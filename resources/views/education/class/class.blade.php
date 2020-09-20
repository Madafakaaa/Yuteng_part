@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">班级管理</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教学中心</li>
    <li class="breadcrumb-item active">班级管理</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="?@foreach($filters as $key => $value) @if($key!='filter_teacher') {{$key}}={{$value}}& @endif @endforeach&filter_teacher={{Session::get('user_id')}}" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加客户">
        <span class="btn-inner--icon"><i class="fas fa-user-circle"></i></span>
        <span class="btn-inner--text">我的班级</span>
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
                  <option value=''>搜索班级...</option>
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
            </div>
          </form>
        </div>
        <div class="table-responsive freeze-table-3">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:200px;'>班级</th>
                <th style='width:90px;'>校区</th>
                <th style='width:70px;'>年级</th>
                <th style='width:70px;'>科目</th>
                <th style='width:140px;' class="text-right">班级人数</th>
                <th style='width:140px;' class="text-right">已排课</th>
                <th style='width:100px;' class="text-right">已上课</th>
                <th style='width:130px;'>负责教师</th>
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
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($row->class_id, 'class_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td><a href="/class?id={{encode($row->class_id, 'class_id')}}">{{ $row->class_name }}</a></td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>@if($row->class_subject==0) 全科目 @else{{ $row->subject_name }}@endif</td>
                <td class="text-right">
                  @if($row->class_current_num==$row->class_max_num)
                    <span style="color:green;">{{ $row->class_current_num }} / {{ $row->class_max_num }} 人</span>
                  @else
                    <span style="color:red;">{{ $row->class_current_num }} / {{ $row->class_max_num }} 人</span>
                  @endif
                  <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modal-{{$loop->iteration}}-1">查看列表</button>
                  <div class="modal fade" id="modal-{{$loop->iteration}}-1" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h6 class="modal-title">{{ $row->class_name }}</h6>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <ul class="list-group list-group-flush list my--3">
                            @if(count($members[$loop->iteration-1])==0)
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
                            @foreach ($members[$loop->iteration-1] as $member)
                              <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                  <div class="col-auto">
                                    <a href="#" class="avatar rounded-circle">
                                      <img alt="..." src="{{ asset(_ASSETS_.'/avatar/student.png') }}">
                                    </a>
                                  </div>
                                  <div class="col ml--2">
                                    <h4 class="mb-0">
                                      <a href="/student?id={{encode($member['student_id'], 'student_id')}}">{{ $member['student_name'] }}</a>
                                    </h4>
                                    <span class="text-success">●</span>
                                    <small>{{ $member['student_id'] }}</small>
                                  </div>
                                  <div class="col-auto">
                                    <a href="/student?id={{encode($member['student_id'], 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>
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
                <td class="text-right">
                  {{ $row->class_schedule_num }} 节
                  <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modal-{{$loop->iteration}}-2">查看列表</button>
                  <div class="modal fade" id="modal-{{$loop->iteration}}-2" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h6 class="modal-title">{{ $row->class_name }}</h6>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <ul class="list-group list-group-flush list my--3">
                            @if(count($schedules[$loop->iteration-1])==0)
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
                            @foreach ($schedules[$loop->iteration-1] as $schedule)
                              <li class="list-group-item px-0">
                                <div class="row align-items-center">
                                  <div class="col ml--2">
                                    <h4 class="mb-0">
                                      <a href="#">{{ $schedule['schedule_date'] }} {{ dateToDay($schedule['schedule_date']) }} {{ date('H:i', strtotime($schedule['schedule_start'])) }} - {{ date('H:i', strtotime($schedule['schedule_end'])) }}</a>
                                    </h4>
                                  </div>
                                  <div class="col-auto">
                                    <a href="/schedule?id={{encode($schedule['schedule_id'],'schedule_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>
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
                <td class="text-right">{{ $row->class_attended_num }} 节</td>
                <td><a href="/user?id={{encode($row->user_id,'user_id')}}">{{ $row->user_name }}</a> [ {{ $row->position_name }} ]</td>
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
  linkActive('educationClass');
</script>
@endsection
