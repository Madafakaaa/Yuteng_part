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
            <i class="ni ni-atom text-green"></i>
            <span class="nav-link-text">公司管理</span>
          </a>
          <div class="collapse" id="navbar-company">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/department" class="nav-link" id="department">校区设置</a>
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
        <li class="nav-item">
          <a class="nav-link" href="#navbar-human" data-toggle="collapse" aria-expanded="false" role="button" aria-controls="navbar-human" id="link-human">
            <i class="ni ni-atom text-green"></i>
            <span class="nav-link-text">人事管理</span>
          </a>
          <div class="collapse" id="navbar-human">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/user" class="nav-link" id="user">用户管理</a>
              </li>
              <li class="nav-item">
                <a href="/archive" class="nav-link" id="archive">员工档案</a>
              </li>
              <li class="nav-item">
                <a href="/section" class="nav-link" id="section">部门架构</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-market" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-market" id="link-market">
            <i class="ni ni-ungroup text-orange"></i>
            <span class="nav-link-text">招生中心</span>
          </a>
          <div class="collapse" id="navbar-market">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/market/publicCustomer/create" class="nav-link" id="marketPublicCustomerCreate">公共客户录入</a>
              </li>
              <li class="nav-item">
                <a href="/market/myCustomer/create" class="nav-link" id="marketMyCustomerCreate">我的客户录入</a>
              </li>
              <li class="nav-item">
                <a href="/market/follower/edit" class="nav-link" id="marketFollowerEdit">修改负责人</a>
              </li>
              <li class="nav-item">
                <a href="/market/all/customer" class="nav-link" id="marketAllCustomer">部门客户</a>
              </li>
              <li class="nav-item">
                <a href="/market/department/customer" class="nav-link" id="marketDepartmentCustomer">本校客户</a>
              </li>
              <li class="nav-item">
                <a href="/market/my/customer" class="nav-link" id="marketMyCustomer">我的客户</a>
              </li>
              <li class="nav-item">
                <a href="/market/my/student" class="nav-link" id="marketMyStudent">我的学生</a>
              </li>
              <li class="nav-item">
                <br>
              </li>
              <li class="nav-item">
                <a href="/market/contract/create" class="nav-link" id="marketContractCreate">签约合同</a>
              </li>
              <li class="nav-item">
                <a href="/market/all/contract" class="nav-link" id="marketAllContract">部门签约</a>
              </li>
              <li class="nav-item">
                <a href="/market/department/contract" class="nav-link" id="marketDepartmentContract">本校签约</a>
              </li>
              <li class="nav-item">
                <a href="/market/my/contract" class="nav-link" id="marketMyContract">我的签约</a>
              </li>
              <li class="nav-item">
                <br>
              </li>
              <li class="nav-item">
                <a href="/market/refund/create" class="nav-link" id="marketRefundCreate">学生退费</a>
              </li>
              <li class="nav-item">
                <a href="/market/all/refund" class="nav-link" id="marketAllRefund">部门退费</a>
              </li>
              <li class="nav-item">
                <a href="/market/department/refund" class="nav-link" id="marketDepartmentRefund">本校退费</a>
              </li>
              <li class="nav-item">
                <a href="/market/my/refund" class="nav-link" id="marketMyRefund">我的退费</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-operation" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-operation" id="link-operation">
            <i class="ni ni-single-copy-04 text-pink"></i>
            <span class="nav-link-text">运营中心</span>
          </a>
          <div class="collapse" id="navbar-operation">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/operation/follower/edit" class="nav-link" id="operationFollowerEdit">修改负责人</a>
              </li>
              <li class="nav-item">
                <a href="/operation/student/all" class="nav-link" id="operationStudentAll">全部学生</a>
              </li>
              <li class="nav-item">
                <a href="/operation/student/department" class="nav-link" id="operationStudentDepartment">本校学生</a>
              </li>
              <li class="nav-item">
                <a href="/operation/student/my" class="nav-link" id="operationStudentMy">我的学生</a>
              </li>
              <li class="nav-item">
                <br>
              </li>
              <li class="nav-item">
                <a href="/operation/class/create" class="nav-link" id="operationClassCreate">新建班级</a>
              </li>
              <li class="nav-item">
                <a href="/operation/class/all" class="nav-link" id="operationClassAll">全部班级</a>
              </li>
              <li class="nav-item">
                <a href="/operation/class/department" class="nav-link" id="operationClassDepartment">本校班级</a>
              </li>
              <li class="nav-item">
                <br>
              </li>
              <li class="nav-item">
                <a href="/operation/studentSchedule/create" class="nav-link" id="operationStudentScheduleCreate">安排学生课程</a>
              </li>
              <li class="nav-item">
                <a href="/operation/classSchedule/create" class="nav-link" id="operationClassScheduleCreate">安排班级课程</a>
              </li>
              <li class="nav-item">
                <a href="/operation/studentSchedule/department" class="nav-link" id="operationStudentScheduleDepartment">本校学生课程安排</a>
              </li>
              <li class="nav-item">
                <a href="/operation/classSchedule/department" class="nav-link" id="operationClassScheduleDepartment">本校班级课程安排</a>
              </li>
              <li class="nav-item">
                <a href="/operation/attendedSchedule/department" class="nav-link" id="operationAttendedScheduleDepartment">本校上课记录</a>
              </li>
              <li class="nav-item">
                <a href="/operation/schedule/my" class="nav-link" id="operationScheduleMy">我的学生课程安排</a>
              </li>
              <li class="nav-item">
                <a href="/operation/attendedSchedule/my" class="nav-link" id="operationAttendedScheduleMy">我的学生上课记录</a>
              </li>
              <li class="nav-item">
                <br>
              </li>
              <li class="nav-item">
                <a href="/operation/contract/create" class="nav-link" id="operationContractCreate">签约合同</a>
              </li>
              <li class="nav-item">
                <a href="/operation/all/contract" class="nav-link" id="operationAllContract">部门签约</a>
              </li>
              <li class="nav-item">
                <a href="/operation/department/contract" class="nav-link" id="operationDepartmentContract">本校签约</a>
              </li>
              <li class="nav-item">
                <a href="/operation/my/contract" class="nav-link" id="operationMyContract">我的签约</a>
              </li>
              <li class="nav-item">
                <br>
              </li>
              <li class="nav-item">
                <a href="/operation/refund/create" class="nav-link" id="operationRefundCreate">学生退费</a>
              </li>
              <li class="nav-item">
                <a href="/operation/all/refund" class="nav-link" id="operationAllRefund">部门退费</a>
              </li>
              <li class="nav-item">
                <a href="/operation/department/refund" class="nav-link" id="operationDepartmentRefund">本校退费</a>
              </li>
              <li class="nav-item">
                <a href="/operation/my/refund" class="nav-link" id="operationMyRefund">我的退费</a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-education" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-education" id="link-education">
            <i class="ni ni-single-copy-04 text-pink"></i>
            <span class="nav-link-text">教学中心</span>
          </a>
          <div class="collapse" id="navbar-education">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/departmentStudent" class="nav-link" id="departmentStudent">部门学生</a>
              </li>
              <li class="nav-item">
                <a href="/departmentStudent" class="nav-link" id="departmentStudent">本校学生</a>
              </li>
              <li class="nav-item">
                <a href="/myStudent" class="nav-link" id="myStudent">我的学生</a>
              </li>
              <li class="nav-item">
                <a href="/myStudent" class="nav-link" id="myStudent">我的班级</a>
              </li>
              <li class="nav-item">
                <a href="/departmentSchedule" class="nav-link" id="departmentSchedule">本校课程安排</a>
              </li>
              <li class="nav-item">
                <a href="/departmentAttendedSchedule" class="nav-link" id="departmentAttendedSchedule">本校上课记录</a>
              </li>
              <li class="nav-item">
                <a href="/mySchedule" class="nav-link" id="mySchedule">我的课程安排</a>
              </li>
              <li class="nav-item">
                <a href="/myAttendedSchedule" class="nav-link" id="myAttendedSchedule">我的上课记录</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="/document" id="document">
                  <i class="ni ni-archive-2 text-red"></i>
                  <span class="nav-link-text">教案查询</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#navbar-finance" data-toggle="collapse" role="button" aria-expanded="false" aria-controls="navbar-finance" id="link-finance">
            <i class="ni ni-align-left-2 text-default"></i>
            <span class="nav-link-text">财务中心</span>
          </a>
          <div class="collapse" id="navbar-finance">
            <ul class="nav nav-sm flex-column">
              <li class="nav-item">
                <a href="/contract/create" class="nav-link" id="contractCreate">签约统计</a>
              </li>
              <li class="nav-item">
                <a href="/contract/create" class="nav-link" id="contractCreate">退费统计</a>
              </li>
              <li class="nav-item">
                <a href="/contract" class="nav-link" id="contract">公司签约记录</a>
              </li>
              <li class="nav-item">
                <a href="/refund" class="nav-link" id="refund">公司退费记录</a>
              </li>
              <li class="nav-item">
                <a href="/refund/create" class="nav-link" id="refundCreate">课时退费</a>
              </li>
              <li class="nav-item">
                <a href="/departmentContract" class="nav-link" id="departmentContract">本校签约记录</a>
              </li>
              <li class="nav-item">
                <a href="/departmentRefund" class="nav-link" id="departmentRefund">本校退费记录</a>
              </li>
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
