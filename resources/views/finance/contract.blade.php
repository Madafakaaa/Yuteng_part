@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">签约统计</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">统计中心</li>
    <li class="breadcrumb-item active">签约统计</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-3">
        <div class="col-3 text-left">
          <a href="?month={{ date('Y-m', strtotime ('-1 month', strtotime($month))) }}"><button type="button" class="btn btn-secondary btn-icon-only rounded-circle"><i class="fa fa-chevron-left"></i></button></a>
        </div>
        <div class="col-6 text-center">
          <h1 class="text-white mb-0">{{date('Y.m', strtotime($month))}}</h1>
        </div>
        <div class="col-3 text-right">
          <a href="?month={{ date('Y-m', strtotime ('+1 month', strtotime($month))) }}"><button type="button" class="btn btn-secondary btn-icon-only rounded-circle"><i class="fa fa-chevron-right"></i></button></a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h4 class="card-title text-uppercase mb-1 text-muted">签单金额</h4>
              <span class="h2 font-weight-bold mb-1 text-blue">{{$dashboard['sum_contract_price']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                <i class="ni ni-money-coins"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            @if($dashboard['sum_contract_price_change']>0)
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $dashboard['sum_contract_price_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @elseif($dashboard['sum_contract_price_change']==0)
            <span class="text-info mr-2"><i class="fa fa-minus"></i> 0%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @else
            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ $dashboard['sum_contract_price_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @endif
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h4 class="card-title text-uppercase mb-1 text-muted">售出课时</h4>
              <span class="h2 font-weight-bold mb-1 text-blue">{{$dashboard['sum_hour_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                <i class="ni ni-ruler-pencil"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            @if($dashboard['sum_hour_num_change']>0)
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $dashboard['sum_hour_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @elseif($dashboard['sum_contract_num_change']==0)
            <span class="text-info mr-2"><i class="fa fa-minus"></i> 0%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @else
            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ $dashboard['sum_hour_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @endif
          </p>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-stats mb-2">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h4 class="card-title text-uppercase mb-1 text-muted">签单数量</h4>
              <span class="h2 font-weight-bold mb-1 text-blue">{{$dashboard['sum_contract_num']}}</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                <i class="ni ni-single-copy-04"></i>
              </div>
            </div>
          </div>
          <p class="mt-2 mb-0 text-sm">
            @if($dashboard['sum_contract_num_change']>0)
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> {{ $dashboard['sum_contract_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @elseif($dashboard['sum_contract_num_change']==0)
            <span class="text-info mr-2"><i class="fa fa-minus"></i> 0%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @else
            <span class="text-danger mr-2"><i class="fa fa-arrow-down"></i> {{ $dashboard['sum_contract_num_change'] }}%</span>
            <span class="text-nowrap text-muted">较上月</span>
            @endif
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card my-2">
        <div class="table-responsive py-4">
          <table class="table table-flush datatable-basic">
            <thead class="thead-light">
              <tr>
                <th>序号</th>
                <th>校区</th>
                <th>签约金额</th>
                <th>售出课时</th>
                <th>签约数量</th>
              </tr>
            </thead>
            <tbody>
              @foreach($department_contracts as $department_contract)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{$department_contract->department_name}}</td>
                  <td>{{$department_contract->department_total_price}}</td>
                  <td>{{$department_contract->department_total_hour}}</td>
                  <td>{{$department_contract->department_contract_num}}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card my-2">
        <div class="table-responsive py-4">
          <table class="table table-flush datatable-basic">
            <thead class="thead-light">
              <tr>
                <th>序号</th>
                <th>用户</th>
                <th>签约金额</th>
                <th>售出课时</th>
                <th>签约数量</th>
              </tr>
            </thead>
            <tbody>
              @foreach($user_contracts as $user_contract)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{$user_contract->user_name}}</td>
                  <td>{{$user_contract->user_total_price}}</td>
                  <td>{{$user_contract->user_total_hour}}</td>
                  <td>{{$user_contract->user_contract_num}}</td>
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
  linkActive('link-finance');
  navbarActive('navbar-finance');
  linkActive('financeContract');
</script>
@endsection
