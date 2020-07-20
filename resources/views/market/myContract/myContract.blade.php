@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">我的签约</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">招生中心</li>
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
      <div class="card main_card mb-4" style="display:none">
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
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:130px;'>学生</th>
                <th style='width:250px;'></th>
                <th style='width:90px;'>校区</th>
                <th style='width:65px;'>年级</th>
                <th style='width:65px;'>部门</th>
                <th style='width:90px;' class="text-right">合计课时</th>
                <th style='width:110px;' class="text-right">应付金额</th>
                <th style='width:110px;' class="text-right">实付金额</th>
                <th style='width:80px;'>支付方式</th>
                <th style='width:140px;'>签约人</th>
                <th style='width:97px;'>购课日期</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="13">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td></td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>
                  {{ $row->student_name }}&nbsp;
                  @if($row->student_gender=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td>
                  @if($row->contract_total_price!=$row->contract_paid_price)
                    <a href='/market/myContract/edit?id={{encode($row->contract_id, 'contract_id')}}'><button type="button" class="btn btn-info btn-sm">补缴</button></a>
                  @endif
                  <a href="/student?id={{encode($row->student_id, 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                  <a href="/contract?id={{encode($row->contract_id, 'contract_id')}}" target="_blank"><button type="button" class="btn btn-primary btn-sm">合同</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/market/myContract/delete?id={{encode($row->contract_id, 'contract_id')}}', '确认删除合同？')">删除</button>
                </td>
                <td title="{{ $row->department_name }}">{{ $row->department_name }}</td>
                <td title="{{ $row->grade_name }}">{{ $row->grade_name }}</td>
                @if($row->contract_section==0)
                  <td><span style="color:red;">招生部</span></td>
                @else
                  <td><span style="color:green;">运营部</span></td>
                @endif
                <td class="text-right" title="{{ $row->contract_total_hour }} 课时"><strong>{{ $row->contract_total_hour }} 课时</strong></td>
                <td class="text-right" title="{{ number_format($row->contract_total_price, 2) }} 元"><strong>{{ number_format($row->contract_total_price, 2) }} 元</strong></td>
                @if($row->contract_total_price==$row->contract_paid_price)
                  <td class="text-right" title="{{ number_format($row->contract_paid_price, 2) }} 元"><span style="color:green;"><strong>{{ number_format($row->contract_paid_price, 2) }} 元</strong></span></td>
                @else
                  <td class="text-right" title="{{ number_format($row->contract_paid_price, 2) }} 元"><span style="color:red;"><strong>{{ number_format($row->contract_paid_price, 2) }} 元</strong></span></td>
                @endif
                <td title="{{ $row->contract_payment_method }}">{{ $row->contract_payment_method }}</td>
                <td title="{{ $row->user_name }} ({{ $row->position_name }})">{{ $row->user_name }} ({{ $row->position_name }})</td>
                <td title="{{ $row->contract_date }}">{{ $row->contract_date }}</td>
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
  linkActive('link-market');
  navbarActive('navbar-market');
  linkActive('marketMyContract');
</script>
@endsection
