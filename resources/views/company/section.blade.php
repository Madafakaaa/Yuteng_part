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
          <h6 class="h2 text-white d-inline-block mb-0">部门架构</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">公司管理</li>
              <li class="breadcrumb-item active">部门架构</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
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
                    {{$section->section_id}}
                  </h4>
                </div>
                <div class="col ml--2">
                  <h4 class="mb-0">
                    <a href="?section={{$section->section_id}}" @if($request->input('section')==$section->section_id) style="color:red;" @endif>{{ $section->section_name }}</a>
                  </h4>
                </div>
                <div class="col-auto">
                  <form action="/company/section/{{$section->section_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/company/section/{{$section->section_id}}'><button type="button" class="btn btn-sm btn-primary">修改</button></a>
                    {{ deleteConfirm($section->section_id, ["部门名称：".$section->section_name]) }}
                  </form>
                </div>
              </div>
            </li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>
    <div class="col-lg-8 col-md-12 col-sm-12 card-wrapper ct-example">
      <div class="card main_card mb-4" style="display:none">
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
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:70px;'>序号</th>
                <th style='width:180px;'>岗位</th>
                <th style='width:180px;'>部门</th>
                <th style='width:140px;'>等级</th>
                <th style='width:180px;'>操作管理</th>
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
                <td>
                  <form action="/company/position/{{$row->position_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/company/position/{{$row->position_id}}'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
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
  linkActive('link-company');
  navbarActive('navbar-company');
  linkActive('companySection');
</script>
@endsection
