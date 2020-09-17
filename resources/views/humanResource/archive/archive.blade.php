@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">员工档案</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">人事中心</li>
    <li class="breadcrumb-item active">员工档案</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="/humanResource/archive/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加档案">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">添加档案</span>
      </a>
      <button class="btn btn-sm btn-outline-danger btn-round btn-icon" data-toggle="tooltip" data-original-title="批量删除" onclick="batchDeleteConfirm('/humanResource/archive/delete', '确认批量删除所选档案？')">
        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
        <span class="btn-inner--text">批量删除</span>
      </button>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card mb-4">
        <div class="card-header p-1" style="border-bottom:0px;">
          <div class="row m-2">
            <div class="col-12">
              <small class="text-muted font-weight-bold px-2">校区：</small>
              <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach">
                <button type="button" @if(!isset($filters['filter_department'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
              </a>
              @foreach($filter_departments as $filter_department)
                <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach &filter_department={{$filter_department->department_id}}"><button type="button" @if($filters['filter_department']==$filter_department->department_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_department->department_name}}</button></a>
              @endforeach
            </div>
          </div>
        </div>
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover table-bordered text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:50px;'>序号</th>
                <th style='width:90px;'>用户</th>
                <th style='width:320px;'>档案</th>
                <th style='width:100px;'>校区</th>
                <th style='width:90px;'>部门</th>
                <th style='width:90px;'>岗位</th>
                <th style='width:120px;'>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="8">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($row->archive_id, 'archive_id')}}'>
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>
                  <a href="/user?id={{encode($row->user_id,'user_id')}}">{{ $row->user_name }}</a>&nbsp;
                  @if($row->user_gender=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td><a href="/files/archive/{{$row->archive_path}}" target="_blank">{{ $row->archive_name }}</a></td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->section_name }}</td>
                <td>{{ $row->position_name }}</td>
                <td>
                  <a href='/humanResource/archive/download?id={{encode($row->archive_id, 'archive_id')}}'><button type="button" class="btn btn-primary btn-sm">文件下载</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/humanResource/archive/delete?id={{encode($row->archive_id, 'archive_id')}}', '确认删除该档案？')">删除</button>
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
  linkActive('link-humanResource');
  navbarActive('navbar-humanResource');
  linkActive('humanResourceArchive');
</script>
@endsection
