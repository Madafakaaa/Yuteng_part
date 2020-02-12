@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">财务中心</li>
    <li class="breadcrumb-item active">本校退费记录</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-1">
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
                <th style='width:95px;'>校区</th>
                <th style='width:105px;'>学生</th>
                <th style='width:187px;'>课程</th>
                <th style='width:100px;'>类型</th>
                <th style='width:90px;' class="text-right">扣除课时</th>
                <th style='width:110px;' class="text-right">违约金</th>
                <th style='width:110px;' class="text-right">退款金额</th>
                <th style='width:100px;'>日期</th>
                <th style='width:80px;'>状态</th>
                <th style='width:270px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="14">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="可退金额：{{ number_format($row->refund_amount, 1) }} 元.年级：{{ $row->grade_name }}. 创建时间：{{ $row->refund_createtime }}. 创建用户：{{ $row->user_name }}. 备注：{{ $row->refund_remark }}.">
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td title="{{ $row->department_name }}">{{ $row->department_name }}</td>
                <td title="{{ $row->student_name }}">{{ $row->student_name }}</td>
                <td title="{{ $row->course_name }}">{{ $row->course_name }}</td>
                <td title="{{ $row->course_type }}">{{ $row->course_type }}</td>
                <td class="text-right" title="{{ $row->refund_total_hour }} 课时 (包含赠送课时)">{{ $row->refund_total_hour }} 课时</td>
                <td class="text-right" title="- {{ number_format($row->refund_fine, 1) }} 元"><span style="color:red;">- {{ number_format($row->refund_fine, 1) }} 元</span></td>
                <td class="text-right" title="{{ number_format($row->refund_actual_amount, 1) }} 元"><strong>{{ number_format($row->refund_actual_amount, 1) }} 元</strong></td>
                <td title="{{ $row->refund_date }}">{{ $row->refund_date }}</td>
                @if($row->refund_checked==0)
                  <td title="未审核"><span style="color:red;">未审核</span></td>
                @else
                  <td title="已审核"><span style="color:green;">已审核</span></td>
                @endif
                <td>
                  <form action="refund/{{$row->refund_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/refund/{{$row->refund_id}}'><button type="button" class="btn btn-primary btn-sm">查看详情</button></a>&nbsp;
                    <a href='/contract/{{$row->refund_contract}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a>&nbsp;
                    @if($row->refund_checked==0&&$row->refund_createuser!=Session::get('user_id'))
                      <a href='/refund/{{$row->refund_id}}/edit'><button type="button" class="btn btn-warning btn-sm">审核</button></a>&nbsp;
                    @else
                      <a href='#'><button type="button" class="btn btn-warning btn-sm" disabled>审核</button></a>&nbsp;
                    @endif
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
  linkActive('link-5');
  navbarActive('navbar-5');
  linkActive('departmentRefund');
</script>
@endsection
