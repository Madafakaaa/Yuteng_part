@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item active">学生课程表</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-2 col-md-12 col-sm-12">
      <div class="row justify-content-center mb-3">
        <div class="col-12 text-left">
          <a href="/schedule/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="新建排课">
            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
            <span class="btn-inner--text">新建排课</span>
          </a>
        </div>
      </div>
      <div class="card bg-transparent">
        <form action="" method="get" id="form1" name="form1">
        <div class="card-body border-0">
          <div class="row">
            <div class="col-12 px-2 mb-2">
              <div class="form-group mb-1">
                <select class="form-control" name="filter1" data-toggle="select">
                  <option value=''>请选择学生/班级...</option>
                  @foreach ($students as $student)
                    <option value="{{ $student->student_id }}">学生: {{ $student->student_name }}</option>
                  @endforeach
                  @foreach ($classes as $class)
                    <option value="{{ $class->class_id }}">班级: {{ $class->class_name }}</option>
                  @endforeach
                </select>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-12 px-2">
              <div class="form-group mb-1">
                <input type="submit" class="btn btn-primary btn-block" value="查询">
              </div>
            </div>
          </div>
        </div>
        </form>
      </div>
    </div>
    <div class="col-lg-10 col-md-12 col-sm-12">
      <div class="row justify-content-center mb-3 text-center">
        <div class="col-lg-2 col-md-2 col-sm-3 mb-1">
          <a href="/schedule/create" class="btn btn-sm btn-neutral btn-round btn-block" data-toggle="tooltip" data-original-title="上一周">
            <span class="btn-inner--icon"><i class="ni ni-bold-left"></i></span>
            <span class="btn-inner--text">上一周</span>
          </a>
        </div>
        <div class="col-lg-3 col-md-4 col-sm-4 mb-1">
          <button type="button" class="btn btn-secondary btn-sm btn-block" disabled>
            {{ $days[0] }} ~ {{ $days[6] }}
          </button>
        </div>
        <div class="col-lg-2 col-md-2 col-sm-3 mb-1">
          <a href="/schedule/create" class="btn btn-sm btn-neutral btn-round btn-block" data-toggle="tooltip" data-original-title="下一周">
            <span class="btn-inner--text">下一周</span>
            <span class="btn-inner--icon"><i class="ni ni-bold-right"></i></span>
          </a>
        </div>
      </div>
      <div class="card main_card" style="display:none">
        <div class="table-responsive">
          <table class="table align-items-center text-center table-bordered table-hover">
            <thead class="thead-light">
              <tr>
                <th style='width:76px;'>时间</th>
                @foreach ($days as $day)
                  @if($day==date('Y-m-d'))
                    <th style='width:143px; color:#FFF;background-color:#DC965A;'>{{ date('m-d', strtotime($day)) }} 周{{ $ch_str[$loop->iteration] }} </th>
                  @else
                    <th style='width:143px;'> {{ date('m-d', strtotime($day)) }} 周{{ $ch_str[$loop->iteration] }}</th>
                  @endif
                @endforeach
                <th></th>
              </tr>
            </thead>
            <tbody>
              @foreach($calendar as $row)
                <tr style="height:32px;">
                  <td style="height:32px;">{{  date('H:i', strtotime($times[$loop->iteration-1])) }}</th>
                  @foreach($row as $column)
                    @if($column==-1)
                      <td></td>
                    @elseif($column>=0)
                      @if($schedules[$column]->schedule_attended==1)
                        <td rowspan="{{ $schedules[$column]->schedule_time/30 }}" class="text-left align-top p-1 m-0" style="overflow-y:hidden; background-color:#19A06E; border-radius:15px;">
                          <div style="height:{{ $schedules[$column]->schedule_time*32/30 }}px; background-color:#2DCE89; border-radius:10px;">
                      @else
                        <td rowspan="{{ $schedules[$column]->schedule_time/30 }}" class="text-left align-top p-1 m-0" style="overflow-y:hidden; background-color:#B43246; border-radius:15px;">
                          <div style="height:{{ $schedules[$column]->schedule_time*32/30 }}px; background-color:#F5365C; border-radius:10px;">
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
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-3');
  navbarActive('navbar-3');
  linkActive('calendar');
</script>
@endsection
