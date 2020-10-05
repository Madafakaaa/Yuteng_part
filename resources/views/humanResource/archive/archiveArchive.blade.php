@include('layout.header')
<body>
  <div class="main-content" id="panel">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-9">
          <h2 class="text-center mt-3">{{$user->user_name}}-档案文件</h2>
          <form action="/user/archive" method="post" id="form1" name="form1" enctype="multipart/form-data" onsubmit="submitButtonDisable('submitButton2')">
            @csrf
            <div class="card mb-4">
              <div class="card-body pb-0 pt-4">
                <div class="row">
                  <div class="col-lg-2 col-md-6 col-sm-12">
                    <div class="form-group text-center">
                      <label class="form-control-label">档案名称<span style="color:red">*</span></label>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                      <input class="form-control form-control-sm" type="text" name="archive_name" placeholder="请输入档案名称... " autocomplete='off' maxlength="30" required>
                    </div>
                  </div>
                  <div class="col-lg-4 col-md-6 col-sm-12">
                    <div class="form-group">
                      <div class="input-group">
                        <input id='location' class="form-control form-control-sm" disabled aria-describedby="button-addon">
                        <div class="input-group-append">
                          <input type="button" id="i-check" value="浏览文件" class="btn btn-outline-primary btn-sm" onClick="$('#i-file').click();" style="margin:0;" id="button-addon">
                          <input type="file" name='file' id='i-file' onChange="$('#location').val($('#i-file').val());" style="display: none" required>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-2 col-md-6 col-sm-12">
                    <input type="hidden" name="archive_user" value="{{$user->user_id}}">
                    <input type="submit" id="submitButton2" class="btn btn-warning btn-block btn-sm" value="上传">
                  </div>
                </div>
              </div>
            </div>
          </form>

          <div class="card mb-4">
            <div class="table-responsive py-4">
              <table class="table table-flush datatable-basic">
                <thead class="thead-light">
                  <tr>
                    <th style='width:50px;'>序号</th>
                    <th style='width:320px;'>档案</th>
                    <th style='width:120px;'>上传日期</th>
                    <th style='width:120px;'>操作管理</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($archives as $archive)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="/files/archive/{{$archive->archive_path}}" target="_blank">{{ $archive->archive_name }}</a></td>
                      <td>{{ date('Y-m-d', strtotime($archive->archive_createtime)) }}</td>
                      <td>
                        <a href='/humanResource/archive/download?id={{encode($archive->archive_id, 'archive_id')}}'><button type="button" class="btn btn-primary btn-sm">文件下载</button></a>
                        <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/humanResource/archive/delete?id={{encode($archive->archive_id, 'archive_id')}}', '确认删除该档案？')">删除</button>
                      </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
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

