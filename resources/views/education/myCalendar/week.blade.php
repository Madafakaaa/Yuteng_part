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
          <h6 class="h2 text-white d-inline-block mb-0">课程表</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">教学中心</li>
              <li class="breadcrumb-item active">我的课程表</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card main_card mb-4" style="display:none">
        <div class="card-header py-2" style="border-bottom:0px;">
          <a href="?date={{$first_day}}"><button type="button" class="btn btn-primary btn-sm" @if($current_department_id==0) disabled @endif>全部</button></a>
          @foreach($department_links as $department_link)
            <a href="?date={{$first_day}}&department={{encode($department_link['department_id'],'department_id')}}"><button type="button" class="btn btn-primary btn-sm" @if($current_department_id==$department_link['department_id']) disabled @endif style="background-color:{{$department_link['department_color']}};border-color:{{$department_link['department_color']}};">{{$department_link['department_name']}}</button></a>
          @endforeach
        </div>
        <hr class="m-1">
        <div class="card-header py-2" style="border-bottom:0px;">
        <form action="" method="get" onsubmit="submitButtonDisable('submitButton1')">
          <div class="row">
            <div class="col-6">
              <a href="?date={{$first_day_prev}}&department={{encode($current_department_id,'department_id')}}" ><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-left"></i></button></a>
              <a href="?date={{$first_day_next}}&department={{encode($current_department_id,'department_id')}}" <button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-right"></i></button></a>
              <span style="vertical-align: middle; font-size:20px; font-family: 'Noto Sans', sans-serif;">{{ date('Y.m.d', strtotime($first_day)) }} ~ {{ date('m.d', strtotime($last_day)) }}</span>
            </div>
            <div class="col-3">
            </div>
            <div class="col-2 text-right">
              <input class="form-control datepicker" name="date" placeholder="选择日期" type="text" value="{{$first_day}}">
            </div>
            <div class="col-1 text-right">
              <input type="hidden" name="department" value="{{encode($current_department_id,'department_id')}}">
              <input type="submit" id="submitButton1" class="btn btn-primary btn-block" value="查询">
            </div>
          </div>
        </form>
        </div>
        <div id="calendar"></div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
$(document).ready(function(){
    calendar_weekly(
      "{{ $first_day }}",
      [
        @foreach($calendars as $calendar)
          {
            id: "{{$calendar['id']}}",
            name: "{{$calendar['name']}}",
            color: "{{$calendar['color']}}",
            bgColor: "{{$calendar['bgColor']}}",
            dragBgColor: "{{$calendar['dragBgColor']}}",
            borderColor: "{{$calendar['borderColor']}}",
          },
        @endforeach
      ],
      [
        @foreach($rows as $row)
          {
            calendarId: "{{$row['calendarId']}}",
            title: "{{$row['title']}}",
            body: "",
            category: "time",
            location: "{{$row['location']}}",
            start: "{{$row['start']}}",
            end: "{{$row['end']}}",
            attendees: [
                         @foreach($row['attendees'] as $attendee)
                           '{{$attendee}}',
                         @endforeach
                       ],
            raw: {
                teacher: "{{$row['teacher']}}",
            },
            isReadOnly: true
          },
        @endforeach
      ],
    );
});
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationMyCalendarWeek');
</script>
@endsection
