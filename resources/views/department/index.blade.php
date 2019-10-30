@extends('../main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">校区管理</li>
@endsection

@section('content')
<div class="container-fluid mt--6">
  <div class="row justify-content-center">
    <div class="col-lg-12 card-wrapper ct-example">
      <div class="card" id="main_card" style="display:none">
        <!-- Card header -->
        <div class="card-header border-0">
          <div class="row">
            <div class="col-6">
              <h2 class="mb-0">校区列表</h2>
            </div>
            <div class="col-6 text-right">
              <a href="department/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加校区">
                <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
                <span class="btn-inner--text">添加校区</span>
              </a>
            </div>
          </div>
        </div>
        <div class="table-responsive pl-4 pr-4">
          <table class="table align-items-center table-flush table-hover text-center">
            <thead class="thead-light">
              <tr>
                <th style='width:30%;'>序号</th>
                <th style='width:30%;'>校区名称</th>
                <th>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($departments as $department)
              <tr>
                <td class="p-2">{{ $startIndex+$loop->iteration }}</td>
                <td class="p-2">{{ $department->department_name }}</td>
                <td class="p-2">
                  <form action="department/{{$department->department_id}}" method="POST">
                    @method('DELETE')
                    @csrf
                    <a href='department/{{$department->department_id}}'><button type="button" class="btn btn-primary btn-sm">查看详情</button></a>
                    {{ deleteConfirm($department->department_id, ["校区名称：".$department->department_name]) }}
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
  sidebarActive('department');
</script>
@endsection
