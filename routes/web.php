<?php
// 登陆控制器
Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/exit', 'LoginController@exit');
// 主页
Route::get('/home', 'HomeController@home');

// 公司管理 ********************************************************************************
// 校区设置
Route::get('/company/department', 'Company\DepartmentController@department');
    // 添加校区
    Route::get('/company/department/create', 'Company\DepartmentController@departmentCreate');
    // 保存校区
    Route::post('/company/department/store', 'Company\DepartmentController@departmentStore');
    // 修改校区
    Route::get('/company/department/edit', 'Company\DepartmentController@departmentEdit');
    // 更新校区
    Route::post('/company/department/update', 'Company\DepartmentController@departmentUpdate');
    // 删除校区
    Route::get('/company/department/delete', 'Company\DepartmentController@departmentDelete');
// 课程设置
Route::get('/company/course', 'Company\CourseController@course');
    // 添加课程
    Route::get('/company/course/create', 'Company\CourseController@courseCreate');
    // 保存课程
    Route::post('/company/course/store', 'Company\CourseController@courseStore');
    // 修改课程
    Route::get('/company/course/edit', 'Company\CourseController@courseEdit');
    // 更新课程
    Route::post('/company/course/update', 'Company\CourseController@courseUpdate');
    // 删除课程
    Route::get('/company/course/delete', 'Company\CourseController@courseDelete');
// 大区设置
Route::get('/company/school', 'Company\SchoolController@school');
    // 添加大区
    Route::get('/company/school/create', 'Company\SchoolController@schoolCreate');
    // 保存大区
    Route::post('/company/school/store', 'Company\SchoolController@schoolStore');
    // 修改大区
    Route::get('/company/school/edit', 'Company\SchoolController@schoolEdit');
    // 更新大区
    Route::post('/company/school/update', 'Company\SchoolController@schoolUpdate');
    // 删除大区
    Route::get('/company/school/delete', 'Company\SchoolController@schoolDelete');
// 教室设置
Route::get('/company/classroom', 'Company\ClassroomController@classroom');
    // 添加教室
    Route::get('/company/classroom/create', 'Company\ClassroomController@classroomCreate');
    // 保存教室
    Route::post('/company/classroom/store', 'Company\ClassroomController@classroomStore');
    // 修改教室
    Route::get('/company/classroom/edit', 'Company\ClassroomController@classroomEdit');
    // 更新教室
    Route::post('/company/classroom/update', 'Company\ClassroomController@classroomUpdate');
    // 删除教室
    Route::get('/company/classroom/delete', 'Company\ClassroomController@classroomDelete');
// 用户管理
Route::get('/company/user', 'Company\UserController@user');
    // 添加用户
    Route::get('/company/user/create', 'Company\UserController@userCreate');
    // 保存用户
    Route::post('/company/user/store', 'Company\UserController@userStore');
    // 删除用户
    Route::get('/company/user/delete', 'Company\UserController@userDelete');
    // 用户权限
    Route::get('/company/user/access', 'Company\UserController@userAccess');
    Route::post('/company/user/access/update', 'Company\UserController@userAccessUpdate');
    // 密码恢复
    Route::get('/company/user/password/restore', 'Company\UserController@userPasswordRestore');
// 部门设置
Route::get('/company/section', 'Company\SectionController@section');
    // 添加部门
    Route::get('/company/section/create', 'Company\SectionController@sectionCreate');
    // 保存部门
    Route::post('/company/section/store', 'Company\SectionController@sectionStore');
    // 修改部门
    Route::get('/company/section/edit', 'Company\SectionController@sectionEdit');
    // 更新部门
    Route::post('/company/section/update', 'Company\SectionController@sectionUpdate');
    // 删除部门
    Route::get('/company/section/delete', 'Company\SectionController@sectionDelete');
    // 添加岗位
    Route::get('/company/position/create', 'Company\SectionController@positionCreate');
    // 保存岗位
    Route::post('/company/position/store', 'Company\SectionController@positionStore');
    // 修改岗位
    Route::get('/company/position/edit', 'Company\SectionController@positionEdit');
    // 更新岗位
    Route::post('/company/position/update', 'Company\SectionController@positionUpdate');
    // 删除岗位
    Route::get('/company/position/delete', 'Company\SectionController@positionDelete');

