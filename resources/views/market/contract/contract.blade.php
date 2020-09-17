@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">签约管理</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">招生中心</li>
    <li class="breadcrumb-item active">签约管理</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
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
              <div class="col-3">
                <select class="form-control form-control-sm" name="filter_user" data-toggle="select" onChange="form_submit('filterForm')">
                  <option value=''>搜索签约用户...</option>
                  @foreach ($filter_users as $filter_user)
                    <option value="{{ $filter_user->user_id }}" @if($filters['filter_user']==$filter_user->user_id) selected @endif>[ {{$filter_user->department_name}} ] {{ $filter_user->user_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-3">
                <input class="form-control form-control-sm datepicker" name="filter_date" placeholder="搜索签约日期..." autocomplete="off" type="text" @if(isset($filters['filter_date']))) value="{{ $filters['filter_date'] }}" @endif onChange="form_submit('filterForm')">
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive freeze-table-7">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:50px;'>序号</th>
                <th style='width:100px;'>校区</th>
                <th style='width:70px;'>日期</th>
                <th style='width:80px;'>签约人</th>
                <th style='width:80px;'>学生</th>
                <th style='width:55px;'>年级</th>
                <th style='width:55px;'>类型</th>
                <th style='width:150px;'>课程</th>
                <!-- <th style='width:60px;' class="text-right">单价</th> -->
                <!-- <th style='width:50px;' class="text-right">折扣</th> -->
                <!-- <th style='width:70px;' class="text-right">优惠金额</th> -->
                <!-- <th style='width:60px;' class="text-right">赠送课时</th> -->
                <th style='width:80px;' class="text-right">课时数量</th>
                <!-- <th style='width:80px;' class="text-right">总课时</th> -->
                <th style='width:90px;' class="text-right">课程应收</th>
                <th style='width:140px;' class="text-right">实付金额</th>
                <th style='width:240px;'>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($contracts)==0)
              <tr class="text-center"><td colspan="19">当前没有记录</td></tr>
              @endif
              @foreach ($contracts as $contract)
              <tr>
                <td rowspan="{{$contract['contract_course_num']}}">{{ $loop->iteration }}</td>
                <td rowspan="{{$contract['contract_course_num']}}">{{ $contract['department_name'] }}</td>
                <td rowspan="{{$contract['contract_course_num']}}">{{ date('m-d', strtotime($contract['contract_date'])) }}</td>
                <td rowspan="{{$contract['contract_course_num']}}"><a href="/user?id={{encode($contract['user_id'],'user_id')}}">{{ $contract['user_name'] }}</a></td>
                <td rowspan="{{$contract['contract_course_num']}}">
                  <a href="/student?id={{encode($contract['student_id'], 'student_id')}}">{{ $contract['student_name'] }}</a>
                </td>
                <td rowspan="{{$contract['contract_course_num']}}">{{ $contract['grade_name'] }}</td>
                @if($contract['contract_type']==0)
                  <td rowspan="{{$contract['contract_course_num']}}"><span style="color:red;">首签</span></td>
                @else
                  <td rowspan="{{$contract['contract_course_num']}}"><span style="color:green;">续签</span></td>
                @endif
                <td>{{$contract['contract_courses'][0]['course_name']}}</td>
                <!-- <td class="text-right">{{floatval($contract['contract_courses'][0]['contract_course_original_hour'])}}</td> -->
                <!-- <td class="text-right">{{floatval($contract['contract_courses'][0]['contract_course_original_unit_price'])}}</td> -->
                <!-- <td class="text-right">{{$contract['contract_courses'][0]['contract_course_discount_rate']}}</td> -->
                <!-- <td class="text-right">{{$contract['contract_courses'][0]['contract_course_discount_amount']}}</td> -->
                <!-- <td class="text-right">{{$contract['contract_courses'][0]['contract_course_free_hour']}}</td> -->
                <td class="text-right">{{floatval($contract['contract_courses'][0]['contract_course_total_hour'])}}</td>
                <td class="text-right">{{floatval($contract['contract_courses'][0]['contract_course_total_price'])}}</td>
                @if($contract['contract_total_price']==$contract['contract_paid_price'])
                  <td rowspan="{{$contract['contract_course_num']}}" class="text-right"><span style="color:green;"><strong>{{ floatval($contract['contract_paid_price']) }} 元</strong></span></td>
                @else
                  <td rowspan="{{$contract['contract_course_num']}}" class="text-right"><span style="color:red;"><strong>{{ floatval($contract['contract_paid_price']) }} / {{ floatval($contract['contract_total_price']) }} 元</strong></span></td>
                @endif
                <td rowspan="{{$contract['contract_course_num']}}">
                  <a href="/student?id={{encode($contract['student_id'], 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                  <a href="/contract?id={{encode($contract['contract_id'], 'contract_id')}}" target="_blank"><button type="button" class="btn btn-primary btn-sm">合同</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/market/contract/delete?id={{encode($contract['contract_id'], 'contract_id')}}', '确认删除合同？')">删除</button>
                  @if($contract['contract_total_price']!=$contract['contract_paid_price'])
                    <a href='/market/contract/edit?id={{encode($contract['contract_id'], 'contract_id')}}'><button type="button" class="btn btn-info btn-sm">补缴</button></a>
                  @endif
                </td>
              </tr>
              @for ($i = 1; $i < $contract['contract_course_num']; $i++)
                <tr>
                  <td>{{$contract['contract_courses'][$i]['course_name']}}</td>
                  <!-- <td class="text-right">{{floatval($contract['contract_courses'][$i]['contract_course_original_hour'])}}</td> -->
                  <!-- <<td class="text-right">{{floatval($contract['contract_courses'][$i]['contract_course_original_unit_price'])}}</td> -->
                  <!-- <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_discount_rate']}}</td> -->
                  <!-- <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_discount_amount']}}</td> -->
                  <!-- <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_free_hour']}}</td> -->
                  <td class="text-right">{{floatval($contract['contract_courses'][$i]['contract_course_total_hour'])}}</td>
                  <td class="text-right">{{floatval($contract['contract_courses'][$i]['contract_course_total_price'])}}</td>
                </tr>
              @endfor
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
  linkActive('link-market');
  navbarActive('navbar-market');
  linkActive('marketContract');
</script>
@endsection
