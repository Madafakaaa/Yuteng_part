<!-- Topnav -->
<nav class="navbar navbar-top navbar-expand navbar-dark bg-primary border-bottom">
  <div class="container-fluid">
    <div class="collapse navbar-collapse" id="navbarSupportedContent">


      <nav aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
          <!-- Nav -->
          @section('nav')
            <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
          @show
        </ol>
      </nav>


      <!-- Navbar links -->
      <ul class="navbar-nav align-items-center ml-md-auto">
        <li class="nav-item d-xl-none">
          <!-- Sidenav toggler -->
          <div class="pr-3 sidenav-toggler sidenav-toggler-dark" data-action="sidenav-pin" data-target="#sidenav-main">
            <div class="sidenav-toggler-inner">
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
              <i class="sidenav-toggler-line"></i>
            </div>
          </div>
        </li>
        <li class="nav-item d-sm-none">
          <a class="nav-link" href="#" data-action="search-show" data-target="#navbar-search-main">
            <i class="ni ni-zoom-split-in"></i>
          </a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <i class="ni ni-bell-55"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right py-0 overflow-hidden">
            <!-- Dropdown header -->
            <div class="px-3 py-3">
              <h6 class="text-sm text-muted m-0">您有<strong class="text-primary">2</strong>个新通知.</h6>
            </div>
            <!-- List group -->
            <div class="list-group list-group-flush">
              <a href="#!" class="list-group-item list-group-item-action">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <!-- Avatar -->
                    <img alt="Image placeholder" src="{{ asset(_ASSETS_.'/img/theme/team-1.jpg') }}" class="avatar rounded-circle">
                  </div>
                  <div class="col ml--2">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h4 class="mb-0 text-sm">{{ Session::get('user_name') }}</h4>
                      </div>
                      <div class="text-right text-muted">
                        <small>2019-09-29</small>
                      </div>
                    </div>
                    <p class="text-sm mb-0">最新通知2</p>
                  </div>
                </div>
              </a>
              <a href="#!" class="list-group-item list-group-item-action">
                <div class="row align-items-center">
                  <div class="col-auto">
                    <!-- Avatar -->
                    <img alt="Image placeholder" src="{{ asset(_ASSETS_.'/img/theme/team-2.jpg') }}" class="avatar rounded-circle">
                  </div>
                  <div class="col ml--2">
                    <div class="d-flex justify-content-between align-items-center">
                      <div>
                        <h4 class="mb-0 text-sm">管理员2</h4>
                      </div>
                      <div class="text-right text-muted">
                        <small>2019-09-28</small>
                      </div>
                    </div>
                    <p class="text-sm mb-0">最新通知1.</p>
                  </div>
                </div>
              </a>
            </div>
            <!-- View all -->
            <a href="#!" class="dropdown-item text-center text-primary font-weight-bold py-3">查看全部</a>
          </div>
        </li>
      </ul>
      <ul class="navbar-nav align-items-center ml-auto ml-md-0">
        <li class="nav-item dropdown">
          <a class="nav-link pr-0" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <div class="media align-items-center">
              <span class="avatar avatar-sm rounded-circle">
                <img alt="Image placeholder" src="{{ asset(_ASSETS_.'/img/theme/team-1.jpg') }}">
              </span>
              <div class="media-body ml-2 d-none d-lg-block">
                <span class="mb-0 text-sm  font-weight-bold">{{ Session::get('user_name') }}</span>
              </div>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-right">
            <a href="#!" class="dropdown-item">
              <i class="ni ni-single-02 text-green"></i>
              <span>{{ Session::get('user_name') }}</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-square-pin text-green"></i>
              <span>{{ Session::get('user_department_name') }}</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-building text-green"></i>
              <span>{{ Session::get('user_section') }}</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-badge text-green"></i>
              <span>{{ Session::get('user_position') }}</span>
            </a>
            <a href="#!" class="dropdown-item">
              <i class="ni ni-chart-bar-32 text-green"></i>
              <span>等级 {{ Session::get('user_level') }}</span>
            </a>
            <div class="dropdown-divider"></div>
            <a href="/exit" class="dropdown-item">
              <i class="ni ni-user-run text-red"></i>
              <span>退出系统</span>
            </a>
          </div>
        </li>
      </ul>
    </div>
  </div>
</nav>
<!-- Header -->
<!--  <div class="header bg-primary pb-6"></div> -->
