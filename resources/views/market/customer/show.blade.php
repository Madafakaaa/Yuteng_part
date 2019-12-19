@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/customer">客户设置</a></li>
    <li class="breadcrumb-item active">客户详情</li>
@endsection

@section('content')
<div class="container-fluid mt--4">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <div class="card-header">
          <h3 class="mb-0">客户详情</h3>
        </div>
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">客户姓名</label>
                <input class="form-control" type="text" value="{{ $customer->customer_name }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">联系电话</label>
                <input class="form-control" type="text" value="{{ $customer->customer_phone }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">客户校区</label>
                <input class="form-control" type="text" value="{{ $customer->department_name }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">来源类型</label>
                <input class="form-control" type="text" value="{{ $customer->source_name }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">微信号</label>
                <input class="form-control" type="text" value="{{ $customer->customer_wechat }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">客户关系</label>
                <input class="form-control" type="text" value="{{ $customer->customer_relationship }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">意向课程</label>
                <input class="form-control" type="text" value="{{ $customer->course_name }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">负责人</label>
                <input class="form-control" type="text" value="{{ $customer->user_name }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">学生姓名</label>
                <input class="form-control" type="text" value="{{ $customer->customer_student_name }}" readonly>
              </div>
            </div>
            <div class="col-6">
              <div class="form-group">
                <label class="form-control-label">学生年级</label>
                <input class="form-control" type="text" value="{{ $customer->grade_name }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label">备注</label>
                <input class="form-control" type="text" value="{{ $customer->customer_remark }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <label class="form-control-label">添加时间</label>
                <input class="form-control" type="text" value="{{ $customer->customer_createtime }}" readonly>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-4">
              <a href="/customer/{{ $customer->customer_id }}/edit"><button class="btn btn-block btn-warning">修改</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-md-6 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <div class="card-header">
          <div class="row">
            <div class="col-10">
              <h3 class="mb-0">客户跟进动态</h3>
            </div>
            @if($customer->customer_conversed==0)
              <div class="col-2">
                <a class="btn btn-sm btn-neutral btn-round btn-icon btn-block" data-toggle="collapse" href="#collapse1" aria-expanded="false" aria-controls="collapse1">
                  <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                  <span class="btn-inner--text">添加动态</span>
                </a>
              </div>
            @else
              <div class="col-2">
                <a class="btn btn-sm btn-neutral btn-round btn-icon btn-block" disabled>
                  <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                  <span class="btn-inner--text">已签约</span>
                </a>
              </div>
            @endif
          </div>
        </div>
        <div class="collapse" id="collapse1">
          <form action="/customer/{{ $customer->customer_id }}/record" method="post" id="form1" name="form1">
            @csrf
            <div class="card-header mb-0 pb-0">
              <div class="row">
                <div class="col-6 mb-3">
                  <div class="form-group mb-0 pb-0">
                    <label class="form-control-label">跟进人</label>
                    <select class="form-control" name="input1" data-toggle="select" required>
                      <option value=''>请选择跟进人...</option>
                      @foreach ($users as $user)
                        <option value="{{ $user->user_id }}" @if($customer->customer_follower==$user->user_id) selected @endif>{{ $user->user_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
                <div class="col-6 mb-3">
                  <div class="form-group mb-0 pb-0">
                    <label class="form-control-label">跟进状态</label>
                    <select class="form-control" name="input2" data-toggle="select" required>
                      <option value='0' selected>跟进中</option>
                      <option value='1'>签约成功</option>
                    </select>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-12">
                  <label class="form-control-label">跟进记录</label>
                  <div class="form-group mb-3">
                    <textarea class="form-control" name="input3" placeholder="跟进记录... " autocomplete='off' maxlength="255" required></textarea>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-2">
                  <div class="form-group mb-3">
                    <input type="submit" class="btn btn-primary btn-block" value="保存">
                  </div>
                </div>
              </div>
            </div>
          </form>
        </div>
        <!-- Card body -->
        <div class="card-body">
          <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
            @foreach ($customer_follow_records as $customer_follow_record)
              <div class="timeline-block">
                @if($customer_follow_record->customer_follow_record_conversed==0)
                  <span class="timeline-step badge-info">
                    <i class="ni ni-chat-round"></i>
                  </span>
                @else
                  <span class="timeline-step badge-success">
                    <i class="ni ni-check-bold"></i>
                  </span>
                @endif
                <div class="timeline-content">
                  <small class="text-muted font-weight-bold">{{ $customer_follow_record->customer_follow_record_createtime }}</small>
                  <h5 class=" mt-3 mb-0">
                    跟进人：{{ $customer_follow_record->user_name }}
                  </h5>
                  <p class=" text-sm mt-1 mb-0">
                    {{ $customer_follow_record->customer_follow_record_remark }}
                  </p>
                </div>
              </div>
            @endforeach
            <div class="timeline-block">
              <span class="timeline-step badge-success">
                <i class="ni ni-circle-08"></i>
              </span>
              <div class="timeline-content">
                <small class="text-muted font-weight-bold">{{ $customer->customer_createtime }}</small>
                <h5 class=" mt-3 mb-0">新客户创建</h5>
              </div>
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
  linkActive('link-2');
  navbarActive('navbar-2');
  linkActive('customer');
</script>
@endsection
