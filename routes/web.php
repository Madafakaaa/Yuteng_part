<?php
/*
Route::get('/', function () {
    return view('welcome');
});
//控制器路由
Route::get('/', 'UserController@function_name');
Route::get('/', 'User/OrderController@function_name');
//资源路由
Route::resource('/article', 'ArticleController');
//配置参数
Route::get('/user/{id}/{name}', function ($id,$name) {
    return view('welcome');
});
//默认参数
Route::get('/user/{id?}', function ($id=null) {
    return view('welcome');
});
//过滤参数
Route::get('/user/{id?}', function ($id=null) {
    return view('welcome');
})->where('id','[a-z]+');
//重定向
Route::redirect('/a', '/b');
//视图
Route::view('/','view_name');
//多种请求
Route::match(['get','post'], '/', function () {
    return view('welcome');
});
//任意请求
Route::any('/', function () {
    return view('welcome');
});
//路由组前缀
Route::prefix('admin')->group(function(){
    Route::get('users', function () {
        return view('welcome');
    });
    Route::get('orders', function () {
        return view('welcome');
    });
});
*/

// 0.  登陆控制器
Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/exit', 'LoginController@exit');
// 1.  主页
Route::get('/home', 'HomeController@home');

// 2.  公司管理
// 2.1 校区设置
Route::resource('/department', 'DepartmentController');
// 2.4 课程设置
Route::resource('/course', 'CourseController');
// 2.2 用户管理
Route::resource('/user', 'UserController');
// 2.3 员工档案
Route::resource('/archive', 'ArchiveController');
// 2.3 部门架构
Route::resource('/section', 'SectionController');
Route::resource('/position', 'PositionController');
// 2.5 公立学校
Route::resource('/school', 'SchoolController');
// 2.6 教室设置
Route::resource('/classroom', 'ClassroomController');

// 招生中心
// 公共客户录入
Route::get('/market/publicCustomer/create', 'MarketController@publicCustomerCreate');
Route::post('/market/publicCustomer/create', 'MarketController@publicCustomerStore');
// 我的客户录入
Route::get('/market/myCustomer/create', 'MarketController@myCustomerCreate');
Route::post('/market/myCustomer/create', 'MarketController@myCustomerStore');
// 修改负责人
Route::get('/market/follower/edit', 'MarketController@followerEdit');
Route::post('/market/follower/edit2', 'MarketController@followerEdit2');
Route::post('/market/follower/store', 'MarketController@followerStore');
// 部门客户
Route::get('/market/all/customer', 'MarketController@allCustomer');
// 本校客户
Route::get('/market/department/customer', 'MarketController@departmentCustomer');
// 我的客户
Route::get('/market/my/customer', 'MarketController@myCustomer');
// 我的学生
Route::get('/market/my/student', 'MarketController@myStudent');
// 签约合同
Route::get('/market/contract/create', 'MarketController@contractCreate');
Route::post('/market/contract/create2', 'MarketController@contractCreate2');
Route::post('/market/contract/store', 'MarketController@contractStore');
Route::delete('/market/contract/{contract_id}', 'MarketController@contractDelete');
// 部门签约
Route::get('/market/all/contract', 'MarketController@allContract');
// 本校签约
Route::get('/market/department/contract', 'MarketController@departmentContract');
// 我的签约
Route::get('/market/my/contract', 'MarketController@myContract');
// 学生退费
Route::get('/market/refund/create', 'MarketController@refundCreate');
Route::post('/market/refund/create2', 'MarketController@refundCreate2');
Route::post('/market/refund/create3', 'MarketController@refundCreate3');
Route::post('/market/refund/create4', 'MarketController@refundCreate4');
Route::post('/market/refund/store', 'MarketController@refundStore');
Route::delete('/market/refund/{refund_id}', 'MarketController@refundDelete');
// 部门退费
Route::get('/market/all/refund', 'MarketController@allRefund');
// 本校退费
Route::get('/market/department/refund', 'MarketController@departmentRefund');
// 我的退费
Route::get('/market/my/refund', 'MarketController@myRefund');

