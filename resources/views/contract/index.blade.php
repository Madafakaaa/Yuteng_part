@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">招生中心</li>
    <li class="breadcrumb-item active">全部签约</li>
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
                <select class="form-control" name="filter1" data-toggle="select">
                  <option value=''>全部校区</option>
                  @foreach ($filter_departments as $filter_department)
                    <option value="{{ $filter_department->department_id }}" @if($request->input('filter1')==$filter_department->department_id) selected @endif>{{ $filter_department->department_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter2" data-toggle="select">
                  <option value=''>全部学生</option>
                  @foreach ($filter_students as $filter_student)
                    <option value="{{ $filter_student->student_id }}" @if($request->input('filter2')==$filter_student->student_id) selected @endif>{{ $filter_student->student_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-3">
                <select class="form-control" name="filter3" data-toggle="select">
                  <option value=''>全部年级</option>
                  @foreach ($filter_grades as $filter_grade)
                    <option value="{{ $filter_grade->grade_id }}" @if($request->input('filter4')==$filter_grade->grade_id) selected @endif>{{ $filter_grade->grade_name }}</option>
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
                <th style='width:100px;'>学生</th>
                <th style='width:70px;'>年级</th>
                <th style='width:70px;'>类型</th>
                <th style='width:80px;' class="text-right">课程数量</th>
                <th style='width:90px;' class="text-right">合计课时</th>
                <th style='width:90px;' class="text-right">优惠金额</th>
                <th style='width:90px;' class="text-right">服务费</th>
                <th style='width:110px;' class="text-right">实付金额</th>
                <th style='width:80px;'>支付方式</th>
                <th style='width:110px;'>签约人</th>
                <th style='width:96px;'>购课日期</th>
                <th style='width:140px;'>操作管理</th>
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
                <td title="{{ $row->department_name }}">{{ $row->department_name }}</td>
                <td title="{{ $row->student_name }}">{{ $row->student_name }}</td>
                <td title="{{ $row->grade_name }}">{{ $row->grade_name }}</td>
                @if($row->contract_type==0)
                  <td title="首签"><span style="color:red;">首签</span></td>
                @else
                  <td title="续费"><span style="color:green;">续费</span></td>
                @endif
                <td class="text-right" title="{{ $row->contract_course_num }} 种课程">{{ $row->contract_course_num }} 种课程</td>
                <td class="text-right" title="{{ $row->contract_total_hour }} 课时"><strong>{{ $row->contract_total_hour }} 课时</strong></td>
                <td class="text-right" title="- {{ number_format($row->contract_discount_price, 1) }} 元"><span style="color:red;">- {{ number_format($row->contract_discount_price, 1) }} 元</span></td>
                <td class="text-right" title="{{ number_format($row->contract_extra_fee, 1) }} 元">{{ number_format($row->contract_extra_fee, 1) }} 元</td>
                <td class="text-right" title="{{ number_format($row->contract_total_price, 1) }} 元"><strong>{{ number_format($row->contract_total_price, 1) }} 元</strong></td>
                <td title="{{ $row->contract_payment_method }}">{{ $row->contract_payment_method }}</td>
                <td title="{{ $row->user_name }}">{{ $row->user_name }}</td>
                <td title="{{ $row->contract_date }}">{{ $row->contract_date }}</td>
                <td>
                  <form action="contract/{{$row->contract_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/contract/{{$row->contract_id}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a>
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
  linkActive('link-4');
  navbarActive('navbar-4');
  linkActive('contract');
</script>
@endsection
