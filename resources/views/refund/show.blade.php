@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">退款管理</li>
    <li class="breadcrumb-item active">校区管理</li>
    <li class="breadcrumb-item"><a href="/refund">退款申请</a></li>
    <li class="breadcrumb-item active">退款详情</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-8 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <div class="card-header">
          <h4 class="mb-0">退款详情</h4>
        </div>
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">学生</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->student_name }}" readonly>
            </div>
            <div class="col-2">
              <label class="form-control-label">校区</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->department_name }}" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">课程</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->course_name }}" readonly>
            </div>
            <div class="col-2">
              <label class="form-control-label">类型</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->course_type }}" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">课时单价</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ number_format($refund->refund_original_unit_price, 2) }} 元/课时" readonly>
            </div>
            <div class="col-2">
              <label class="form-control-label">实付金额</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ number_format($refund->refund_actual_total_price, 2) }} 元" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">可退金额</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ number_format($refund->refund_amount, 2) }} 元" readonly>
            </div>
            <div class="col-2">
              <label class="form-control-label">违约金</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ number_format($refund->refund_fine, 2) }} 元" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">扣正常课时</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->refund_remain_hour }} 课时" readonly>
            </div>
            <div class="col-2">
              <label class="form-control-label">扣赠送课时</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->refund_free_hour }} 课时" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label"><span style="color:red;">扣课时合计</span></label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->refund_total_hour }} 课时" readonly>
            </div>
            <div class="col-2">
              <label class="form-control-label"><span style="color:red;">实退金额</span></label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ number_format($refund->refund_actual_amount, 2) }} 元" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">退费原因</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->refund_reason }}" readonly>
            </div>
            <div class="col-2">
              <label class="form-control-label">退款方式</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->refund_payment_method }}" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">退费日期</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->refund_date }}" readonly>
            </div>
            <div class="col-2">
              <label class="form-control-label">退费用户</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
              <input class="form-control form-control-sm" value="{{ $refund->user_name }}" readonly>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">备注</label>
            </div>
            <div class="col-10 pl-2 pr-2 mb-2">
              <textarea class="form-control" name="input7" rows="3" resize="none" maxlength="255" readonly>{{ $refund->refund_remark }}</textarea>
            </div>
          </div>
          <div class="row">
            <div class="col-2">
              <label class="form-control-label">审核状态</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
            @if($refund->refund_checked==0)
              <input class="form-control form-control-sm" value="未审核" readonly>
            @else
              <input class="form-control form-control-sm" value="已审核" readonly>
            @endif
            </div>
            <div class="col-2">
              <label class="form-control-label">审核用户</label>
            </div>
            <div class="col-4 pl-2 pr-2 mb-2">
            @if($refund->refund_checked==0)
              <input class="form-control form-control-sm" value="" readonly>
            @else
              <input class="form-control form-control-sm" value="{{ $refund_checked_user_name }}" readonly>
            @endif
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-12">
              <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-1');
  navbarActive('navbar-1-1');
  linkActive('refund');
</script>
@endsection
