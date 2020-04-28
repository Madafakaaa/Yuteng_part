<?php
// 登陆控制器
Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/exit', 'LoginController@exit');
// 主页
Route::get('/home', 'HomeController@home');

// 公司管理
// 校区设置
Route::get('/company/department', 'CompanyController@department');
    // 添加校区
    Route::get('/company/department/create', 'CompanyController@departmentCreate');
    // 保存校区
    Route::post('/company/department/store', 'CompanyController@departmentStore');
    // 修改校区
    Route::get('/company/department/edit', 'CompanyController@departmentEdit');
    // 更新校区
    Route::post('/company/department/update', 'CompanyController@departmentUpdate');
    // 删除校区
    Route::get('/company/department/delete', 'CompanyController@departmentDelete');

// 课程设置
Route::get('/company/course', 'CompanyController@course');
    // 添加课程
    Route::get('/company/course/create', 'CompanyController@courseCreate');
    // 保存课程
    Route::post('/company/course/create', 'CompanyController@courseStore');
    // 修改课程
    Route::get('/company/course/{course_id}', 'CompanyController@courseEdit');
    // 更新课程
    Route::put('/company/course/{course_id}', 'CompanyController@courseUpdate');
    // 删除课程
    Route::delete('/company/course/{course_id}', 'CompanyController@courseDelete');

// 大区设置
Route::get('/company/school', 'CompanyController@school');
    // 添加大区
    Route::get('/company/school/create', 'CompanyController@schoolCreate');
    // 保存大区
    Route::post('/company/school/create', 'CompanyController@schoolStore');
    // 修改大区
    Route::get('/company/school/{school_id}', 'CompanyController@schoolEdit');
    // 更新大区
    Route::put('/company/school/{school_id}', 'CompanyController@schoolUpdate');
    // 删除大区
    Route::delete('/company/school/{school_id}', 'CompanyController@schoolDelete');

// 教室设置
Route::get('/company/classroom', 'CompanyController@classroom');
    // 添加教室
    Route::get('/company/classroom/create', 'CompanyController@classroomCreate');
    // 保存教室
    Route::post('/company/classroom/create', 'CompanyController@classroomStore');
    // 修改教室
    Route::get('/company/classroom/{classroom_id}', 'CompanyController@classroomEdit');
    // 更新教室
    Route::put('/company/classroom/{classroom_id}', 'CompanyController@classroomUpdate');
    // 删除教室
    Route::delete('/company/classroom/{classroom_id}', 'CompanyController@classroomDelete');

// 用户管理
Route::get('/company/user', 'CompanyController@user');
    // 添加用户
    Route::get('/company/user/create', 'CompanyController@userCreate');
    // 保存用户
    Route::post('/company/user/create', 'CompanyController@userStore');
    // 删除用户
    Route::delete('/company/user/{user_id}', 'CompanyController@userDelete');
    // 用户权限
    Route::get('/company/user/access/{user_id}', 'CompanyController@userAccess');
    Route::post('/company/user/access/{user_id}', 'CompanyController@userAccessUpdate');
    // 密码恢复
    Route::get('/company/user/password/restore/{user_id}', 'CompanyController@userPasswordRestore');

// 部门设置
Route::get('/company/section', 'CompanyController@section');
    // 添加部门
    Route::get('/company/section/create', 'CompanyController@sectionCreate');
    // 保存部门
    Route::post('/company/section/create', 'CompanyController@sectionStore');
    // 修改部门
    Route::get('/company/section/{section_id}', 'CompanyController@sectionEdit');
    // 更新部门
    Route::put('/company/section/{section_id}', 'CompanyController@sectionUpdate');
    // 删除部门
    Route::delete('/company/section/{section_id}', 'CompanyController@sectionDelete');
    // 添加岗位
    Route::get('/company/position/create', 'CompanyController@positionCreate');
    // 保存岗位
    Route::post('/company/position/create', 'CompanyController@positionStore');
    // 修改岗位
    Route::get('/company/position/{position_id}', 'CompanyController@positionEdit');
    // 更新岗位
    Route::put('/company/position/{position_id}', 'CompanyController@positionUpdate');
    // 删除岗位
    Route::delete('/company/position/{position_id}', 'CompanyController@positionDelete');

