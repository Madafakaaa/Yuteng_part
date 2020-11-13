@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">上传教案</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item"><a href="/education/document">教案中心</a></li>
    <li class="breadcrumb-item active">上传教案</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12">
      <div class="card">
        <form id="my-awesome-dropzone" action="/education/document/store" method="post" id="form1" name="form1" enctype="multipart/form-data" onsubmit="submitButtonDisable('submitButton1')">
          @csrf
          <!-- Card body -->
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">科目<span style="color:red">*</span></label>
                  <select class="form-control" name="document_subject" data-toggle="select" required>
                    <option value=''>请选择科目...</option>
                    @foreach ($subjects as $subject)
                      <option value="{{ $subject->subject_id }}" @if($subject_id==$subject->subject_id) selected @endif>{{ $subject->subject_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">年级<span style="color:red">*</span></label>
                  <select class="form-control" name="document_grade" data-toggle="select" required>
                    <option value=''>请选择年级...</option>
                    @foreach ($grades as $grade)
                      <option value="{{ $grade->grade_id }}" @if($grade_id==$grade->grade_id) selected @endif>{{ $grade->grade_name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">学期<span style="color:red">*</span></label>
                  <select class="form-control" name="document_semester" data-toggle="select" required>
                    <option value=''>请选择学期...</option>
                    <option value='第一学期' @if($semester=='第一学期') selected @endif>第一学期</option>
                    <option value='第二学期' @if($semester=='第二学期') selected @endif>第二学期</option>
                    <option value='寒假班' @if($semester=='寒假班') selected @endif>寒假班</option>
                    <option value='暑假班' @if($semester=='暑假班') selected @endif>暑假班</option>
                    <option value='资料库' @if($semester=='资料库') selected @endif>资料库</option>
                  </select>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <label class="form-control-label">文件<span style="color:red">*</span></label>
                  <div class="input-group">
                    <input id='location' class="form-control" disabled aria-describedby="button-addon">
                    <div class="input-group-append">
                      <input type="button" id="i-check" value="浏览文件" class="btn btn-outline-primary" onClick="$('#i-file').click();" style="margin:0;" id="button-addon">
                      <input type="file" name='file' id='i-file' onChange="$('#location').val($('#i-file').val());" style="display: none">
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <hr class="my-3">
            <div class="row">
              <div class="col-lg-4 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-4 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-4 col-md-5 col-sm-12">
                <input type="submit" id="submitButton1" class="btn btn-warning btn-block" value="上传">
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
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationDocument');
</script>
@endsection
