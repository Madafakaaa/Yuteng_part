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
    <div class="col-lg-3 col-md-6 col-sm-12">
      <div class="card card-profile mt-6">
        <div class="row justify-content-center">
          <div class="col-lg-2 order-lg-2">
            <div class="card-profile-image">
              <img src="{{ asset(_ASSETS_.'/avatar/'.$user->user_photo) }}" class="rounded-circle" style="height:100px;">
            </div>
          </div>
        </div>
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
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
            <div class="h5">
              {{ $user->department_name }}
            </div>
            <div class="h5">
              {{ $user->section_name }} - {{ $user->position_name }}
            </div>
            <div class="h5">
              手机 - {{ $user->user_phone }}
            </div>
            <div class="h5">
              微信 - {{ $user->user_wechat }}
            </div>
            <div class="h5">
              注册日期 - {{ $user->user_entry_date }}
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-9 col-md-6 col-sm-12">
      <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-3 active" id="schedule-tab" data-toggle="tab" href="#schedule-card" role="tab" aria-selected="true">课程安排</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="attended-schedule-tab" data-toggle="tab" href="#attended-schedule-card" role="tab" aria-selected="false">上课记录</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="student-tab" data-toggle="tab" href="#student-card" role="tab" aria-selected="false">负责学生</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="class-tab" data-toggle="tab" href="#class-card" role="tab" aria-selected="false">负责班级</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="contract-tab" data-toggle="tab" href="#contract-card" role="tab" aria-selected="false">签约合同</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="record-tab" data-toggle="tab" href="#record-card" role="tab" aria-selected="false">员工动态</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="archive-tab" data-toggle="tab" href="#archive-card" role="tab" aria-selected="false">员工档案</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="edit-tab" data-toggle="tab" href="#edit-card" role="tab" aria-selected="false">修改信息</a>
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
                      <td><a href="/class?id={{encode($schedule->class_id,'class_id')}}">{{ $schedule->class_name }}</a></span></td>
                      <td><a href="/user?id={{encode($schedule->user_id,'user_id')}}">{{ $schedule->user_name }}</a> ({{ $schedule->position_name }})</td>
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
                      <td><a href="/class?id={{encode($schedule->class_id,'class_id')}}">{{ $schedule->class_name }}</a></span></td>
                      <td><a href="/user?id={{encode($schedule->user_id,'user_id')}}">{{ $schedule->user_name }}</a> ({{ $schedule->position_name }})</td>
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
                      <td><a href="/student?id={{encode($student->student_id,'student_id')}}">{{ $student->student_name }}</a></td>
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
                      <td><a href="/class?id={{encode($class->class_id,'class_id')}}">{{ $class->class_name }}</a></td>
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

        <div class="tab-pane fade" id="record-card" role="tabpanel">
          <div class="card">
            <form action="/user/record" method="post" onsubmit="submitButtonDisable('submitButton1')">
              @csrf
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-2">
                      <textarea class="form-control" name="user_record_content" rows="2" resize="none" spellcheck="false" autocomplete='off' maxlength="255" placeholder="添加动态..." required></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-9">
                  </div>
                  <div class="col-3">
                    <input type="hidden" name="user_id" value="{{$user->user_id}}">
                    <input type="submit" id="submitButton1" class="btn btn-sm btn-warning btn-block" value="添加">
                  </div>
                </div>
              </div>
            </form>
            <hr>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed" style="max-height:400px; overflow:auto;">
                @foreach($user_records as $user_record)
                  <div class="timeline-block">
                    <span class="timeline-step badge-info">
                      <i class="fa fa-bars"></i>
                    </span>
                    <div class="timeline-content">
                      <small class="text-muted font-weight-bold">{{$user_record->user_record_createtime}} | 操作用户: {{$user_record->user_name}}</small>
                      <h5 class="mt-3 mb-0">{{$user_record->user_record_type}}</h5>
                      <p class="text-sm mt-1 mb-0">{{$user_record->user_record_content}}</p>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="archive-card" role="tabpanel">
          <form action="/user/archive" method="post" id="form1" name="form1" enctype="multipart/form-data" onsubmit="submitButtonDisable('submitButton2')">
            @csrf
            <div class="card mb-4">
              <div class="card-body pb-0 pt-4">
                <div class="row">
                  <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group text-center">
                      <label class="form-control-label">档案名称<span style="color:red">*</span></label>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                      <input class="form-control form-control-sm" type="text" name="archive_name" placeholder="请输入档案名称... " autocomplete='off' maxlength="30" required>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                      <div class="input-group">
                        <input id='location' class="form-control form-control-sm" disabled aria-describedby="button-addon">
                        <div class="input-group-append">
                          <input type="button" id="i-check" value="浏览文件" class="btn btn-outline-primary btn-sm" onClick="$('#i-file').click();" style="margin:0;" id="button-addon">
                          <input type="file" name='file' id='i-file' onChange="$('#location').val($('#i-file').val());" style="display: none" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 col-md-6 col-sm-12">
                    <input type="hidden" name="archive_user" value="{{$user->user_id}}">
                    <input type="submit" id="submitButton2" class="btn btn-warning btn-block btn-sm" value="上传">
                  </div>
                </div>
              </div>
            </div>
          </form>

          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style='width:50px;'>序号</th>
                    <th style='width:320px;'>档案</th>
                    <th style='width:120px;'>上传日期</th>
                    <th style='width:120px;'>操作管理</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($archives as $archive)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="/files/archive/{{$archive->archive_path}}" target="_blank">{{ $archive->archive_name }}</a></td>
                      <td>{{ date('Y-m-d', strtotime($archive->archive_createtime)) }}</td>
                      <td>
                        <a href='/humanResource/archive/download?id={{encode($archive->archive_id, 'archive_id')}}'><button type="button" class="btn btn-primary btn-sm">文件下载</button></a>
                        <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/humanResource/archive/delete?id={{encode($archive->archive_id, 'archive_id')}}', '确认删除该档案？')">删除</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div class="tab-pane fade" id="edit-card" role="tabpanel">
          <div class="card mb-4">
            <form action="/user/update?id={{encode($user->user_id,'user_id')}}" method="post">
              @csrf
              <!-- Card body -->
              <div class="card-body">
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">账号</label>
                      <input class="form-control" type="text" value="{{ $user->user_id }}" readonly>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">姓名<span style="color:red">*</span></label>
                      <input class="form-control" type="text" name="input1" value="{{ $user->user_name }}" autocomplete='off' required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">性别<span style="color:red">*</span></label>
                      <select class="form-control" name="input2" data-toggle="select" required>
                        <option value=''>请选择性别...</option>
                        <option value='男' @if($user->user_gender=="男") selected @endif>男</option>
                        <option value='女' @if($user->user_gender=="女") selected @endif>女</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">校区<span style="color:red">*</span></label>
                      <select class="form-control" name="input3" data-toggle="select" required>
                        <option value=''>请选择校区...</option>
                        @foreach ($departments as $department)
                          <option value="{{ $department->department_id }}" @if($user->user_department==$department->department_id) selected @endif>{{ $department->department_name }}</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">岗位<span style="color:red">*</span></label>
                      <select class="form-control" name="input4" data-toggle="select" required>
                        <option value=''>请选择岗位...</option>
                        @foreach ($positions as $position)
                          <option value="{{ $position->position_id }}" @if($user->user_position==$position->position_id) selected @endif>{{ $position->section_name }}：{{ $position->position_name }} (等级 {{ $position->position_level }})</option>
                        @endforeach
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">入职日期<span style="color:red">*</span></label>
                      <input class="form-control datepicker" name="input5" placeholder="Select date" type="text" value="{{ $user->user_entry_date }}" required>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">跨校区上课<span style="color:red">*</span></label>
                      <select class="form-control" name="input6" data-toggle="select" required>
                        <option value=''>请选择是否可以跨校区上课...</option>
                        <option value='1' @if($user->user_cross_teaching==1) selected @endif>是</option>
                        <option value='0' @if($user->user_cross_teaching==0) selected @endif>否</option>
                      </select>
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">手机</label>
                      <input class="form-control" type="text" name="input7" value="{{ $user->user_phone }}" autocomplete='off' maxlength="11">
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">微信</label>
                      <input class="form-control" type="text" name="input8" value="{{ $user->user_wechat }}" autocomplete='off' maxlength="20">
                    </div>
                  </div>
                  <div class="col-6">
                    <div class="form-group">
                      <label class="form-control-label">添加时间</label>
                      <input class="form-control" type="text" value="{{ $user->user_createtime }}" readonly>
                    </div>
                  </div>
                </div>
                <hr class="my-3">
                <div class="row">
                  <div class="col-lg-4 col-md-5 col-sm-12">
                    <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
                  </div>
                  <div class="col-lg-4 col-md-2 col-sm-12 my-2"></div>
                  <div class="col-lg-4 col-md-5 col-sm-12">
                    <input type="submit" class="btn btn-warning btn-block" value="修改">
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
@endsection
