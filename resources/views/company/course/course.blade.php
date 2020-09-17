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
      <button class="btn btn-sm btn-outline-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="批量删除" onclick="batchDeleteConfirm('/company/course/delete', '确认批量删除所选课程？')">
        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
        <span class="btn-inner--text">批量删除</span>
      </button>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header p-1" style="border-bottom:0px;">
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
        </div>
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:50px;'>序号</th>
                <th style='width:120px;'>课程名称</th>
                <th style='width:120px;'>课程类型</th>
                <th style='width:100px;'>开课校区</th>
                <th style='width:100px;'>课程季度</th>
                <th style='width:100px;'>课程年级</th>
                <th style='width:100px;'>课程科目</th>
                <th style='width:120px;'>课时单价</th>
                <th style='width:130px;'>课程时长</th>
                <th style='width:160px;'>操作管理</th>
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
                <td><img src="{{ asset(_ASSETS_.$row->course_type_icon_path) }}" /> {{ $row->course_type }}</td>
                <td>@if($row->course_department==0) 全校区 @else{{ $row->department_name }}@endif</td>
                <td>{{ $row->course_quarter }}</td>
                <td>@if($row->course_grade==0) 全年级 @else{{ $row->grade_name }}@endif</td>
                <td>@if($row->course_subject==0) 全科目 @else{{ $row->subject_name }}@endif</td>
                <td>{{ $row->course_unit_price }}元</td>
                <td>{{ $row->course_time }}分钟</td>
                <td>
                  <a href='/company/course/edit?id={{encode($row->course_id, 'course_id')}}'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/company/course/delete?id={{encode($row->course_id, 'course_id')}}', '确认删除课程？')">删除</button>
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('companyCourse');
</script>
@endsection
