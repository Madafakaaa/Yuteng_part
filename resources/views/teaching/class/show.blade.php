@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教务中心</li>
    <li class="breadcrumb-item"><a href="/class">班级管理</a></li>
    <li class="breadcrumb-item active">班级详情</li>
@endsection

@section('content')
<div class="container-fluid">
  <div class="row justify-content-center">
    <div class="col-lg-8 col-md-8 col-sm-12 card-wrapper ct-example">
      <div class="nav-wrapper">
        <ul class="nav nav-pills nav-fill flex-column flex-md-row" id="tabs-icons-text" role="tablist">
          <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0 active" id="tabs-icons-text-1-tab" data-toggle="tab" href="#tabs-icons-text-1" role="tab" aria-controls="tabs-icons-text-1" aria-selected="true"><i class="ni ni-badge mr-2"></i>班级详情</a>
          </li>
          <li class="nav-item">
            <a class="nav-link mb-sm-3 mb-md-0" id="tabs-icons-text-2-tab" data-toggle="tab" href="#tabs-icons-text-2" role="tab" aria-controls="tabs-icons-text-2" aria-selected="false"><i class="ni ni-archive-2 mr-2"></i>班级成员</a>
          </li>
        </ul>
      </div>
      <div class="card shadow">
        <div class="card-body">
          <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="tabs-icons-text-1" role="tabpanel" aria-labelledby="tabs-icons-text-1-tab">
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">班级班号</label>
                    <input class="form-control" type="text" value="{{ $class->class_id }}" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">班级名称</label>
                    <input class="form-control" type="text" value="{{ $class->class_name }}" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">班级校区</label>
                    <input class="form-control" type="text" value="{{ $class->department_name }}" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">班级年级</label>
                    <input class="form-control" type="text" value="{{ $class->grade_name }}" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">班级科目</label>
                    @if($class->class_subject==0)
                      <input class="form-control" type="text" value="全科目" readonly>
                    @else
                      <input class="form-control" type="text" value="{{ $class->subject_name }}" readonly>
                    @endif
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">负责教师</label>
                    <input class="form-control" type="text" value="{{ $class->user_name }}" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">最大人数</label>
                    <input class="form-control" type="text" value="{{ $class->class_max_num }}人" readonly>
                  </div>
                </div>
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">当前人数</label>
                    <input class="form-control" type="text" value="{{ $class->class_current_num }}人" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-6">
                  <div class="form-group">
                    <label class="form-control-label">添加时间</label>
                    <input class="form-control" type="text" value="{{ $class->class_createtime }}" readonly>
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-4">
                  <a href="/class/{{ $class->class_id }}/edit"><button class="btn btn-block btn-warning">修改</button></a>
                </div>
              </div>
            </div>
            <div class="tab-pane fade" id="tabs-icons-text-2" role="tabpanel" aria-labelledby="tabs-icons-text-2-tab">
              <form action="/member/{{ $class->class_id }}" method="post" id="form1" name="form1">
                @csrf
                <div class="form-group row">
                  <div class="col-lg-2 col-md-2 col-sm-1"></div>
                  <label class="col-lg-2 col-md-2 col-sm-2 col-form-label form-control-label text-center">添加学生</label>
                  <div class="col-lg-4 col-md-4 col-sm-6">
                    @if($class->class_max_num>$class->class_current_num)
                      <select class="form-control" name="input1" data-toggle="select" required>
                        <option value=''>请选择学生...</option>
                        @foreach ($students as $student)
                          <option value="{{ $student->student_id }}">{{ $student->student_name }}</option>
                        @endforeach
                      </select>
                    @else
                      <select class="form-control" data-toggle="select" disabled>
                        <option value=''>班级已满</option>
                      </select>
                    @endif
                  </div>
                  <div class="col-lg-2 col-md-2 col-sm-2">
                    @if($class->class_max_num>$class->class_current_num)
                      <input type="submit" class="btn btn-primary btn-block" value="添加">
                    @else
                      <input type="submit" class="btn btn-primary btn-block" value="班级已满" disabled>
                    @endif
                  </div>
                </div>
              </form>
              <table class="table align-items-center table-flush table-hover text-center">
                <thead class="thead-light">
                  <tr>
                    <th style='width:10%;'>序号</th>
                    <th style='width:30%;'>学生姓名</th>
                    <th style='width:30%;'>学生学号</th>
                    <th>操作管理</th>
                  </tr>
                </thead>
                <tbody>
                  @if(count($rows)==0)
                  <tr><td colspan="4">当前没有记录</td></tr>
                  @endif
                  @foreach ($rows as $row)
                  <tr>
                    <td class="p-2">{{ $loop->iteration }}</td>
                    <td class="p-2">{{ $row->student_name }}</td>
                    <td class="p-2">{{ $row->student_id }}</td>
                    <td class="p-2">
                      <form action="/member/{{$row->class_id}}" method="POST">
                        <input type="hidden" name="input1" value="{{ $row->student_id }}">
                        @method('DELETE')
                        @csrf
                        {{ deleteConfirm($row->student_id, ["学生姓名：".$row->student_name]) }}
                      </form>
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
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-3');
  navbarActive('navbar-3');
  linkActive('class');
</script>
@endsection
