@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">学生课时 @if (Session::get('user_access_self')==1) （个人） @endif</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">学生课时</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="?@foreach($filters as $key => $value) @if($key!='filter_class_adviser') {{$key}}={{$value}}& @endif @endforeach&filter_class_adviser={{Session::get('user_id')}}" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加客户">
        <span class="btn-inner--icon"><i class="fas fa-user-circle"></i></span>
        <span class="btn-inner--text">我的学生</span>
      </a>
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
              <div class="col-3">
                <select class="form-control form-control-sm" name="filter_class_adviser" data-toggle="select" onChange="form_submit('filterForm')">
                  <option value=''>搜索班主任...</option>
                  @foreach ($filter_users as $filter_user)
                    <option value="{{ $filter_user->user_id }}" @if($filters['filter_class_adviser']==$filter_user->user_id) selected @endif>[ {{$filter_user->department_name}} ] {{ $filter_user->user_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-3">
                <input type="number" class="form-control form-control-sm" @if($request->filled('filter_hour_min')) value="{{$request->input('filter_hour_min')}}" @endif name="filter_hour_min" placeholder="课时数大于等于..." min="0"  onChange="form_submit('filterForm')">
              </div>
              <div class="col-3">
                <input type="number" class="form-control form-control-sm" @if($request->filled('filter_hour_max')) value="{{$request->input('filter_hour_max')}}" @endif name="filter_hour_max" placeholder="课时数小于等于..." min="0"  onChange="form_submit('filterForm')">
              </div>
            </div>
          </form>
        </div>
        <hr>
        <div class="table-responsive pb-4">
          <table class="table table-flush datatable-basic">
            <thead class="thead-light">
              <tr>
                <th style='width:120px;'>学生</th>
                <th style='width:90px;'>校区</th>
                <th style='width:60px;'>年级</th>
                <th style='width:180px;'>课程</th>
                <th style='width:110px;' class="text-right">剩余</th>
                <th style='width:110px;' class="text-right">已消耗</th>
                <th style='width:145px;'>班主任</th>
                <th style='width:200px;'>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($datas as $data)
              <tr>
                <td>
                  <a href="/student?id={{encode($data['student_id'], 'student_id')}}">{{ $data['student_name'] }}</a>&nbsp;
                  @if($data['student_gender']=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td>{{ $data['department_name'] }}</td>
                <td>{{ $data['grade_name'] }}</td>
                <td><strong>{{ $data['course_name'] }}</strong></td>
                @if($data['hour_remain']<5)
                  <td class="text-right"><span class="text-danger">{{ $data['hour_remain'] }} 课时</span></td>
                @else
                  <td class="text-right">{{ $data['hour_remain'] }} 课时</td>
                @endif
                <td class="text-right">{{ $data['hour_used'] }} 课时</td>
                @if($data['class_adviser_name']=="")
                  <td><span style="color:red;">无</span></td>
                @else
                  <td><a href="/user?id={{encode($data['class_adviser_id'],'user_id')}}">{{ $data['class_adviser_name'] }}</a> [ {{ $data['class_adviser_position_name'] }} ]</td>
                @endif
                <td>
                  <a href="/operation/hour/edit?student_id={{encode($data['student_id'], 'student_id')}}&course_id={{encode($data['course_id'], 'course_id')}}"><button type="button" class="btn btn-outline-primary btn-sm">修改课时</button></a>
                  <a href="/operation/hour/refund/create?student_id={{encode($data['student_id'], 'student_id')}}&course_id={{encode($data['course_id'], 'course_id')}}"><button type="button" class="btn btn-outline-danger btn-sm">退费</button></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
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
  linkActive('operationHour');
</script>
@endsection
