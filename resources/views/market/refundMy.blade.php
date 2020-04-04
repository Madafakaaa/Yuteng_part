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
          <h6 class="h2 text-white d-inline-block mb-0">我的退费</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">招生中心</li>
              <li class="breadcrumb-item active">我的退费</li>
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
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:65px;'>序号</th>
                <th style='width:110px;'>学生</th>
                <th style='width:90px;'>校区</th>
                <th style='width:123px;'>课程</th>
                <th style='width:90px;' class="text-right">扣除课时</th>
                <th style='width:100px;' class="text-right">违约金</th>
                <th style='width:110px;' class="text-right">退款金额</th>
                <th style='width:95px;'>日期</th>
                <th style='width:132px;'>退费人</th>
                <th style='width:132px;'>复核人</th>
                <th style='width:270px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="11">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->student_name }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->course_name }}</td>
                <td class="text-right">{{ $row->refund_total_hour }} 课时</td>
                <td class="text-right"><span style="color:red;">- {{ number_format($row->refund_fine, 2) }} 元</span></td>
                <td class="text-right"><strong>{{ number_format($row->refund_actual_amount, 2) }} 元</strong></td>
                <td>{{ $row->refund_date }}</td>
                <td>{{ $row->createuser_name }} ({{ $row->createuser_position_name }})</td>
                @if($row->refund_checked==0)
                  <td><span style="color:red;">未审核</span></td>
                @else
                  <td><span style="color:green;">{{ $row->checked_user_name }} ({{ $row->checked_user_position_name }})</span></td>
                @endif
                <td>
                  <form action="/market/refund/{{$row->refund_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/student/{{$row->student_id}}'><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>&nbsp;
                    <a href='/contract/{{$row->refund_contract}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a>&nbsp;
                    @if($row->refund_checked==1)
                      <a href='#'><button type="button" class="btn btn-outline-danger btn-sm" disabled>删除</button></a>&nbsp;
                    @else
                      {{ deleteConfirm($row->refund_id, ["退费学生：".$row->student_name]) }}
                    @endif
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
  linkActive('marketRefundMy');
</script>
@endsection
