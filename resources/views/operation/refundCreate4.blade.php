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
          <h6 class="h2 text-white d-inline-block mb-0">学生退费</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item active">学生退费</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">选择退费学生</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">选择退费课程</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">填写退费信息</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">退费信息确认</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/operation/refund/store" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">四、退费信息确认</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">学生</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $student->student_name }}" readonly>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">校区</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $student->department_name }}" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">合同</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $hour->contract_id }}" readonly>
              </div>
              <div class="col-2 pt-1 text-left">
                <label class="form-control-label"><a href='/contract/{{$hour->contract_id}}' target="_blank">查看</a></label>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">退费课程</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $hour->course_name }}" readonly>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">课时单价</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{number_format($hour->contract_course_original_unit_price, 2) }} 元" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">购买课时</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $hour->contract_course_original_hour }} 课时" readonly>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">已上课时</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $hour->hour_used + $hour->hour_used_free }} 课时" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:blue">扣除赠送课时</span></label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $hour->contract_course_free_hour }} 课时" readonly>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:blue">扣除剩余课时</span></label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $hour->hour_remain }} 课时" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">实付金额</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ number_format($hour->contract_course_total_price, 2) }} 元" readonly>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">可退金额</span></label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ number_format($refund_amount, 2) }} 元" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">违约金</span></label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ number_format($refund_fine, 2) }} 元" readonly>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">退款原因</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $refund_reason }}" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">退款方式</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $refund_payment_method }}" readonly>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">退款日期</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $refund_date }}" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">备注</label>
              </div>
              <div class="col-10 px-2 mb-2">
                <textarea class="form-control" name="input7" rows="3" resize="none" maxlength="255" readonly>{{ $refund_remark }}</textarea>
              </div>
            </div>
            <div class="row text-right">
              <div class="col-6"></div>
              <div class="col-2">
                <label class="form-control-label"><span style="color:red">实退金额</span></label>
              </div>
              <div class="col-4 px-2 mb-2">
                <h1>{{ number_format($refund_actual_amount, 2) }} 元</h1>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">上一步</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
                <input type="hidden" name="input1" value="{{ $student->student_id }}">
                <input type="hidden" name="input2" value="{{ $hour->hour_id }}">
                <input type="hidden" name="input3" value="{{ $refund_fine }}">
                <input type="hidden" name="input4" value="{{ $refund_reason }}">
                <input type="hidden" name="input5" value="{{ $refund_payment_method }}">
                <input type="hidden" name="input6" value="{{ $refund_date }}">
                <input type="hidden" name="input7" value="{{ $refund_remark }}">
                <input type="submit" class="btn btn-warning btn-block" value="提交">
              </div>
            </div>
          </div>
        <form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationRefundCreate');
</script>
@endsection
