@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">员工档案</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">人事管理</li>
              <li class="breadcrumb-item active">员工档案</li>
            </ol>
          </nav>
        </div>
        <div class="col-6 text-right">
          <a href="archive/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加档案">
            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
            <span class="btn-inner--text">添加档案</span>
          </a>
          <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
            <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
            <span class="btn-inner--text">搜索</span>
          </a>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="collapse" id="filter">
        <div class="card mb-1">
          <div class="card-header border-0 p-0 mb-1">
            <form action="" method="get" id="filter" name="filter">
              <div class="row m-2">
                <div class="col-lg-2 col-md-3 col-sm-4 mb-1">
                  <input class="form-control" type="text" name="filter1" placeholder="校区名称..." autocomplete="off" @if($request->filled('filter1')) value="{{ $request->filter1 }}" @endif>
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
      </div>
      <div class="card main_card mb-4" style="display:none">
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:10%;'>序号</th>
                <th style='width:15%;'>校区</th>
                <th style='width:15%;'>用户</th>
                <th style='width:20%;'>档案名称</th>
                <th style='width:15%;'>文件大小</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="6">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="上传时间：{{ $row->archive_createtime }}">
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $row->department_name }}</td>
                <td class="p-2">{{ $row->user_name }}</td>
                <td class="p-2">{{ $row->archive_name }}</td>
                <td class="p-2">{{ $row->archive_file_size }}MB</td>
                <td class="p-2">
                  <form action="/archive/{{$row->archive_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/archive/{{$row->archive_id}}'><button type="button" class="btn btn-primary btn-sm">下载档案</button></a>
                    {{ deleteConfirm($row->archive_id, ["档案名称：".$row->archive_name]) }}
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
  linkActive('link-human');
  navbarActive('navbar-human');
  linkActive('archive');
</script>
@endsection
