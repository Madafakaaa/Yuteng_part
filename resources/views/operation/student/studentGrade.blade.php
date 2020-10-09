@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">年级升降</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item"><a href="/operation/student">学生管理</a></li>
    <li class="breadcrumb-item active">年级升降</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header p-1" style="border-bottom:0px;">
          <div class="row m-2">
            <div class="col-12">
              <small class="text-muted font-weight-bold px-2">校区：</small>
              <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach">
                <button type="button" @if(!isset($filters['filter_department'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
              </a>
              @foreach($filter_departments as $filter_department)
                <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach &filter_department={{$filter_department->department_id}}"><button type="button" @if($filters['filter_department']==$filter_department->department_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_department->department_name}}</button></a>
              @endforeach
            </div>
          </div>
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
        </div>
        <div class="table-responsive freeze-table-4">
          <form action="/operation/student/grade/store" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submitButton1')">
            @csrf
            <table class="table align-items-center table-hover table-bordered text-left">
              <thead class="thead-light">
                <tr>
                  <th style='width:50px;'>
                    <button type="button" class="btn btn-primary btn-block btn-sm" style="white-space:nowrap; overflow:hidden;" onclick="checkAll('student_id');">全选</button>
                  </th>
                  <th style='width:100px;'>学生</th>
                  <th style='width:90px;'>学号</th>
                  <th style='width:100px;'>校区</th>
                  <th style='width:100px;'>年级</th>
                </tr>
              </thead>
              <tbody>
                @if(count($students)==0)
                <tr class="text-center"><td colspan="5">当前没有记录</td></tr>
                @endif
                @foreach ($students as $student)
                <tr>
                  <td>
                    <div class="custom-control custom-checkbox">
                      <input type="checkbox" class="custom-control-input student_id" id="checkbox_{{ $loop->iteration }}" name="student_id[]" value='{{encode($student['student_id'], 'student_id')}}'>
                      <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                    </div>
                  </td>
                  <td>
                    <a href="/student?id={{encode($student['student_id'], 'student_id')}}">{{ $student['student_name'] }}</a>&nbsp;
                    @if($student['student_gender']=="男")
                      <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                    @else
                      <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                    @endif
                  </td>
                  <td>{{ $student['student_id'] }}</td>
                  <td>{{ $student['department_name'] }}</td>
                  <td>{{ $student['grade_name'] }}</td>
                </tr>
                @endforeach
                <tr class="text-center">
                  <td colspan="4">
                    <select class="form-control" name="upgrade_type" data-toggle="select" required>
                      <option value="1">升一年级</option>
                      <option value="0">降一年级</option>
                    </select>
                  </td>
                  <td>
                    <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="提交">
                  </td>
                </tr>
              </tbody>
            </table>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationStudent');
</script>
@endsection
