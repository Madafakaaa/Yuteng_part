@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">档案管理</li>
@endsection

@section('content')
<div class="container-fluid mt--6">
  <div class="row justify-content-center">
    <div class="col-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <div class="col-6">
              <h2 class="mb-0">档案列表</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/archive/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加档案">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加档案</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
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
              <tr><td colspan="10">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
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
        {{ pageLink($currentPage, $totalPage) }}
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  sidebarActive('section1');
  sidebarActive('archive');
</script>
@endsection
