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
        @if(Session::get('user_level')<=1)
        <li class="nav-item">
          <a class="nav-link" href="#navbar-1" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="navbar-1" id="link-1">
            <i class="ni ni-atom text-green"></i>
            <span class="nav-link-text">内部管理</span>
          </a>
          <div class="collapse" id="navbar-1">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/department" class="nav-link" id="department">校区设置</a>
              </li>
              <li class="nav-item">
                <a href="/user" class="nav-link" id="user">用户管理</a>
              </li>
              <li class="nav-item">
                <a href="/archive" class="nav-link" id="archive">员工档案</a>
              </li>
              <li class="nav-item">
                <a href="/section" class="nav-link" id="section">部门架构</a>
              </li>
              <li class="nav-item">
                <a href="/course" class="nav-link" id="course">课程设置</a>
              </li>
              <li class="nav-item">
                <a href="/school" class="nav-link" id="school">公立学校</a>
              </li>
              <li class="nav-item">
                <a href="/classroom" class="nav-link" id="classroom">教室设置</a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        @if(Session::get('user_level')<=3)
        <li class="nav-item">
          <a class="nav-link" href="#navbar-2" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="navbar-2" id="link-2">
            <i class="ni ni-archive-2 text-green"></i>
            <span class="nav-link-text">全校数据</span>
          </a>
          <div class="collapse" id="navbar-2">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/customer" class="nav-link" id="customer">全部客户</a>
              </li>
              <li class="nav-item">
                <a href="/student" class="nav-link" id="student">全部学生</a>
              </li>
              <li class="nav-item">
                <a href="/class" class="nav-link" id="class">全部班级</a>
              </li>
              <li class="nav-item">
                <a href="/schedule" class="nav-link" id="schedule">全部课程安排</a>
              </li>
              <li class="nav-item">
                <a href="/attendedSchedule" class="nav-link" id="attendedSchedule">全部上课记录</a>
              </li>
              <li class="nav-item">
                <a href="/contract" class="nav-link" id="contract">全部签约记录</a>
              </li>
              <li class="nav-item">
                <a href="/refund" class="nav-link" id="refund">全部退费记录</a>
              </li>
            </ul>
          </div>
        </li>
        @endif

        <li class="nav-item">
          <a class="nav-link" href="#navbar-3" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-3" id="link-3">
            <i class="ni ni-ungroup text-orange"></i>
            <span class="nav-link-text">招生中心</span>
          </a>
          <div class="collapse" id="navbar-3">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/customer/create" class="nav-link" id="customerCreate">客户录入</a>
              </li>

              @if(Session::get('user_level')<=6)
              <li class="nav-item">
                <a href="/departmentCustomer" class="nav-link" id="departmentCustomer">本校客户</a>
              </li>
              @endif

              <li class="nav-item">
                <a href="/myCustomer" class="nav-link" id="myCustomer">我的客户</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-4" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-4" id="link-4">
            <i class="ni ni-single-copy-04 text-pink"></i>
            <span class="nav-link-text">教务中心</span>
          </a>
          <div class="collapse" id="navbar-4">
            <ul class="nav nav-sm flex-column">
              @if(Session::get('user_level')<=6)
              <li class="nav-item">
                <a href="/class/create" class="nav-link" id="classCreate">新建班级</a>
              </li>
              <li class="nav-item">
                <a href="/schedule/create" class="nav-link" id="scheduleCreate">安排课程</a>
              </li>
              <li class="nav-item">
                <a href="/departmentStudent" class="nav-link" id="departmentStudent">本校学生</a>
              </li>
              <li class="nav-item">
                <a href="/departmentClass" class="nav-link" id="departmentClass">本校班级</a>
              </li>
              <li class="nav-item">
                <a href="/departmentSchedule" class="nav-link" id="departmentSchedule">本校课程安排</a>
              </li>
              <li class="nav-item">
                <a href="/departmentAttendedSchedule" class="nav-link" id="departmentAttendedSchedule">本校上课记录</a>
              </li>
              @endif
              <li class="nav-item">
                <a href="/myStudent" class="nav-link" id="myStudent">我的学生</a>
              </li>
              <li class="nav-item">
                <a href="/myClass" class="nav-link" id="myClass">我的班级</a>
              </li>
              <li class="nav-item">
                <a href="/mySchedule" class="nav-link" id="mySchedule">我的课程安排</a>
              </li>
              <li class="nav-item">
                <a href="/myAttendedSchedule" class="nav-link" id="myAttendedSchedule">我的上课记录</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-5" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-5" id="link-5">
            <i class="ni ni-align-left-2 text-default"></i>
            <span class="nav-link-text">财务中心</span>
          </a>
          <div class="collapse" id="navbar-5">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/contract/create" class="nav-link" id="contractCreate">签约合同</a>
              </li>
              <li class="nav-item">
                <a href="/refund/create" class="nav-link" id="refundCreate">课时退费</a>
              </li>
              @if(Session::get('user_level')<=6)
              <li class="nav-item">
                <a href="/departmentContract" class="nav-link" id="departmentContract">本校签约记录</a>
              </li>
              <li class="nav-item">
                <a href="/departmentRefund" class="nav-link" id="departmentRefund">本校退费记录</a>
              </li>
              @endif
              <li class="nav-item">
                <a href="/myContract" class="nav-link" id="myContract">我的签约记录</a>
              </li>
              <li class="nav-item">
                <a href="/myRefund" class="nav-link" id="myRefund">我的退费记录</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/document" id="document">
            <i class="ni ni-archive-2 text-red"></i>
            <span class="nav-link-text">教案查询</span>
          </a>
        </li>
        <li class="nav-item">
          <a href="/calendar" class="nav-link" id="calendar">
            <i class="ni ni-calendar-grid-58 text-info"></i>
            <span class="nav-link-text">课程表</span>
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
          <a class="nav-link" href="#">
            <i class="ni ni-notification-70"></i>
            <span class="nav-link-text">站内通知</span>
          </a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="/profile" id="profile">
            <i class="ni ni-circle-08"></i>
            <span class="nav-link-text">个人信息</span>
          </a>
        </li>
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
