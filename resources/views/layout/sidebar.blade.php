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
              <li class="nav-item" @if(!in_array('marketPublicCustomerCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/publicCustomer/create" class="nav-link" id="marketPublicCustomerCreate">
                  <i class="ni ni-fat-add text-orange"></i>
                  <span class="nav-link-text">公共客户录入</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketMyCustomerCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/myCustomer/create" class="nav-link" id="marketMyCustomerCreate">
                  <i class="ni ni-fat-add text-orange"></i>
                  <span class="nav-link-text">我的客户录入</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketFollowerEdit', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/follower/edit" class="nav-link" id="marketFollowerEdit">
                  <i class="ni ni-badge text-orange"></i>
                  <span class="nav-link-text">修改负责人</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketCustomerAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/customer/all" class="nav-link" id="marketCustomerAll">
                  <i class="ni ni-single-02 text-orange"></i>
                  <span class="nav-link-text">客户管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketCustomerMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/customer/my" class="nav-link" id="marketCustomerMy">
                  <i class="ni ni-single-02 text-orange"></i>
                  <span class="nav-link-text">我的客户</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketStudentMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/student/my" class="nav-link" id="marketStudentMy">
                  <i class="ni ni-single-02 text-orange"></i>
                  <span class="nav-link-text">我的学生</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketContractCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/contract/create" class="nav-link" id="marketContractCreate">
                  <i class="ni ni-money-coins text-orange"></i>
                  <span class="nav-link-text">签约合同</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketContractAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/contract/all" class="nav-link" id="marketContractAll">
                  <i class="ni ni-money-coins text-orange"></i>
                  <span class="nav-link-text">签约管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketContractMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/contract/my" class="nav-link" id="marketContractMy">
                  <i class="ni ni-money-coins text-orange"></i>
                  <span class="nav-link-text">我的签约</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketRefundCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/refund/create" class="nav-link" id="marketRefundCreate">
                  <i class="ni ni-cart text-orange"></i>
                  <span class="nav-link-text">学生退费</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketRefundAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/refund/all" class="nav-link" id="marketRefundAll">
                  <i class="ni ni-cart text-orange"></i>
                  <span class="nav-link-text">退费管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('marketRefundMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/market/refund/my" class="nav-link" id="marketRefundMy">
                  <i class="ni ni-cart text-orange"></i>
                  <span class="nav-link-text">我的退费</span>
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
              <li class="nav-item" @if(!in_array('operationFollowerEdit', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/follower/edit" class="nav-link" id="operationFollowerEdit">
                  <i class="ni ni-badge text-info"></i>
                  <span class="nav-link-text">修改负责人</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationStudentAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/student/all" class="nav-link" id="operationStudentAll">
                  <i class="ni ni-single-02 text-info"></i>
                  <span class="nav-link-text">学生管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationStudentMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/student/my" class="nav-link" id="operationStudentMy">
                  <i class="ni ni-single-02 text-info"></i>
                  <span class="nav-link-text">我的学生</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationClassCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/class/create" class="nav-link" id="operationClassCreate">
                  <i class="ni ni-fat-add text-info"></i>
                  <span class="nav-link-text">新建班级</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationClassAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/class/all" class="nav-link" id="operationClassAll">
                  <i class="ni ni-single-copy-04 text-info"></i>
                  <span class="nav-link-text">班级管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationStudentScheduleCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/studentSchedule/create" class="nav-link" id="operationStudentScheduleCreate">
                  <i class="ni ni-calendar-grid-58 text-info"></i>
                  <span class="nav-link-text">学生排课</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationStudentScheduleAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/studentSchedule/all" class="nav-link" id="operationStudentScheduleAll">
                  <i class="ni ni-calendar-grid-58 text-info"></i>
                  <span class="nav-link-text">学生课程</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationClassScheduleCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/classSchedule/create" class="nav-link" id="operationClassScheduleCreate">
                  <i class="ni ni-calendar-grid-58 text-info"></i>
                  <span class="nav-link-text">班级排课</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationClassScheduleAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/classSchedule/all" class="nav-link" id="operationClassScheduleAll">
                  <i class="ni ni-calendar-grid-58 text-info"></i>
                  <span class="nav-link-text">班级课程</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationAttendedScheduleAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/attendedSchedule/all" class="nav-link" id="operationAttendedScheduleAll">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">上课记录</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationScheduleMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/schedule/my" class="nav-link" id="operationScheduleMy">
                  <i class="ni ni-calendar-grid-58 text-info"></i>
                  <span class="nav-link-text">我的学生课程安排</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationAttendedScheduleMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/attendedSchedule/my" class="nav-link" id="operationAttendedScheduleMy">
                  <i class="ni ni-bullet-list-67 text-info"></i>
                  <span class="nav-link-text">我的学生上课记录</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationContractCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/contract/create" class="nav-link" id="operationContractCreate">
                  <i class="ni ni-money-coins text-info"></i>
                  <span class="nav-link-text">签约合同</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationContractAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/contract/all" class="nav-link" id="operationContractAll">
                  <i class="ni ni-money-coins text-info"></i>
                  <span class="nav-link-text">签约管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationContractMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/contract/my" class="nav-link" id="operationContractMy">
                  <i class="ni ni-money-coins text-info"></i>
                  <span class="nav-link-text">我的签约</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationRefundCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/refund/create" class="nav-link" id="operationRefundCreate">
                  <i class="ni ni-cart text-info"></i>
                  <span class="nav-link-text">学生退费</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationRefundAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/refund/all" class="nav-link" id="operationRefundAll">
                  <i class="ni ni-cart text-info"></i>
                  <span class="nav-link-text">退费管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('operationRefundMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/operation/refund/my" class="nav-link" id="operationRefundMy">
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
              <li class="nav-item" @if(!in_array('educationStudentAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/student/all" class="nav-link" id="educationStudentAll">
                  <i class="ni ni-single-02 text-pink"></i>
                  <span class="nav-link-text">学生管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationStudentMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/student/my" class="nav-link" id="educationStudentMy">
                  <i class="ni ni-single-02 text-pink"></i>
                  <span class="nav-link-text">我的学生</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationClassAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/class/all" class="nav-link" id="educationClassAll">
                  <i class="ni ni-single-copy-04 text-pink"></i>
                  <span class="nav-link-text">班级管理</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationClassMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/class/my" class="nav-link" id="educationClassMy">
                  <i class="ni ni-single-copy-04 text-pink"></i>
                  <span class="nav-link-text">我的班级</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationScheduleAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/schedule/all" class="nav-link" id="educationScheduleAll">
                  <i class="ni ni-calendar-grid-58 text-pink"></i>
                  <span class="nav-link-text">课程安排</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationScheduleMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/schedule/my" class="nav-link" id="educationScheduleMy">
                  <i class="ni ni-calendar-grid-58 text-pink"></i>
                  <span class="nav-link-text">我的课程安排</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationAttendedScheduleAll', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/attendedSchedule/all" class="nav-link" id="educationAttendedScheduleAll">
                  <i class="ni ni-bullet-list-67 text-pink"></i>
                  <span class="nav-link-text">上课记录</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationAttendedScheduleMy', Session::get('page_access'))) style="display:none;" @endif>
                <a href="/education/attendedSchedule/my" class="nav-link" id="educationAttendedScheduleMy">
                  <i class="ni ni-bullet-list-67 text-pink"></i>
                  <span class="nav-link-text">我的上课记录</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationDocumentCreate', Session::get('page_access'))) style="display:none;" @endif>
                <a class="nav-link" href="/education/document/create" id="educationDocumentCreate">
                  <i class="ni ni-cloud-upload-96 text-pink"></i>
                  <span class="nav-link-text">上传教案</span>
                </a>
              </li>
              <li class="nav-item" @if(!in_array('educationDocument', Session::get('page_access'))) style="display:none;" @endif>
                <a class="nav-link" href="/education/document" id="educationDocument">
                  <i class="ni ni-cloud-download-95 text-pink"></i>
                  <span class="nav-link-text">教案中心</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <!-- <li class="nav-item">
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
