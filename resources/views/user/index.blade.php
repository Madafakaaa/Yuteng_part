@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">用户管理</li>
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
              <h2 class="mb-0">用户列表</h2>
            </div>
            <div class="col-6 text-right">
              <a href="/user/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加用户">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加用户</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:10%;'>序号</th>
                <th style='width:15%;'>用户名称</th>
                <th style='width:15%;'>用户序号</th>
                <th style='width:15%;'>用户校区</th>
                <th style='width:15%;'>用户岗位</th>
                <th style='width:15%;'>用户等级</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr><td colspan="10">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr title="入职日期: {{ $row->user_dateOfEntry }}, 手机: {{ $row->user_phone }}, 微信: {{ $row->user_wechat }}。">
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $row->user_name }}</td>
                <td class="p-2">{{ $row->user_id }}</td>
                <td class="p-2">{{ $row->department_name }}</td>
                <td class="p-2">{{ $row->position_name }}</td>
                <td class="p-2">等级{{ $row->user_level }}</td>
                <td class="p-2">
                  <form action="/user/{{ $row->user_id }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='/user/{{$row->user_id}}'><button type="button" class="btn btn-primary btn-sm">查看详情</button></a>
                    {{ deleteConfirm($row->user_id, ["用户名称：".$row->user_name]) }}
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
  sidebarActive('user');
</script>
@endsection
