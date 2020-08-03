@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">用户详情</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">用户详情</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card card-profile">
        <img src="{{ asset(_ASSETS_.'/img/theme/bg1.jpg') }}" alt="Image placeholder" class="card-img-top">
        <div class="row justify-content-center">
          <div class="col-lg-3 order-lg-2">
            <div class="card-profile-image">
              <a href="#">
                <img src="{{ asset(_ASSETS_.'/avatar/'.$user->user_photo) }}" class="rounded-circle">
              </a>
            </div>
          </div>
        </div>
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
            <a href="/user/edit?id={{encode($user->user_id,'user_id')}}" class="btn btn-sm btn-primary mr-4">修改信息</a>
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="text-center pb-2">
            <h1>{{ $user->user_name }}
              @if($user->user_gender=="男")
                <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
              @else
                <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
              @endif
            </h1>
            <div class="h5 font-weight-300">{{ $user->user_id }}</div>
            <hr>
            <div class="h5 mt-4">
              <i class="ni business_briefcase-24 mr-2"></i>{{ $user->department_name }}
            </div>
            <div class="h5">
              <i class="ni business_briefcase-24 mr-2"></i>{{ $user->section_name }} - {{ $user->position_name }}
            </div>
            <div class="h5">
              <i class="ni business_briefcase-24 mr-2"></i>手机 - {{ $user->user_phone }}
            </div>
            <div class="h5">
              <i class="ni business_briefcase-24 mr-2"></i>微信 - {{ $user->user_wechat }}
            </div>
            <div class="h5">
              <i class="ni business_briefcase-24 mr-2"></i>注册日期 - {{ $user->user_entry_date }}
            </div>
            <hr>
          </div>
          <div class="card-profile-stats d-flex justify-content-center">
            <div>
              <span class="heading">{{ $dashboard['schedule_num'] }} 节</span>
              <span class="description">本月课程</span>
            </div>
            <div>
              <span class="heading">{{ $dashboard['attended_schedule_num'] }} 节</span>
              <span class="description">本月已上</span>
            </div>
            <div>
              <span class="heading">{{ $dashboard['contract_num'] }}</span>
              <span class="description">本月签约数</span>
            </div>
          </div>
        </div>
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
            <a class="nav-link mb-3" id="student-tab" data-toggle="tab" href="#student-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>负责学生</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="class-tab" data-toggle="tab" href="#class-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>负责班级</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="contract-tab" data-toggle="tab" href="#contract-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>签约合同</a>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="schedule-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:100px;">班级</th>
                    <th style="width:100px;">教师</th>
                    <th style="width:30px;">科目</th>
                    <th style="width:30px;">年级</th>
                    <th style="width:60px;">日期</th>
                    <th style="width:60px;">时间</th>
                    <th style="width:60px;">地点</th>
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
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:100px;">班级</th>
                    <th style="width:100px;">教师</th>
                    <th style="width:30px;">科目</th>
                    <th style="width:30px;">年级</th>
                    <th style="width:60px;">日期</th>
                    <th style="width:60px;">时间</th>
                    <th style="width:60px;">地点</th>
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

        <div class="tab-pane fade" id="student-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>学生</th>
                    <th>校区</th>
                    <th>年级</th>
                    <th>课程顾问</th>
                    <th>班主任</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($students as $student)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $student->student_name }}</td>
                      <td>{{ $student->department_name }}</td>
                      <td>{{ $student->grade_name }}</td>
                      @if($student->consultant_name=="")
                      <td><span style="color:red;">无</span></td>
                      @else
                      <td>{{ $student->consultant_name }} ({{ $student->consultant_position_name }})</td>
                      @endif
                      @if($student->class_adviser_name=="")
                      <td><span style="color:red;">无</span></td>
                      @else
                      <td>{{ $student->class_adviser_name }} ({{ $student->class_adviser_position_name }})</td>
                      @endif
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="class-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>班级</th>
                    <th>科目</th>
                    <th>人数</th>
                    <th>已排课程</th>
                    <th>已上课程</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($classes as $class)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td>{{ $class->class_name }}</td>
                      <td>{{ $class->subject_name }}</td>
                      <td>{{ $class->class_current_num }} / {{ $class->class_max_num }} 人</td>
                      <td>{{ $class->class_schedule_num }} 节</td>
                      <td>{{ $class->class_attended_num }} 节</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="contract-card" role="tabpanel">
          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th>序号</th>
                    <th>类型</th>
                    <th>合计课时</th>
                    <th>实付金额</th>
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
                      <td><strong>{{ $contract->contract_total_hour }} 课时</strong></td>
                      <td><strong>{{ number_format($contract->contract_total_price, 1) }} 元</strong></td>
                      <td>{{ $contract->contract_payment_method }}</td>
                      <td>{{ $contract->contract_date }}</td>
                      <td><a href="/contract?id={{encode($contract->contract_id, 'contract_id')}}" target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a></td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
@endsection
