@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教学中心</li>
    <li class="breadcrumb-item active">本校学生</li>
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
                <input class="form-control" type="text" name="filter1" placeholder=" 客户姓名..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
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
                <th style='width:139px;'>学生</th>
                <th style='width:90px;'>校区</th>
                <th style='width:60px;'>年级</th>
                <th style='width:60px;'>性别</th>
                <th style='width:130px;'>监护人</th>
                <th style='width:110px;'>电话</th>
                <th style='width:80px;'>优先级</th>
                <th style='width:100px;'>上次跟进</th>
                <th style='width:70px;'>状态</th>
                <th style='width:145px;'>课程顾问</th>
                <th style='width:145px;'>班主任</th>
                <th style='width:188px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="12">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>{{ $row->student_name }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->student_gender }}</td>
                <td>{{ $row->student_guardian_relationship }}：{{ $row->student_guardian }}</td>
                <td>{{ $row->student_phone }}</td>
                @if($row->student_follow_level==1)
                  <td><span style="color:#8B4513;">低</span></td>
                @elseif($row->student_follow_level==2)
                  <td><span style="color:#FF4500;">中</span></td>
                @elseif($row->student_follow_level==3)
                  <td><span style="color:#FF0000;">高</span></td>
                @endif
                <td>{{ $row->student_last_follow_date }}</td>
                @if($row->student_customer_status==0)
                  <td><span style="color:red;">未签约</span></td>
                @else
                  <td><span style="color:green;">已签约</span></td>
                @endif
                @if($row->consultant_name=="")
                  <td><span style="color:red;">无</span></td>
                @else
                  <td>{{ $row->consultant_name }} ({{ $row->consultant_position_name }})</td>
                @endif
                @if($row->class_adviser_name=="")
                  <td><span style="color:red;">无</span></td>
                @else
                  <td>{{ $row->class_adviser_name }} ({{ $row->class_adviser_position_name }})</td>
                @endif
                <td>
                  <a href='/student/{{$row->student_id}}'><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                  <a href='/operation/follower/edit?student_id={{$row->student_id}}'><button type="button" class="btn btn-warning btn-sm">修改负责人</button></a>
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
  linkActive('educationStudentDepartment');
</script>
@endsection
