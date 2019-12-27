@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item active">等级设置</li>
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
                <input class="form-control" type="text" name="filter1" placeholder=" 等级名称..." autocomplete="off" autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
              </div>
              <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                <div class="row">
                  <div class="col-6">
                    <input type="submit" class="btn btn-primary btn-block" value="查询">
                  </div>
                  <div class="col-6">
                    <a href="?"><button type="button" class="form-control btn btn-outline-primary btn-block" style="white-space:nowrap; overflow:hidden;">重置</button></a>
                  </div>
                </div>
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
              <h2 class="mb-0">等级列表</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/level/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加等级">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加等级</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:30%;'>序号</th>
                <th style='width:30%;'>等级名称</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr><td colspan="3">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $row->level_name }}</td>
                <td class="p-2">
                  <form action="level/{{$row->level_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/level/{{$row->level_id}}/edit'><button type="button" class="btn btn-primary btn-sm">修改信息</button></a>
                    {{ deleteConfirm($row->level_id, ["等级名称：".$row->level_name]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
        {{ pageLink($currentPage, $totalPage, $request, $totalNum) }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('link-1-2');
  navbarActive('navbar-1-2');
  linkActive('level');
</script>
@endsection
