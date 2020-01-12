@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item active">学生课程表</li>
@endsection

@section('content')
<div class="container-fluid mt--6">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-3">
        <div class="card-header border-0 p-0 m-2">
          <form action="/schedule/student" method="post" id="filter" name="filter">
          @csrf
            <div class="row m-2">
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <select class="form-control" name="filter1" data-toggle="select">
                  <option value=''>请选择学生...</option>
                </select>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input class="form-control datepicker" name="filter2" type="text" value="{{ date('Y-m-d') }}" required>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input type="submit" class="btn btn-primary btn-block" value="查询">
              </div>
            </div>
          </form>
        </div>
      </div>
      <div class="card main_card" style="display:none">
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <div class="col-6">
              <h2 class="mb-0">课程安排</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/payment/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加购课">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">新建排课</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-hover table-bordered table-sm text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:8%;' class="p-2"></th>
                @foreach ($days as $day)
                  <th style='width:12%;' class="p-2">{{ $day }}</th>
                @endforeach
              </tr>
            </thead>
            <tbody class="p-0">
              <tr>
                <td class="p-0">8:00</th>
                <td class="p-0" rowspan="3" style="">gg<br>fsds<br>dsds<br>dasdsa</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">8:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">9:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">9:30</th>
                <td class="p-0" rowspan="3">gg</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">10:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">10:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">11:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">11:30</th>
                <td class="p-0" rowspan="3">gg</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">12:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">12:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">13:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">13:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">14:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">14:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">15:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">15:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">16:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">16:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">17:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">17:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">18:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">18:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">19:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">19:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">20:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">20:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">21:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">21:30</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
              <tr>
                <td class="p-0">22:00</th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
                <td class="p-0"> </th>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-3');
  navbarActive('navbar-3');
  linkActive('schedule/student');
</script>
@endsection
