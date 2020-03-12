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
          <h6 class="h2 text-white d-inline-block mb-0">教案中心</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">教学中心</li>
              <li class="breadcrumb-item active">教案中心</li>
            </ol>
          </nav>
        </div>
        <div class="col-6 text-right">
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
                <th style='width:70px;'>序号</th>
                <th style='width:259px;'>教案名称</th>
                <th style='width:80px;'>科目</th>
                <th style='width:80px;'>年级</th>
                <th style='width:100px;'>学期</th>
                <th style='width:90px;'>校区</th>
                <th style='width:100px;'>文件大小</th>
                <th style='width:100px;'>下载次数</th>
                <th style='width:140px;'>上传用户</th>
                <th style='width:110px;'>上传日期</th>
                <th style='width:188px;'>操作管理</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="11">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->document_name }}</td>
                <td>{{ $row->subject_name }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->document_semester }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->document_file_size }}MB</td>
                <td>{{ $row->document_download_time }}</td>
                <td>{{ $row->user_name }} ({{ $row->position_name }})</td>
                <td>{{ date('Y-m-d', strtotime($row->document_createtime)) }}</td>
                <td>
                  <form action="/education/document/{{$row->document_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/education/document/{{$row->document_id}}'><button type="button" class="btn btn-primary btn-sm">下载教案</button></a>
                    {{ deleteConfirm($row->document_id, ["教案名称：".$row->document_name]) }}
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
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationDocument');
</script>
@endsection