// 招生中心 ********************************************************************************
// 客户管理
Route::get('/market/customer', 'Market\CustomerController@customer');
    // 添加新公共客户
    Route::get('/market/customer/create', 'Market\CustomerController@customerCreate');
    Route::post('/market/customer/store', 'Market\CustomerController@customerStore');
    Route::get('/market/customer/success', 'Market\CustomerController@customerSuccess');
    // 修改客户课程顾问
    Route::get('/market/customer/consultant/edit', 'Market\CustomerController@consultantEdit');
    Route::post('/market/customer/consultant/update', 'Market\CustomerController@consultantUpdate');
    // 客户删除
    Route::get('/market/customer/delete', 'Market\CustomerController@customerDelete');
// 学生管理
Route::get('/market/student', 'Market\StudentController@student');
    // 学生删除(转为离校)
    Route::get('/market/student/delete', 'Market\StudentController@studentDelete');
    // 修改学生负责人
    Route::get('/market/student/follower/edit', 'Market\StudentController@followerEdit');
    Route::post('/market/student/follower/update', 'Market\StudentController@followerUpdate');
// 离校学生
Route::get('/market/student/deleted', 'Market\StudentDeletedController@studentDeleted');
    // 离校学生恢复
    Route::get('/market/student/deleted/restore', 'Market\StudentDeletedController@studentDeletedRestore');
    // 离校学生删除
    Route::get('/market/student/deleted/delete', 'Market\StudentDeletedController@studentDeletedDelete');
// 我的客户
Route::get('/market/myCustomer', 'Market\MyCustomerController@myCustomer');
    // 添加我的客户
    Route::get('/market/myCustomer/create', 'Market\MyCustomerController@myCustomerCreate');
    Route::post('/market/myCustomer/store', 'Market\MyCustomerController@myCustomerStore');
    Route::get('/market/myCustomer/success', 'Market\MyCustomerController@myCustomerSuccess');
    // 我的客户删除
    Route::get('/market/myCustomer/delete', 'Market\MyCustomerController@myCustomerDelete');
    // 签约合同
    Route::get('/market/myCustomer/contract/create', 'Market\MyCustomerController@myCustomerContractCreate');
    Route::post('/market/myCustomer/contract/store', 'Market\MyCustomerController@myCustomerContractStore');
    Route::get('/market/myCustomer/contract/success', 'Market\MyCustomerController@myCustomerContractSuccess');
// 我的学生
Route::get('/market/myStudent', 'Market\MyStudentController@myStudent');
    // 签约合同
    Route::get('/market/myStudent/contract/create', 'Market\MyStudentController@myStudentContractCreate');
    Route::post('/market/myStudent/contract/store', 'Market\MyStudentController@myStudentContractStore');
    Route::get('/market/myStudent/contract/success', 'Market\MyStudentController@myStudentContractSuccess');
// 签约管理
Route::get('/market/contract', 'Market\ContractController@contract');
    // 删除签约
    Route::get('/market/contract/delete', 'Market\ContractController@contractDelete');
    // 补缴
    Route::get('/market/contract/edit', 'Market\ContractController@contractEdit');
    Route::post('/market/contract/update', 'Market\ContractController@contractUpdate');
// 我的签约
Route::get('/market/myContract', 'Market\MyContractController@myContract');
    // 删除签约
    Route::get('/market/myContract/delete', 'Market\MyContractController@myContractDelete');
    // 补缴
    Route::get('/market/myContract/edit', 'Market\MyContractController@myContractEdit');
    Route::post('/market/myContract/update', 'Market\MyContractController@myContractUpdate');



// 运营中心 ********************************************************************************
// 学生管理
Route::get('/operation/student', 'Operation\StudentController@student');
    // 插入班级
    Route::get('/operation/student/member/add', 'Operation\StudentController@studentMemberAdd');
    Route::post('/operation/student/member/store', 'Operation\StudentController@studentMemberStore');
    // 修改学生负责人
    Route::get('/operation/student/follower/edit', 'Operation\StudentController@followerEdit');
    Route::post('/operation/student/follower/update', 'Operation\StudentController@followerUpdate');
    // 学生删除(转为离校)
    Route::get('/operation/student/delete', 'Operation\StudentController@studentDelete');
    // 学生一对一排课
    Route::get('/operation/student/schedule/create', 'Operation\StudentController@studentScheduleCreate');
    Route::post('/operation/student/schedule/create2', 'Operation\StudentController@studentScheduleCreate2');
    Route::post('/operation/student/schedule/store', 'Operation\StudentController@studentScheduleStore');
    Route::get('/operation/student/schedule/success', 'Operation\StudentController@studentScheduleCreateSuccess');
    // 插入班级
    Route::get('/operation/student/joinClass', 'Operation\StudentController@joinClass');
    Route::post('/operation/student/joinClass/store', 'Operation\StudentController@joinClassStore');
