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
Route::resource('/department', 'DepartmentController');
// 2.1.2 学校设置
Route::resource('/school', 'SchoolController');
// 2.1.3 教室设置
Route::resource('/classroom', 'ClassroomController');
// 2.2.1 用户管理
Route::resource('/user', 'UserController');
// 2.2.2 岗位管理
Route::resource('/position', 'PositionController');
// 2.2.3 部门管理
Route::resource('/section', 'SectionController');
// 2.2.4 档案管理
Route::resource('/archive', 'ArchiveController');
// 2.3.1 课程管理
Route::resource('/course', 'CourseController');

// 3.    招生管理
// 3.1   全部客户
Route::resource('/customer', 'CustomerController');
// 客户修改
Route::post('/customer/{id}/record', 'CustomerController@record');
Route::post('/customer/{id}/remark', 'CustomerController@remark');
Route::post('/customer/{id}/follower', 'CustomerController@follower');
Route::post('/customer/{id}/followLevel', 'CustomerController@followLevel');
// 3.2   本校区客户
Route::resource('/departmentCustomer', 'DepartmentCustomerController');
// 3.3   我的客户
Route::resource('/myCustomer', 'MyCustomerController');
// 3.4   全部签约
Route::resource('/contract', 'ContractController');
Route::post('/contract/create/step2', 'ContractController@createStep2');
// 3.5   全部签约
Route::resource('/departmentContract', 'DepartmentContractController');
// 3.6   我的签约
Route::resource('/myContract', 'MyContractController');

// 4.    教务中心
// 4.1   全部学生
Route::resource('/student', 'StudentController');
Route::post('/student/{id}/remark', 'StudentController@remark');
Route::post('/student/{id}/follower', 'StudentController@follower');
// 4.2   本校学生
Route::resource('/departmentStudent', 'DepartmentStudentController');
// 4.3   我的学生
Route::resource('/myStudent', 'MyStudentController');
// 4.2   全部班级
Route::resource('/class', 'ClassController');
Route::post('/class/{id}/remark', 'ClassController@remark');
// 4.2   本校班级
Route::resource('/departmentClass', 'DepartmentClassController');
// 4.2   我的班级
Route::resource('/myClass', 'MyClassController');
// 4.2   班级成员管理
Route::post('/member/{class_id}', 'MemberController@add');
Route::delete('/member/{class_id}', 'MemberController@delete');
// 4.3   全部课程安排
Route::resource('/schedule', 'ScheduleController');
Route::get('/schedule/create/Irregular', 'ScheduleController@createIrregular');
Route::post('/schedule/create/step2', 'ScheduleController@createStep2');
Route::post('/schedule/create/step2Irregular', 'ScheduleController@createStep2Irregular');
Route::post('/schedule/create/step3', 'ScheduleController@createStep3');
Route::get('/schedule/attend/{schedule_id}', 'ScheduleController@attend');
Route::post('/schedule/attend/{schedule_id}/step2', 'ScheduleController@attendStep2');
Route::post('/schedule/attend/{schedule_id}/step3', 'ScheduleController@attendStep3');
Route::post('/schedule/attend/{schedule_id}/step4', 'ScheduleController@attendStep4');
// 4.3   本校课程安排
Route::resource('/departmentSchedule', 'DepartmentScheduleController');
// 4.3   我的课程安排
Route::resource('/mySchedule', 'MyScheduleController');
// 4.3   全部上课记录
Route::resource('/attendedSchedule', 'AttendedScheduleController');
// 4.3   本校上课记录
Route::resource('/departmentAttendedSchedule', 'DepartmentAttendedScheduleController');
// 4.3   我的上课记录
Route::resource('/myAttendedSchedule', 'MyAttendedScheduleController');

// 5.    财务中心
// 5.2   退费申请
Route::resource('/refund', 'RefundController');
Route::post('/refund/create/step2', 'RefundController@createStep2');
Route::post('/refund/create/step3', 'RefundController@createStep3');
Route::post('/refund/create/step4', 'RefundController@createStep4');

// 6     教案查询
Route::resource('/document', 'DocumentController');
// 7     个人信息
Route::resource('/profile', 'ProfileController');

// 课程表 Calendar
Route::get('/calendar', 'CalendarController@calendar');


// test page
Route::get('/buttons', function () { return view('button_template'); });

