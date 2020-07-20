@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">上课记录详情</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">上课记录详情</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="card main_card" style="display:none">
          @csrf
          <div class="card-header">
            <h3 class="mb-0">上课记录详情</h3>
          </div>
          <!-- Card body -->
          <div class="card-body py-3">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课校区</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $schedule->department_name }}</label>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">班级</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $schedule->class_name }}</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课日期</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $schedule->schedule_date }}</label>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">上课时间</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ date('H:i', strtotime($schedule->schedule_start)) }} ~ {{ date('H:i', strtotime($schedule->schedule_end)) }}</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">教师</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $schedule->user_name }}</label>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">科目</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $schedule->subject_name }}</label>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">教室</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <label>{{ $schedule->classroom_name }}</label>
                </div>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-12">
                <ul class="list-group list-group-flush list my--3">
                  @foreach($members as $member)
                    <li class="list-group-item px-0">
                      <div class="row align-items-center">
                        <div class="col-auto">
                          <!-- Avatar -->
                          <a href="/student?id={{encode($member->student_id, 'student_id')}}" class="avatar rounded-circle">
                            <img alt="..." src="{{ asset(_ASSETS_.'/avatar/student.png') }}">
                          </a>
                        </div>
                        <div class="col ml--2">
                          <h4 class="mb-0">
                            <a href="/student?id={{encode($member->student_id, 'student_id')}}">{{ $member->student_name }}</a>
                          </h4>
                          @if($member->participant_attend_status==1)
                            <span class="text-success"><small>●正常</small></span>
                          @elseif($member->participant_attend_status==2)
                            <span class="text-warning"><small>●请假</small></span>
                          @else
                            <span class="text-danger"><small>●旷课</small></span>
                          @endif
                          <small>{{ $member->course_name }} 使用{{ $member->participant_amount }}课时</small>
                        </div>
                        <div class="col-auto">
                          <a href="/student?id={{encode($member->student_id, 'student_id')}}"><button type="button" class="btn btn-primary btn-sm">学生详情</button></a>
                        </div>
                      </div>
                    </li>
                  @endforeach
                </ul>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)"><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
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
</script>
@endsection
