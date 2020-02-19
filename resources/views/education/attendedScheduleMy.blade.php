@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教学中心</li>
    <li class="breadcrumb-item active">我的上课记录</li>
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
                  <option value=''>全部学生</option>
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
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:114px;'>学生</th>
                <th style='width:110px;'>班级</th>
                <th style='width:120px;'>教师</th>
                <th style='width:65px;'>年级</th>
                <th style='width:65px;'>科目</th>
                <th style='width:65px;'>考勤</th>
                <th style='width:180px;'>扣除课时</th>
                <th style='width:110px;'>日期</th>
                <th style='width:110px;'>时间</th>
                <th style='width:120px;'>复核人</th>
                <th style='width:188px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
                <tr class="text-center"><td colspan="12">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->student_name }}</td>
                <td>{{ $row->class_name }}</td>
                <td>{{ $row->teacher_name }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->subject_name }}</td>
                @if($row->participant_attend_status==1)
                  <td><span style="color:green;">正常</span></td>
                @elseif($row->participant_attend_status==2)
                  <td><span style="color:yellow;">请假</span></td>
                @else
                  <td><span style="color:red;">旷课</span></td>
                @endif
                <td>{{ $row->participant_amount }}课时：{{ $row->course_name }}</td>
                <td>{{ $row->schedule_date }}</td>
                <td>{{ date('H:i', strtotime($row->schedule_start)) }} - {{ date('H:i', strtotime($row->schedule_end)) }}</td>
                @if($row->participant_checked==1)
                  <td>{{ $row->checked_user_name }}</td>
                @else
                  <td><span style="color:red;">待复核</span></td>
                @endif
                <td>
                  <a href='/schedule/{{$row->schedule_id}}'><button type="button" class="btn btn-primary btn-sm">上课详情</button></a>&nbsp;
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
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationAttendedScheduleMy');
</script>
@endsection
