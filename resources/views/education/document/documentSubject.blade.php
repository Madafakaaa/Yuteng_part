@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">教案中心</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">教案中心</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-3">
  <div class="row">
    <div class="col-6">
      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-dark bg-primary">
          <li class="breadcrumb-item"><a href="/education/document">全部</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{$subject->subject_name}}</li>
        </ol>
      </nav>
    </div>
    <div class="col-6 text-right">
      <a href="/education/document/create?subject_id={{$subject->subject_id}}"><button type="button" class="btn btn-primary btn-sm">添加教案</button></a>
    </div>
  </div>
  <div class="row">
    @foreach($grades as $grade)
      <div class="col-lg-2 col-md-3 col-sm-4">
        <a href="/education/document/subject/grade?subject_id={{$subject->subject_id}}&grade_id={{$grade->grade_id}}">
          <div class="card">
            <div class="card-body">
              <img src="/assets/img/icons/folder.png" class="img-center img-fluid" style="width: 120px;">
              <div class="pt-4 text-center">
                <h5 class="h3 title">
                  <span class="d-block mb-1">{{$grade->grade_name}}</span>
                </h5>
              </div>
            </div>
          </div>
        </a>
      </div>
    @endforeach
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
