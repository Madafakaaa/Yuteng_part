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
          <h6 class="h2 text-white d-inline-block mb-0">补缴合同费用</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">招生中心</li>
              <li class="breadcrumb-item active">补缴合同费用</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="card main_card" style="display:none">
        <form action="/market/contract/edit/{{ $contract->contract_id }}" method="post">
          @csrf
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">学生</label>
              </div>
              <div class="col-6 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $contract->student_name }}" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">校区</label>
              </div>
              <div class="col-6 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $contract->department_name }}" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">应付费用</label>
              </div>
              <div class="col-6 px-2 mb-2">
                <input class="form-control form-control-sm" value="{{ $contract->contract_total_price }}" readonly>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>实付费用</label>
              </div>
              <div class="col-6 px-2 mb-2">
                <input type="number" class="form-control form-control-sm" value="{{ $contract->contract_paid_price }}" name="input1" autocomplete='off' required min="0.00" max="{{ $contract->contract_total_price }}" step="0.01">
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label"><span style="color:red">*</span>合同备注</label>
              </div>
              <div class="col-6 px-2 mb-2">
                <textarea class="form-control" rows="3" resize="none" maxlength="140" name="input2">{{ $contract->contract_remark }}</textarea>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)"><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-6 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-3 col-md-5 col-sm-12">
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
  linkActive('link-market');
  navbarActive('navbar-market');
</script>
@endsection
