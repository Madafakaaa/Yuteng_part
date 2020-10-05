@include('layout.header')
<body>
  <div class="main-content" id="panel">
    <div class="container-fluid">
      <div class="row justify-content-center">
        <div class="col-9">
          <h2 class="text-center mt-3">{{$user->user_name}}-上课记录</h2>
          <div class="card my-3">
            <div class="table-responsive">
              <button type="button" class="btn btn-waring btn-block" onclick="table_export('table-1', '{{$user->user_name}}-上课记录')">导出表格</button>
              <table class="table text-left table-bordered" id="table-1">
                <thead class="thead-light">
                  <tr>
                    <th style="width:20px;">序号</th>
                    <th style="width:100px;">班级</th>
                    <th style="width:100px;">教师</th>
                    <th style="width:30px;">科目</th>
                    <th style="width:30px;">年级</th>
                    <th style="width:60px;">日期</th>
                    <th style="width:60px;">时间</th>
                    <th style="width:60px;">地点</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($attended_schedules as $schedule)
                    <tr title="创建时间：{{ $schedule->schedule_createtime }}。">
                      <td>{{ $loop->iteration }}</td>
                      <td><a href="/class?id={{encode($schedule->class_id,'class_id')}}">{{ $schedule->class_name }}</a></span></td>
                      <td><a href="/user?id={{encode($schedule->user_id,'user_id')}}">{{ $schedule->user_name }}</a> [{{ $schedule->position_name }}]</td>
                      <td>{{ $schedule->subject_name }}</td>
                      <td>{{ $schedule->grade_name }}</td>
                      <td>{{ $schedule->schedule_date }}</td>
                      <td>{{ date('H:i', strtotime($schedule->schedule_start)) }} - {{ date('H:i', strtotime($schedule->schedule_end)) }}</td>
                      <td>{{ $schedule->classroom_name }}</td>
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

