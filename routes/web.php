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

// 2.  内部管理
// 2.1 校区设置
Route::resource('/department', 'DepartmentController');
// 2.2 用户管理
Route::resource('/user', 'UserController');
// 2.3 员工档案
Route::resource('/archive', 'ArchiveController');
// 2.3 部门架构
Route::resource('/position', 'PositionController');
Route::resource('/section', 'SectionController');
// 2.4 课程设置
Route::resource('/course', 'CourseController');
// 2.5 公立学校
Route::resource('/school', 'SchoolController');
// 2.6 教室设置
Route::resource('/classroom', 'ClassroomController');

// 3.  全校数据
// 3.1 全部客户
Route::resource('/customer', 'CustomerController');
// 客户修改
Route::post('/customer/{id}/record', 'CustomerController@record');
Route::post('/customer/{id}/remark', 'CustomerController@remark');
Route::post('/customer/{id}/follower', 'CustomerController@follower');
Route::post('/customer/{id}/followLevel', 'CustomerController@followLevel');
Route::delete('/myCustomer/{id}', 'CustomerController@myDelete');
// 3.2 全部学生
Route::resource('/student', 'StudentController');
Route::post('/student/{id}/remark', 'StudentController@remark');
Route::post('/student/{id}/follower', 'StudentController@follower');
// 3.3 全部班级
Route::resource('/class', 'ClassController');
Route::post('/class/{id}/remark', 'ClassController@remark');
// 3.4 全部课程安排
Route::resource('/schedule', 'ScheduleController');
Route::get('/schedule/create/Irregular', 'ScheduleController@createIrregular');
Route::post('/schedule/create/step2', 'ScheduleController@createStep2');
Route::post('/schedule/create/step2Irregular', 'ScheduleController@createStep2Irregular');
Route::post('/schedule/create/step3', 'ScheduleController@createStep3');
Route::get('/schedule/attend/{schedule_id}', 'ScheduleController@attend');
Route::post('/schedule/attend/{schedule_id}/step2', 'ScheduleController@attendStep2');
Route::post('/schedule/attend/{schedule_id}/step3', 'ScheduleController@attendStep3');
Route::post('/schedule/attend/{schedule_id}/step4', 'ScheduleController@attendStep4');
// 3.5 全部上课记录
Route::resource('/attendedSchedule', 'AttendedScheduleController');
// 3.6 全部签约记录
Route::resource('/contract', 'ContractController');
Route::post('/contract/create/step2', 'ContractController@createStep2');
// 3.7 全部退费记录
Route::resource('/refund', 'RefundController');
Route::post('/refund/create/step2', 'RefundController@createStep2');
Route::post('/refund/create/step3', 'RefundController@createStep3');
Route::post('/refund/create/step4', 'RefundController@createStep4');

// 4.  招生中心
// 4.1 客户录入
// 4.2 本校客户
Route::get('/departmentCustomer', 'CustomerController@department');
// 4.3 我的客户
Route::get('/myCustomer', 'CustomerController@my');

// 5.  教务中心
// 5.0 班级成员管理
Route::post('/member/{class_id}', 'MemberController@add');
Route::delete('/member/{class_id}', 'MemberController@delete');
// 5.1 新建班级
// 5.2 安排课程
// 5.3 本校学生
Route::get('/departmentStudent', 'StudentController@department');
// 5.4 本校班级
Route::get('/departmentClass', 'ClassController@department');
// 5.5 本校课程安排
Route::get('/departmentSchedule', 'ScheduleController@department');
// 5.6 本校上课记录
Route::get('/departmentAttendedSchedule', 'AttendedScheduleController@department');
// 5.7 我的学生
Route::get('/myStudent', 'StudentController@my');
// 5.8 我的班级
Route::get('/myClass', 'ClassController@my');
// 5.9 我的课程安排
Route::get('/mySchedule', 'ScheduleController@my');
// 5.10 我的上课记录
Route::get('/myAttendedSchedule', 'AttendedScheduleController@my');

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
