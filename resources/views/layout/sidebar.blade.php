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
          <a class="nav-link" href="#navbar-company" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="navbar-company" id="link-company">
            <i class="ni ni-building text-green"></i>
            <span class="nav-link-text">公司管理</span>
          </a>
          <div class="collapse" id="navbar-company">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item" @if(!in_array('companyDepartment', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/company/department" class="nav-link" id="companyDepartment">
                  <i class="ni ni-building text-green"></i>
                  <span class="nav-link-text">校区设置</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('companyCourse', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/company/course" class="nav-link" id="companyCourse">
                  <i class="ni ni-book-bookmark text-green"></i>
                  <span class="nav-link-text">课程设置</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('companySchool', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/company/school" class="nav-link" id="companySchool">
                  <i class="ni ni-map-big text-green"></i>
                  <span class="nav-link-text">大区设置</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('companyClassroom', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/company/classroom" class="nav-link" id="companyClassroom">
                  <i class="ni ni-pin-3 text-green"></i>
                  <span class="nav-link-text">教室设置</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('companyUser', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/company/user" class="nav-link" id="companyUser">
                  <i class="ni ni-single-02 text-green"></i>
                  <span class="nav-link-text">用户管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('companySection', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/company/section" class="nav-link" id="companySection">
                  <i class="ni ni-app text-green"></i>
                  <span class="nav-link-text">部门架构</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-market" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-market" id="link-market">
            <i class="ni ni-credit-card text-orange"></i>
            <span class="nav-link-text">招生中心</span>
          </a>
          <div class="collapse" id="navbar-market">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item" @if(!in_array('marketCustomer', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/customer" class="nav-link" id="marketCustomer">
                  <i class="ni ni-single-02 text-orange"></i>
                  <span class="nav-link-text">客户管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketStudent', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/student" class="nav-link" id="marketStudent">
                  <i class="ni ni-single-02 text-orange"></i>
                  <span class="nav-link-text">学生管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketStudentDeleted', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/student/deleted" class="nav-link" id="marketStudentDeleted">
                  <i class="ni ni-fat-remove text-orange"></i>
                  <span class="nav-link-text">离校学生</span>
                </a>
              </li>
              <hr>
              <li class="nav-item" @if(!in_array('marketMyCustomer', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/myCustomer" class="nav-link" id="marketMyCustomer">
                  <i class="ni ni-single-02 text-orange"></i>
                  <span class="nav-link-text">我的客户</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketMyStudent', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/myStudent" class="nav-link" id="marketMyStudent">
                  <i class="ni ni-single-02 text-orange"></i>
                  <span class="nav-link-text">我的学生</span>
                </a>
              </li>
              <hr>
              <li class="nav-item" @if(!in_array('marketContract', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/contract" class="nav-link" id="marketContract">
                  <i class="ni ni-money-coins text-orange"></i>
                  <span class="nav-link-text">签约管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketMyContract', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/myContract" class="nav-link" id="marketMyContract">
                  <i class="ni ni-money-coins text-orange"></i>
                  <span class="nav-link-text">我的签约</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-operation" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-operation" id="link-operation">
            <i class="ni ni-single-copy-04 text-info"></i>
            <span class="nav-link-text">运营中心</span>
          </a>
          <div class="collapse" id="navbar-operation">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item" @if(!in_array('operationStudent', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/student" class="nav-link" id="operationStudent">
                  <i class="ni ni-single-02 text-info"></i>
                  <span class="nav-link-text">学生管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationHour', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/hour" class="nav-link" id="operationHour">
                  <i class="ni ni-single-copy-04 text-info"></i>
                  <span class="nav-link-text">学生课时</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationStudentDeleted', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/student/deleted" class="nav-link" id="operationStudentDeleted">
                  <i class="ni ni-fat-remove text-info"></i>
                  <span class="nav-link-text">离校学生</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationClass', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/class" class="nav-link" id="operationClass">
                  <i class="ni ni-circle-08 text-info"></i>
                  <span class="nav-link-text">班级管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationSchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/schedule" class="nav-link" id="operationSchedule">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">课程安排</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationAttendedSchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/attendedSchedule" class="nav-link" id="operationAttendedSchedule">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">上课记录</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationSchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/calendar/week" class="nav-link" id="operationCalendarWeek">
                  <i class="ni ni-calendar-grid-58 text-info"></i>
                  <span class="nav-link-text">课程表</span>
                </a>
              </li>
              <hr>
              <li class="nav-item" @if(!in_array('operationMyStudent', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/myStudent" class="nav-link" id="operationMyStudent">
                  <i class="ni ni-single-02 text-info"></i>
                  <span class="nav-link-text">我的学生</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationMyHour', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/myHour" class="nav-link" id="operationMyHour">
                  <i class="ni ni-single-copy-04 text-info"></i>
                  <span class="nav-link-text">我的学生课时</span>
                </a>
              </li>
              <!-- <li class="nav-item" @if(!in_array('operationMySchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/schedule/my" class="nav-link" id="operationMySchedule">
                  <i class="ni ni-calendar-grid-58 text-info"></i>
                  <span class="nav-link-text">我的学生课程安排</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationMyAttendedSchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/attendedSchedule/my" class="nav-link" id="operationMyAttendedSchedule">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">我的学生上课记录</span>
                </a>
              </li> -->
              <hr>
              <li class="nav-item" @if(!in_array('operationContract', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/contract" class="nav-link" id="operationContract">
                  <i class="ni ni-money-coins text-info"></i>
                  <span class="nav-link-text">签约管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationMyContract', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/myContract" class="nav-link" id="operationMyContract">
                  <i class="ni ni-money-coins text-info"></i>
                  <span class="nav-link-text">我的签约</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationRefund', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/refund" class="nav-link" id="operationRefund">
                  <i class="ni ni-cart text-info"></i>
                  <span class="nav-link-text">退费管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationMyRefund', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/myRefund" class="nav-link" id="operationMyRefund">
                  <i class="ni ni-cart text-info"></i>
                  <span class="nav-link-text">我的退费</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-education" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-education" id="link-education">
            <i class="ni ni-ruler-pencil text-pink"></i>
            <span class="nav-link-text">教学中心</span>
          </a>
          <div class="collapse" id="navbar-education">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item" @if(!in_array('educationStudent', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/student" class="nav-link" id="educationStudent">
                  <i class="ni ni-single-02 text-pink"></i>
                  <span class="nav-link-text">学生管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationClass', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/class" class="nav-link" id="educationClass">
                  <i class="ni ni-single-copy-04 text-pink"></i>
                  <span class="nav-link-text">班级管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationSchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/schedule" class="nav-link" id="educationSchedule">
                  <i class="ni ni-calendar-grid-58 text-pink"></i>
                  <span class="nav-link-text">课程安排</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationAttendedSchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/attendedSchedule" class="nav-link" id="educationAttendedSchedule">
                  <i class="ni ni-bullet-list-67 text-pink"></i>
                  <span class="nav-link-text">上课记录</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationSchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/calendar/week" class="nav-link" id="educationCalendarWeek">
                  <i class="ni ni-calendar-grid-58 text-pink"></i>
                  <span class="nav-link-text">课程表</span>
                </a>
              </li>
              <hr>
              <!-- <li class="nav-item" @if(!in_array('educationMyStudent', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/myStudent" class="nav-link" id="educationMyStudent">
                  <i class="ni ni-single-02 text-pink"></i>
                  <span class="nav-link-text">我的学生</span>
                </a>
              </li> -->
              <li class="nav-item" @if(!in_array('educationMyClass', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/myClass" class="nav-link" id="educationMyClass">
                  <i class="ni ni-single-copy-04 text-pink"></i>
                  <span class="nav-link-text">我的班级</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationMySchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/mySchedule" class="nav-link" id="educationMySchedule">
                  <i class="ni ni-calendar-grid-58 text-pink"></i>
                  <span class="nav-link-text">我的课程安排</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationMyAttendedSchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/myAttendedSchedule" class="nav-link" id="educationMyAttendedSchedule">
                  <i class="ni ni-bullet-list-67 text-pink"></i>
                  <span class="nav-link-text">我的上课记录</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationMySchedule', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/myCalendar/week" class="nav-link" id="educationMyCalendarWeek">
                  <i class="ni ni-calendar-grid-58 text-pink"></i>
                  <span class="nav-link-text">我的课程表</span>
                </a>
              </li>
              <hr>
              <li class="nav-item" @if(!in_array('educationDocument', Session::get('page_access'))) style="display:none;" @endif>
                <a class="nav-link" href="/education/document" id="educationDocument">
                  <i class="ni ni-cloud-download-95 text-pink"></i>
                  <span class="nav-link-text">教案中心</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-finance" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-finance" id="link-finance">
            <i class="ni ni-chart-pie-35 text-default"></i>
            <span class="nav-link-text">统计中心</span>
          </a>
          <div class="collapse" id="navbar-finance">
            <ul class="nav nav-sm flex-column">
              @if(Session::get('user_id')=='yuto2018')
              <li class="nav-item">
                <a href="/finance/contract" class="nav-link" id="financeContract">
                  <i class="ni ni-money-coins text-default"></i>
                  <span class="nav-link-text">签约统计</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/finance/hour" class="nav-link" id="financeHour">
                  <i class="ni ni-single-copy-04 text-default"></i>
                  <span class="nav-link-text">课消统计</span>
                </a>
              </li>
              @endif
              <!-- <li class="nav-item">
                <a href="/finance/consumption" class="nav-link" id="financeConsumption">
                  <i class="ni ni-book-bookmark text-default"></i>
                  <span class="nav-link-text">个人签约</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/finance/refund" class="nav-link" id="financeRefund">
                  <i class="ni ni-scissors text-default"></i>
                  <span class="nav-link-text">退费统计</span>
                </a>
              </li> -->
            </ul>
          </div>
        </li>
        <!-- <li class="nav-item">
          <a href="/calendar" class="nav-link" id="calendar">
            <i class="ni ni-calendar-grid-58 text-info"></i>
            <span class="nav-link-text">课程表</span>
          </a>
        </li> -->
      </ul>
      <!-- Divider -->
      <hr class="my-3">
      <!-- Heading -->
      <!-- <h6 class="navbar-heading p-0 text-muted">Documentation</h6> -->
      <!-- Navigation -->
      <ul class="navbar-nav mb-md-3">
        <!--
        <li class="nav-item">
          <a class="nav-link" href="#">
            <i class="ni ni-notification-70"></i>
            <span class="nav-link-text">站内通知</span>
          </a>
        </li>
        -->
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