// 招生中心
// 客户管理
Route::get('/market/customer/all', 'MarketController@customerAll');
    // 客户删除
    Route::delete('/market/customer/all/{student_id}', 'MarketController@customerAllDelete');
    // 修改客户课程顾问
    Route::get('/market/customer/consultant/edit', 'MarketController@consultantEdit');
    Route::post('/market/customer/consultant/store', 'MarketController@consultantStore');
    // 添加新公共客户
    Route::get('/market/publicCustomer/create', 'MarketController@publicCustomerCreate');
    Route::post('/market/publicCustomer/create', 'MarketController@publicCustomerStore');
// 我的客户
Route::get('/market/customer/my', 'MarketController@customerMy');
    // 添加我的客户
    Route::get('/market/myCustomer/create', 'MarketController@myCustomerCreate');
    Route::post('/market/myCustomer/create', 'MarketController@myCustomerStore');
    // 我的客户删除
    Route::delete('/market/customer/my/{student_id}', 'MarketController@customerMyDelete');
    // 签约合同
    Route::any('/market/contract/create', 'MarketController@contractCreate');
    Route::post('/market/contract/store', 'MarketController@contractStore');
// 学生管理
Route::get('/market/student/all', 'MarketController@studentAll');
    // 学生删除(转为离校)
    Route::delete('/market/student/all/{student_id}', 'MarketController@studentDelete');
    // 修改学生负责人
    Route::get('/market/student/follower/edit', 'MarketController@followerEdit');
    Route::post('/market/student/follower/store', 'MarketController@followerStore');
// 离校学生
Route::get('/market/student/deleted', 'MarketController@studentDeleted');
    // 离校学生恢复
    Route::get('/market/student/deleted/restore/{student_id}', 'MarketController@studentRestore');
    // 离校学生删除
    Route::delete('/market/student/deleted/{student_id}', 'MarketController@studentDeletedDelete');
// 我的学生
Route::get('/market/student/my', 'MarketController@studentMy');
    // 学生退费
    Route::get('/market/refund/create', 'MarketController@refundCreate');
    Route::post('/market/refund/create2', 'MarketController@refundCreate2');
    Route::post('/market/refund/create3', 'MarketController@refundCreate3');
    Route::post('/market/refund/create4', 'MarketController@refundCreate4');
    Route::post('/market/refund/store', 'MarketController@refundStore');
// 签约管理
Route::get('/market/contract/all', 'MarketController@contractAll');
    // 删除签约
    Route::delete('/market/contract/{contract_id}', 'MarketController@contractDelete');
    // 补缴
    Route::get('/market/contract/edit/{contract_id}', 'MarketController@contractEdit');
    Route::post('/market/contract/edit/{contract_id}', 'MarketController@contractUpdate');
// 我的签约
Route::get('/market/contract/my', 'MarketController@contractMy');
// 退费管理
Route::get('/market/refund/all', 'MarketController@refundAll');
    // 退费复核
    Route::get('/market/refund/check/{refund_id}', 'MarketController@refundCheck');
    // 删除退费
    Route::delete('/market/refund/{refund_id}', 'MarketController@refundDelete');
// 我的退费
Route::get('/market/refund/my', 'MarketController@refundMy');


// 运营中心
// 学生管理
Route::get('/operation/student/all', 'OperationController@studentAll');
    // 修改学生负责人
    Route::get('/operation/follower/edit', 'OperationController@followerEdit');
    Route::post('/operation/follower/store', 'OperationController@followerStore');
    // 学生删除(转为离校)
    Route::delete('/operation/student/all/{student_id}', 'OperationController@studentDelete');
// 离校学生
Route::get('/operation/student/deleted', 'OperationController@studentDeleted');
    // 离校学生恢复
    Route::get('/operation/student/deleted/restore/{student_id}', 'OperationController@studentRestore');
    // 离校学生删除
    Route::delete('/operation/student/deleted/{student_id}', 'OperationController@studentDeletedDelete');
