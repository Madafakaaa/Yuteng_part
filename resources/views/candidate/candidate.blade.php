@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">面试用户详情</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">面试用户详情</li>
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
              <img src="{{ asset(_ASSETS_.'/avatar/male.png') }}" class="rounded-circle" style="height:100px;">
            </div>
          </div>
        </div>
        <div class="card-header text-center border-0 pb-0 pb-4">
          <div class="d-flex justify-content-between">
          </div>
        </div>
        <div class="card-body pt-0">
          <div class="text-center">
            <h1>{{ $candidate->candidate_name }}
              @if($candidate->candidate_gender=="男")
                <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
              @else
                <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
              @endif
            </h1>
            <div class="h5 font-weight-300">{{ $candidate->candidate_id }}</div>
            <hr>
            <div class="h5">
              手机 - {{ $candidate->candidate_phone }}
            </div>
            <div class="h5">
              微信 - {{ $candidate->candidate_wechat }}
            </div>
            <hr>
            <div class="h5">
              求职岗位 - {{ $candidate->candidate_position }}
            </div>
            <div class="h5">
              面试官 - {{ $candidate->user_name }}
            </div>
            <div class="h5">
              备注 - {{ $candidate->candidate_comment }}
            </div>
            <hr>
            <div class="h5">
              <a href='/files/archive/{{$candidate->archive_path}}' target="_blank"><button type="button" class="btn btn-primary btn-block">查看简历</button></a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-9 col-md-6 col-sm-12">
      <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-3 active" id="record-tab" data-toggle="tab" href="#record-card" role="tab" aria-selected="true"><i class="ni ni-badge mr-2"></i>用户动态</a>
          </li>
          <!-- <li class="nav-item">
            <a class="nav-link mb-3" id="attended-record-tab" data-toggle="tab" href="#attended-record-card" role="tab" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>上课记录</a>
          </li> -->
        </ul>
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="record-card" role="tabpanel">
          <div class="card">
            <div class="card-header">
              <h5 class="h3 mb-0">添加跟进动态</h5>
            </div>
            <form action="/candidate/record" method="post" onsubmit="submitButtonDisable('submitButton1')">
              @csrf
              <div class="card-body p-3">
                <div class="row">
                  <div class="col-12">
                    <div class="form-group mb-2">
                      <textarea class="form-control" name="user_record_content" rows="2" resize="none" spellcheck="false" autocomplete='off' maxlength="255" placeholder="跟进内容..." required></textarea>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-9">
                  </div>
                  <div class="col-3">
                    <input type="hidden" name="user_id" value="{{$candidate->candidate_id}}">
                    <input type="submit" id="submitButton1" class="btn btn-sm btn-warning btn-block" value="添加">
                  </div>
                </div>
              </div>
            </form>
            <hr>
            <div class="card-body">
              <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
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

        <div class="tab-pane fade" id="attended-record-card" role="tabpanel">
          <div class="card mb-4">
          </div>
        </div>

      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
@endsection
