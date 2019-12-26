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
          <a class="nav-link" href="#navbar-1" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="navbar-1" id="link-1">
            <i class="ni ni-archive-2 text-green"></i>
            <span class="nav-link-text">学校管理</span>
          </a>
          <div class="collapse" id="navbar-1">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="#navbar-1-1" class="nav-link" data-toggle="collapse" role="button" aria-controls="navbar-1-1" id="link-1-1">校区管理</a>
                <div class="collapse" id="navbar-1-1">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="/department" class="nav-link" id="department">校区设置</a>
                    </li>
                    <li class="nav-item">
                      <a href="/school" class="nav-link" id="school">学校设置</a>
                    </li>
                    <li class="nav-item">
                      <a href="/classroom" class="nav-link" id="classroom">教室设置</a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="#navbar-1-2" class="nav-link" data-toggle="collapse" role="button" aria-controls="navbar-1-2" id="link-1-2">用户管理</a>
                <div class="collapse" id="navbar-1-2">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="/user" class="nav-link" id="user">用户列表</a>
                    </li>
                    <li class="nav-item">
                      <a href="/position" class="nav-link" id="position">岗位设置</a>
                    </li>
                    <li class="nav-item">
                      <a href="/level" class="nav-link" id="level">等级设置</a>
                    </li>
                    <li class="nav-item">
                      <a href="/archive" class="nav-link" id="archive">档案管理</a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a href="#navbar-1-3" class="nav-link" data-toggle="collapse" role="button" aria-controls="navbar-1-3" id="link-1-3">课程管理</a>
                <div class="collapse" id="navbar-1-3">
                  <ul class="nav nav-sm flex-column">
                    <li class="nav-item">
                      <a href="/course" class="nav-link" id="course">课程设置</a>
                    </li>
                    <li class="nav-item">
                      <a href="/grade" class="nav-link" id="grade">年级设置</a>
                    </li>
                    <li class="nav-item">
                      <a href="/subject" class="nav-link" id="subject">科目设置</a>
                    </li>
                  </ul>
                </div>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-2" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-2" id="link-2">
            <i class="ni ni-ungroup text-orange"></i>
            <span class="nav-link-text">招生管理</span>
          </a>
          <div class="collapse" id="navbar-2">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/customer" class="nav-link" id="customer">客户管理</a>
              </li>
              <li class="nav-item">
                <a href="/source" class="nav-link" id="source">来源设置</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-3" id="link-3">
            <i class="ni ni-single-copy-04 text-pink"></i>
            <span class="nav-link-text">教务中心</span>
          </a>
          <div class="collapse" id="navbar-3">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/student" class="nav-link" id="student">学生管理</a>
              </li>
              <li class="nav-item">
                <a href="/class" class="nav-link" id="class">班级管理</a>
              </li>
              <li class="nav-item">
                <a href="/schedule" class="nav-link" id="schedule">课程安排</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-4" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-4" id="link-4">
            <i class="ni ni-align-left-2 text-default"></i>
            <span class="nav-link-text">财务中心</span>
          </a>
          <div class="collapse" id="navbar-4">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/payment" class="nav-link" id="payment">学生购课</a>
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