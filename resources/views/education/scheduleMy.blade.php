@extends('main')

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
      <div class="collapse @if($filter_status==1) show @endif" id="filter">
        <div class="card mb-4">
          <div class="card-body border-1 p-0 my-1">
            <form action="" method="get">
              <div class="row m-2">
                <div class="col-lg-8 col-md-8 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <input class="form-control" type="text" name="filter1" placeholder="学生姓名..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <input class="form-control" type="text" name="filter2" placeholder="班级名称..." autocomplete="off" @if($request->filled('filter2')) value="{{ $request->filter2 }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter3" data-toggle="select">
                        <option value=''>全部校区</option>
                        @foreach ($filter_departments as $filter_department)
                          <option value="{{ $filter_department->department_id }}" @if($request->input('filter3')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter4" data-toggle="select">
                        <option value=''>全部年级</option>
                        @foreach ($filter_grades as $filter_grade)
                          <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter4')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter5" data-toggle="select">
                        <option value=''>全部科目</option>
                        @foreach ($filter_subjects as $filter_subject)
                          <option value="{{ $filter_subject->subject_id }}" @if($request->input('filter5')==$filter_subject->subject_id) selected @endif>{{ $filter_subject->subject_name }}</option>
                        @endforeach
                      </select>
	                </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
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
