@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">用户档案</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">人事中心</li>
    <li class="breadcrumb-item active">用户档案</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row">
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
        <hr>
        <div class="table-responsive pb-4">
          <table class="table table-flush table-bordered datatable-basic">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:120px;'>姓名</th>
                <th style='width:90px;'>校区</th>
                <th style='width:90px;'>岗位</th>
                <th style='width:300px;'>操作管理</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($rows as $row)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td>
                  <a href="/user?id={{encode($row->user_id,'user_id')}}">{{ $row->user_name }}</a>&nbsp;
                  @if($row->user_gender=="男")
                    <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                  @else
                    <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                  @endif
                </td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->section_name }} - {{ $row->position_name }}</td>
                <td>
                  <a href='/humanResource/archive/lesson?id={{encode($row->user_id, 'user_id')}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">上课记录</button></a>
                  <a href='/humanResource/archive/contract?id={{encode($row->user_id, 'user_id')}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">签约记录</button></a>
                  <a href='/humanResource/archive/record?id={{encode($row->user_id, 'user_id')}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">员工动态</button></a>
                  <a href='/humanResource/archive/archive?id={{encode($row->user_id, 'user_id')}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">档案文件</button></a>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
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
