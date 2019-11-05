<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="Smart cushion">
  <meta name="author" content="COMP5047">
  <title>Smart Cushion Dashboard</title>
  <!-- Favicon -->
  <link rel="icon" href="{{ asset(_ASSETS_.'/img/brand/favicon.png') }}" type="image/png">
  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700">
  <!-- Icons -->
  <link rel="stylesheet" href="{{ asset(_ASSETS_.'/vendor/nucleo/css/nucleo.css') }}" type="text/css">
  <link rel="stylesheet" href="{{ asset(_ASSETS_.'/vendor/@fortawesome/fontawesome-free/css/all.min.css') }}" type="text/css">
  <!-- Page plugins -->
  <!-- Argon CSS -->
  <link rel="stylesheet" href="{{ asset(_ASSETS_.'/css/argon.css?v=1.1.0') }}" type="text/css">
</head>

<body>
  <!-- Sidenav -->
  <nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
    <div class="scrollbar-inner">
      <!-- Brand -->
      <div class="sidenav-header d-flex align-items-center">
        <a class="navbar-brand" href="/comp5047/dashboard">
          <img src="{{ asset(_ASSETS_.'/img/brand/comp5047.png') }}" class="navbar-brand-img" alt="...">
        </a>
        <div class="ml-auto">
          <!-- Sidenav toggler -->
          <div class="sidenav-toggler d-none d-xl-block" data-action="sidenav-unpin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </div>
      </div>
      <div class="navbar-inner">
        <!-- Collapse -->
        <div class="collapse navbar-collapse" id="sidenav-collapse-main">
          <!-- Nav items -->
          <ul class="navbar-nav">
            <li class="nav-item">
              <a class="nav-link" href="/comp5047/dashboard">
                <i class="ni ni-app text-green"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link active" href="/comp5047/setting">
                <i class="ni ni-settings text-red"></i>
                <span class="nav-link-text">Setting</span>
              </a>
            </li>
          </ul>
          <!-- Divider -->
          <hr class="my-3">
        </div>
      </div>
    </div>
  </nav>
  <!-- Main content -->
  <div class="main-content" id="panel">
    <!-- Header -->
    <div class="header bg-primary">
      <div class="container-fluid">
        <div class="header-body">
          <div class="row align-items-center py-4">
            <div class="col-lg-6 col-7">
              <h6 class="h2 text-white d-inline-block mb-0">Smart Cushion</h6>
              <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
                <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
                  <li class="breadcrumb-item active" aria-current="page">COMP5047</li>
                </ol>
              </nav>
            </div>
          </div>
          <div class="row">
            <div class="col-md-3"></div>
            <div class="col-md-6">
              <div class="card mb-4">
                <!-- Card header -->
                <div class="card-header">
                  <h3 class="mb-0">Setting Time Limit</h3>
                </div>
                <!-- Card body -->
                <div class="card-body">
                  <form action="/comp5047/update" method="post">
                  @csrf
                  <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <label class="form-control-label" for="input1">Maximum continuous sitting time (Second)</label>
                        <input type="text" class="form-control" id="input1" name="input1" required autocomplete="off" value="{{ $con_limit }}">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <label class="form-control-label" for="input2">Maximum sitting time per day (Second)</label>
                        <input type="text" class="form-control" id="input2" name="input2" required autocomplete="off" value="{{ $day_limit }}">
                      </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-2"></div>
                    <div class="col-md-8">
                      <div class="form-group">
                        <input type="submit" class="form-control btn btn-danger" value="submit">
                      </div>
                    </div>
                  </div>
                  </form>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!-- Argon Scripts -->
  <!-- Core -->
  <script src="{{ asset(_ASSETS_.'/vendor/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset(_ASSETS_.'/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset(_ASSETS_.'/vendor/js-cookie/js.cookie.js') }}"></script>
  <script src="{{ asset(_ASSETS_.'/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
  <script src="{{ asset(_ASSETS_.'/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
  <!-- Optional JS -->
  <script src="{{ asset(_ASSETS_.'/vendor/chart.js/dist/Chart.min.js') }}"></script>
  <script src="{{ asset(_ASSETS_.'/vendor/chart.js/dist/Chart.extension.js') }}"></script>
  <!-- Argon JS -->
  <script src="{{ asset(_ASSETS_.'/js/argon.js?v=1.1.0') }}"></script>
  <!-- Demo JS - remove this in your project -->
  <script src="{{ asset(_ASSETS_.'/js/demo.min.js') }}"></script>
</body>
</html>
