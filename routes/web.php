<?php
// 登陆控制器
Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/exit', 'LoginController@exit');
// 主页
Route::get('/home', 'HomeController@home');



// 公司管理
// 校区设置
Route::resource('/department', 'DepartmentController');
// 课程设置
Route::resource('/course', 'CourseController');
// 用户管理
Route::resource('/user', 'UserController');
// 员工档案
Route::resource('/archive', 'ArchiveController');
// 部门架构
Route::resource('/section', 'SectionController');
Route::resource('/position', 'PositionController');
// 公立学校
Route::resource('/school', 'SchoolController');
// 教室设置
Route::resource('/classroom', 'ClassroomController');



// 招生中心
// 公共客户录入
Route::get('/market/publicCustomer/create', 'MarketController@publicCustomerCreate');
Route::post('/market/publicCustomer/create', 'MarketController@publicCustomerStore');
// 修改负责人
Route::get('/market/follower/edit', 'MarketController@followerEdit');
Route::post('/market/follower/edit2', 'MarketController@followerEdit2');
Route::post('/market/follower/store', 'MarketController@followerStore');
// 客户管理
Route::get('/market/customer/all', 'MarketController@customerAll');
// 我的客户录入
Route::get('/market/myCustomer/create', 'MarketController@myCustomerCreate');
Route::post('/market/myCustomer/create', 'MarketController@myCustomerStore');
// 我的客户
Route::get('/market/customer/my', 'MarketController@customerMy');
// 我的学生
Route::get('/market/student/my', 'MarketController@studentMy');
// 签约合同
Route::get('/market/contract/create', 'MarketController@contractCreate');
Route::post('/market/contract/create2', 'MarketController@contractCreate2');
Route::post('/market/contract/store', 'MarketController@contractStore');
Route::delete('/market/contract/{contract_id}', 'MarketController@contractDelete');
// 签约管理
Route::get('/market/contract/all', 'MarketController@contractAll');
// 我的签约
Route::get('/market/contract/my', 'MarketController@contractMy');
// 学生退费
Route::get('/market/refund/create', 'MarketController@refundCreate');
Route::post('/market/refund/create2', 'MarketController@refundCreate2');
Route::post('/market/refund/create3', 'MarketController@refundCreate3');
Route::post('/market/refund/create4', 'MarketController@refundCreate4');
Route::post('/market/refund/store', 'MarketController@refundStore');
Route::delete('/market/refund/{refund_id}', 'MarketController@refundDelete');
// 部门管理
Route::get('/market/refund/all', 'MarketController@refundAll');
// 我的退费
Route::get('/market/refund/my', 'MarketController@refundMy');



// 运营中心
// 修改负责人
Route::get('/operation/follower/edit', 'OperationController@followerEdit');
Route::post('/operation/follower/edit2', 'OperationController@followerEdit2');
Route::post('/operation/follower/store', 'OperationController@followerStore');
// 学生管理
Route::get('/operation/student/all', 'OperationController@studentAll');
// 我的学生
Route::get('/operation/student/my', 'OperationController@studentMy');
// 插入班级
Route::get('/operation/member/edit', 'OperationController@memberEdit');
Route::post('/operation/member/edit2', 'OperationController@memberEdit2');
Route::post('/operation/member/store', 'OperationController@memberStore');
// 新建班级
Route::get('/operation/class/create', 'OperationController@classCreate');
Route::post('/operation/class/store', 'OperationController@classStore');
// 班级管理
Route::get('/operation/class/all', 'OperationController@classAll');
// 学生排课
Route::get('/operation/studentSchedule/create', 'OperationController@studentScheduleCreate');
Route::get('/operation/studentSchedule/createIrregular', 'OperationController@studentScheduleCreateIrregular');
Route::post('/operation/studentSchedule/create2', 'OperationController@studentScheduleCreate2');
Route::post('/operation/studentSchedule/createIrregular2', 'OperationController@studentScheduleCreateIrregular2');
Route::post('/operation/studentSchedule/create3', 'OperationController@studentScheduleCreate3');
Route::post('/operation/studentSchedule/store', 'OperationController@studentScheduleStore');
// 学生课程
Route::get('/operation/studentSchedule/all', 'OperationController@studentScheduleAll');
// 班级排课
Route::get('/operation/classSchedule/create', 'OperationController@classScheduleCreate');
Route::get('/operation/classSchedule/createIrregular', 'OperationController@classScheduleCreateIrregular');
Route::post('/operation/classSchedule/create2', 'OperationController@classScheduleCreate2');
Route::post('/operation/classSchedule/createIrregular2', 'OperationController@classScheduleCreateIrregular2');
Route::post('/operation/classSchedule/create3', 'OperationController@classScheduleCreate3');
Route::post('/operation/classSchedule/store', 'OperationController@classScheduleStore');
// 班级课程
Route::get('/operation/classSchedule/all', 'OperationController@classScheduleAll');
// 上课记录
Route::get('/operation/attendedSchedule/all', 'OperationController@attendedScheduleAll');
// 我的学生课程安排
Route::get('/operation/schedule/my', 'OperationController@ScheduleMy');
// 我的学生上课记录
Route::get('/operation/attendedSchedule/my', 'OperationController@attendedScheduleMy');
// 签约合同
Route::get('/operation/contract/create', 'OperationController@contractCreate');
Route::post('/operation/contract/create2', 'OperationController@contractCreate2');
Route::post('/operation/contract/store', 'OperationController@contractStore');
Route::delete('/operation/contract/{contract_id}', 'OperationController@contractDelete');
// 签约管理
Route::get('/operation/contract/all', 'OperationController@contractAll');
// 我的签约
Route::get('/operation/contract/my', 'OperationController@contractMy');
// 学生退费
Route::get('/operation/refund/create', 'OperationController@refundCreate');
Route::post('/operation/refund/create2', 'OperationController@refundCreate2');
Route::post('/operation/refund/create3', 'OperationController@refundCreate3');
Route::post('/operation/refund/create4', 'OperationController@refundCreate4');
Route::post('/operation/refund/store', 'OperationController@refundStore');
Route::delete('/operation/refund/{refund_id}', 'OperationController@refundDelete');
// 退费管理
Route::get('/operation/refund/all', 'OperationController@refundAll');
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
// 考勤
Route::get('/education/schedule/attend/{schedule_id}', 'EducationController@scheduleAttend');
Route::post('/education/schedule/attend/{schedule_id}/step2', 'EducationController@scheduleAttend2');
Route::post('/education/schedule/attend/{schedule_id}/step3', 'EducationController@scheduleAttend3');
Route::post('/education/schedule/attend/{schedule_id}/store', 'EducationController@scheduleAttendStore');
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

// 合同
// 查看合同
Route::get('/contract/{contract_id}', 'ContractController@show');

// 班级
// 查看班级
Route::get('/class/{class_id}', 'ClassController@show');
// 修改班级
Route::get('/class/{class_id}/edit', 'ClassController@edit');
Route::put('/class/{class_id}', 'ClassController@update');

// 上课
// 查看上课安排详情
Route::get('/schedule/{schedule_id}', 'ScheduleController@schedule');
// 查看上课记录详情
Route::get('/attendedSchedule/{participant_id}', 'ScheduleController@attendedSchedule');
// 复核上课记录
Route::get('/attendedSchedule/{participant_id}/check', 'ScheduleController@attendedScheduleCheck');

// 课程表
Route::get('/calendar', 'CalendarController@calendar');



// 个人信息
Route::resource('/profile', 'ProfileController');
