@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">校区设置</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">公司管理</li>
    <li class="breadcrumb-item active">校区设置</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="/company/department/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加校区">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">添加校区</span>
      </a>
      <button class="btn btn-sm btn-outline-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="批量删除校区" onclick="batchDeleteConfirm('/company/department/delete', '确认批量删除所选校区？')">
        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
        <span class="btn-inner--text">批量删除</span>
      </button>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:100px;'>校区</th>
                <th style='width:120px;'></th>
                <th style='width:205px;'>电话1</th>
                <th style='width:205px;'>电话2</th>
                <th style='width:500px;'>地址</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
                <tr class="text-center"><td colspan="7">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($row->department_id, 'department_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>

                <td>{{ $startIndex+$loop->iteration }}</td>

                <td>{{ $row->department_name }}</td>

                <td>
                  <a href='/company/department/edit?id={{encode($row->department_id, 'department_id')}}'><button type="button" class="btn btn-primary btn-sm">修改</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/company/department/delete?id={{encode($row->department_id, 'department_id')}}', '确认删除校区？')">删除</button>
                </td>

                <td>{{ $row->department_phone1 }}</td>

                <td>{{ $row->department_phone2 }}</td>

                <td>{{ $row->department_location }}</td>
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
  linkActive('companyDepartment');
</script>
@endsection
