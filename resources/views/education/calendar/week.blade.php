@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">课程表</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">课程表</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card main_card mb-4" style="display:none">
        <div class="card-header py-3" style="border-bottom:0px;">
          <form action="" method="get" onsubmit="submitButtonDisable('submitButton1')">
            <div class="row">
              <div class="col-6">
                <a href="?filter_date={{$first_day_prev}}&@foreach($filters as $key => $value) @if($key!='filter_date') {{$key}}={{$value}}& @endif @endforeach"><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-left"></i></button></a>
                <a href="?filter_date={{$first_day_next}}&@foreach($filters as $key => $value) @if($key!='filter_date') {{$key}}={{$value}}& @endif @endforeach"><button type="button" class="btn btn-outline-primary btn-icon-only rounded-circle"><i class="fa fa-chevron-right"></i></button></a>
                <span style="vertical-align: middle; font-size:26px; font-family: 'Noto Sans', sans-serif;" class="ml-3">{{ date('Y.m.d', strtotime($first_day)) }} ~ {{ date('m.d', strtotime($last_day)) }}</span>
              </div>
              <div class="col-3">
              </div>
              <div class="col-2 text-right">
                <input class="form-control datepicker" name="filter_date" placeholder="选择日期" type="text" value="{{$first_day}}">
              </div>
              <div class="col-1 text-right">
                <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
                <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
                <input type="hidden" name="filter_subject" value="{{$filters['filter_subject']}}">
                <input type="hidden" name="filter_attended" value="{{$filters['filter_attended']}}">
                <input type="submit" id="submitButton1" class="btn btn-primary btn-block" value="查询">
              </div>
            </div>
          </form>
        </div>
        <hr class="mb-1 mt-0">
        <div class="card-header p-1" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">校区：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_department'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_departments as $filter_department)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach &filter_department={{$filter_department->department_id}}"><button type="button" @if($filters['filter_department']==$filter_department->department_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_department->department_name}}</button></a>
          @endforeach
        </div>
        <div class="card-header p-1" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">年级：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_grade'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_grades as $filter_grade)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach filter_grade={{$filter_grade->grade_id}}"><button type="button" @if($filters['filter_grade']==$filter_grade->grade_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_grade->grade_name}}</button></a>
          @endforeach
        </div>
        <div class="card-header p-1" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">科目：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_subject') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_subject'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_subjects as $filter_subject)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_subject') {{$key}}={{$value}}& @endif @endforeach filter_subject={{$filter_subject->subject_id}}"><button type="button" @if($filters['filter_subject']==$filter_subject->subject_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_subject->subject_name}}</button></a>
          @endforeach
        </div>
        <div class="card-header p-1" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">点名：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_attended') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_attended'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_attended') {{$key}}={{$value}}& @endif @endforeach filter_attended=1"><button type="button" @if($filters['filter_attended']==1) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>未点名</button></a>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_attended') {{$key}}={{$value}}& @endif @endforeach filter_attended=2"><button type="button" @if($filters['filter_attended']==2) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>已点名</button></a>
        </div>
        <div id="calendar" class="mt-2"></div>
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
            state: "{{$row['teacher']}}",
            isReadOnly: true
          },
        @endforeach
      ],
      "/education/calendar/day?@foreach($filters as $key => $value) @if($key!='filter_date') {{$key}}={{$value}}& @endif @endforeach"
    );
});
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationCalendarWeek');
</script>
@endsection
