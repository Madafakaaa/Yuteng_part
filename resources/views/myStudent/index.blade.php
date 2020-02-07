@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item active">我的学生</li>
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
                  <option value=''>全部公立学校</option>
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
      <div class="card main_card mb-4" style="display:none">
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:100px;'>校区</th>
                <th style='width:120px;'>学生</th>
                <th style='width:110px;'>学号</th>
                <th style='width:70px;'>年级</th>
                <th style='width:70px;'>性别</th>
                <th style='width:120px;'>公立学校</th>
                <th style='width:120px;'>监护人</th>
                <th style='width:120px;'>联系电话</th>
                <th style='width:120px;'>负责人</th>
                <th style='width:297px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="11">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="创建时间：{{ $row->student_createtime }}。">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->student_name }}</td>
                <td>{{ $row->student_id }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->student_gender }}</td>
                <td>{{ $row->school_name }}</td>
                <td>{{ $row->student_guardian_relationship }} {{ $row->student_guardian }}</td>
                <td>{{ $row->student_phone }}</td>
                <td>{{ $row->user_name }}</td>
                <td>
                  <form action="student/{{$row->student_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/student/{{$row->student_id}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">查看详情</button></a>
                    <a href='/contract/create?student_id={{$row->student_id}}' target="_blank"><button type="button" class="btn btn-warning btn-sm">续签</button></a>
                    {{ deleteConfirm($row->student_id, ["学生名称：".$row->student_name]) }}
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
  linkActive('myStudent');
</script>
@endsection
