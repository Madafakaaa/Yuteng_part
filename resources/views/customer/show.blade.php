@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">招生中心</li>
    <li class="breadcrumb-item active">客户详情</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card card-profile">
        <img src="../../assets/img/theme/img-1-1000x600.jpg" alt="Image placeholder" class="card-img-top">
        <div class="row justify-content-center">
          <div class="col-lg-3 order-lg-2">
            <div class="card-profile-image">
              <a href="#">
                <img src="../../assets/img/portrait/user_1.gif" class="rounded-circle">
              </a>
            </div>
          </div>
        </div>
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
            <a href="/customer/{{ $student->student_id }}/edit" class="btn btn-sm btn-primary mr-4">修改信息</a>
            <a href="/contract/create?student_id={{ $student->student_id }}"  target="_blank" class="btn btn-sm btn-warning float-right">签约合同</a>
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="text-center pb-2">
            <h1>{{ $student->student_name }}</h1>
            <div class="h5 font-weight-300">{{ $student->student_id }}</div>
            <hr>
            <div class="row text-left ml-2">
              <div class="col-6">
                <div class="h4">性别 - {{ $student->student_gender }}</div>
              </div>
              <div class="col-6">
                <div class="h4">校区 - {{ $student->department_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">年级 - {{ $student->grade_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">学校 - @if($student->school_name=="") 无 @else {{ $student->school_name }} @endif</div>
              </div>
              <div class="col-6">
                <div class="h4">家长 - {{ $student->student_guardian_relationship }} {{ $student->student_guardian }}</div>
              </div>
              <div class="col-6">
                <div class="h4">电话 - {{ $student->student_phone }}</div>
              </div>
              <div class="col-6">
                <div class="h4">微信 - {{ $student->student_wechat }}</div>
              </div>
              <div class="col-6">
                <div class="h4">生日 - {{ $student->student_birthday }}</div>
              </div>
              <div class="col-6">
                <div class="h4">跟进人 - @if($student->user_name=="") 无 (公共) @else {{ $student->user_name }} @endif</div>
              </div>
              <div class="col-6">
                <div class="h4">上次跟进 - {{ $student->student_last_follow_date }}</div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col">
              <div class="card-profile-stats d-flex justify-content-center p-0">
                <div>
                  <span class="heading">{{ $student->student_follow_num }}</span>
                  <span class="description">跟进次数</span>
                </div>
                <div>
                  <span class="heading">
                    @if($student->student_follow_level==1)
                      <span>低</span>
                    @elseif($student->student_follow_level==2)
                      <span style="color:#8B4513;">中</span>
                    @elseif($student->student_follow_level==3)
                      <span style="color:#FF4500;">高</span>
                    @else
                      <span style="color:#FF0000;">重点*</span>
                    @endif
                  </span>
                  <span class="description">跟进优先级</span>
                </div>
                <div>
                  <span class="heading">
                    @if($student->student_customer_status==0)
                      <span style="color:red;">未签约</span>
                    @else
                      <span style="color:green;">已签约</span>
                    @endif
                  </span>
                  <span class="description">签约状态</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <form action="/customer/{{ $student->student_id }}/follower" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-3 text-center">
                <label class="form-control-label">跟进人</label>
              </div>
              <div class="col-5">
                <select class="form-control form-control-sm" name="input1" data-toggle="select">
                  <option value=''>无负责人（公共）</option>
                  @foreach ($users as $user)
                    <option value="{{ $user->user_id }}" @if($user->user_id==$student->student_follower) selected @endif>{{ $user->user_name }}</option>
                  @endforeach
                </select>
              </div>
              <div class="col-4">
                <input type="submit" class="btn btn-sm btn-warning btn-block" value="修改">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="card">
        <form action="/customer/{{ $student->student_id }}/followLevel" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-3 text-center">
                <label class="form-control-label">优先级</label>
              </div>
              <div class="col-5">
                <select class="form-control form-control-sm" name="input1" data-toggle="select" required>
                  <option value='1' @if($student->student_follow_level=="1") selected @endif>低</option>
                  <option value='2' @if($student->student_follow_level=="2") selected @endif>中</option>
                  <option value='3' @if($student->student_follow_level=="3") selected @endif>高</option>
                  <option value='4' @if($student->student_follow_level=="4") selected @endif>重点</option>
                </select>
              </div>
              <div class="col-4">
                <input type="submit" class="btn btn-sm btn-warning btn-block" value="修改">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">学生备注</h5>
        </div>
        <form action="/customer/{{ $student->student_id }}/remark" method="post">
          @csrf
          <div class="card-body p-3">
            <div class="row">
              <div class="col-12">
                <div class="form-group mb-2">
                  <textarea class="form-control" name="input1" rows="6" resize="none" spellcheck="false" autocomplete='off' maxlength="140" required>{{ $student->student_remark }}</textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-8"></div>
              <div class="col-4">
                <input type="submit" class="btn btn-sm btn-warning btn-block" value="修改备注">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
    <div class="col-lg-8 col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">添加跟进记录</h5>
        </div>
        <form action="/customer/{{ $student->student_id }}/record" method="post">
          @csrf
          <div class="card-body p-3">
            <div class="row">
              <div class="col-12">
                <div class="form-group mb-2">
                  <textarea class="form-control" name="input1" rows="2" resize="none" spellcheck="false" autocomplete='off' maxlength="140" placeholder="请输入跟进内容..." required></textarea>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-center">
                <label class="form-control-label">跟进方式</label>
              </div>
              <div class="col-2 px-2 mb-2">
                <div class="form-group mb-1">
                  <select class="form-control form-control-sm" name="input2" data-toggle="select" required>
                    <option value=''>请选择...</option>
                    <option value='电话'>电话</option>
                    <option value='上门'>上门</option>
                    <option value='微信'>微信</option>
                    <option value='短信'>短信</option>
                    <option value='其它'>其它</option>
                  </select>
                </div>
              </div>
              <div class="col-2 text-center">
                <label class="form-control-label">跟进日期</label>
              </div>
              <div class="col-2 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm datepicker" name="input3" type="text" autocomplete="off" value="{{ date('Y-m-d') }}" required>
                </div>
              </div>
              <div class="col-1"></div>
              <div class="col-3">
                <input type="submit" class="btn btn-sm btn-warning btn-block" value="保存">
              </div>
            </div>
          </div>
        </form>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">学生动态</h5>
        </div>
        <div class="card-body p-0">
          <!-- List group -->
          <div class="list-group list-group-flush" style="max-height:870px; overflow:auto;">
            @foreach ($student_records as $student_record)
            <a href="#" class="list-group-item list-group-item-action flex-column align-items-start py-4 px-4">
              <div class="d-flex w-100 justify-content-between">
                <div>
                  <div class="d-flex w-100 align-items-center">
                    <img src="../assets/img/theme/team-1.jpg" alt="Image placeholder" class="avatar avatar-xs mr-2" />
                    <h5 class="mb-1">{{ $student_record->user_name }}</h5>
                  </div>
                </div>
                <small>{{ $student_record->student_record_createtime }}</small>
              </div>
              <h4 class="mt-3 mb-1">{{ $student_record->student_record_type }}</h4>
              <p class="text-sm mb-0">{!! $student_record->student_record_content !!}</p>
            </a>
            @endforeach
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
</script>
@endsection
