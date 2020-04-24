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
              <li class="breadcrumb-item active">招生中心</li>
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
          <span class="badge badge-pill badge-info">填写退费信息</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #fdd1da;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-danger">退费信息确认</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/market/refund/create3" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">二、填写退费信息</h4>
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
                <label class="form-control-label"><span style="color:blue">赠送课时</span></label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $hour->contract_course_free_hour }} 课时" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">已上课时</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $hour->hour_used + $hour->hour_used_free }} 课时" readonly>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:blue">剩余课时</span></label>
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
                <label class="form-control-label"><span style="color:red">*</span>违约金</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm" type="number" value="0" autocomplete='off' min="0.00" max="{{ $refund_amount }}" step="0.01" name="input3" required>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>退款原因</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <select class="form-control form-control-sm" name="input4" data-toggle="select" required>
                  <option value=''>请选择退款原因...</option>
                  @foreach ($refund_reasons as $refund_reason)
                    <option value="{{ $refund_reason->refund_reason_name }}">{{ $refund_reason->refund_reason_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>退款方式</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <select class="form-control form-control-sm" name="input5" data-toggle="select" required>
                  <option value=''>请选择退款方式...</option>
                  @foreach ($payment_methods as $payment_method)
                    <option value="{{ $payment_method->payment_method_name }}">{{ $payment_method->payment_method_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>退款时间</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <input class="form-control form-control-sm datepicker" name="input6" type="text" value="{{ date('Y-m-d') }}" required>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">备注</label>
              </div>
              <div class="col-10 px-2 mb-2">
                <textarea class="form-control" name="input7" rows="3" resize="none" placeholder="备注..." maxlength="255"></textarea>
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
                <input type="submit" class="btn btn-primary btn-block" value="下一步">
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
  linkActive('link-market');
  navbarActive('navbar-market');
  linkActive('marketRefundCreate');
</script>
@endsection
