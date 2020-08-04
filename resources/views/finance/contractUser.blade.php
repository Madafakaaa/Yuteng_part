@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">个人签约</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">统计中心</li>
    <li class="breadcrumb-item"><a href="/finance/contract">签约统计</a></li>
    <li class="breadcrumb-item active">个人签约</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-1">签单总数</h5>
              <span class="h2 font-weight-bold mb-1 counter-value text-info">{{$dashboard['dashboard_contract_num']}}</span>
            </div>
          </div>
          @if($dashboard['dashboard_contract_num_today']>0)
          <p class="mt-1 mb-0 text-sm">
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$dashboard['dashboard_contract_num_today']}}</span>
            <span class="text-nowrap">今日新增</span>
          </p>
          @else
          <p class="mt-1 mb-0 text-sm">
            <span class="text-default mr-2"><i class="fa fa-minus"></i> 0</span>
            <span class="text-nowrap">今日新增</span>
          </p>
          @endif
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-1">签单金额</h5>
              <span class="h2 font-weight-bold mb-1 counter-value text-info">{{$dashboard['dashboard_price_total']}}</span>
            </div>
          </div>
          @if($dashboard['dashboard_price_total_today']>0)
          <p class="mt-1 mb-0 text-sm">
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{$dashboard['dashboard_price_total_today']}}</span>
            <span class="text-nowrap">今日新增</span>
          </p>
          @else
          <p class="mt-1 mb-0 text-sm">
            <span class="text-default mr-2"><i class="fa fa-minus"></i> 0</span>
            <span class="text-nowrap">今日新增</span>
          </p>
          @endif
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card main_card mb-4" style="display:none">
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
                <input type="hidden" name="filter_user" value="{{$filters['filter_user']}}">
                <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
                <input type="submit" id="submitButton1" class="btn btn-primary btn-block" value="查询">
              </div>
            </div>
          </form>
        </div>
        <hr class="mb-1 mt-0">
        <div class="card-header p-2" style="border-bottom:0px;">
          <div class="row">
            <div class="col-lg-1 col-md-2 col-sm-3">
              <small class="text-muted font-weight-bold px-2">用户：</small>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
              <div class="form-group mb-0">
                <form action="" method="get" onsubmit="submitButtonDisable('submitButton1')" id='form2'>
                  <select class="form-control form-control-sm" name="filter_user" data-toggle="select" onChange="form_submit('form2')">
                    <option value=''>全部用户</option>
                    @foreach ($filter_users as $filter_user)
                      <option value="{{ $filter_user->user_id }}" @if($filter_user->user_id==$filters['filter_user']) selected @endif>{{ $filter_user->user_name }} {{ $filter_user->department_name }}</option>
                    @endforeach
                  </select>
                  <input type="hidden" name="filter_month" value="{{$filters['filter_month']}}">
                  <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
                </form>
              </div>
            </div>
          </div>
        </div>
        <div class="card-header p-2" style="border-bottom:0px;">
          <div class="row">
            <div class="col-lg-1 col-md-2 col-sm-3">
              <small class="text-muted font-weight-bold px-2">类型：</small>
            </div>
            <div class="col-lg-3 col-md-4 col-sm-6">
              <a href="?@foreach($filters as $key => $value) @if($key!='filter_type') {{$key}}={{$value}}& @endif @endforeach">
                <button type="button" @if(!isset($filters['filter_type'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
              </a>
              <a href="?@foreach($filters as $key => $value) @if($key!='filter_type') {{$key}}={{$value}}& @endif @endforeach &filter_type=1"><button type="button" @if($filters['filter_type']==1) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>首签</button></a>
              <a href="?@foreach($filters as $key => $value) @if($key!='filter_type') {{$key}}={{$value}}& @endif @endforeach &filter_type=2"><button type="button" @if($filters['filter_type']==2) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>续签</button></a>
            </div>
          </div>
        </div>
        <div class="table-responsive"  style="max-height:600px;">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:45px;'>序号</th>
                <th style='width:70px;'>校区</th>
                <th style='width:55px;'>日期</th>
                <th style='width:75px;'>签约人</th>
                <th style='width:75px;'>学生</th>
                <th style='width:55px;'>年级</th>
                <th style='width:55px;'>类型</th>
                <th style='width:130px;'>课程</th>
                <th style='width:70px;'>课程类型</th>
                <th style='width:70px;' class="text-right">课时数量</th>
                <th style='width:65px;' class="text-right">单价</th>
                <th style='width:50px;' class="text-right">折扣</th>
                <th style='width:70px;' class="text-right">优惠金额</th>
                <th style='width:70px;' class="text-right">赠送课时</th>
                <th style='width:70px;' class="text-right">总课时</th>
                <th style='width:90px;' class="text-right">课程应收</th>
                <th style='width:110px;' class="text-right">合计金额</th>
                <th style='width:110px;' class="text-right">实付金额</th>
                <th style='width:80px;'>操作</th>
              </tr>
            </thead>
            <tbody>
              @if(count($contracts)==0)
              <tr class="text-center"><td colspan="14">当前没有记录</td></tr>
              @endif
              @foreach ($contracts as $contract)
              <tr>
                <td rowspan="{{$contract['contract_course_num']}}">{{ $loop->iteration }}</td>
                <td rowspan="{{$contract['contract_course_num']}}">{{ $contract['department_name'] }}</td>
                <td rowspan="{{$contract['contract_course_num']}}">{{ date('m-d', strtotime($contract['contract_date'])) }}</td>
                <td rowspan="{{$contract['contract_course_num']}}"><a href="/user?id={{encode($contract['user_id'],'user_id')}}">{{ $contract['user_name'] }}</a></td>
                <td rowspan="{{$contract['contract_course_num']}}">
                  <a href="/student?id={{encode($contract['student_id'],'student_id')}}">{{ $contract['student_name'] }}</a>
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
                <td rowspan="{{$contract['contract_course_num']}}"><a href="/contract?id={{encode($contract['contract_id'],'contract_id')}}">查看合同</a></td>
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
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-finance');
  navbarActive('navbar-finance');
  linkActive('financeContract');
</script>
@endsection
