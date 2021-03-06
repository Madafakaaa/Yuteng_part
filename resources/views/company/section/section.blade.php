@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">部门架构</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">公司管理</li>
    <li class="breadcrumb-item active">部门架构</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
    <div class="col-lg-4 col-md-12 col-sm-12 card-wrapper ct-example">
      <div class="card">
        <div class="card-header">
          <div class="row">
            <div class="col-6">
              <h5 class="h3 mb-0">部门列表</h5>
            </div>
            <div class="col-6 text-right">
              <a href="/company/section/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加部门">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加部门</span>
              </a>
            </div>
          </div>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush list my--3">
            @if($request->filled('section'))
            <li class="list-group-item px-0">
              <div class="row align-items-center">
                <div class="col ml-2">
                  <h4 class="mb-0">
                    <a href="?">查看全部</a>
                  </h4>
                </div>
              </div>
            </li>
            @endif
            @foreach ($sections as $section)
            <li class="list-group-item px-0">
              <div class="row align-items-center">
                <div class="col-auto">
                  <h4 class="mb-0">
                    {{ $loop->iteration }}
                  </h4>
                </div>
                <div class="col ml--2">
                  <h4 class="mb-0">
                    <a href="?section={{$section->section_id}}" @if($request->input('section')==$section->section_id) style="color:red;" @endif>{{ $section->section_name }}</a>
                  </h4>
                </div>
                <div class="col-auto">
                  <a href='/company/section/edit?id={{encode($section->section_id, 'section_id')}}'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/company/section/delete?id={{encode($section->section_id, 'section_id')}}', '确认删除部门？')">删除</button>
                </div>
              </div>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-md-12 col-sm-12 card-wrapper ct-example">
      <div class="card mb-4">
        <!-- Card header -->
        <div class="card-header">
          <div class="row">
            <div class="col-6">
              <h5 class="h3 mb-0">岗位列表</h5>
            </div>
            <div class="col-6 text-right">
              <a href="/company/position/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加岗位">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加岗位</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:180px;'>岗位</th>
                <th style='width:180px;'>部门</th>
                <th style='width:140px;'>等级</th>
                <th style='width:180px;'>操作管理</th>
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
                <td>
                  <a href='/company/position/edit?id={{encode($row->position_id, 'position_id')}}'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/company/position/delete?id={{encode($row->position_id, 'position_id')}}', '确认删除岗位？')">删除</button>
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('companySection');
</script>
@endsection
