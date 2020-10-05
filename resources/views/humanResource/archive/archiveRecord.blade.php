@include('layout.header')
<body>
  <div class="main-content" id="panel">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-9">
          <h2 class="text-center mt-3">{{$user->user_name}}-用户动态</h2>
          <div class="card my-3">
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
      </div>
    </div>
  </div>
  <!-- Script files -->
  @include('layout.scripts')
</body>
</html>

