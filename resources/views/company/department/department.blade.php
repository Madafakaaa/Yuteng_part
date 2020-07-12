@extends('main')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">校区设置</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">公司管理</li>
              <li class="breadcrumb-item active">校区设置</li>
            </ol>
          </nav>
        </div>
        <!--<div class="col-6 text-right">
          <a href="/company/department/create" class="btn btn-sm btn-neutral btn-round btn-icon" data-toggle="tooltip" data-original-title="添加校区">
            <span class="btn-inner--icon"><i class="fas fa-user-edit"></i></span>
            <span class="btn-inner--text">添加校区</span>
          </a>
        </div>-->
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-3">
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
  <div class="row">
    <div class="col-xl-3 col-md-6">
      <div class="card card-stats">
        <!-- Card body -->
        <div class="card-body">
          <div class="row">
            <div class="col">
              <h5 class="card-title text-uppercase text-muted mb-0">Total traffic</h5>
              <span class="h2 font-weight-bold mb-0 counter-value">350,897</span>
            </div>
            <div class="col-auto">
              <div class="icon icon-shape bg-gradient-red text-white rounded-circle shadow">
                <i class="ni ni-active-40"></i>
              </div>
            </div>
          </div>
          <p class="mt-3 mb-0 text-sm">
            <span class="text-success mr-2"><i class="fa fa-arrow-up"></i> 3.48%</span>
            <span class="text-nowrap">Since last month</span>
          </p>
        </div>
      </div>
    </div>
  </div>
  <div class="row justify-content-center">
    <div class="col-12">
      <div class="card main_card mb-4" style="display:none">
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
<script type="text/javascript">
$(document).ready(function(){
	$('.counter-value').each(function(){
		$(this).prop('Counter',0).animate({
			Counter: $(this).text()
		},{
			duration: 3500,
			easing: 'swing',
			step: function (now){
				$(this).text(Math.ceil(now));
			}
		});
	});
});
</script>
@endsection
