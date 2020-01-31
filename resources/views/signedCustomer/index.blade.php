@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">招生中心</li>
    <li class="breadcrumb-item active">已签约客户</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-4">
        <div class="card-header border-0 p-0 mb-1">
          <form action="" method="get" id="filter" name="filter">
            <div class="row m-2">
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input class="form-control" type="text" name="filter1" placeholder=" 客户姓名..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter2" data-toggle="select">
                  <option value=''>全部校区</option>
                  @foreach ($filter_departments as $filter_department)
                    <option value="{{ $filter_department->department_id }}" @if($request->input('filter2')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter3" data-toggle="select">
                  <option value=''>全部年级</option>
                  @foreach ($filter_grades as $filter_grade)
                    <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter3')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
                  @endforeach
                </select>
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
      <div class="card main_card mb-4" style="display:none">
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:90px;'>校区</th>
                <th style='width:90px;'>学生</th>
                <th style='width:60px;'>年级</th>
                <th style='width:58px;'>性别</th>
                <th style='width:140px;'>监护人</th>
                <th style='width:108px;'>电话</th>
                <th style='width:105px;'>微信</th>
                <th style='width:80px;'>跟进人</th>
                <th style='width:82px;'>跟进次数</th>
                <th style='width:100px;'>签约时间</th>
                <th style='width:80px;'>签约状态</th>
                <th style='width:254px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="14">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="备注：{{ $row->student_remark }}">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td title="校区：{{ $row->department_name }}">{{ $row->department_name }}</td>
                <td title="学生：{{ $row->student_name }}">{{ $row->student_name }}</td>
                <td title="年级：{{ $row->grade_name }}">{{ $row->grade_name }}</td>
                <td title="性别：{{ $row->student_gender }}">{{ $row->student_gender }}</td>
                <td title="{{ $row->student_guardian_relationship }}：{{ $row->student_guardian }}">{{ $row->student_guardian_relationship }}：{{ $row->student_guardian }}</td>
                <td title="电话：{{ $row->student_phone }}">{{ $row->student_phone }}</td>
                <td title="微信：{{ $row->student_wechat }}">{{ $row->student_wechat }}</td>
                <td title="跟进人：{{ $row->user_name }}">{{ $row->user_name }}</td>
                <td title="跟进次数：{{ $row->student_follow_num }} 次">{{ $row->student_follow_num }} 次</td>
                <td title="上次跟进：{{ $row->contract_date }}">{{ $row->contract_date }}</td>
                @if($row->student_customer_status==0)
                  <td title="签约状态：未签约"><span style="color:red;">未签约</span></td>
                @else
                  <td title="签约状态：已签约"><span style="color:green;">已签约</span></td>
                @endif
                <td>
                  <form action="customer/{{$row->student_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/student/{{$row->student_id}}'><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                    <a href='/contract/{{$row->contract_id}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a>
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
  linkActive('link-2');
  navbarActive('navbar-2');
  linkActive('signedCustomer');
</script>
@endsection
