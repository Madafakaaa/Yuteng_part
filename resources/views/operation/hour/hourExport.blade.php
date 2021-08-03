@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">学生课时 @if (Session::get('user_access_self')==1) （个人） @endif</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">运营中心</li>
    <li class="breadcrumb-item active">学生课时</li>
  </ol>
</nav>
@endsection

@section('content')

<div class="main-content" id="panel">
  <div class="container-fluid mt-3">
    <div class="row justify-content-center">
      <div class="col-12">
        <div class="card mb-4">
          <div class="table-responsive">
            <button type="button" class="btn btn-waring btn-block" onclick="table_export('table-1', '课时名单')">导出表格</button>
            <table class="table text-left table-bordered" id="table-1">
              <thead class="thead-light">
                <tr>
                  <th style='width:120px;'>学生</th>
                  <th style='width:90px;'>校区</th>
                  <th style='width:120px;'>电话</th>
                  <th style='width:60px;'>年级</th>
                  <th style='width:180px;'>课程</th>
                  <th style='width:110px;' class="text-right">剩余</th>
                  <th style='width:110px;' class="text-right">已消耗</th>
                  <th style='width:110px;' class="text-right">平均价格</th>
                  <th style='width:145px;'>班主任</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($datas as $data)
                <tr>
                  <td>
                    <a href="/student?id={{encode($data['student_id'], 'student_id')}}">{{ $data['student_name'] }}</a>&nbsp;
                    @if($data['student_gender']=="男")
                      <img src="{{ asset(_ASSETS_.'/img/icons/male.png') }}" style="height:20px;">
                    @else
                      <img src="{{ asset(_ASSETS_.'/img/icons/female.png') }}" style="height:20px;">
                    @endif
                  </td>
                  <td>{{ $data['department_name'] }}</td>
                  <td>{{ $data['student_phone'] }}</td>
                  <td>{{ $data['grade_name'] }}</td>
                  <td><strong>{{ $data['course_name'] }}</strong></td>
                  @if($data['hour_remain']<5)
                    <td class="text-right"><span class="text-danger">{{ $data['hour_remain'] }} 课时</span></td>
                  @else
                    <td class="text-right">{{ $data['hour_remain'] }} 课时</td>
                  @endif
                  <td class="text-right">{{ $data['hour_used'] }} 课时</td>
                  <td class="text-right">{{ $data['hour_average_price'] }} 元</td>
                  @if($data['class_adviser_name']=="")
                    <td><span style="color:red;">无</span></td>
                  @else
                    <td><a href="/user?id={{encode($data['class_adviser_id'],'user_id')}}">{{ $data['class_adviser_name'] }}</a> [ {{ $data['class_adviser_position_name'] }} ]</td>
                  @endif
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationHour');
</script>
@endsection
