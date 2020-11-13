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
          <li class="breadcrumb-item"><a href="/education/document/subject?subject_id={{$subject->subject_id}}">{{$subject->subject_name}}</a></li>
          <li class="breadcrumb-item"><a href="/education/document/subject/grade?subject_id={{$subject->subject_id}}&grade_id={{$grade->grade_id}}">{{$grade->grade_name}}</a></li>
          <li class="breadcrumb-item active" aria-current="page">{{$semester}}</li>
        </ol>
      </nav>
    </div>
    <div class="col-6 text-right">
      <a href="/education/document/create?subject_id={{$subject->subject_id}}&grade_id={{$grade->grade_id}}&semester={{$semester}}"><button type="button" class="btn btn-primary btn-sm">添加教案</button></a>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card mb-4">
        <div class="table-responsive py-4">
          <table class="table table-flush datatable-basic">
            <thead class="thead-light">
              <tr>
                <th style="width:40px;">#</th>
                <th style="width:400px;">教案名</th>
                <th style="width:60px;">操作</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($documents as $document)
                <tr>
                  <td>{{ $loop->iteration }}</td>
                  <td>{{ $document->document_name }}</td>
                  <td>
                    <a href="/education/document/download?id={{encode($document->document_id, 'document_id')}}"><button type="button" class="btn btn-primary btn-sm">下载教案</button></a>
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
  linkActive('link-education');
  navbarActive('navbar-education');
  linkActive('educationDocument');
</script>
@endsection
