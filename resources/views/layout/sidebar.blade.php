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
        @if(in_array("公司管理", Session::get('access_categories')))
        <li class="nav-item">
          <a class="nav-link" href="#navbar-company" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="navbar-company" id="link-company">
            <i class="ni ni-building text-green"></i>
            <span class="nav-link-text">公司管理</span>
          </a>
          <div class="collapse" id="navbar-company">
            <ul class="nav nav-sm flex-column">
              @if(in_array("/company/department", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/company/department" class="nav-link" id="companyDepartment">
                  <i class="ni ni-building text-green"></i>
                  <span class="nav-link-text">校区设置</span>
                </a>
              </li>
              @endif
              @if(in_array("/company/course", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/company/course" class="nav-link" id="companyCourse">
                  <i class="ni ni-book-bookmark text-green"></i>
                  <span class="nav-link-text">课程设置</span>
                </a>
              </li>
              @endif
              @if(in_array("/company/classroom", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/company/classroom" class="nav-link" id="companyClassroom">
                  <i class="ni ni-pin-3 text-green"></i>
                  <span class="nav-link-text">教室设置</span>
                </a>
              </li>
              @endif
              @if(in_array("/company/section", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/company/section" class="nav-link" id="companySection">
                  <i class="fa fa-sitemap text-green"></i>
                  <span class="nav-link-text">部门架构</span>
                </a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif
        @if(in_array("人事管理", Session::get('access_categories')))
        <li class="nav-item">
          <a class="nav-link" href="#navbar-humanResource" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="navbar-humanResource" id="link-humanResource">
            <i class="fa fa-user-tie text-grey"></i>
            <span class="nav-link-text">人事管理</span>
          </a>
          <div class="collapse" id="navbar-humanResource">
            <ul class="nav nav-sm flex-column">
              @if(in_array("/humanResource/user", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/humanResource/user" class="nav-link" id="humanResourceUser">
                  <i class="fa fa-user-tie text-grey"></i>
                  <span class="nav-link-text">用户管理</span>
                </a>
              </li>
              @endif
              @if(in_array("/humanResource/candidate", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/humanResource/candidate" class="nav-link" id="humanResourceCandidate">
                  <i class="fa fa-user-plus text-grey"></i>
                  <span class="nav-link-text">面试用户</span>
                </a>
              </li>
              @endif
              @if(in_array("/humanResource/user/deleted", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/humanResource/user/deleted" class="nav-link" id="humanResourceUserDeleted">
                  <i class="fa fa-user-slash text-grey"></i>
                  <span class="nav-link-text">离职用户</span>
                </a>
              </li>
              @endif
              @if(in_array("/humanResource/archive", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/humanResource/archive" class="nav-link" id="humanResourceArchive">
                  <i class="fa fa-folder-open text-grey"></i>
                  <span class="nav-link-text">用户档案</span>
                </a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif
        @if(in_array("招生中心", Session::get('access_categories')))
        <li class="nav-item">
          <a class="nav-link" href="#navbar-market" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-market" id="link-market">
            <i class="ni ni-credit-card text-orange"></i>
            <span class="nav-link-text">招生中心</span>
          </a>
          <div class="collapse" id="navbar-market">
            <ul class="nav nav-sm flex-column">
              @if(in_array("/market/customer", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/market/customer" class="nav-link" id="marketCustomer">
                  <i class="fa fa-user-plus text-orange"></i>
                  <span class="nav-link-text">未签约学生</span>
                </a>
              </li>
              @endif
              @if(in_array("/market/student", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/market/student" class="nav-link" id="marketStudent">
                  <i class="fa fa-user-graduate text-orange"></i>
                  <span class="nav-link-text">学生管理</span>
                </a>
              </li>
              @endif
              @if(in_array("/market/contract", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/market/contract" class="nav-link" id="marketContract">
                  <i class="ni ni-money-coins text-orange"></i>
                  <span class="nav-link-text">签约管理</span>
                </a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif
        @if(in_array("运营中心", Session::get('access_categories')))
        <li class="nav-item">
          <a class="nav-link" href="#navbar-operation" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-operation" id="link-operation">
            <i class="ni ni-single-copy-04 text-info"></i>
            <span class="nav-link-text">运营中心</span>
          </a>
          <div class="collapse" id="navbar-operation">
            <ul class="nav nav-sm flex-column">
              @if(in_array("/operation/student", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/student" class="nav-link" id="operationStudent">
                  <i class="fa fa-user-graduate text-info"></i>
                  <span class="nav-link-text">学生管理</span>
                </a>
              </li>
              @endif
              @if(in_array("/operation/hour", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/hour" class="nav-link" id="operationHour">
                  <i class="fa fa-user-clock text-info"></i>
                  <span class="nav-link-text">学生课时</span>
                </a>
              </li>
              @endif
              @if(in_array("/operation/class", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/class" class="nav-link" id="operationClass">
                  <i class="fa fa-users text-info"></i>
                  <span class="nav-link-text">班级管理</span>
                </a>
              </li>
              @endif
              @if(in_array("/operation/schedule", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/schedule" class="nav-link" id="operationSchedule">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">课程安排</span>
                </a>
              </li>
              @endif
              @if(in_array("/operation/attendedSchedule", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/attendedSchedule" class="nav-link" id="operationAttendedSchedule">
                  <i class="fa fa-chalkboard-teacher text-info"></i>
                  <span class="nav-link-text">上课记录</span>
                </a>
              </li>
              @endif
              @if(in_array("/operation/calendar", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/calendar/week" class="nav-link" id="operationCalendarWeek">
                  <i class="ni ni-calendar-grid-58 text-info"></i>
                  <span class="nav-link-text">课程表</span>
                </a>
              </li>
              @endif
              @if(in_array("/operation/contract", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/contract" class="nav-link" id="operationContract">
                  <i class="ni ni-money-coins text-info"></i>
                  <span class="nav-link-text">签约管理</span>
                </a>
              </li>
              @endif
              @if(in_array("/operation/refund", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/refund" class="nav-link" id="operationRefund">
                  <i class="ni ni-cart text-info"></i>
                  <span class="nav-link-text">退费管理</span>
                </a>
              </li>
              @endif
              @if(in_array("/operation/student/deleted", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/operation/student/deleted" class="nav-link" id="operationStudentDeleted">
                  <i class="fa fa-user-slash text-green"></i>
                  <span class="nav-link-text">离校学生</span>
                </a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif
        @if(in_array("教学中心", Session::get('access_categories')))
        <li class="nav-item">
          <a class="nav-link" href="#navbar-education" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-education" id="link-education">
            <i class="ni ni-ruler-pencil text-pink"></i>
            <span class="nav-link-text">教学中心</span>
          </a>
          <div class="collapse" id="navbar-education">
            <ul class="nav nav-sm flex-column">
              @if(in_array("/education/student", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/education/student" class="nav-link" id="educationStudent">
                  <i class="fa fa-user-graduate text-pink"></i>
                  <span class="nav-link-text">学生管理</span>
                </a>
              </li>
              @endif
              @if(in_array("/education/class", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/education/class" class="nav-link" id="educationClass">
                  <i class="fa fa-users text-pink"></i>
                  <span class="nav-link-text">班级管理</span>
                </a>
              </li>
              @endif
              @if(in_array("/education/schedule", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/education/schedule" class="nav-link" id="educationSchedule">
                  <i class="ni ni-bullet-list-67 text-pink"></i>
                  <span class="nav-link-text">课程安排</span>
                </a>
              </li>
              @endif
              @if(in_array("/education/attendedSchedule", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/education/attendedSchedule" class="nav-link" id="educationAttendedSchedule">
                  <i class="fa fa-chalkboard-teacher text-pink"></i>
                  <span class="nav-link-text">上课记录</span>
                </a>
              </li>
              @endif
              @if(in_array("/education/calendar", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/education/calendar/week" class="nav-link" id="educationCalendarWeek">
                  <i class="ni ni-calendar-grid-58 text-pink"></i>
                  <span class="nav-link-text">课程表</span>
                </a>
              </li>
              @endif
              @if(in_array("/education/document", Session::get('user_accesses')))
              <li class="nav-item">
                <a class="nav-link" href="/education/document" id="educationDocument">
                  <i class="fa fa-folder-open text-pink"></i>
                  <span class="nav-link-text">教案中心</span>
                </a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif
        @if(in_array("统计中心", Session::get('access_categories')))
        <li class="nav-item">
          <a class="nav-link" href="#navbar-finance" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-finance" id="link-finance">
            <i class="ni ni-chart-pie-35 text-default"></i>
            <span class="nav-link-text">统计中心</span>
          </a>
          <div class="collapse" id="navbar-finance">
            <ul class="nav nav-sm flex-column">
              @if(in_array("/finance/contract/department", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/finance/contract/department" class="nav-link" id="financeContractDepartment">
                  <i class="ni ni-money-coins text-default"></i>
                  <span class="nav-link-text">校区签约统计</span>
                </a>
              </li>
              @endif
              @if(in_array("/finance/contract/user", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/finance/contract/user" class="nav-link" id="financeContractUser">
                  <i class="ni ni-money-coins text-default"></i>
                  <span class="nav-link-text">个人签约统计</span>
                </a>
              </li>
              @endif
              @if(in_array("/finance/consumption/department", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/finance/consumption/department" class="nav-link" id="financeConsumptionDepartment">
                  <i class="ni ni-single-copy-04 text-default"></i>
                  <span class="nav-link-text">校区课消统计</span>
                </a>
              </li>
              @endif
              @if(in_array("/finance/consumption/user", Session::get('user_accesses')))
              <li class="nav-item">
                <a href="/finance/consumption/user" class="nav-link" id="financeConsumptionUser">
                  <i class="ni ni-single-copy-04 text-default"></i>
                  <span class="nav-link-text">个人课消统计</span>
                </a>
              </li>
              @endif
            </ul>
          </div>
        </li>
        @endif
        <!-- <li class="nav-item">
          <a class="nav-link" href="#navbar-self" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-self" id="link-self">
            <i class="ni ni-circle-08 text-teal"></i>
            <span class="nav-link-text">个人中心</span>
          </a>
          <div class="collapse" id="navbar-self">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/self/customer" class="nav-link" id="selfCustomer">
                  <i class="fa fa-user-plus text-teal"></i>
                  <span class="nav-link-text">我的未签约客户</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/self/consultant/student" class="nav-link" id="selfConsultantStudent">
                  <i class="fa fa-user-graduate text-teal"></i>
                  <span class="nav-link-text">我的学生（课程顾问）</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/self/adviser/student" class="nav-link" id="selfCustomer">
                  <i class="fa fa-user-graduate text-teal"></i>
                  <span class="nav-link-text">我的学生（班主任）</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/self/contract" class="nav-link" id="selfCustomer">
                  <i class="ni ni-money-coins text-teal"></i>
                  <span class="nav-link-text">我的签约</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/self/refund" class="nav-link" id="selfCustomer">
                  <i class="ni ni-cart text-teal"></i>
                  <span class="nav-link-text">我的退费</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/self/class" class="nav-link" id="selfCustomer">
                  <i class="fa fa-users text-teal"></i>
                  <span class="nav-link-text">我的班级</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/self/schedule" class="nav-link" id="selfCustomer">
                  <i class="ni ni-bullet-list-67 text-teal"></i>
                  <span class="nav-link-text">我的课程安排</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/self/attendedSchedule" class="nav-link" id="selfCustomer">
                  <i class="fa fa-chalkboard-teacher text-teal"></i>
                  <span class="nav-link-text">我的上课记录</span>
                </a>
              </li>
              <li class="nav-item">
                <a href="/self/calendar/week" class="nav-link" id="selfCustomer">
                  <i class="ni ni-calendar-grid-58 text-teal"></i>
                  <span class="nav-link-text">我的课程表</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        -->
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
            <i class="fa fa-user"></i>
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
