@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item active">课程安排</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-1">
        <div class="card-header border-0 p-0 mb-1">
          <form action="" method="get" id="filter" name="filter">
            <div class="row m-2">
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter1" data-toggle="select">
                  <option value=''>全部校区</option>
                  @foreach ($filter_departments as $filter_department)
                    <option value="{{ $filter_department->department_id }}" @if($request->input('filter1')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter2" data-toggle="select">
                  <option value=''>全部学生/班级</option>
                  @foreach ($filter_students as $filter_student)
                    <option value="{{ $filter_student->student_id }}" @if($request->input('filter2')==$filter_student->student_id) selected @endif>学生: {{ $filter_student->student_name }}</option>
                  @endforeach
                  @foreach ($filter_classes as $filter_class)
                    <option value="{{ $filter_class->class_id }}" @if($request->input('filter2')==$filter_class->class_id) selected @endif>班级: {{ $filter_class->class_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter3" data-toggle="select">
                  <option value=''>全部年级</option>
                  @foreach ($filter_grades as $filter_grade)
                    <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter3')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter4" data-toggle="select">
                  <option value=''>全部教师</option>
                  @foreach ($filter_users as $filter_user)
                    <option value="{{ $filter_user->user_id }}" @if($request->input('filter4')==$filter_user->user_id) selected @endif>{{ $filter_user->user_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input class="form-control datepicker" name="filter5" type="text" autocomplete="off" placeholder="全部日期" @if($request->filled('filter5')) value="{{ $request->filter5 }}" @endif>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <div class="row">
                  <div class="col-6">
                    <input type="submit" class="btn btn-primary btn-block" value="查询">
                  </div>
                  <div class="col-6">
                    <a href="?"><button type="button" class="form-control btn btn-outline-primary btn-block" style="white-space:nowrap; overflow:hidden;">重置</button></a>
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card main_card mb-4" style="display:none">
        <div class="card-header table-top">
          <div class="row">
            <div class="col-6">
              <a href="/schedule/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="新建排课">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">新建排课</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:70px;'>校区</th>
                <th style='width:160px;'>学生/班级</th>
                <th style='width:160px;'>课程</th>
                <th style='width:100px;'>教师</th>
                <th style='width:80px;'>科目</th>
                <th style='width:80px;'>年级</th>
                <th style='width:100px;'>日期</th>
                <th style='width:110px;'>时间</th>
                <th style='width:110px;'>地点</th>
                <th style='width:90px;'>考勤状态</th>
                <th style='width:170px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
                <tr class="text-center"><td colspan="12">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="创建时间：{{ $row->schedule_createtime }}。">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->student_name }}{{ $row->class_name }}</td>
                <td>{{ $row->course_name }}</td>
                <td>{{ $row->user_name }}</td>
                <td>{{ $row->subject_name }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->schedule_date }}</td>
                <td>{{ date('H:i', strtotime($row->schedule_start)) }} - {{ date('H:i', strtotime($row->schedule_end)) }}</td>
                <td>{{ $row->classroom_name }}</td>
                @if($row->schedule_attended==0)
                  <td><span style="color:red;">未考勤</span></td>
                @elseif($row->schedule_checked==0)
                  <td><span style="color:orange;">待审核</span></td>
                @else
                  <td><span style="color:green;">已审核</span></td>
                @endif
                <td>
                  <form action="schedule/{{$row->schedule_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/schedule/{{$row->schedule_id}}'><button type="button" class="btn btn-primary btn-sm">查看</button></a>&nbsp;
                    <a href='/schedule/attend/{{$row->schedule_id}}'><button type="button" class="btn btn-warning btn-sm">考勤</button></a>&nbsp;
                    {{ deleteConfirm($row->schedule_id, ["上课成员：".$row->student_name.$row->class_name.", 教师：".$row->user_name]) }}
                  </form>
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
  linkActive('link-3');
  navbarActive('navbar-3');
  linkActive('schedule');
</script>
@endsection