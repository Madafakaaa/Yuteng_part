@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">我的签约</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">我的签约</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row mb-3">
    <div class="col-auto">
      <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
        <span class="btn-inner--text">搜索</span>
      </a>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="collapse @if($filter_status==1) show @endif" id="filter">
        <div class="card mb-4">
          <div class="card-body border-1 p-0 my-1">
            <form action="" method="get">
              <div class="row m-2">
                <div class="col-lg-8 col-md-8 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <input class="form-control" type="text" name="filter_name" placeholder="学生姓名..." autocomplete="off" @if(isset($filters['filter_name']))) value="{{ $filters['filter_name'] }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter_user" data-toggle="select">
                        <option value=''>签约人</option>
                        @foreach ($filter_users as $filter_user)
                          <option value="{{ $filter_user->user_id }}" @if($filters['filter_user']==$filter_user->user_id) selected @endif>{{$filter_user->department_name}} {{ $filter_user->user_name }}</option>
                        @endforeach
                      </select>
	                </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
                      <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
                      <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
                      <input type="submit" class="btn btn-primary btn-block" value="查询">
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
                      <a href="?"><button type="button" class="form-control btn btn-outline-primary btn-block" style="white-space:nowrap; overflow:hidden;">重置</button></a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-header py-3" style="border-bottom:0px;">
          <form action="" method="get" onsubmit="submitButtonDisable('submitButton1')">
            <div class="row">
              <div class="col-6">
                <a href="?filter_month={{ date('Y-m', strtotime ('-1 month', strtotime($filters['filter_month']))) }}&@foreach($filters as $key => $value) @if($key!='filter_month') {{$key}}={{$value}}& @endif @endforeach"><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-left"></i></button></a>
                <a href="?filter_month={{ date('Y-m', strtotime ('+1 month', strtotime($filters['filter_month']))) }}&@foreach($filters as $key => $value) @if($key!='filter_month') {{$key}}={{$value}}& @endif @endforeach"><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-right"></i></button></a>
                <span style="vertical-align: middle; font-size:26px; font-family: 'Noto Sans', sans-serif;" class="ml-3">{{ date('Y.m', strtotime($filters['filter_month'])) }}</span>
              </div>
              <div class="col-3">
              </div>
              <div class="col-2 text-right">
                <input class="form-control monthpicker" name="filter_month" placeholder="选择月份" type="text" value="{{$filters['filter_month']}}" autocomplete="off">
              </div>
              <div class="col-1 text-right">
                <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
                <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
                <input type="submit" id="submitButton1" class="btn btn-primary btn-block" value="查询">
              </div>
            </div>
          </form>
        </div>
        <hr class="mb-1 mt-0">
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">校区：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_department'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_departments as $filter_department)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach &filter_department={{$filter_department->department_id}}"><button type="button" @if($filters['filter_department']==$filter_department->department_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_department->department_name}}</button></a>
          @endforeach
        </div>
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">年级：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_grade'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_grades as $filter_grade)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach filter_grade={{$filter_grade->grade_id}}"><button type="button" @if($filters['filter_grade']==$filter_grade->grade_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_grade->grade_name}}</button></a>
          @endforeach
        </div>
        <div class="table-responsive freeze-table-7">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:50px;'>序号</th>
                <th style='width:70px;'>校区</th>
                <th style='width:70px;'>日期</th>
                <th style='width:80px;'>签约人</th>
                <th style='width:100px;'>学生</th>
                <th style='width:55px;'>年级</th>
                <th style='width:55px;'>类型</th>
                <th style='width:120px;'>课程</th>
                <th style='width:60px;'>类型</th>
                <th style='width:60px;' class="text-right">课时数量</th>
                <th style='width:60px;' class="text-right">单价</th>
                <th style='width:50px;' class="text-right">折扣</th>
                <th style='width:70px;' class="text-right">优惠金额</th>
                <th style='width:60px;' class="text-right">赠送课时</th>
                <th style='width:60px;' class="text-right">总课时</th>
                <th style='width:80px;' class="text-right">课程应收</th>
                <th style='width:100px;' class="text-right">合计金额</th>
                <th style='width:100px;' class="text-right">实付金额</th>
                <th style='width:250px;'>操作管理</th>
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
                  <a href="/student?id={{encode($contract['student_id'], 'student_id')}}">{{ $contract['student_name'] }}</a>&nbsp;
                  @if($contract['student_gender']=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td rowspan="{{$contract['contract_course_num']}}">{{ $contract['grade_name'] }}</td>
                @if($contract['contract_type']==0)
                  <td rowspan="{{$contract['contract_course_num']}}"><span style="color:red;">首签</span></td>
                @else
                  <td rowspan="{{$contract['contract_course_num']}}"><span style="color:green;">续签</span></td>
                @endif
                <td>{{$contract['contract_courses'][0]['course_name']}}</td>
                <td>{{$contract['contract_courses'][0]['course_type']}}</td>
                <td class="text-right">{{$contract['contract_courses'][0]['contract_course_original_hour']}}</td>
                <td class="text-right">{{$contract['contract_courses'][0]['contract_course_original_unit_price']}}</td>
                <td class="text-right">{{$contract['contract_courses'][0]['contract_course_discount_rate']}}</td>
                <td class="text-right">{{$contract['contract_courses'][0]['contract_course_discount_amount']}}</td>
                <td class="text-right">{{$contract['contract_courses'][0]['contract_course_free_hour']}}</td>
                <td class="text-right">{{$contract['contract_courses'][0]['contract_course_total_hour']}}</td>
                <td class="text-right">{{$contract['contract_courses'][0]['contract_course_total_price']}}</td>
                <td rowspan="{{$contract['contract_course_num']}}" class="text-right" title="{{ number_format($contract['contract_total_price'], 2) }} 元"><strong>{{ number_format($contract['contract_total_price'], 2) }} 元</strong></td>
                @if($contract['contract_total_price']==$contract['contract_paid_price'])
                  <td rowspan="{{$contract['contract_course_num']}}" class="text-right" title="{{ number_format($contract['contract_paid_price'], 2) }} 元"><span style="color:green;"><strong>{{ number_format($contract['contract_paid_price'], 2) }} 元</strong></span></td>
                @else
                  <td rowspan="{{$contract['contract_course_num']}}" class="text-right" title="{{ number_format($contract['contract_paid_price'], 2) }} 元"><span style="color:red;"><strong>{{ number_format($contract['contract_paid_price'], 2) }} 元</strong></span></td>
                @endif
                <td rowspan="{{$contract['contract_course_num']}}">
                  @if($contract['contract_total_price']!=$contract['contract_paid_price'])
                    <a href='/operation/contract/edit?id={{encode($contract['contract_id'], 'contract_id')}}'><button type="button" class="btn btn-info btn-sm">补缴</button></a>
                  @endif
                  <a href="/student?id={{encode($contract['student_id'], 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                  <a href="/contract?id={{encode($contract['contract_id'], 'contract_id')}}" target="_blank"><button type="button" class="btn btn-primary btn-sm">合同</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/operation/contract/delete?id={{encode($contract['contract_id'], 'contract_id')}}', '确认删除合同？')">删除</button>
                </td>
              </tr>
              @for ($i = 1; $i < $contract['contract_course_num']; $i++)
                <tr>
                  <td>{{$contract['contract_courses'][$i]['course_name']}}</td>
                  <td>{{$contract['contract_courses'][$i]['course_type']}}</td>
                  <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_original_hour']}}</td>
                  <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_original_unit_price']}}</td>
                  <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_discount_rate']}}</td>
                  <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_discount_amount']}}</td>
                  <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_free_hour']}}</td>
                  <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_total_hour']}}</td>
                  <td class="text-right">{{$contract['contract_courses'][$i]['contract_course_total_price']}}</td>
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationMyContract');
</script>
@endsection
