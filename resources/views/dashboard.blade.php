@extends('main')

@section('content')
<div class="container-fluid mt-4">
  @if(in_array("业绩排名", Session::get('user_dashboards')))
  <div class="row">
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card pb-2">
        <div class="card-header border-0 py-3">
          <div class="row align-items-center">
            <h3 class="mb-0 text-muted">本月业绩排名</h3>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col">用户</th>
                <th scope="col">校区</th>
                <th scope="col">签约数量</th>
                <th scope="col">签约金额</th>
                <th scope="col">已收金额</th>
              </tr>
            </thead>
            <tbody>
              @forelse($contracts as $contract)
                <tr>
                  <th scope="row">{{$contract->user_name}}</th>
                  <td>{{$contract->department_name}}</td>
                  <td>{{$contract->contract_num}}</td>
                  <td>{{$contract->sum_contract_total_price}}</td>
                  <td>{{$contract->sum_contract_paid_price}}</td>
                </tr>
              @empty
                <tr>
                  <th colspan="5">无</th>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-4 col-md-6 col-sm-12">
      <div class="card pb-2">
        <div class="card-header border-0 py-3">
          <div class="row align-items-center">
            <h3 class="mb-0 text-muted">本月课消排名</h3>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-flush">
            <thead class="thead-light">
              <tr>
                <th scope="col">用户</th>
                <th scope="col">校区</th>
                <th scope="col">上课数量</th>
                <th scope="col">消耗课时</th>
              </tr>
            </thead>
            <tbody>
              @forelse($consumptions as $consumption)
                <tr>
                  <th scope="row">{{$consumption->user_name}}</th>
                  <td>{{$consumption->department_name}}</td>
                  <td>{{$consumption->schedule_num}}</td>
                  <td>{{$consumption->sum_participant_amount}}</td>
                </tr>
              @empty
                <tr>
                  <th colspan="4">无</th>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @endif
  @if(in_array("课时提醒", Session::get('user_dashboards')))
  <div class="row">
    <div class="col-12">
      <div class="card pb-2">
        <div class="card-header border-0 py-3">
          <div class="row align-items-center">
            <h3 class="mb-0 text-muted">剩余课时不足提醒</h3>
          </div>
        </div>
        <hr>
        <div class="table-responsive pb-4">
          <table class="table table-flush datatable-basic">
            <thead class="thead-light">
              <tr>
                <th scope="col">学生</th>
                <th scope="col">校区</th>
                <th scope="col">年级</th>
                <th scope="col">课程</th>
                <th scope="col">已用课时</th>
                <th scope="col">剩余课时</th>
              </tr>
            </thead>
            <tbody>
              @forelse($hours as $hours)
                <tr>
                  <th scope="row">{{$hours->student_name}}</th>
                  <td>{{$hours->department_name}}</td>
                  <td>{{$hours->grade_name}}</td>
                  <td>{{$hours->course_name}}</td>
                  <td>{{$hours->hour_used}}</td>
                  <td>{{$hours->hour_remain}}</td>
                </tr>
              @empty
                <tr>
                  <th colspan="6">无</th>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  @endif
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('home');
</script>
@endsection
