@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">我的课程安排</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">教学中心</li>
              <li class="breadcrumb-item active">我的课程安排</li>
            </ol>
          </nav>
        </div>
        <div class="col-6 text-right">
          <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
            <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            <span class="btn-inner--text">搜索</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="collapse" id="filter">
        <div class="card mb-1">
          <div class="card-header border-0 p-0 mb-1">
            <form action="" method="get" id="filter" name="filter">
              <div class="row m-2">
                <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                  <input class="form-control" type="text" name="filter1" placeholder="校区名称..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
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
      </div>
      <div class="card main_card mb-4" style="display:none">
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:99px;'>校区</th>
                <th style='width:200px;'>学生/班级</th>
                <th style='width:200px;'>课程</th>
                <th style='width:100px;'>教师</th>
                <th style='width:70px;'>科目</th>
                <th style='width:70px;'>年级</th>
                <th style='width:100px;'>日期</th>
                <th style='width:110px;'>时间</th>
                <th style='width:110px;'>地点</th>
                <th style='width:188px;'>操作管理</th>
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
                <td>@if($row->schedule_participant_type==0) 学生 @else 班级 @endif - {{ $row->student_name }}{{ $row->class_name }}</td>
                <td>{{ $row->course_name }}</td>
                <td>{{ $row->user_name }}</td>
                <td>{{ $row->subject_name }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->schedule_date }}</td>
                <td>{{ date('H:i', strtotime($row->schedule_start)) }} - {{ date('H:i', strtotime($row->schedule_end)) }}</td>
                <td>{{ $row->classroom_name }}</td>
                <td>
                  <form action="schedule/{{$row->schedule_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/schedule/{{$row->schedule_id}}'><button type="button" class="btn btn-primary btn-sm">安排详情</button></a>
                    <a href='/education/schedule/attend/{{$row->schedule_id}}'><button type="button" class="btn btn-warning btn-sm">考勤</button></a>&nbsp;
                    {{ deleteConfirm($row->schedule_id, ["上课成员：".$row->student_name.", 教师：".$row->user_name]) }}
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
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationScheduleMy');
</script>
@endsection
