@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item active">学生详情</li>
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
            <a href="/student/{{ $student->student_id }}/edit" class="btn btn-sm btn-primary mr-4">修改信息</a>
            @if($student->student_follower==Session::get('user_id'))
              <a href="/contract/create?student_id={{ $student->student_id }}"  target="_blank" class="btn btn-sm btn-warning float-right">续约合同</a>
            @else
              <button class="btn btn-sm btn-warning float-right" disabled>续约合同</button>
            @endif
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
                <div class="h4">来源 - {{ $student->student_source }}</div>
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
                <div class="h4">创建日期 - {{ date('Y-m-d', strtotime($student->student_createtime)) }}</div>
              </div>
              <div class="col-6">
                <div class="h4">负责人 - @if($student->user_name=="") 无 (公共) @else {{ $student->user_name }} @endif</div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <form action="/student/{{ $student->student_id }}/follower" method="post">
          @csrf
          <div class="card-body">
            <div class="row">
              <div class="col-3 text-center">
                <label class="form-control-label">负责人</label>
              </div>
              <div class="col-5">
                <select class="form-control form-control-sm" name="input1" data-toggle="select">
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
        <div class="card-header">
          <h5 class="h3 mb-0">学生备注</h5>
        </div>
        <form action="/student/{{ $student->student_id }}/remark" method="post">
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
      <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-3 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-badge mr-2"></i>课程安排</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>上课记录</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-3-tab" data-toggle="tab" href="#tabs-icons-text-3" role="tab" aria-controls="tabs-icons-text-3" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>剩余课时</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-4-tab" data-toggle="tab" href="#tabs-icons-text-4" role="tab" aria-controls="tabs-icons-text-4" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>学生合同</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>学生动态</a>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive">
              <table class="table align-items-center table-hover text-left table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th style='width:65px;'>序号</th>
                    <th style='width:185px;'>班级</th>
                    <th style='width:176px;'>教师</th>
                    <th style='width:55px;'>科目</th>
                    <th style='width:55px;'>年级</th>
                    <th style='width:95px;'>日期</th>
                    <th style='width:105px;'>时间</th>
                    <th style='width:120px;'>地点</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($schedules)==0)
                    <tr class="text-center"><td colspan="8">当前没有记录</td></tr>
                  @else
                    @foreach ($schedules as $schedule)
                      <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                        <td>{{ $loop->iteration }}</td>
                        @if($schedule->schedule_participant_type==0)
                          <td><span style="color:green;">一对一</span></td>
                        @else
                          <td><span style="color:red;">{{ $schedule->class_name }}</span></td>
                        @endif
                        <td>{{ $schedule->user_name }} ({{ $schedule->position_name }})</td>
                        <td>{{ $schedule->subject_name }}</td>
                        <td>{{ $schedule->grade_name }}</td>
                        <td>{{ $schedule->schedule_date }}</td>
                        <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                        <td>{{ $schedule->classroom_name }}</td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive">
              <table class="table align-items-center table-hover text-left table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th style='width:60px;'>序号</th>
                    <th style='width:145px;'>班级</th>
                    <th style='width:151px;'>教师</th>
                    <th style='width:55px;'>科目</th>
                    <th style='width:55px;'>年级</th>
                    <th style='width:95px;'>日期</th>
                    <th style='width:105px;'>时间</th>
                    <th style='width:100px;'>地点</th>
                    <th style='width:90px;'>操作</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($schedules)==0)
                    <tr class="text-center"><td colspan="8">当前没有记录</td></tr>
                  @else
                    @foreach ($attended_schedules as $schedule)
                      <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                        <td>{{ $loop->iteration }}</td>
                        @if($schedule->schedule_participant_type==0)
                          <td><span style="color:green;">一对一</span></td>
                        @else
                          <td><span style="color:red;">{{ $schedule->class_name }}</span></td>
                        @endif
                        <td>{{ $schedule->user_name }} ({{ $schedule->position_name }})</td>
                        <td>{{ $schedule->subject_name }}</td>
                        <td>{{ $schedule->grade_name }}</td>
                        <td>{{ $schedule->schedule_date }}</td>
                        <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                        <td>{{ $schedule->classroom_name }}</td>
                        <td><a href='/document/{{$schedule->schedule_document}}'><button type="button" class="btn btn-primary btn-sm">教案下载</button></a></td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive">
              <table class="table align-items-center table-hover text-left table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th style='width:60px;'>序号</th>
                    <th style='width:196px;'>课程</th>
                    <th style='width:150px;'>已用正常课时</th>
                    <th style='width:150px;'>已用赠送课时</th>
                    <th style='width:150px;'>剩余正常课时</th>
                    <th style='width:150px;'>剩余赠送课时</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($hours)==0)
                    <tr class="text-center"><td colspan="6">当前没有记录</td></tr>
                  @else
                    @foreach ($hours as $hour)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $hour->course_name }}</td>
                        <td>{{ $hour->hour_used }} 课时</td>
                        <td>{{ $hour->hour_used_free }} 课时</td>
                        <td>{{ $hour->hour_remain }} 课时</td>
                        <td>{{ $hour->hour_remain_free }} 课时</td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive">
              <table class="table align-items-center table-hover text-left table-bordered">
                <thead class="thead-light">
                  <tr>
                    <th style='width:70px;'>序号</th>
                    <th style='width:70px;'>类型</th>
                    <th style='width:120px;' class="text-right">合计课时</th>
                    <th style='width:120px;' class="text-right">实付金额</th>
                    <th style='width:150px;'>签约人</th>
                    <th style='width:100px;'>支付方式</th>
                    <th style='width:100px;'>购课日期</th>
                    <th style='width:126px;'>操作</th>
                    <th></th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($contracts)==0)
                    <tr class="text-center"><td colspan="6">当前没有记录</td></tr>
                  @else
                    @foreach ($contracts as $contract)
                      <tr>
                        <td>{{ $loop->iteration }}</td>
                        @if($contract->contract_type==0)
                          <td><span style="color:red;">首签</span></td>
                        @else
                          <td><span style="color:green;">续签</span></td>
                        @endif
                        <td class="text-right" title="{{ $contract->contract_total_hour }} 课时"><strong>{{ $contract->contract_total_hour }} 课时</strong></td>
                        <td class="text-right" title="{{ number_format($contract->contract_total_price, 1) }} 元"><strong>{{ number_format($contract->contract_total_price, 1) }} 元</strong></td>
                        <td title="{{ $contract->user_name }} ({{ $contract->position_name }})">{{ $contract->user_name }} ({{ $contract->position_name }})</td>
                        <td title="{{ $contract->contract_payment_method }}">{{ $contract->contract_payment_method }}</td>
                        <td title="{{ $contract->contract_date }}">{{ $contract->contract_date }}</td>
                        <td><a href='/contract/{{$contract->contract_id}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a></td>
                      </tr>
                    @endforeach
                  @endif
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">
          <div class="list-group list-group-flush" style="max-height:990px; overflow:auto;">
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
  linkActive('link-3');
  navbarActive('navbar-3');
</script>
@endsection
