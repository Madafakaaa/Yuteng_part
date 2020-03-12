@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">签约管理</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">招生中心</li>
              <li class="breadcrumb-item active">签约管理</li>
            </ol>
          </nav>
        </div>
        <div class="col-6 text-right">
          <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
            <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            <span class="btn-inner--text">搜索</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="collapse" id="filter">
        <div class="card mb-1">
          <div class="card-header border-0 p-0 mb-1">
            <form action="" method="get" id="filter" name="filter">
              <div class="row m-2">
                <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                  <input class="form-control" type="text" name="filter1" placeholder="校区名称..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
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
      </div>
      <div class="card main_card mb-4" style="display:none">
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:120px;'>学生</th>
                <th style='width:90px;'>校区</th>
                <th style='width:65px;'>年级</th>
                <th style='width:65px;'>类型</th>
                <th style='width:90px;' class="text-right">合计课时</th>
                <th style='width:101px;' class="text-right">优惠金额</th>
                <th style='width:101px;' class="text-right">服务费</th>
                <th style='width:110px;' class="text-right">实付金额</th>
                <th style='width:80px;'>支付方式</th>
                <th style='width:140px;'>签约人</th>
                <th style='width:97px;'>购课日期</th>
                <th style='width:188px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="14">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="备注：{{ $row->contract_remark }}. 创建时间：{{ $row->contract_createtime }}。">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td title="{{ $row->student_name }}">{{ $row->student_name }}</td>
                <td title="{{ $row->department_name }}">{{ $row->department_name }}</td>
                <td title="{{ $row->grade_name }}">{{ $row->grade_name }}</td>
                @if($row->contract_type==0)
                  <td title="首签"><span style="color:red;">首签</span></td>
                @else
                  <td title="续费"><span style="color:green;">续费</span></td>
                @endif
                <td class="text-right" title="{{ $row->contract_total_hour }} 课时"><strong>{{ $row->contract_total_hour }} 课时</strong></td>
                <td class="text-right" title="- {{ number_format($row->contract_discount_price, 1) }} 元"><span style="color:red;">- {{ number_format($row->contract_discount_price, 1) }} 元</span></td>
                <td class="text-right" title="{{ number_format($row->contract_extra_fee, 1) }} 元">{{ number_format($row->contract_extra_fee, 1) }} 元</td>
                <td class="text-right" title="{{ number_format($row->contract_total_price, 1) }} 元"><strong>{{ number_format($row->contract_total_price, 1) }} 元</strong></td>
                <td title="{{ $row->contract_payment_method }}">{{ $row->contract_payment_method }}</td>
                <td title="{{ $row->user_name }} ({{ $row->position_name }})">{{ $row->user_name }} ({{ $row->position_name }})</td>
                <td title="{{ $row->contract_date }}">{{ $row->contract_date }}</td>
                <td>
                  <form action="/market/contract/{{$row->contract_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/student/{{$row->student_id}}'><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                    <a href='/contract/{{$row->contract_id}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">合同</button></a>
                    {{ deleteConfirm($row->contract_id, ["购课学生：".$row->student_name."，<br> 购买课程：".$row->contract_course_num."课程，
                                                          购课数量：".$row->contract_total_hour."课时，金额：".$row->contract_total_price."元。"]) }}
                  </form>
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
  linkActive('link-market');
  navbarActive('navbar-market');
  linkActive('marketContractAll');
</script>
@endsection