// 我的学生
Route::get('/operation/student/my', 'OperationController@studentMy');
    // 学生排课
    Route::get('/operation/studentSchedule/create', 'OperationController@studentScheduleCreate');
    Route::post('/operation/studentSchedule/create2', 'OperationController@studentScheduleCreate2');
    Route::post('/operation/studentSchedule/create3', 'OperationController@studentScheduleCreate3');
    Route::post('/operation/studentSchedule/store', 'OperationController@studentScheduleStore');
    // 插入班级
    Route::get('/operation/member/add', 'OperationController@memberAdd');
    Route::post('/operation/member/store', 'OperationController@memberStore');
    // 签约合同
    Route::get('/operation/contract/create', 'OperationController@contractCreate');
    Route::any('/operation/contract/create2', 'OperationController@contractCreate2');
    Route::post('/operation/contract/store', 'OperationController@contractStore');
    Route::delete('/operation/contract/{contract_id}', 'OperationController@contractDelete');
    Route::get('/operation/contract/edit/{contract_id}', 'OperationController@contractEdit');
    Route::post('/operation/contract/edit/{contract_id}', 'OperationController@contractUpdate');
    // 学生退费
    Route::get('/operation/refund/create', 'OperationController@refundCreate');
    Route::post('/operation/refund/create2', 'OperationController@refundCreate2');
    Route::post('/operation/refund/create3', 'OperationController@refundCreate3');
    Route::post('/operation/refund/store', 'OperationController@refundStore');
    Route::delete('/operation/refund/{refund_id}', 'OperationController@refundDelete');
// 新建班级
Route::get('/operation/class/create', 'OperationController@classCreate');
Route::post('/operation/class/store', 'OperationController@classStore');
// 班级管理
Route::get('/operation/class/all', 'OperationController@classAll');
    // 班级排课
    Route::get('/operation/classSchedule/create', 'OperationController@classScheduleCreate');
    Route::get('/operation/classSchedule/createIrregular', 'OperationController@classScheduleCreateIrregular');
    Route::post('/operation/classSchedule/create2', 'OperationController@classScheduleCreate2');
    Route::post('/operation/classSchedule/createIrregular2', 'OperationController@classScheduleCreateIrregular2');
    Route::post('/operation/classSchedule/create3', 'OperationController@classScheduleCreate3');
    Route::post('/operation/classSchedule/store', 'OperationController@classScheduleStore');
    // 删除班级
    Route::delete('/operation/class/all/{class_id}', 'OperationController@classDelete');
// 学生课程
Route::get('/operation/studentSchedule/all', 'OperationController@studentScheduleAll');
    // 学生课程删除
    Route::delete('/operation/studentSchedule/{schedule_id}', 'OperationController@studentScheduleDelete');
    // 考勤
    Route::get('/operation/schedule/attend/{schedule_id}', 'OperationController@scheduleAttend');
    Route::post('/operation/schedule/attend/{schedule_id}/step2', 'OperationController@scheduleAttend2');
    Route::post('/operation/schedule/attend/{schedule_id}/store', 'OperationController@scheduleAttendStore');
    Route::get('/operation/schedule/attend/{schedule_id}/result', 'OperationController@scheduleAttendResult');
// 班级课程
Route::get('/operation/classSchedule/all', 'OperationController@classScheduleAll');
    // 班级课程删除
    Route::delete('/operation/classSchedule/{schedule_id}', 'OperationController@classScheduleDelete');
// 上课记录
Route::get('/operation/attendedSchedule/all', 'OperationController@attendedScheduleAll');
// 我的学生课程安排
Route::get('/operation/schedule/my', 'OperationController@ScheduleMy');
    // 我的学生课程安排删除
    Route::delete('/operation/schedule/my/{schedule_id}', 'OperationController@myScheduleDelete');
// 我的学生上课记录
Route::get('/operation/attendedSchedule/my', 'OperationController@attendedScheduleMy');
    // 复核上课记录
    Route::get('/attendedSchedule/{participant_id}/check', 'OperationController@attendedScheduleCheck');
