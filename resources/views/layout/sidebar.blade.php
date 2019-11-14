<!-- Sidenav -->
<nav class="sidenav navbar navbar-vertical fixed-left navbar-expand-xs navbar-light bg-white" id="sidenav-main">
<div class="scrollbar-inner">
  <!-- Brand -->
  <div class="sidenav-header d-flex align-items-center">
    <a class="navbar-brand" href="/home">
      <img src="{{ asset(_ASSETS_.'/img/brand/blue.png') }}" class="navbar-brand-img" alt="...">
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
          <a class="nav-link" href="/home" id="home">
            <i class="ni ni-shop text-primary"></i>
            <span class="nav-link-text">主页</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-dashboards" data-toggle="collapse" role="button" aria-controls="navbar-dashboards" id="section1">
            <i class="ni ni-archive-2 text-green"></i>
            <span class="nav-link-text">学校管理</span>
          </a>
          <div class="collapse show" id="navbar-dashboards">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/department" class="nav-link" id="department">校区管理</a>
              </li>
              <li class="nav-item">
                <a href="/position" class="nav-link" id="position">岗位管理</a>
              </li>
              <li class="nav-item">
                <a href="/user" class="nav-link" id="user">用户管理</a>
              </li>
              <li class="nav-item">
                <a href="/archive" class="nav-link" id="archive">档案管理</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-examples" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-examples">
            <i class="ni ni-ungroup text-orange"></i>
            <span class="nav-link-text">招生管理</span>
          </a>
          <div class="collapse" id="navbar-examples">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="../../pages/examples/pricing.html" class="nav-link">Pricing</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-components" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-components">
            <i class="ni ni-ui-04 text-info"></i>
            <span class="nav-link-text">学生管理</span>
          </a>
          <div class="collapse" id="navbar-components">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="../../pages/components/buttons.html" class="nav-link">Buttons</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-forms" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-forms">
            <i class="ni ni-single-copy-04 text-pink"></i>
            <span class="nav-link-text">教务中心</span>
          </a>
          <div class="collapse" id="navbar-forms">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="../../pages/forms/elements.html" class="nav-link">Elements</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-tables" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-tables">
            <i class="ni ni-align-left-2 text-default"></i>
            <span class="nav-link-text">财务中心</span>
          </a>
          <div class="collapse" id="navbar-tables">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="../../pages/tables/tables.html" class="nav-link">Tables</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-maps" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-maps">
            <i class="ni ni-map-big text-primary"></i>
            <span class="nav-link-text">报表</span>
          </a>
          <div class="collapse" id="navbar-maps">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="../../pages/maps/google.html" class="nav-link">Google</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../pages/widgets.html">
            <i class="ni ni-archive-2 text-green"></i>
            <span class="nav-link-text">站内通知</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="../../pages/charts.html">
            <i class="ni ni-circle-08 text-info"></i>
            <span class="nav-link-text">个人信息</span>
          </a>
        </li>
      </ul>
      <!-- Divider -->
      <hr class="my-3">
      <!-- Heading -->
      <!-- <h6 class="navbar-heading p-0 text-muted">Documentation</h6> -->
      <!-- Navigation -->
      <ul class="navbar-nav mb-md-3">
        <li class="nav-item">
          <a class="nav-link" href="/exit">
            <i class="ni ni-user-run text-red"></i>
            <span class="nav-link-text">退出系统</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
</nav>
