@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">学生管理</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">学生管理</li>
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
      <button class="btn btn-sm btn-outline-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="批量删除" onclick="batchDeleteConfirm('/operation/student/delete', '确认批量删除所选学生？')">
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
                      <input class="form-control" type="text" name="filter_name" placeholder="学生姓名..." autocomplete="off" @if(isset($filters['filter_name']))) value="{{ $filters['filter_name'] }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter_consultant" data-toggle="select">
                        <option value=''>课程顾问</option>
                        @foreach ($filter_users as $filter_user)
                          <option value="{{ $filter_user->user_id }}" @if($filters['filter_consultant']==$filter_user->user_id) selected @endif>{{$filter_user->department_name}} {{ $filter_user->user_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter_class_adviser" data-toggle="select">
                        <option value=''>班主任</option>
                        @foreach ($filter_users as $filter_user)
                          <option value="{{ $filter_user->user_id }}" @if($filters['filter_class_adviser']==$filter_user->user_id) selected @endif>{{$filter_user->department_name}} {{ $filter_user->user_name }}</option>
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
      <div class="card main_card mb-4" style="display:none">
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
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:110px;'>学生</th>
                <th style='width:90px;'>校区</th>
                <th style='width:60px;'>年级</th>
                <th style='width:145px;'>课程顾问</th>
                <th style='width:145px;'>班主任</th>
                <th style='width:145px;'>学生课时</th>
                <th style='width:320px;'></th>
              </tr>
            </thead>
            <tbody>
              @if(count($students)==0)
              <tr class="text-center"><td colspan="8">当前没有记录</td></tr>
              @endif
              @foreach ($students as $student)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($student['student_id'], 'student_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>
                  {{ $student['student_name'] }}&nbsp;
                  @if($student['student_gender']=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td>{{ $student['department_name'] }}</td>
                <td>{{ $student['grade_name'] }}</td>
                @if($student['consultant_name']=="")
                  <td><span style="color:red;">无</span></td>
                @else
                  <td>{{ $student['consultant_name'] }} ({{ $student['consultant_position_name'] }})</td>
                @endif
                @if($student['class_adviser_name']=="")
                  <td><span style="color:red;">无</span></td>
                @else
                  <td>{{ $student['class_adviser_name'] }} ({{ $student['class_adviser_position_name'] }})</td>
                @endif
                <td>
                  {{ $student['student_hour_num'] }} 课程
                  <button type="button" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#modal-{{$loop->iteration}}-1">查看列表</button>
                  <div class="modal fade" id="modal-{{$loop->iteration}}-1" tabindex="-1" role="dialog" aria-labelledby="modal-default" aria-hidden="true">
                    <div class="modal-dialog modal- modal-dialog-centered modal-" role="document">
                      <div class="modal-content">
                        <div class="modal-header">
                          <h6 class="modal-title">{{ $student['student_name'] }} {{ $student['student_id'] }}</h6>
                          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                          </button>
                        </div>
                        <div class="modal-body">
                          <ul class="list-group list-group-flush list my--3">
                            @if($student['student_hour_num']==0)
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
                            @foreach ($student['student_hours'] as $student_hour)
                            <li class="checklist-entry list-group-item flex-column align-items-start p-3">
                              <div class="checklist-item @if($student_hour['hour_remain']>0) checklist-item-success @else checklist-item-warning @endif ">
                                <div class="checklist-info">
                                  <h5 class="checklist-title mb-0">{{ $student_hour['course_name'] }}</h5>
                                  <small>剩余:{{ $student_hour['hour_remain'] }} 课时</small>
                                </div>
                                <div class="custom-control">
                                  <a href="/operation/hour/edit?student_id={{encode($student['student_id'], 'student_id')}}&course_id={{encode($student_hour['course_id'], 'course_id')}}"><button type="button" class="btn btn-outline-primary btn-sm">修改课时</button></a>
                                  <a href="/operation/hour/refund/create?student_id={{encode($student['student_id'], 'student_id')}}&course_id={{encode($student_hour['course_id'], 'course_id')}}"><button type="button" class="btn btn-outline-danger btn-sm">退费</button></a>
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
                <td>
                  <a href="/student?id={{encode($student['student_id'], 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>
                  <a href="/operation/student/follower/edit?id={{encode($student['student_id'], 'student_id')}}"><button type="button" class="btn btn-warning btn-sm">修改负责人</button></a>
                  <a href="/operation/student/schedule/create?id={{encode($student['student_id'], 'student_id')}}"><button type="button" class="btn btn-warning btn-sm">一对一排课</button></a>
                  <a href="/operation/student/joinClass?student_id={{encode($student['student_id'], 'student_id')}}"><button type="button" class="btn btn-warning btn-sm">加入班级</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/operation/student/delete?id={{encode($student['student_id'], 'student_id')}}', '确认删除学生？')">删除</button>
                </td>
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
  linkActive('operationStudent');
</script>
@endsection