// 学生课时
Route::get('/operation/hour', 'Operation\HourController@hour');
    // 学生退费
    Route::get('/operation/hour/refund/create', 'Operation\HourController@refundCreate');
    Route::post('/operation/hour/refund/store', 'Operation\HourController@refundStore');
    Route::get('/operation/hour/refund/delete', 'Operation\HourController@refundDelete');
    // 课时清理
    Route::get('/operation/hour/clean', 'Operation\HourController@hourClean');
    Route::post('/operation/hour/clean/store', 'Operation\HourController@hourCleanStore');
// 班级管理
Route::get('/operation/class', 'Operation\ClassController@class');
    // 新建班级
    Route::get('/operation/class/create', 'Operation\ClassController@classCreate');
    Route::post('/operation/class/store', 'Operation\ClassController@classStore');
    // 删除班级
    Route::get('/operation/class/delete', 'Operation\ClassController@classDelete');
    // 班级排课
    Route::get('/operation/class/schedule/create', 'Operation\ClassController@classScheduleCreate');
    Route::post('/operation/class/schedule/create2', 'Operation\ClassController@classScheduleCreate2');
    Route::post('/operation/class/schedule/store', 'Operation\ClassController@classScheduleStore');
    Route::get('/operation/class/schedule/success', 'Operation\ClassController@classScheduleCreateSuccess');
// 离校学生
Route::get('/operation/student/deleted', 'Operation\StudentDeletedController@studentDeleted');
    // 离校学生恢复
    Route::get('/operation/student/deleted/restore', 'Operation\StudentDeletedController@studentDeletedRestore');
    // 离校学生删除
    Route::get('/operation/student/deleted/delete', 'Operation\StudentDeletedController@studentDeletedDelete');
// 我的学生
Route::get('/operation/myStudent', 'Operation\MyStudentController@myStudent');
    // 签约合同
    Route::get('/operation/myStudent/contract/create', 'Operation\MyStudentController@myStudentContractCreate');
    Route::post('/operation/myStudent/contract/store', 'Operation\MyStudentController@myStudentContractStore');
    Route::get('/operation/myStudent/contract/success', 'Operation\MyStudentController@myStudentContractSuccess');
    // 学生一对一排课
    Route::get('/operation/myStudent/schedule/create', 'Operation\MyStudentController@myStudentScheduleCreate');
    Route::post('/operation/myStudent/schedule/create2', 'Operation\MyStudentController@myStudentScheduleCreate2');
    Route::post('/operation/myStudent/schedule/store', 'Operation\MyStudentController@myStudentScheduleStore');
    Route::get('/operation/myStudent/schedule/success', 'Operation\MyStudentController@myStudentScheduleCreateSuccess');
    // 插入班级
    Route::get('/operation/myStudent/joinClass', 'Operation\MyStudentController@joinClass');
    Route::post('/operation/myStudent/joinClass/store', 'Operation\MyStudentController@joinClassStore');
// 我的学生课时
Route::get('/operation/myHour', 'Operation\MyHourController@myHour');
    // 学生退费
    Route::get('/operation/myHour/refund/create', 'Operation\MyHourController@refundCreate');
    Route::post('/operation/myHour/refund/store', 'Operation\MyHourController@refundStore');
    Route::get('/operation/myHour/refund/delete', 'Operation\MyHourController@refundDelete');
// 课程安排
Route::get('/operation/schedule', 'Operation\ScheduleController@schedule');
    // 课程安排删除
    Route::get('/operation/schedule/delete', 'Operation\ScheduleController@scheduleDelete');
    // 考勤
    Route::get('/operation/schedule/attend', 'Operation\ScheduleController@scheduleAttend');
    Route::post('/operation/schedule/attend/store', 'Operation\ScheduleController@scheduleAttendStore');
    Route::get('/operation/schedule/attend/success', 'Operation\ScheduleController@scheduleAttendSuccess');
// 上课记录
Route::get('/operation/attendedSchedule', 'Operation\AttendedScheduleController@attendedSchedule');
// 我的学生课程安排
Route::get('/operation/mySchedule', 'Operation\MyScheduleController@mySchedule');
    // 我的学生课程安排删除
    Route::get('/operation/mySchedule/delete', 'Operation\MyScheduleController@myScheduleDelete');
