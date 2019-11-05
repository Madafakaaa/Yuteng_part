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
              <a class="nav-link active" href="/comp5047/dashboard">
                <i class="ni ni-app text-green"></i>
                <span class="nav-link-text">Dashboard</span>
              </a>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="/comp5047/setting">
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
          <!-- Ajax Card stats -->
          <div class="row pb-5" id="ajax">
            <div class="col-xl-3 col-sm-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Device status</h5>
                      <span class="h2 font-weight-bold mb-0">Loading...</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="fas fa-spinner"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Sitting status</h5>
                      <span class="h2 font-weight-bold mb-0">Loading...</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="fas fa-spinner"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Posture status</h5>
                      <span class="h2 font-weight-bold mb-0">Loading...</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="fas fa-spinner"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Sitting user</h5>
                      <span class="h2 font-weight-bold mb-0">Loading...</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="fas fa-spinner"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Distance sensor 1</h5>
                      <span class="h2 font-weight-bold mb-0">Loading...</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="fas fa-spinner"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Distance sensor 2</h5>
                      <span class="h2 font-weight-bold mb-0">Loading...</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="fas fa-spinner"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Distance sensor 3</h5>
                      <span class="h2 font-weight-bold mb-0">Loading...</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="fas fa-spinner"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-xl-3 col-sm-6">
              <div class="card card-stats">
                <!-- Card body -->
                <div class="card-body">
                  <div class="row">
                    <div class="col">
                      <h5 class="card-title text-uppercase text-muted mb-0">Distance sensor 4</h5>
                      <span class="h2 font-weight-bold mb-0">Loading...</span>
                    </div>
                    <div class="col-auto">
                      <div class="icon icon-shape bg-gradient-orange text-white rounded-circle shadow">
                        <i class="fas fa-spinner"></i>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class='col-xl-6 col-sm-12'>
              <!--* Card init *-->
              <div class="card">
                <!-- Card header -->
                <div class="card-header">
                  <!-- Surtitle -->
                  <h6 class="surtitle">Realtime</h6>
                  <!-- Title -->
                  <h5 class="h3 mb-0">Sitting posture</h5>
                </div>
                <!-- Card body -->
                <div class="card-body ml-auto mr-auto">
                  <img style='height:270px;width:270px;' src="{{ asset(_ASSETS_.'/img/brand/posture0.png') }}" />
                </div>
              </div>
            </div>
            <div class="col-xl-6 col-md-12">
              <div class="row">
                <div class="col-xl-12 col-md-12">
                  <div class="card bg-gradient-info border-0">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0 text-white">Continuous sitting time</h5>
                          <span class="h2 font-weight-bold mb-0 text-white">... / ...</span>
                          <div class="progress progress-xs mt-3 mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                          </div>
                        </div>
                        <div class="col-auto">
                          <a href="#">
                            <button type="button" class="btn btn-sm btn-neutral mr-0">
                              Update
                            </button>
                          </a>
                        </div>
                      </div>
                      <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">... %</a>
                      </p>
                    </div>
                  </div>
                </div>
                <div class="col-xl-12 col-md-12">
                  <div class="card bg-gradient-info border-0">
                    <div class="card-body">
                      <div class="row">
                        <div class="col">
                          <h5 class="card-title text-uppercase text-muted mb-0 text-white">Sitting time today</h5>
                          <span class="h2 font-weight-bold mb-0 text-white">... / ...</span>
                          <div class="progress progress-xs mt-3 mb-0">
                            <div class="progress-bar bg-success" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;"></div>
                          </div>
                        </div>
                        <div class="col-auto">
                          <a href="#">
                            <button type="button" class="btn btn-sm btn-neutral mr-0">
                              Update
                            </button>
                          </a>
                        </div>
                      </div>
                      <p class="mt-3 mb-0 text-sm">
                        <a href="#" class="text-nowrap text-white font-weight-600">... %</a>
                      </p>
                    </div>
                  </div>
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
  <!-- Ajax -->
  <script>
    var autoRefresh = setInterval(function(){
           $.ajax({
               type: "GET",
               url: "/comp5047/getData",
               dataType: "html",
               success: function(data){
                   $("#ajax").html(data);
               }
           });
    },1000)
  </script>
</body>

</html>
