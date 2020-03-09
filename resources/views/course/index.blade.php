@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">课程管理</li>
    <li class="breadcrumb-item active">课程设置</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-1">
        <div class="card-header border-0 p-0 m-1">
          <form action="" method="get" id="filter" name="filter">
            <div class="row m-2">
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input class="form-control" type="text" name="filter1" placeholder="课程名称..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter2" data-toggle="select">
                  <option value=''>全部校区</option>
                  <option value='0'>全校区</option>
                  @foreach ($filter_departments as $filter_department)
                    <option value="{{ $filter_department->department_id }}" @if($request->input('filter2')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter3" data-toggle="select">
                  <option value=''>全部季度</option>
                  <option value='全年' @if($request->input('filter3')=="全年") selected @endif>全年</option>
                  <option value='春季' @if($request->input('filter3')=="春季") selected @endif>春季</option>
                  <option value='暑假' @if($request->input('filter3')=="暑假") selected @endif>暑假</option>
                  <option value='秋季' @if($request->input('filter3')=="秋季") selected @endif>秋季</option>
                  <option value='寒假' @if($request->input('filter3')=="寒假") selected @endif>寒假</option>
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter4" data-toggle="select">
                  <option value=''>全部年级</option>
                  @foreach ($filter_grades as $filter_grade)
                    <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter4')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter5" data-toggle="select">
                  <option value=''>全部科目</option>
                  @foreach ($filter_subjects as $filter_subject)
                    <option value="{{ $filter_subject->subject_id }}" @if($request->input('filter5')==$filter_subject->subject_id) selected @endif>{{ $filter_subject->subject_name }}</option>
                  @endforeach
                </select>
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
            <div class="col-6 text-left">
              <a href="/course/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加课程">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加课程</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:80px;'>序号</th>
                <th style='width:220px;'>课程名称</th>
                <th style='width:130px;'>课程类型</th>
                <th style='width:100px;'>开课校区</th>
                <th style='width:100px;'>课程季度</th>
                <th style='width:100px;'>课程年级</th>
                <th style='width:100px;'>课程科目</th>
                <th style='width:152px;'>课时单价</th>
                <th style='width:130px;'>课程时长</th>
                <th style='width:188px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="10">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="备注：{{ $row->course_remark }}， 创建日期：{{ $row->course_createtime }}">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->course_name }}</td>
                <td><img src="{{ asset(_ASSETS_.$row->course_type_icon_path) }}" /> {{ $row->course_type }}</td>
                <td>@if($row->course_department==0) 全校区 @else{{ $row->department_name }}@endif</td>
                <td>{{ $row->course_quarter }}</td>
                <td>@if($row->course_grade==0) 全年级 @else{{ $row->grade_name }}@endif</td>
                <td>@if($row->course_subject==0) 全科目 @else{{ $row->subject_name }}@endif</td>
                <td>{{ $row->course_unit_price }}元</td>
                <td>{{ $row->course_time }}分钟</td>
                <td class="p-2">
                  <form action="course/{{$row->course_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/course/{{$row->course_id}}/edit'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                    {{ deleteConfirm($row->course_id, ["课程名称：".$row->course_name]) }}
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('course');
</script>
@endsection