// 我的学生上课记录
Route::get('/operation/myAttendedSchedule', 'Operation\MyAttendedScheduleController@myAttendedSchedule');
    // 复核上课记录
    // Route::get('/operation/myAttendedSchedule/check', 'Operation\MyAttendedScheduleController@myAttendedScheduleCheck');
// 签约管理
Route::get('/operation/contract', 'Operation\ContractController@contract');
    // 删除签约
    Route::get('/operation/contract/delete', 'Operation\ContractController@contractDelete');
    // 补缴
    Route::get('/operation/contract/edit', 'Operation\ContractController@contractEdit');
    Route::post('/operation/contract/update', 'Operation\ContractController@contractUpdate');
// 我的签约
Route::get('/operation/myContract', 'Operation\MyContractController@myContract');
    // 删除签约
    Route::get('/operation/myContract/delete', 'Operation\MyContractController@myContractDelete');
    // 补缴
    Route::get('/operation/myContract/edit', 'Operation\MyContractController@myContractEdit');
    Route::post('/operation/myContract/update', 'Operation\MyContractController@myContractUpdate');
// 退费管理
Route::get('/operation/refund', 'Operation\RefundController@refund');
  // 退费复核
  // Route::get('/operation/refund/check', 'Operation\RefundController@refundCheck');
  // 退费删除
  Route::get('/operation/refund/delete', 'Operation\RefundController@refundDelete');
// 我的退费
Route::get('/operation/myRefund', 'Operation\MyRefundController@myRefund');
  // 退费复核
  // Route::get('/operation/refund/check', 'Operation\RefundController@refundCheck');
  // 退费删除
  Route::get('/operation/myRefund/delete', 'Operation\MyRefundController@myRefundDelete');



// 教学中心
// 学生管理
Route::get('/education/student', 'Education\StudentController@student');
// 班级管理
Route::get('/education/class', 'Education\ClassController@class');
// 课程安排
Route::get('/education/schedule', 'Education\ScheduleController@schedule');
// 上课记录
Route::get('/education/attendedSchedule', 'Education\AttendedScheduleController@attendedSchedule');
// 我的班级
Route::get('/education/myClass', 'Education\MyClassController@myClass');
// 我的课程安排
Route::get('/education/mySchedule', 'Education\MyScheduleController@mySchedule');
// 我的上课记录
Route::get('/education/myAttendedSchedule', 'Education\MyAttendedScheduleController@myAttendedSchedule');
// 教案中心
Route::get('/education/document', 'Education\DocumentController@document');
    // 上传教案
    Route::get('/education/document/create', 'Education\DocumentController@documentCreate');
    Route::post('/education/document/store', 'Education\DocumentController@documentStore');
    // 下载教案
    Route::get('/education/document/download', 'Education\DocumentController@documentDownload');
    // 删除教案
    Route::get('/education/document/delete', 'Education\DocumentController@documentDelete');

// 数据中心
// 签约统计
Route::any('/finance/contract', 'FinanceController@contract');
// 课时消耗
Route::any('/finance/consumption', 'FinanceController@consumption');
// 退费统计
Route::any('/finance/refund', 'FinanceController@refund');


// 用户详情
Route::get('/user/{user_id}', 'UserController@show');
    // 修改用户
    Route::get('/user/{user_id}/edit', 'UserController@edit');
    // 更新用户
    Route::put('/user/{user_id}', 'UserController@update');


// 学生详情
Route::get('/student', 'StudentController@show');
    // 修改学生
    Route::get('/student/edit', 'StudentController@edit');
    Route::post('/student/update', 'StudentController@update');
    // 学生跟进记录提交
    Route::post('/student/remark', 'StudentController@remark');
    // 学生跟进记录提交
    Route::post('/student/record', 'StudentController@record');

// 班级详情
Route::get('/class', 'ClassController@show');
    // 修改班级
    Route::get('/class/edit', 'ClassController@edit');
    Route::post('/class/update', 'ClassController@update');
    // 添加成员
    Route::post('/class/memberAdd', 'ClassController@memberAdd');
    // 删除成员
    Route::get('/class/memberDelete', 'ClassController@memberDelete');

// 查看合同
Route::get('/contract', 'ContractController@show');

// 上课
// 查看上课安排详情
Route::get('/schedule', 'ScheduleController@schedule');
// 查看上课记录详情
Route::get('/attendedSchedule', 'ScheduleController@attendedSchedule');

// 个人信息
Route::get('/profile', 'ProfileController@show');
Route::post('/user/{user_id}/password', 'ProfileController@password');

// 课程表
Route::get('/calendar', 'CalendarController@calendar');
