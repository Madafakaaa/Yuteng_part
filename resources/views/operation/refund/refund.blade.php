@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">退费管理</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">退费管理</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="?">
        <button class="btn btn-sm btn-outline-primary btn-round btn-icon">
          <span class="btn-inner--icon"><i class="fas fa-redo"></i></span>
          <span class="btn-inner--text">重置搜索</span>
        </button>
      </a>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header p-1" style="border-bottom:0px;">
          <form action="" method="get" id="filterForm">
            <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
            <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
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
            <hr>
            <div class="row m-2">
              <div class="col-3">
                <select class="form-control form-control-sm" name="filter_student" data-toggle="select" onChange="form_submit('filterForm')">
                  <option value=''>搜索学生...</option>
                  @foreach ($filter_students as $filter_student)
                    <option value="{{ $filter_student->student_id }}" @if($filters['filter_student']==$filter_student->student_id) selected @endif>[ {{ $filter_student->department_name }} ] {{ $filter_student->student_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:100px;'>学生</th>
                <th style='width:90px;'>校区</th>
                <th style='width:150px;'>课程</th>
                <th style='width:100px;' class="text-right">退费课时</th>
                <th style='width:120px;' class="text-right">退款金额</th>
                <th style='width:110px;'>日期</th>
                <th style='width:140px;'>退费用户</th>
                <th style='width:200px;'>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="9">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>
                  <a href="/student?id={{encode($row->student_id, 'student_id')}}">{{ $row->student_name }}</a>&nbsp;
                  @if($row->student_gender=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->course_name }}</td>
                <td class="text-right">{{ $row->refund_remain }} 课时</td>
                <td class="text-right"><strong>{{ number_format($row->refund_amount, 2) }} 元</strong></td>
                <td>{{ $row->refund_date }}</td>
                <td><a href="/user?id={{encode($row->createuser_id,'user_id')}}">{{ $row->createuser_name }}</a> [ {{ $row->createuser_position_name }} ]</td>
                <td>
                  <a href="/student?id={{encode($row->student_id, 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/operation/refund/delete?id={{encode($row->refund_id, 'refund_id')}}', '确认删除退费记录？')">删除</button>
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationRefund');
</script>
@endsection
