@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">教案中心</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教学中心</li>
    <li class="breadcrumb-item active">教案中心</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row mb-3">
    <div class="col-auto">
      <a href="/education/document/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加教案">
        <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
        <span class="btn-inner--text">添加教案</span>
      </a>
      <a class="btn btn-sm btn-neutral btn-round btn-icon"data-toggle="collapse" href="#filter" role="button" aria-expanded="false" aria-controls="filter">
        <span class="btn-inner--icon"><i class="fas fa-search"></i></span>
        <span class="btn-inner--text">搜索</span>
      </a>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="collapse @if($filter_status==1) show @endif" id="filter">
        <div class="card mb-4">
          <div class="card-body border-1 p-0 my-1">
            <form action="" method="get">
              <div class="row m-2">
                <div class="col-lg-8 col-md-8 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-4 col-md-6 col-sm-12 mb-1">
                      <input class="form-control" type="text" name="filter_name" placeholder="教案名称..." autocomplete="off" @if(isset($filters['filter_name']))) value="{{ $filters['filter_name'] }}" @endif>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 mb-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
                      <input type="hidden" name="filter_department" value="{{$filters['filter_department']}}">
                      <input type="hidden" name="filter_grade" value="{{$filters['filter_grade']}}">
                      <input type="hidden" name="filter_subject" value="{{$filters['filter_subject']}}">
                      <input type="submit" class="btn btn-primary btn-block" value="查询">
                    </div>
                    <div class="col-lg-6 col-md-12 col-sm-12 mb-1">
                      <a href="?"><button type="button" class="form-control btn btn-outline-primary btn-block" style="white-space:nowrap; overflow:hidden;">重置</button></a>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="card mb-4">
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">校区：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_department'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_departments as $filter_department)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_department') {{$key}}={{$value}}& @endif @endforeach &filter_department={{$filter_department->department_id}}"><button type="button" @if($filters['filter_department']==$filter_department->department_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_department->department_name}}</button></a>
          @endforeach
        </div>
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">年级：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_grade'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_grades as $filter_grade)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_grade') {{$key}}={{$value}}& @endif @endforeach filter_grade={{$filter_grade->grade_id}}"><button type="button" @if($filters['filter_grade']==$filter_grade->grade_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_grade->grade_name}}</button></a>
          @endforeach
        </div>
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">科目：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_subject') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_subject'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          @foreach($filter_subjects as $filter_subject)
            <a href="?@foreach($filters as $key => $value) @if($key!='filter_subject') {{$key}}={{$value}}& @endif @endforeach filter_subject={{$filter_subject->subject_id}}"><button type="button" @if($filters['filter_subject']==$filter_subject->subject_id) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>{{$filter_subject->subject_name}}</button></a>
          @endforeach
        </div>
        <div class="card-header p-2" style="border-bottom:0px;">
          <small class="text-muted font-weight-bold px-2">学期：</small>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_semester') {{$key}}={{$value}}& @endif @endforeach">
            <button type="button" @if(!isset($filters['filter_semester'])) class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>全部</button>
          </a>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_semester') {{$key}}={{$value}}& @endif @endforeach filter_semester=第一学期"><button type="button" @if($filters['filter_semester']=='第一学期') class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>第一学期</button></a>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_semester') {{$key}}={{$value}}& @endif @endforeach filter_semester=第二学期"><button type="button" @if($filters['filter_semester']=='第二学期') class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>第二学期</button></a>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_semester') {{$key}}={{$value}}& @endif @endforeach filter_semester=寒假"><button type="button" @if($filters['filter_semester']=='寒假') class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>寒假</button></a>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_semester') {{$key}}={{$value}}& @endif @endforeach filter_semester=暑假"><button type="button" @if($filters['filter_semester']=='暑假') class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>暑假</button></a>
          <a href="?@foreach($filters as $key => $value) @if($key!='filter_semester') {{$key}}={{$value}}& @endif @endforeach filter_semester=其它"><button type="button" @if($filters['filter_semester']=='其它') class="btn btn-primary btn-sm" disabled @else class="btn btn-sm" @endif>其它</button></a>
        </div>
        <div class="table-responsive freeze-table-4">
          <table class="table align-items-center table-hover text-left">
            <thead class="thead-light">
              <tr>
                <th style='width:40px;'></th>
                <th style='width:70px;'>序号</th>
                <th style='width:200px;'>教案名称</th>
                <th style='width:130px;'></th>
                <th style='width:80px;'>科目</th>
                <th style='width:80px;'>年级</th>
                <th style='width:100px;'>学期</th>
                <th style='width:90px;'>校区</th>
                <th style='width:100px;'>文件大小</th>
                <th style='width:100px;'>下载次数</th>
                <th style='width:140px;'>上传用户</th>
                <th style='width:110px;'>上传日期</th>
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
                    <input type="checkbox" class="custom-control-input" id="checkbox_{{ $loop->iteration }}" name="id" value="{{encode($row->document_id, 'document_id')}}">
                    <label class="custom-control-label" for="checkbox_{{ $loop->iteration }}"></label>
                  </div>
                </td>
                <td>{{ $startIndex+$loop->iteration }}</td>
                <td>{{ $row->document_name }}</td>
                <td>
                  <a href="/education/document/download?id={{encode($row->document_id, 'document_id')}}"><button type="button" class="btn btn-primary btn-sm">下载教案</button></a>
                  <button type="button" class="btn btn-outline-danger btn-sm delete-button" id='delete_button_{{$loop->iteration}}' onclick="deleteConfirm('delete_button_{{$loop->iteration}}', '/education/document/delete?id={{encode($row->document_id, 'document_id')}}', '确认删除教案？')">删除</button>
                </td>
                <td>{{ $row->subject_name }}</td>
                <td>{{ $row->grade_name }}</td>
                <td>{{ $row->document_semester }}</td>
                <td>{{ $row->department_name }}</td>
                <td>{{ $row->document_file_size }}MB</td>
                <td>{{ $row->document_download_time }}</td>
                <td><a href="/user?id={{encode($row->user_id,'user_id')}}">{{ $row->user_name }}</a> ({{ $row->position_name }})</td>
                <td>{{ date('Y-m-d', strtotime($row->document_createtime)) }}</td>
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
