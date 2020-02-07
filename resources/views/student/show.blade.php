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
          <hr>
          <div class="row">
            <div class="col">
              <div class="card-profile-stats d-flex justify-content-center p-0">
                <div>
                  <span class="heading">{{ $student->student_contract_num }}</span>
                  <span class="description">签约次数</span>
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
                    <span style="color:green;">学生</span>
                  </span>
                  <span class="description">状态</span>
                </div>
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
            <a class="nav-link mb-3" id="tabs-icons-text-5-tab" data-toggle="tab" href="#tabs-icons-text-5" role="tab" aria-controls="tabs-icons-text-5" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>学生档案</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-6-tab" data-toggle="tab" href="#tabs-icons-text-6" role="tab" aria-controls="tabs-icons-text-6" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>学生动态</a>
          </li>
        </ul>
      </div>
      <div class="card shadow">
        <div class="card-body p-0">
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
              <div class="row justify-content-center text-center my-1">
                <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                  <a href="{{ $request_url_prev }}" class="btn btn-sm btn-neutral btn-round btn-block" data-toggle="tooltip" data-original-title="上一周">
                    <span class="btn-inner--icon"><i class="ni ni-bold-left"></i></span>
                    <span class="btn-inner--text">上一周</span>
                  </a>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                  <a href="{{ $request_url_today }}" class="btn btn-sm btn-neutral btn-round btn-block" data-toggle="tooltip" data-original-title="今天">
                    <span class="btn-inner--text">今天</span>
                  </a>
                </div>
                <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                  <a href="{{ $request_url_next }}" class="btn btn-sm btn-neutral btn-round btn-block" data-toggle="tooltip" data-original-title="下一周">
                    <span class="btn-inner--text">下一周</span>
                    <span class="btn-inner--icon"><i class="ni ni-bold-right"></i></span>
                  </a>
                </div>
              </div>
              <div class="table-responsive">
                <table class="table align-items-center text-center table-bordered table-hover">
                  <thead class="thead-light">
                    <tr>
                      <th style='width:65px;'>时间</th>
                      @foreach ($days as $day)
                        @if($day==date('Y-m-d'))
                          <th style='width:113px; color:#FFF;background-color:#DC965A;'>{{ date('m-d', strtotime($day)) }} {{ $numToStr[$loop->iteration] }} </th>
                        @else
                          <th style='width:113px;'> {{ date('m-d', strtotime($day)) }} {{ $numToStr[$loop->iteration] }}</th>
                        @endif
                      @endforeach
                      <th></th>
                    </tr>
                  </thead>
                  <tbody style="max-height:100px; overflow:hidden;">
                    @foreach($calendar as $row)
                      <tr style="height:32px;">
                        <td style="height:32px;">{{  date('H:i', strtotime($times[$loop->iteration-1])) }}</th>
                        @foreach($row as $column)
                          @if($column==-1)
                            <td></td>
                          @elseif($column>=0)
                            @if($schedules[$column]->schedule_attended==1)
                              <td rowspan="{{ $schedules[$column]->schedule_time/30 }}" class="text-left align-top p-1 m-0" style="overflow-y:hidden; background-color:#19A06E; border-radius:15px;">
                                <div class="px-1" style="height:{{ $schedules[$column]->schedule_time*32/30 }}px; background-color:#2DCE89; border-radius:10px;">
                            @else
                              <td rowspan="{{ $schedules[$column]->schedule_time/30 }}" class="text-left align-top p-1 m-0" style="overflow-y:hidden; background-color:#B43246; border-radius:15px;">
                                <div class="px-1" style="height:{{ $schedules[$column]->schedule_time*32/30 }}px; background-color:#F5365C; border-radius:10px;">
                            @endif
                              <div class="row m-0 p-0 pt-1" style="height:32px;">
                                <div class="col-2 mx-0 my-1 px-1 py-0">
                                  <i class="ni ni-single-02 text-white"></i>
                                </div>
                                <div class="col-10 mx-0 my-1 px-1 py-0">
                                  <span style="color:white;" class="align-items-center">
                                    {{ $schedules[$column]->student_name }}{{ $schedules[$column]->class_name }}
                                  </span>
                                </div>
                              </div>
                              <div class="row m-0 p-0" style="height:32px;">
                                <div class="col-2 mx-0 my-1 px-1 py-0">
                                  <i class="ni ni-badge text-white"></i>
                                </div>
                                <div class="col-10 mx-0 my-1 px-1 py-0">
                                  <span style="color:white;" class="align-items-center">
                                    {{ $schedules[$column]->user_name }}
                                  </span>
                                </div>
                              </div>
                              <div class="row m-0 p-0" style="height:32px;">
                                <div class="col-2 mx-0 my-1 px-1 py-0">
                                  <i class="ni ni-pin-3 text-white"></i>
                                </div>
                                <div class="col-10 mx-0 my-1 px-1 py-0">
                                  <span style="color:white;" class="align-items-center">
                                    {{ $schedules[$column]->department_name }}
                                    {{ $schedules[$column]->classroom_name }}
                                  </span>
                                </div>
                              </div>
                              <div class="row m-0 p-0" style="height:32px;">
                                <div class="col-2 mx-0 my-1 px-1 py-0">
                                  <i class="ni ni-book-bookmark text-white"></i>
                                </div>
                                <div class="col-10 mx-0 my-1 px-1 py-0">
                                  <span style="color:white;" class="align-items-center">
                                    {{ $schedules[$column]->grade_name }}
                                    {{ $schedules[$column]->subject_name }}
                                  </span>
                                </div>
                              </div>
                              <div class="row m-0 p-0" style="height:32px;">
                                <div class="col-2 mx-0 my-1 px-1 py-0">
                                  <i class="ni ni-watch-time text-white"></i>
                                </div>
                                <div class="col-10 mx-0 my-1 px-1 py-0">
                                  <span style="color:white;" class="align-items-center">
                                    {{  date('H:i', strtotime($schedules[$column]->schedule_start)) }} - {{ date('H:i', strtotime($schedules[$column]->schedule_end)) }}
                                  </span>
                                </div>
                              </div>
                              </div>
                            </td>
                          @endif
                        @endforeach
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              </div>
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-3" role="tabpanel" aria-labelledby="tabs-icons-text-3-tab">
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-4" role="tabpanel" aria-labelledby="tabs-icons-text-4-tab">
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-5" role="tabpanel" aria-labelledby="tabs-icons-text-5-tab">
            </div>

            <div class="tab-pane fade" id="tabs-icons-text-6" role="tabpanel" aria-labelledby="tabs-icons-text-6-tab">
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
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-3');
  navbarActive('navbar-3');
</script>
@endsection
