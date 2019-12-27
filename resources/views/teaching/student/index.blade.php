@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item active">学生管理</li>
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
                <input class="form-control" type="text" name="filter1" placeholder=" 学生名称..." autocomplete="off" autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter2" data-toggle="select">
                  <option value=''>全部校区</option>
                  @foreach ($filter_departments as $filter_department)
                    <option value="{{ $filter_department->department_id }}" @if($request->input('filter2')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
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
                  <option value=''>全部学校</option>
                  @foreach ($filter_schools as $filter_school)
                    <option value="{{ $filter_school->school_id }}" @if($request->input('filter4')==$filter_school->school_id) selected @endif>{{ $filter_school->school_name }}</option>
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
      <div class="card main_card" style="display:none">
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <div class="col-6">
              <h2 class="mb-0">学生列表</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/student/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加学生">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加学生</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:10%;'>序号</th>
                <th style='width:10%;'>校区</th>
                <th style='width:10%;'>学生</th>
                <th style='width:10%;'>学号</th>
                <th style='width:10%;'>年级</th>
                <th style='width:10%;'>性别</th>
                <th style='width:10%;'>学校</th>
                <th style='width:10%;'>生日</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr><td colspan="9">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="创建时间：{{ $row->student_createtime }}。">
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $row->department_name }}</td>
                <td class="p-2">{{ $row->student_name }}</td>
                <td class="p-2">{{ $row->student_id }}</td>
                <td class="p-2">{{ $row->grade_name }}</td>
                <td class="p-2">{{ $row->student_gender }}</td>
                <td class="p-2">{{ $row->school_name }}</td>
                <td class="p-2">{{ $row->student_birthday }}</td>
                <td class="p-2">
                  <form action="student/{{$row->student_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/student/{{$row->student_id}}'><button type="button" class="btn btn-primary btn-sm">查看详情</button></a>
                    {{ deleteConfirm($row->student_id, ["学生名称：".$row->student_name]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ pageLink($currentPage, $totalPage, $request, $totalNum) }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-3');
  navbarActive('navbar-3');
  linkActive('student');
</script>
@endsection
