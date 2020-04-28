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
          <h6 class="h2 text-white d-inline-block mb-0">上课记录详情</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">上课记录详情</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-6">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-10 col-sm-12">
      <div class="card main_card" style="display:none">
        <form action="/education/schedule/attend/{{ $schedule->schedule_id }}/step2" method="post" id="form1" name="form1">
          @csrf
          <div class="card-header">
            <h4 class="mb-0">上课详情</h4>
          </div>
          <!-- Card body -->
          <div class="card-body pt-2">
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课校区</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->department_name }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课日期</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->schedule_date }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">上课时间</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}" readonly>
                </div>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->schedule_time }}分钟" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">教师</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->user_name }}" readonly>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">科目</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" type="text" readonly value="{{ $schedule->subject_name }}">
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">教室</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $schedule->classroom_name }}" readonly>
                </div>
              </div>
            </div>
            <hr>
            @foreach($participants as $participant)
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">学生{{ $loop->iteration }}</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $participant->student_name }}" readonly>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">考勤</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  @if($participant->participant_attend_status==1)
                    <input class="form-control form-control-sm" value="正常" readonly>
                  @elseif($participant->participant_attend_status==2)
                    <input class="form-control form-control-sm" value="请假" readonly>
                  @else
                    <input class="form-control form-control-sm" value="旷课" readonly>
                  @endif
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-2 text-right">
                <label class="form-control-label">扣除课时</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $participant->course_name }}" readonly>
                </div>
              </div>
              <div class="col-2 text-right">
                <label class="form-control-label">扣除数量</label>
              </div>
              <div class="col-4 px-2 mb-2">
                <div class="form-group mb-1">
                  <input class="form-control form-control-sm" value="{{ $participant->participant_amount }}课时" readonly>
                </div>
              </div>
            </div>
            <hr>
            @endforeach
            <div class="row">
              <div class="col-lg-3 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)"><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
            </div>
          </div>
        <form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
</script>
@endsection