// 签约管理
Route::get('/operation/contract/all', 'OperationController@contractAll');
// 我的签约
Route::get('/operation/contract/my', 'OperationController@contractMy');
// 退费管理
Route::get('/operation/refund/all', 'OperationController@refundAll');
Route::get('/operation/refund/{refund_id}', 'OperationController@refundCheck');
// 我的退费
Route::get('/operation/refund/my', 'OperationController@refundMy');


// 教学中心
// 全部学生
Route::get('/education/student/all', 'EducationController@studentAll');
// 本校学生
Route::get('/education/student/department', 'EducationController@studentDepartment');
// 我的学生
Route::get('/education/student/my', 'EducationController@studentMy');
// 全部班级
Route::get('/education/class/all', 'EducationController@classAll');
// 删除班级
Route::delete('/education/class/all/{class_id}', 'EducationController@classDelete');
// 本校班级
Route::get('/education/class/department', 'EducationController@classDepartment');
// 我的班级
Route::get('/education/class/my', 'EducationController@classMy');
// 全部课程安排
Route::get('/education/schedule/all', 'EducationController@scheduleAll');
// 本校课程安排
Route::get('/education/schedule/department', 'EducationController@scheduleDepartment');
// 我的课程安排
Route::get('/education/schedule/my', 'EducationController@scheduleMy');
// 全部上课记录
Route::get('/education/attendedSchedule/all', 'EducationController@attendedScheduleAll');
// 本校上课记录
Route::get('/education/attendedSchedule/department', 'EducationController@attendedScheduleDepartment');
// 我的上课记录
Route::get('/education/attendedSchedule/my', 'EducationController@attendedScheduleMy');
// 上传教案
Route::get('/education/document/create', 'EducationController@documentCreate');
Route::post('/education/document/store', 'EducationController@documentStore');
// 教案中心
Route::get('/education/document', 'EducationController@document');
// 下载教案
Route::get('/education/document/{document_id}', 'EducationController@documentDownload');
// 删除教案
Route::delete('/education/document/{document_id}', 'EducationController@documentDelete');

// 数据中心
// 签约统计
Route::any('/finance/contract', 'FinanceController@contract');
// 课时消耗
Route::any('/finance/consumption', 'FinanceController@consumption');
// 退费统计
Route::any('/finance/refund', 'FinanceController@refund');

// 用户
// 查看用户
Route::get('/user/{user_id}', 'UserController@show');
// 修改用户
Route::get('/user/{user_id}/edit', 'UserController@edit');
// 更新用户
Route::put('/user/{user_id}', 'UserController@update');

// 学生
// 查看学生
Route::get('/student/{student_id}', 'StudentController@show');
// 修改学生
Route::get('/student/{student_id}/edit', 'StudentController@edit');
Route::put('/student/{student_id}', 'StudentController@update');
// 学生跟进记录提交
Route::post('/student/{student_id}/remark', 'StudentController@remark');
// 学生跟进记录提交
Route::post('/student/{student_id}/record', 'StudentController@record');

// 班级
// 查看班级
Route::get('/class/{class_id}', 'ClassController@show');
// 修改班级
Route::get('/class/{class_id}/edit', 'ClassController@edit');
Route::put('/class/{class_id}', 'ClassController@update');
// 删除成员
Route::post('/class/{class_id}/add', 'ClassController@memberAdd');
// 删除成员
Route::delete('/class/{class_id}', 'ClassController@memberDelete');

// 合同
// 查看合同
Route::get('/contract/{contract_id}', 'ContractController@show');

// 上课
// 查看上课安排详情
Route::get('/schedule/{schedule_id}', 'ScheduleController@schedule');
// 查看上课记录详情
Route::get('/attendedSchedule/{participant_id}', 'ScheduleController@attendedSchedule');

// 个人信息
Route::get('/profile', 'ProfileController@show');
Route::post('/user/{user_id}/password', 'ProfileController@password');

// 课程表
Route::get('/calendar', 'CalendarController@calendar');
