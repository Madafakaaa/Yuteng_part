@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">财务中心</li>
    <li class="breadcrumb-item active">学生购课</li>
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
                  @foreach ($filter_students as $filter_student)
                    <option value="{{ $filter_student->student_id }}" @if($request->input('filter2')==$filter_student->student_id) selected @endif>{{ $filter_student->student_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter3" data-toggle="select">
                  <option value=''>全部课程</option>
                  @foreach ($filter_courses as $filter_course)
                    <option value="{{ $filter_course->course_id }}" @if($request->input('filter3')==$filter_course->course_id) selected @endif>{{ $filter_course->course_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-3">
                <select class="form-control" name="filter4" data-toggle="select">
                  <option value=''>全部年级</option>
                  @foreach ($filter_grades as $filter_grade)
                    <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter4')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <div class="row">
                  <div class="col-6">
                    <input type="submit" class="btn btn-primary btn-block" value="查询">
                  </div>
                  <div class="col-6">
                    <a href="?"><button type="button" class="btn btn-outline-primary btn-block">重置</button></a>
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
              <h2 class="mb-0">购课列表</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/payment/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加购课">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加购课</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:6%;' class="p-2">序号</th>
                <th style='width:8%;' class="p-2">校区</th>
                <th style='width:10%;' class="p-2">学生</th>
                <th style='width:8%;' class="p-2">年级</th>
                <th style='width:10%;' class="p-2">课程</th>
                <th style='width:8%;' class="p-2">单价</th>
                <th style='width:8%;' class="p-2">课时</th>
                <th style='width:8%;' class="p-2">金额</th>
                <th style='width:8%;' class="p-2">时间</th>
                <th style='width:8%;' class="p-2">上报用户</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr><td colspan="11">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="创建时间：{{ $row->payment_createtime }}。">
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $row->department_name }}</td>
                <td class="p-2">{{ $row->student_name }}</td>
                <td class="p-2">{{ $row->grade_name }}</td>
                <td class="p-2">{{ $row->course_name }}</td>
                <td class="p-2">{{ $row->payment_unit_price }}元</td>
                <td class="p-2">{{ $row->payment_amount }}课时</td>
                <td class="p-2">{{ $row->payment_price }}元</td>
                <td class="p-2">{{ $row->payment_date }}</td>
                <td class="p-2">{{ $row->user_name }}</td>
                <td class="p-2">
                  <form action="payment/{{$row->payment_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    {{ deleteConfirm($row->payment_id, ["购课学生：".$row->student_name."，购买课程：".$row->course_name."，<br>
                                                         购课数量：".$row->payment_amount."课时，金额：".$row->payment_price."元。"]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ pageLink($currentPage, $totalPage, $request) }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-4');
  navbarActive('navbar-4');
  linkActive('payment');
</script>
@endsection
