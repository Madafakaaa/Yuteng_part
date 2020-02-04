@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item active">部门设置</li>
@endsection

@section('content')
<div class="container-fluid mt-2">
  <div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12 card-wrapper ct-example">
      <div class="card main_card mb-4" style="display:none">
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <div class="col-6">
              <h4 class="mb-0">部门列表</h4>
            </div>
            <div class="col-6 text-right">
              <a href="/section/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加部门">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加部门</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:180px;'>部门</th>
                <th style='width:168px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="3">当前没有记录</td></tr>
              @endif
              @foreach ($sections as $section)
              <tr>
                <td>{{ $section->section_id }}</td>
                <td>{{ $section->section_name }}</td>
                <td>
                  <form action="section/{{$section->section_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/section/{{$section->section_id}}/edit'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                    {{ deleteConfirm($section->section_id, ["部门名称：".$section->section_name]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-md-12 col-sm-12 card-wrapper ct-example">
      <div class="card mb-1">
        <div class="card-header border-0 p-0 m-1">
          <form action="" method="get" id="filter" name="filter">
            <div class="row m-2">
              <div class="col-6 mb-1">
                <input class="form-control" type="text" name="filter1" placeholder="岗位名称..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
              </div>
              <div class="col-6 mb-1">
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
      <div class="card main_card mb-4" style="display:none">
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <div class="col-6">
              <h4 class="mb-0">岗位列表</h4>
            </div>
            <div class="col-6 text-right">
              <a href="/position/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加岗位">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加岗位</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:80px;'>序号</th>
                <th style='width:180px;'>岗位</th>
                <th style='width:180px;'>部门</th>
                <th style='width:140px;'>等级</th>
                <th style='width:140px;'>权限</th>
                <th style='width:147px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="6">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->position_name }}</td>
                <td>{{ $row->section_name }}</td>
                <td>等级 {{ $row->position_level }}</td>
                @if($row->position_view_all==1)
                  <td>全部校区 <img src="{{ asset(_ASSETS_.'/img/icons/common/all.png') }}"></td>
                @else
                  <td>所在校区</td>
                @endif
                <td>
                  <form action="position/{{$row->position_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/position/{{$row->position_id}}/edit'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                    {{ deleteConfirm($row->position_id, ["岗位名称：".$row->position_name]) }}
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      {{ pageLink($currentPage, $totalPage, $request, $totalNum) }}
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('section');
</script>
@endsection
