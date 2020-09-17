@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">离职用户</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">人事中心</li>
    <li class="breadcrumb-item active">离职用户</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row mb-3">
    <div class="col-auto">
      <button class="btn btn-sm btn-outline-primary btn-round btn-icon" data-toggle="tooltip" data-original-title="批量恢复" onclick="batchDeleteConfirm('/humanResource/user/deleted/restore', '确认批量恢复所选离职用户？')">
        <span class="btn-inner--icon"><i class="fas fa-trash"></i></span>
        <span class="btn-inner--text">批量恢复</span>
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
                <th style='width:70px;'>序号</th>
                <th style='width:150px;'>姓名</th>
                <th style='width:100px;'>账号</th>
                <th style='width:100px;'>校区</th>
                <th style='width:90px;'>部门</th>
                <th style='width:90px;'>岗位</th>
                <th style='width:70px;'>等级</th>
                <th style='width:90px;'>跨校区教学</th>
                <th style='width:120px;'>手机</th>
                <th style='width:120px;'>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @if(count($rows)==0)
              <tr class="text-center"><td colspan="12">当前没有记录</td></tr>
              @endif
              @foreach ($rows as $row)
              <tr>
                <td>
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value='{{encode($row->user_id, 'user_id')}}'>
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
                <td>{{ $row->user_id }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->section_name }}</td>
                <td>{{ $row->position_name }}</td>
                <td>{{ $row->position_level }}</td>
                @if($row->user_cross_teaching==1)
                  <td><span style="color:green;">是</span></td>
                @else
                  <td><span style="color:red;">否</span></td>
                @endif
                <td>{{ $row->user_phone }}</td>
                <td>
                  <button type="button" class="btn btn-outline-primary btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/humanResource/user/deleted/restore?id={{encode($row->user_id, 'user_id')}}', '确认恢复该离职用户？')">恢复</button>
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
  linkActive('humanResourceUserDeleted');
</script>
@endsection
