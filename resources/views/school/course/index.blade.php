@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">课程管理</li>
    <li class="breadcrumb-item active">课程设置</li>
@endsection

@section('content')
<div class="container-fluid mt--6">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-3">
        <div class="card-header border-0 p-0 m-2">
          <form action="" method="get" id="filter" name="filter">
            <div class="row m-2">
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input class="form-control" type="text" name="filter1" placeholder="课程名称..." autocomplete="off">
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter2" data-toggle="select">
                  <option value=''>请选择校区...</option>
                  <option value='0'>全校区</option>
                  @foreach ($filter_departments as $filter_department)
                    <option value="{{ $filter_department->department_id }}">{{ $filter_department->department_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter3" data-toggle="select">
                  <option value=''>请选择季度...</option>
                  <option value='全年'>全年</option>
                  <option value='春季'>春季</option>
                  <option value='暑假'>暑假</option>
                  <option value='秋季'>秋季</option>
                  <option value='寒假'>寒假</option>
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter4" data-toggle="select">
                  <option value=''>请选择年级...</option>
                  <option value='0'>全年级</option>
                  @foreach ($filter_grades as $filter_grade)
                    <option value="{{ $filter_grade->grade_id }}">{{ $filter_grade->grade_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter5" data-toggle="select">
                  <option value=''>请选择科目...</option>
                  <option value='0'>全科目</option>
                  @foreach ($filter_subjects as $filter_subject)
                    <option value="{{ $filter_subject->subject_id }}">{{ $filter_subject->subject_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input type="submit" class="btn btn-primary btn-block" value="查询">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card main_card" style="display:none">
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <div class="col-6">
              <h2 class="mb-0">课程列表</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/course/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加课程">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加课程</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:5%;'>序号</th>
                <th style='width:10%;'>课程名称</th>
                <th style='width:10%;'>开课校区</th>
                <th style='width:10%;'>课程季度</th>
                <th style='width:10%;'>课程年级</th>
                <th style='width:10%;'>课程科目</th>
                <th style='width:10%;'>课时单价</th>
                <th style='width:10%;'>课时时长</th>
                <th style='width:10%;'>有效状态</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr><td colspan="10">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="有效日期：{{ $row->course_start }} 至 {{ $row->course_end }}。备注：{{ $row->course_remark }}">
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $row->course_name }}</td>
                <td class="p-2">@if($row->course_department==0) 全校区 @else{{ $row->department_name }}@endif</td>
                <td class="p-2">{{ $row->course_quarter }}</td>
                <td class="p-2">@if($row->course_grade==0) 全年级 @else{{ $row->grade_name }}@endif</td>
                <td class="p-2">@if($row->course_subject==0) 全科目 @else{{ $row->subject_name }}@endif</td>
                <td class="p-2">{{ $row->course_unit_price }}元</td>
                <td class="p-2">{{ $row->course_time }}分钟</td>
                <td class="p-2">
                  @if($row->course_start<=date('Y-m-d')&&$row->course_end>=date('Y-m-d'))
                    <span style="color:green">有效</span>
                  @elseif($row->course_start>date('Y-m-d'))
                    <span style="color:red">未生效</span>
                  @else
                    <span style="color:red">已失效</span>
                  @endif
                </td>
                <td class="p-2">
                  <form action="course/{{$row->course_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/course/{{$row->course_id}}'><button type="button" class="btn btn-primary btn-sm">查看详情</button></a>
                    {{ deleteConfirm($row->course_id, ["课程名称：".$row->course_name]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ pageLink($currentPage, $totalPage) }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-3');
  navbarActive('navbar-1-3');
  linkActive('course');
</script>
@endsection
