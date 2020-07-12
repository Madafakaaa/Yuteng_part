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
          <h6 class="h2 text-white d-inline-block mb-0">学生详情</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">学生详情</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card card-profile">
        <img src="{{ asset(_ASSETS_.'/img/theme/bg1.jpg') }}" alt="Image placeholder" class="card-img-top">
        <div class="row justify-content-center">
          <div class="col-lg-3 order-lg-2">
            <div class="card-profile-image">
              <a href="#">
                <img src="{{ asset(_ASSETS_.'/avatar/student.png') }}" class="rounded-circle">
              </a>
            </div>
          </div>
        </div>
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
            <a href="/student/edit?id={{encode($student->student_id,'student_id')}}" class="btn btn-sm btn-primary mr-4">修改信息</a>
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
                <div class="h4">大区 - @if($student->school_name=="") 无 @else {{ $student->school_name }} @endif</div>
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
            </div>
            <hr>
            <div class="row text-left ml-2">
              <div class="col-12">
                <div class="h4">课程顾问 - @if($student->consultant_name=="") <span style="color:red;">无</span> @else {{ $student->consultant_name }}({{ $student->consultant_position_name }}) @endif</div>
              </div>
              <div class="col-6">
                <div class="h4">
                  跟进优先级 -
                  @if($student->student_follow_level==1)
                    <span style="color:#8B4513;">低</span>
                  @elseif($student->student_follow_level==2)
                    <span style="color:#FF4500;">中</span>
                  @elseif($student->student_follow_level==3)
                    <span style="color:#FF0000;">高</span>
                  @endif
                </div>
              </div>
              <div class="col-6">
                <div class="h4">跟进次数 - {{ $student->student_follow_num }}</div>
              </div>
              <div class="col-6">
                <div class="h4">上次跟进 - {{ $student->student_last_follow_date }}</div>
              </div>
              <div class="col-6">
                <div class="h4">签约次数 - {{ $student->student_contract_num }}</div>
              </div>
            </div>
            <hr>
            <div class="row text-left ml-2">
              <div class="col-12">
                <div class="h4">班主任 - @if($student->class_adviser_name=="") <span style="color:red;">无</span> @else {{ $student->class_adviser_name }}({{ $student->class_adviser_position_name }}) @endif</div>
              </div>
            </div>
            <hr>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">学生备注</h5>
        </div>
        <form action="/student/remark?id={{encode($student->student_id,'student_id')}}" method="post" onsubmit="submitButtonDisable('submitButton1')">
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
                <input type="submit" id="submitButton1" class="btn btn-sm btn-warning btn-block" value="修改备注">
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
            <a class="nav-link mb-3 active" id="schedule-tab" data-toggle="tab" href="#schedule-card" role="tab" aria-selected="true"><i class="ni ni-badge mr-2"></i>课程安排</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="attended-schedule-tab" data-toggle="tab" href="#attended-schedule-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>上课记录</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="class-tab" data-toggle="tab" href="#class-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>所在班级</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="hour-tab" data-toggle="tab" href="#hour-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>剩余课时</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="contract-tab" data-toggle="tab" href="#contract-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>学生合同</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="record-tab" data-toggle="tab" href="#record-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>学生动态</a>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="schedule-card" role="tabpanel">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>班级</th>
                    <th>教师</th>
                    <th>科目</th>
                    <th>年级</th>
                    <th>日期</th>
                    <th>时间</th>
                    <th>地点</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($schedules as $schedule)
                    <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                      <td>{{ $loop->iteration }}</td>
                      <td><span style="color:red;">{{ $schedule->class_name }}</span></td>
                      <td>{{ $schedule->user_name }} ({{ $schedule->position_name }})</td>
                      <td>{{ $schedule->subject_name }}</td>
                      <td>{{ $schedule->grade_name }}</td>
                      <td>{{ $schedule->schedule_date }}</td>
                      <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                      <td>{{ $schedule->classroom_name }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="attended-schedule-card" role="tabpanel">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>班级</th>
                    <th>教师</th>
                    <th>科目</th>
                    <th>年级</th>
                    <th>日期</th>
                    <th>时间</th>
                    <th>地点</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($attended_schedules as $schedule)
                    <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                      <td>{{ $loop->iteration }}</td>
                      <td><span style="color:red;">{{ $schedule->class_name }}</span></td>
                      <td>{{ $schedule->user_name }} ({{ $schedule->position_name }})</td>
                      <td>{{ $schedule->subject_name }}</td>
                      <td>{{ $schedule->grade_name }}</td>
                      <td>{{ $schedule->schedule_date }}</td>
                      <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                      <td>{{ $schedule->classroom_name }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="class-card" role="tabpanel">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>班级</th>
                    <th>使用课程</th>
                    <th>教师</th>
                    <th>科目</th>
                    <th>人数</th>
                    <th>已排课程</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($classes as $class)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $class->class_name }}</td>
                      <td>{{ $class->course_name }}</td>
                      <td>{{ $class->user_name }}</td>
                      <td>{{ $class->subject_name }}</td>
                      <td>{{ $class->class_current_num }} / {{ $class->class_max_num }} 人</td>
                      <td>{{ $class->class_schedule_num }} 节</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="hour-card" role="tabpanel">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>课程</th>
                    <th>剩余课时</th>
                    <th>已用课时</th>
                    <th>课时单价</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($hours as $hour)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $hour->course_name }}</td>
                      <td>{{ $hour->hour_remain }} 课时</td>
                      <td>{{ $hour->hour_used }} 课时</td>
                      <td>{{ $hour->hour_average_price }} 元/课时</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="contract-card" role="tabpanel">
          <div class="card main_card mb-4" style="display:none">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>类型</th>
                    <th>合计课时</th>
                    <th>实付金额</th>
                    <th>签约人</th>
                    <th>支付方式</th>
                    <th>购课日期</th>
                    <th>操作</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($contracts as $contract)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      @if($contract->contract_type==0)
                        <td><span style="color:red;">首签</span></td>
                      @else
                        <td><span style="color:green;">续签</span></td>
                      @endif
                      <td title="{{ $contract->contract_total_hour }} 课时"><strong>{{ $contract->contract_total_hour }} 课时</strong></td>
                      <td title="{{ number_format($contract->contract_total_price, 1) }} 元"><strong>{{ number_format($contract->contract_total_price, 1) }} 元</strong></td>
                      <td title="{{ $contract->user_name }} ({{ $contract->position_name }})">{{ $contract->user_name }} ({{ $contract->position_name }})</td>
                      <td title="{{ $contract->contract_payment_method }}">{{ $contract->contract_payment_method }}</td>
                      <td title="{{ $contract->contract_date }}">{{ $contract->contract_date }}</td>
                      <td><a href="/contract?id={{encode($contract->contract_id, 'contract_id')}}" target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="record-card" role="tabpanel">
          <div class="card">
            <div class="card-header">
              <h5 class="h3 mb-0">添加跟进记录</h5>
            </div>
            <form action="/student/record?id={{encode($student->student_id,'student_id')}}" method="post" onsubmit="submitButtonDisable('submitButton2')">
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
                    <input type="submit" id="submitButton2" class="btn btn-sm btn-warning btn-block" value="保存">
                  </div>
                </div>
              </div>
            </form>
          </div>
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
@endsection
