@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">课程设置</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">公司管理</li>
    <li class="breadcrumb-item active">课程设置</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="/company/course/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加课程">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">添加课程</span>
      </a>
      <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
        <span class="btn-inner--text">搜索</span>
      </a>
      <button class="btn btn-sm btn-outline-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="批量删除" onclick="batchDeleteConfirm('/company/course/delete', '确认批量删除所选课程？')">
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
                      <input class="form-control" type="text" name="filter1" placeholder="课程名称..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter2" data-toggle="select">
                        <option value='0'>全部校区</option>
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
      <div class="card mb-4">
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:120px;'>课程名称</th>
                <th style='width:120px;'></th>
                <th style='width:130px;'>课程类型</th>
                <th style='width:100px;'>开课校区</th>
                <th style='width:100px;'>课程季度</th>
                <th style='width:100px;'>课程年级</th>
                <th style='width:100px;'>课程科目</th>
                <th style='width:152px;'>课时单价</th>
                <th style='width:130px;'>课程时长</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="10">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($row->course_id, 'course_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->course_name }}</td>
                <td>
                  <a href='/company/course/edit?id={{encode($row->course_id, 'course_id')}}'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/company/course/delete?id={{encode($row->course_id, 'course_id')}}', '确认删除课程？')">删除</button>
                </td>
                <td><img src="{{ asset(_ASSETS_.$row->course_type_icon_path) }}" /> {{ $row->course_type }}</td>
                <td>@if($row->course_department==0) 全校区 @else{{ $row->department_name }}@endif</td>
                <td>{{ $row->course_quarter }}</td>
                <td>@if($row->course_grade==0) 全年级 @else{{ $row->grade_name }}@endif</td>
                <td>@if($row->course_subject==0) 全科目 @else{{ $row->subject_name }}@endif</td>
                <td>{{ $row->course_unit_price }}元</td>
                <td>{{ $row->course_time }}分钟</td>
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('companyCourse');
</script>
@endsection
