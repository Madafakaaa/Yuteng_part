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
          <h6 class="h2 text-white d-inline-block mb-0">班级管理</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">教学中心</li>
              <li class="breadcrumb-item active">班级管理</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
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
                      <input class="form-control" type="text" name="filter1" placeholder="班级名称..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter2" data-toggle="select">
                        <option value=''>全部校区</option>
                        @foreach ($filter_departments as $filter_department)
                          <option value="{{ $filter_department->department_id }}" @if($request->input('filter2')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter3" data-toggle="select">
                        <option value=''>全部年级</option>
                        @foreach ($filter_grades as $filter_grade)
                          <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter3')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter4" data-toggle="select">
                        <option value=''>全部科目</option>
                        @foreach ($filter_subjects as $filter_subject)
                          <option value="{{ $filter_subject->subject_id }}" @if($request->input('filter4')==$filter_subject->subject_id) selected @endif>{{ $filter_subject->subject_name }}</option>
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
        <div class="table-responsive freeze-table-3">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:80px;'>序号</th>
                <th style='width:150px;'>班级</th>
                <th style='width:210px;'></th>
                <th style='width:100px;'>校区</th>
                <th style='width:120px;'>班号</th>
                <th style='width:90px;'>年级</th>
                <th style='width:90px;'>科目</th>
                <th style='width:150px;'>班级人数</th>
                <th style='width:150px;'>已排课</th>
                <th style='width:80px;'>已上课</th>
                <th style='width:210px;'>负责教师</th>
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
                <td>{{ $row->class_name }}</td>
                <td>
                  <a href="/class?id={{encode($row->class_id, 'class_id')}}"><button type="button" class="btn btn-primary btn-sm">班级详情</button></a>
                </td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->class_id }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>@if($row->class_subject==0) 全科目 @else{{ $row->subject_name }}@endif</td>
                <td>
                  @if($row->class_current_num==$row->class_max_num)
                    <span style="color:green;">{{ $row->class_current_num }} / {{ $row->class_max_num }} 人</span>
                  @else
                    <span style="color:red;">{{ $row->class_current_num }} / {{ $row->class_max_num }} 人</span>
                  @endif
                  <div class="dropdown">
                    <a class="btn btn-sm btn-outline-primary" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">查看列表</a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      @if(count($members[$loop->iteration-1])==0)
                        <a class="dropdown-item" href="#">无</a>
                      @endif
                      @foreach ($members[$loop->iteration-1] as $member)
                        <a class="dropdown-item" href="#">{{ $member['student_name'] }}</a>
                      @endforeach
                    </div>
                  </div>
                </td>
                <td>
                  {{ $row->class_schedule_num }} 节
                  <div class="dropdown">
                    <a class="btn btn-sm btn-outline-primary" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">查看列表</a>
                    <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                      @if(count($schedules[$loop->iteration-1])==0)
                        <a class="dropdown-item" href="#">无</a>
                      @endif
                      @foreach ($schedules[$loop->iteration-1] as $schedule)
                        <a class="dropdown-item" href="#">{{ $schedule['schedule_date'] }} {{ dateToDay($schedule['schedule_date']) }} {{ date('H:i', strtotime($schedule['schedule_start'])) }} - {{ date('H:i', strtotime($schedule['schedule_end'])) }}</a>
                      @endforeach
                    </div>
                  </div>
                </td>
                <td>{{ $row->class_attended_num }} 节</td>
                <td>{{ $row->user_name }} ({{ $row->position_name }})</td>
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
