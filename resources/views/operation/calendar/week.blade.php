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
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item active">课程表</li>
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
        <div class="card-header py-3" style="border-bottom:0px;">
          <a href="?date={{$first_day_prev}}" ><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-left"></i></button></a>
          <a href="?date={{$first_day_next}}" <button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-right"></i></button></a>
          <span style="vertical-align: middle; font-size:20px; font-family: 'Noto Sans', sans-serif;">{{ date('Y.m.d', strtotime($first_day)) }} ~ {{ date('m.d', strtotime($last_day)) }}</span>
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
            body: "<a href='/operation/schedule/attend?id={{$row['schedule_id']}}'><button type='button' class='btn btn-primary btn-sm' @if($row['attended']==1) disabled @endif>点名</button></a>",
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
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationCalendarWeek');
</script>
@endsection
