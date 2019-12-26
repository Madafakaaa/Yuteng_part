@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">课程管理</li>
    <li class="breadcrumb-item active">时间设置</li>
@endsection

@section('content')
<div class="container-fluid mt--6">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card mb-3">
        <div class="card-header border-0 p-0 m-2">
          <form action="" method="get" id="filter" name="filter">
            <div class="row m-2">
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <input class="form-control" type="time" name="filter1" value="{{ date('H:i') }}" autocomplete="off">
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
              <h2 class="mb-0">上课时间</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/timeset/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加时间">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加时间</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:10%;'>序号</th>
                <th style='width:30%;'>开始时间</th>
                <th style='width:30%;'>结束时间</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr><td colspan="4">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $row->timeset_start }}</td>
                <td class="p-2">{{ $row->timeset_end }}</td>
                <td class="p-2">
                  <form action="timeset/{{$row->timeset_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/timeset/{{$row->timeset_id}}/edit'><button type="button" class="btn btn-primary btn-sm">修改信息</button></a>
                    {{ deleteConfirm($row->timeset_id, ["开始时间：".$row->timeset_start."，结束时间:".$row->timeset_end]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ pageLink($currentPage, $totalPage) }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-3');
  navbarActive('navbar-1-3');
  linkActive('timeset');
</script>
@endsection
