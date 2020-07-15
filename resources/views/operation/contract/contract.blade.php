@extends('main')

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
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item active">签约管理</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
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
                      <input class="form-control" type="text" name="filter1" placeholder="学生姓名..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
                    </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter2" data-toggle="select">
                        <option value=''>全部校区</option>
                        @foreach ($filter_departments as $filter_department)
                          <option value="{{ $filter_department->department_id }}" @if($request->input('filter2')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter3" data-toggle="select">
                        <option value=''>全部年级</option>
                        @foreach ($filter_grades as $filter_grade)
                          <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter3')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
                        @endforeach
                      </select>
	                </div>
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <select class="form-control" name="filter4" data-toggle="select">
                        <option value=''>签约人</option>
                        @foreach ($filter_users as $filter_user)
                          <option value="{{ $filter_user->user_id }}" @if($request->input('filter4')==$filter_user->user_id) selected @endif>{{$filter_user->department_name}} {{ $filter_user->user_name }}</option>
                        @endforeach
                      </select>
	                </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
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
              <tr class="text-center"><td colspan="14">当前没有记录</td></tr>
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
                    <a href='/operation/contract/edit?id={{encode($row->contract_id, 'contract_id')}}'><button type="button" class="btn btn-info btn-sm">补缴</button></a>
                  @endif
                  <a href="/student?id={{encode($row->student_id, 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                  <a href='/contract?id={{encode($row->contract_id, 'contract_id')}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">合同</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/operation/contract/delete?id={{encode($row->contract_id, 'contract_id')}}', '确认删除合同？')">删除</button>
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationContract');
</script>
@endsection
