@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">学校管理</li>
    <li class="breadcrumb-item active">用户管理</li>
    <li class="breadcrumb-item"><a href="/position">岗位设置</a></li>
    <li class="breadcrumb-item active">修改岗位</li>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example">
      <div class="card main_card" style="display:none">
        <form action="/position/{{ $position->position_id }}" method="post" id="form1" name="form1">
          @method('PUT')
          @csrf
          <div class="card-header">
            <h4 class="mb-0">修改岗位</h4>
          </div>
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">岗位名称<span style="color:red">*</span></label>
                  <input class="form-control" type="text" name="input1" value="{{ $position->position_name }}" autocomplete='off' required maxlength="10">
                </div>
              </div>
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">所属部门<span style="color:red">*</span></label>
                  <select class="form-control" name="input2" data-toggle="select" required>
                    <option value=''>请选择部门...</option>
                    @foreach ($sections as $section)
                      <option value="{{ $section->section_id }}" @if($position->position_section==$section->section_id) selected @endif>{{ $section->section_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-6">
                <div class="form-group">
                  <label class="form-control-label">岗位等级<span style="color:red">*</span></label>
                  <select class="form-control" name="input3" data-toggle="select" required>
                    <option value=''>请选择等级...</option>
                    <option value='1' @if($position->position_level==1) selected @endif>1</option>
                    <option value='2' @if($position->position_level==2) selected @endif>2</option>
                    <option value='3' @if($position->position_level==3) selected @endif>3</option>
                    <option value='4' @if($position->position_level==4) selected @endif>4</option>
                    <option value='5' @if($position->position_level==5) selected @endif>5</option>
                    <option value='6' @if($position->position_level==6) selected @endif>6</option>
                    <option value='7' @if($position->position_level==7) selected @endif>7</option>
                    <option value='8' @if($position->position_level==8) selected @endif>8</option>
                  </select>
                </div>
              </div>
            </div>
            <hr>
            <div class="row">
              <div class="col-3">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-6"></div>
              <div class="col-3">
                <input type="submit" class="btn btn-warning btn-block" value="修改">
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('sidebar_status')
<script>
  linkActive('link-1');
  navbarActive('navbar-1');
  linkActive('section');
</script>
@endsection