// 运营中心
// 修改负责人
Route::get('/operation/follower/edit', 'OperationController@followerEdit');
Route::post('/operation/follower/edit2', 'OperationController@followerEdit2');
Route::post('/operation/follower/store', 'OperationController@followerStore');
// 全部学生
Route::get('/operation/student/all', 'OperationController@studentAll');
// 本校学生
Route::get('/operation/student/department', 'OperationController@studentDepartment');
// 我的学生
Route::get('/operation/student/my', 'OperationController@studentMy');
// 新建班级
Route::get('/operation/class/create', 'OperationController@classCreate');
Route::post('/operation/class/store', 'OperationController@classStore');
// 全部班级
Route::get('/operation/class/all', 'OperationController@classAll');
// 本校班级
Route::get('/operation/class/department', 'OperationController@classDepartment');
// 安排学生课程
Route::get('/operation/studentSchedule/create', 'OperationController@studentScheduleCreate');
Route::get('/operation/studentSchedule/createIrregular', 'OperationController@studentScheduleCreateIrregular');
Route::post('/operation/studentSchedule/create2', 'OperationController@studentScheduleCreate2');
Route::post('/operation/studentSchedule/createIrregular2', 'OperationController@studentScheduleCreateIrregular2');
Route::post('/operation/studentSchedule/create3', 'OperationController@studentScheduleCreate3');
Route::post('/operation/studentSchedule/store', 'OperationController@studentScheduleStore');
// 安排班级课程
Route::get('/operation/classSchedule/create', 'OperationController@classScheduleCreate');
Route::get('/operation/classSchedule/createIrregular', 'OperationController@classScheduleCreateIrregular');
Route::post('/operation/classSchedule/create2', 'OperationController@classScheduleCreate2');
Route::post('/operation/classSchedule/createIrregular2', 'OperationController@classScheduleCreateIrregular2');
Route::post('/operation/classSchedule/create3', 'OperationController@classScheduleCreate3');
Route::post('/operation/classSchedule/store', 'OperationController@classScheduleStore');
// 本校学生课程安排
Route::get('/operation/studentSchedule/department', 'OperationController@studentScheduleDepartment');
// 本校班级课程安排
Route::get('/operation/classSchedule/department', 'OperationController@classScheduleDepartment');
// 本校上课记录
Route::get('/operation/attendedSchedule/department', 'OperationController@attendedScheduleDepartment');
// 我的学生课程安排
Route::get('/operation/schedule/my', 'OperationController@ScheduleMy');
// 我的学生上课记录
Route::get('/operation/attendedSchedule/my', 'OperationController@attendedScheduleMy');


// 查看学生
Route::get('/student/{student_id}', 'StudentController@show');
// 修改学生
Route::get('/student/{student_id}/edit', 'StudentController@edit');
Route::put('/student/{student_id}', 'StudentController@update');
// 学生跟进记录提交
Route::post('/student/{student_id}/remark', 'StudentController@remark');
// 学生跟进记录提交
Route::post('/student/{student_id}/record', 'StudentController@record');
// 查看合同
Route::get('/contract/{contract_id}', 'ContractController@show');
// 查看班级
Route::get('/class/{class_id}', 'ClassController@show');
// 修改班级
Route::get('/class/{class_id}/edit', 'ClassController@edit');
Route::put('/class/{class_id}', 'ClassController@update');




// 5.  财务中心
// 5.1 签约合同
// 5.2 课时退费
// 5.3 本校签约
Route::get('/departmentContract', 'ContractController@department');
// 5.5 本校退费
Route::get('/departmentRefund', 'RefundController@department');
// 5.4 我的签约
Route::get('/myContract', 'ContractController@my');
// 5.3 我的退费
Route::get('/myRefund', 'RefundController@my');

// 6   教案查询
Route::resource('/document', 'DocumentController');
// 7   课程表
Route::get('/calendar', 'CalendarController@calendar');
// 8   个人信息
Route::resource('/profile', 'ProfileController');
