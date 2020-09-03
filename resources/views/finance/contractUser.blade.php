@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">个人签约统计</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">统计中心</li>
    <li class="breadcrumb-item active">个人签约统计</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <form action="" method="get" onsubmit="submitButtonDisable('submitButton1')">
          <div class="card-header p-3" style="border-bottom:0px;">
            <div class="row">
              <div class="col-1 text-center">
                <small class="text-muted font-weight-bold px-2">起始日期</small>
              </div>
              <div class="col-2">
                <input class="form-control form-control-sm datepicker" name="filter_date_start" type="text" value="{{$filters['filter_date_start']}}" autocomplete="off">
              </div>
              <div class="col-1 text-center">
                <small class="text-muted font-weight-bold px-2">截止日期</small>
              </div>
              <div class="col-2">
                <input class="form-control form-control-sm datepicker" name="filter_date_end" type="text" value="{{$filters['filter_date_end']}}" autocomplete="off">
              </div>
              <div class="col-1 text-center">
                <small class="text-muted font-weight-bold px-2">用户</small>
              </div>
              <div class="col-2">
                <select class="form-control form-control-sm" name="filter_user" data-toggle="select">
                  <option value=''>全部用户</option>
                  @foreach ($filter_users as $user)
                    <option value="{{ $user->user_id }}" @if($filters['filter_user']==$user->user_id) selected @endif>[{{$user->department_name}}] {{ $user->user_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-1">
              </div>
              <div class="col-2">
                <input type="submit" id="submitButton1" class="btn btn-sm btn-primary btn-block" value="查询">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">签单总数</h5>
              <span class="h1 font-weight-bold mb-0 counter-value text-primary">{{$dashboard['dashboard_contract_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                <i class="ni ni-single-copy-04"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            <span class="text-nowrap">{{date('Y.m.d', strtotime($filters['filter_date_start']))}} ~ {{date('Y.m.d', strtotime($filters['filter_date_end']))}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">课时总数</h5>
              <span class="h1 font-weight-bold mb-0 counter-value text-primary">{{$dashboard['dashboard_hour_total']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                <i class="fa fa-user-clock"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            <span class="text-nowrap">{{date('Y.m.d', strtotime($filters['filter_date_start']))}} ~ {{date('Y.m.d', strtotime($filters['filter_date_end']))}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">签单金额</h5>
              <span class="h1 font-weight-bold mb-0 counter-value text-primary">{{$dashboard['dashboard_price_total']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-primary text-white rounded-circle shadow">
                <i class="ni ni-money-coins"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            <span class="text-nowrap">{{date('Y.m.d', strtotime($filters['filter_date_start']))}} ~ {{date('Y.m.d', strtotime($filters['filter_date_end']))}}</span>
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">实收金额</h5>
              <span class="h1 font-weight-bold mb-0 counter-value text-green">{{$dashboard['dashboard_paid_total']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-green text-white rounded-circle shadow">
                <i class="ni ni-money-coins"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            <span class="text-nowrap">{{date('Y.m.d', strtotime($filters['filter_date_start']))}} ~ {{date('Y.m.d', strtotime($filters['filter_date_end']))}}</span>
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="table-responsive"  style="max-height:600px;">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:45px;'>序号</th>
                <th style='width:85px;'>校区</th>
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
                <th style='width:110px;' class="text-right">实收金额</th>
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
  linkActive('financeContractUser');
</script>
@endsection
