@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">学生课时</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">学生课时</li>
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
        <div class="table-responsive freeze-table-5">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:100px;'>学生</th>
                <th style='width:90px;'>校区</th>
                <th style='width:60px;'>年级</th>
                <th style='width:140px;'>课程</th>
                <th style='width:330px;'></th>
                <th style='width:100px;'>剩余</th>
                <th style='width:100px;'>已消耗</th>
                <th style='width:130px;'>课时使用班级</th>
                <th style='width:130px;'>共计排课数量</th>
                <th style='width:145px;'>班主任</th>
                <th style='width:145px;'>课程顾问</th>
              </tr>
            </thead>
            <tbody>
              @if(count($datas)==0)
              <tr class="text-center"><td colspan="13">当前没有记录</td></tr>
              @endif
              @foreach ($datas as $data)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($data['student_id'], 'student_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>
                  {{ $data['student_name'] }}&nbsp;
                  @if($data['student_gender']=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td>{{ $data['department_name'] }}</td>
                <td>{{ $data['grade_name'] }}</td>
                <td><strong>{{ $data['course_name'] }}</strong></td>
                <td>
                  <a href="/student?id={{encode($data['student_id'], 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">详情</button></a>
                  <a href="/operation/student/joinClass?student_id={{encode($data['student_id'], 'student_id')}}&course_id={{encode($data['course_id'], 'course_id')}}"><button type="button" class="btn btn-warning btn-sm">加入班级</button></a>
                  <a href="/operation/student/schedule/create?id={{encode($data['student_id'], 'student_id')}}"><button type="button" class="btn btn-warning btn-sm">一对一排课</button></a>
                  <a href="/operation/hour/edit?student_id={{encode($data['student_id'], 'student_id')}}&course_id={{encode($data['course_id'], 'course_id')}}"><button type="button" class="btn btn-outline-primary btn-sm">修改课时</button></a>
                  <a href="/operation/hour/refund/create?student_id={{encode($data['student_id'], 'student_id')}}&course_id={{encode($data['course_id'], 'course_id')}}"><button type="button" class="btn btn-outline-danger btn-sm">退费</button></a>
                </td>
                <td>{{ $data['hour_remain'] }} 课时</td>
                <td>{{ $data['hour_used'] }} 课时</td>
                <td>
                  {{ count($data['schedule_classes']) }}个班级
                  <div class="dropdown">
                    <a class="btn btn-sm btn-outline-primary" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">查看列表</a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      @if(count($data['schedule_classes'])==0)
                        <a class="dropdown-item" href="#">无</a>
                      @endif
                      @foreach ($data['schedule_classes'] as $schedule_class)
                        <a class="dropdown-item" href="#">{{ $schedule_class['class_name'] }} (已排{{ $schedule_class['class_schedule_num'] }}节课)</a>
                      @endforeach
                    </div>
                  </div>
                </td>
                <td>{{ $data['schedule_count'] }} 节课</td>
                @if($data['class_adviser_name']=="")
                  <td><span style="color:red;">无</span></td>
                @else
                  <td>{{ $data['class_adviser_name'] }} ({{ $data['class_adviser_position_name'] }})</td>
                @endif
                @if($data['consultant_name']=="")
                  <td><span style="color:red;">无</span></td>
                @else
                  <td>{{ $data['consultant_name'] }} ({{ $data['consultant_position_name'] }})</td>
                @endif
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
  linkActive('operationHour');
</script>
@endsection
