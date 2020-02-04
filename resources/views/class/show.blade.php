@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="#">班级详情</a></li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card">
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
            <a href="/class/{{ $class->class_id }}/edit" class="btn btn-sm btn-primary mr-4">修改信息</a>
            <a href="/schedule/create"  target="_blank" class="btn btn-sm btn-warning float-right">新建排课</a>
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="text-center pb-2">
            <h1>{{ $class->class_name }}</h1>
            <div class="h5 font-weight-300">{{ $class->class_id }}</div>
            <hr>
            <div class="row text-left ml-2">
              <div class="col-6">
                <div class="h4">校区 - {{ $class->department_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">年级 - {{ $class->grade_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">科目 - {{ $class->subject_name }}</div>
              </div>
              <div class="col-6">
                <div class="h4">负责人 - {{ $class->user_name }}</div>
              </div>
              <div class="col-12">
                <div class="h4">班级规模 - {{ $class->class_current_num }} / {{ $class->class_max_num }} 人</div>
              </div>
            </div>
          </div>
          <hr>
          <div class="row">
            <div class="col">
              <div class="card-profile-stats d-flex justify-content-center p-0">
                <div>
                  <span class="heading">{{ $class->class_current_num }}</span>
                  <span class="description">班级人数</span>
                </div>
                <div>
                  <span class="heading">{{ $class->class_current_num }}</span>
                  <span class="description">上课次数</span>
                </div>
                <div>
                  <span class="heading">{{ $class->class_last_lesson_date }}</span>
                  <span class="description">最后上课</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">班级成员 ( {{ $class->class_current_num }} / {{ $class->class_max_num }} 人 )</h5>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush list my--3">
            @foreach ($members as $member)
            <li class="list-group-item px-0">
              <form action="/member/{{ $class->class_id }}" method="POST">
                @method('DELETE')
                @csrf
                <div class="row align-items-center">
                  <div class="col-auto">
                    <a href="#" class="avatar rounded-circle">
                      <img alt="Image placeholder" src="../../assets/img/theme/team-1.jpg">
                    </a>
                  </div>
                  <div class="col ml--2">
                    <h4 class="mb-0">
                      <a href="#!">{{ $member->student_name }}</a>
                    </h4>
                    <span class="text-success">●</span>
                    <small>{{ $member->student_id }}</small>
                  </div>
                  <div class="col-auto">
                    <input type="hidden" name="input1" value="{{ $member->student_id }}">
                    <input type="submit" class="btn btn-sm btn-outline-danger" value="删除">
                  </div>
                </div>
              </form>
            </li>
            @endforeach
            <li class="list-group-item px-0">
            <form action="/member/{{ $class->class_id }}" method="post">
              @csrf
              <div class="row align-items-center">
                <div class="col ml-2">
                  <select class="form-control" name="input1" data-toggle="select" required>
                    <option value=''>请选择学生...</option>
                    @foreach ($students as $student)
                      <option value="{{ $student->student_id }}">{{ $student->student_name }}</option>
                    @endforeach
                  </select>
                </div>
                <div class="col-auto">
                  <input type="submit" class="btn btn-warning" value="添加">
                </div>
              </div>
            </form>
            </li>
          </ul>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h5 class="h3 mb-0">班级备注</h5>
        </div>
        <form action="/class/{{ $class->class_id }}/remark" method="post">
          @csrf
          <div class="card-body p-3">
            <div class="row">
              <div class="col-12">
                <div class="form-group mb-2">
                  <textarea class="form-control" name="input1" rows="6" resize="none" spellcheck="false" autocomplete='off' maxlength="140" required>{{ $class->class_remark }}</textarea>
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
      <div class="nav-wrapper p-0">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-3 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-badge mr-2"></i>课程安排</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-3" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>上课记录</a>
          </li>
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
          <div class="row justify-content-center text-center py-1">
            <div class="col-lg-3 col-md-3 col-sm-3 mb-1">
              <a href="{{ $request_url_prev }}" class="btn btn-sm btn-neutral btn-round btn-block" data-toggle="tooltip" data-original-title="上一周">
                <span class="btn-inner--icon"><i class="ni ni-bold-left"></i></span>
                <span class="btn-inner--text">上一周</span>
              </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 mb-1">
              <a href="{{ $request_url_today }}" class="btn btn-sm btn-neutral btn-round btn-block" data-toggle="tooltip" data-original-title="今天">
                <span class="btn-inner--text">今天</span>
              </a>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-3 mb-1">
              <a href="{{ $request_url_next }}" class="btn btn-sm btn-neutral btn-round btn-block" data-toggle="tooltip" data-original-title="下一周">
                <span class="btn-inner--text">下一周</span>
                <span class="btn-inner--icon"><i class="ni ni-bold-right"></i></span>
              </a>
            </div>
          </div>
          <div class="card shadow">
            <div class="card-body p-0">
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
                                    {{ $schedules[$column]->class_name }}
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
          </div>
        </div>

        <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
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
