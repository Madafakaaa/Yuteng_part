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
          <h6 class="h2 text-white d-inline-block mb-0">签约统计</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">数据中心</li>
              <li class="breadcrumb-item active">课时消耗</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-3 col-md-3 col-sm-12">
      <div class="card" style="box-shadow: 4px 4px 5px #777777;">
        <div class="card-body">
          <form action="" method="post" id="form1">
            @csrf
            <div class="row mb-2">
              <div class="col-3">
                <label class="form-control-label py--1">日期</label>
              </div>
              <div class="col-9">
                <input class="form-control form-control-sm datepicker mb-2" name="start_date" type="text" value="{{ $start_date }}" required onchange="form_submit()">
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-3">
                <label class="form-control-label py--1">至</label>
              </div>
              <div class="col-9">
                <input class="form-control form-control-sm datepicker mb-2" name="end_date" type="text" value="{{ $end_date }}" required onchange="form_submit()">
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-3">
                  <label class="form-control-label py--1">校区</label>
              </div>
              <div class="col-9">
                @foreach ($user_departments as $department)
                  <div class="custom-control custom-checkbox mb-2">
                    <input class="custom-control-input" name="departments[]" id="department_{{ $loop->iteration }}" value="{{ $department->department_id }}" type="checkbox" @if($department_array[$department->department_id][2]==1) checked @endif onchange="form_submit()">
                    <label class="custom-control-label" for="department_{{ $loop->iteration }}">{{ $department->department_name }}</label>
                  </div>
                @endforeach
              </div>
            </div>
            <div class="row">
              <div class="col-3">
                <label class="form-control-label py--1">分类</label>
              </div>
              <div class="col-9">
                <select class="form-control form-control-sm" name="analysis_method" data-toggle="select" required onchange="form_submit()">
                  <option value='1' @if($analysis_method==1) selected @endif>日期</option>
                  <option value='2' @if($analysis_method==2) selected @endif>校区</option>
                </select>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <div class="col-lg-9 col-md-9 col-sm-12">

      <div class="row">
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col-auto">
                  <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                    <i class="ni ni-money-coins"></i>
                  </div>
                </div>
                <div class="col">
                  <h5 class="card-title text-muted mb-0">课时消耗</h5>
                  <span class="h2 font-weight-bold mb-0 text-blue">{{ number_format($total_hour) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col-auto">
                  <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                    <i class="ni ni-single-02"></i>
                  </div>
                </div>
                <div class="col">
                  <h5 class="card-title text-muted mb-0">上课人数</h5>
                  <span class="h2 font-weight-bold mb-0 text-blue">{{ number_format($total_student_num) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
          <div class="card card-stats">
            <div class="card-body">
              <div class="row">
                <div class="col-auto">
                  <div class="icon icon-shape bg-blue text-white rounded-circle shadow">
                    <i class="ni ni-calendar-grid-58"></i>
                  </div>
                </div>
                <div class="col">
                  <h5 class="card-title text-muted mb-0">上课次数</h5>
                  <span class="h2 font-weight-bold mb-0 text-blue">{{ number_format($total_schedule_num) }}</span>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-body">
              <div class="chart">
                <canvas id="barChart1" class="chart-canvas"></canvas>
              </div>
            </div>
          </div>
        </div>
      </div>

      <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="table-responsive">
              <table class="table align-items-center table-hover text-left">
                <thead class="thead-light">
                  <tr>
                    <th style="width:16%;"></th>
                    <th style="width:28%;">课时消耗</th>
                    <th style="width:28%;">上课人数</th>
                    <th style="width:28%;">上课次数</th>
                  </tr>
                </thead>
                <tbody>
                  @for($i=0;$i<count($ids);$i++)
                    <tr>
                      <td>{{ $ids[$i] }}</td>
                      @if($hours[$i]!=0)
                        <td>{{ number_format($hours[$i]) }}</td>
                      @else
                        <td></td>
                      @endif
                      @if($student_nums[$i]!=0)
                        <td>{{ number_format($student_nums[$i]) }}</td>
                      @else
                        <td></td>
                      @endif
                      @if($schedule_nums[$i]!=0)
                        <td>{{ number_format($schedule_nums[$i]) }}</td>
                      @else
                        <td></td>
                      @endif
                    </tr>
                  @endfor
                </tbody>
              </table>
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
  linkActive('link-finance');
  navbarActive('navbar-finance');
  linkActive('financeConsumption');

  function form_submit(){
      document.getElementById('form1').submit();
  }

  barChart("barChart1", "课时消耗", [@foreach($ids as $id) "{{ $id }}", @endforeach], [@foreach($hours as $hour) "{{ $hour }}", @endforeach]);
</script>
@endsection
