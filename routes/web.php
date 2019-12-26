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

// 0.    登陆控制器
Route::get('/', 'LoginController@index');
Route::post('/login', 'LoginController@login');
Route::get('/exit', 'LoginController@exit');
// 1.    主页
Route::get('/home', 'HomeController@home');
// 2.    学校管理
// 2.1.1 校区设置
Route::resource('/department', 'School\DepartmentController');
// 2.1.2 学校设置
Route::resource('/school', 'School\SchoolController');
// 2.1.3 教室设置
Route::resource('/classroom', 'School\ClassroomController');
// 2.2.1 用户管理
Route::resource('/user', 'School\UserController');
// 2.2.2 岗位管理
Route::resource('/position', 'School\PositionController');
// 2.2.3 等级管理
Route::resource('/level', 'School\LevelController');
// 2.2.4 档案管理
Route::resource('/archive', 'School\ArchiveController');
// 2.3.1 课程管理
Route::resource('/course', 'School\CourseController');
// 2.3.2 年级管理
Route::resource('/grade', 'School\GradeController');
// 2.3.3 课程管理
Route::resource('/subject', 'School\SubjectController');
// 2.3.4 上课时间
// Route::resource('/timeset', 'School\TimesetController');

// 3.    招生管理
// 3.1.1 客户管理
Route::resource('/customer', 'Market\CustomerController');
Route::post('/customer/{id}/record', 'Market\CustomerController@record');
// 3.1.2 来源设置
Route::resource('/source', 'Market\SourceController');

// 4.    教务中心
// 4.1   学生管理
Route::resource('/student', 'Teaching\StudentController');
// 4.2   班级管理
Route::resource('/class', 'Teaching\ClassController');
// 4.2   班级成员管理
Route::post('/member/{class_id}', 'Teaching\MemberController@add');
Route::delete('/member/{class_id}', 'Teaching\MemberController@delete');
// 4.3   课程安排
Route::resource('/schedule', 'Teaching\ScheduleController');
Route::post('/schedule/create/step2', 'Teaching\ScheduleController@createStep2');
Route::post('/schedule/create/step3', 'Teaching\ScheduleController@createStep3');
// test calendar
Route::get('/calendar', 'Teaching\ScheduleController@calendar');

// 5.    财务中心
// 5.1   学生购课
Route::resource('/payment', 'Finance\PaymentController');
Route::post('/payment/create_second', 'Finance\PaymentController@create_second');
