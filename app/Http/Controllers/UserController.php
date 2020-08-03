<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class UserController extends Controller
{
    /**
     * 显示单个用户详细信息
     * URL: GET /user/{id}
     * @param  int  $user_id
     */
    public function show(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_id = decode($request->input('id'), 'user_id');
        // 获取用户数据信息
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', $user_id)
                  ->first();
        // 获取所有课程安排
        $schedules = DB::table('schedule')
                       ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                       ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                       ->join('position', 'user.user_position', '=', 'position.position_id')
                       ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                       ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                       ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                       ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                       ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                       ->where('schedule_attended', '=', 0)
                       ->where('schedule_teacher', $user_id)
                       ->get();

        // 获取所有上课记录
        $attended_schedules = DB::table('schedule')
                                ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                                ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                                ->join('position', 'user.user_position', '=', 'position.position_id')
                                ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                                ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                                ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                                ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                                ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                ->where('schedule_attended', '=', 1)
                                ->where('schedule_teacher', $user_id)
                                ->get();

        // 获取教师所有负责学生
        $students = DB::table('student')
                      ->join('department', 'student.student_department', '=', 'department.department_id')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                      ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                      ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                      ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                      ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                      ->where('student.student_consultant', '=', $user_id)
                      ->orWhere('student.student_class_adviser', '=', $user_id)
                      ->select('student.student_id AS student_id',
                               'student.student_name AS student_name',
                               'student.student_gender AS student_gender',
                               'student.student_guardian AS student_guardian',
                               'department.department_name AS department_name',
                               'grade.grade_name AS grade_name',
                               'consultant.user_name AS consultant_name',
                               'consultant_position.position_name AS consultant_position_name',
                               'class_adviser.user_name AS class_adviser_name',
                               'class_adviser_position.position_name AS class_adviser_position_name')
                      ->get();

        // 获取教师所有负责班级
        $classes = DB::table('class')
                     ->join('user', 'class.class_teacher', '=', 'user.user_id')
                     ->join('subject', 'subject.subject_id', '=', 'class.class_subject')
                     ->where('class.class_teacher', '=', $user_id)
                     ->get();

        // 获取签约合同
        $contracts = DB::table('contract')
                       ->join('department', 'contract.contract_department', '=', 'department.department_id')
                       ->join('student', 'contract.contract_student', '=', 'student.student_id')
                       ->where('contract_createuser', '=', $user_id)
                       ->get();

        // 获取用户统计数据
        $dashboard=array();
        $dashboard['schedule_num']=DB::table('schedule')
                                     ->where('schedule_teacher', $user_id)
                                     ->where('schedule_date', 'like', date('Y-m').'%')
                                     ->count();

        $dashboard['attended_schedule_num']=DB::table('schedule')
                                              ->where('schedule_attended', '=', 1)
                                              ->where('schedule_teacher', $user_id)
                                              ->where('schedule_date', 'like', date('Y-m').'%')
                                              ->count();

        $dashboard['contract_num']=DB::table('contract')
                                     ->where('contract_createuser', '=', $user_id)
                                     ->where('contract_date', 'like', date('Y-m').'%')
                                     ->count();

        return view('user/show', ['user' => $user,
                                  'schedules' => $schedules,
                                  'attended_schedules' => $attended_schedules,
                                  'students' => $students,
                                  'classes' => $classes,
                                  'contracts' => $contracts,
                                  'dashboard' => $dashboard]);
    }

    /**
     * 修改单个用户
     * URL: GET /user/{id}/edit
     * @param  int  $user_id
     */
    public function edit($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $user = DB::table('user')->where('user_id', $user_id)->get();
        if($user->count()!==1){
            // 未获取到数据
            return redirect()->action('UserController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户显示失败',
                                     'message' => '用户显示失败，请联系系统管理员']);
        }
        $user = $user[0];
        // 获取校区、岗位信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_id', 'asc')->get();
        $positions = DB::table('position')
                       ->join('section', 'position.position_section', '=', 'section.section_id')
                       ->where('position_status', 1)
                       ->where('section_status', 1)
                       ->orderBy('position_id', 'asc')
                       ->get();
        return view('user/edit', ['user' => $user, 'departments' => $departments, 'positions' => $positions]);
    }

    /**
     * 修改新用户提交数据库
     * URL: PUT /user/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 用户姓名
     * @param  $request->input('input2'): 用户性别
     * @param  $request->input('input3'): 用户校区
     * @param  $request->input('input4'): 用户岗位
     * @param  $request->input('input5'): 入职日期
     * @param  $request->input('input6'): 是否可以跨校区上课
     * @param  $request->input('input7'): 用户手机
     * @param  $request->input('input8'): 用户微信
     * @param  int  $user_id: 用户id
     */
    public function update(Request $request, $user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $user_name = $request->input('input1');
        $user_gender = $request->input('input2');
        $user_department = $request->input('input3');
        $user_position = $request->input('input4');
        $user_entry_date = $request->input('input5');
        $user_cross_teaching = $request->input('input6');
        if($request->filled('input7')) {
            $user_phone = $request->input('input7');
        }else{
            $user_phone = "无";
        }
        if($request->filled('input8')) {
            $user_wechat = $request->input('input8');
        }else{
            $user_wechat = "无";
        }
        // 更新数据库
        try{
            DB::table('user')
              ->where('user_id', $user_id)
              ->update(['user_name' => $user_name,
                        'user_gender' => $user_gender,
                        'user_department' => $user_department,
                        'user_position' => $user_position,
                        'user_entry_date' => $user_entry_date,
                        'user_cross_teaching' => $user_cross_teaching,
                        'user_phone' => $user_phone,
                        'user_wechat' => $user_wechat]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/user/{$user_id}/edit")->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '用户修改失败',
                                                            'message' => '用户修改失败，请重新输入信息']);
        }
        return redirect("/user/{$user_id}")->with(['notify' => true,
                                                   'type' => 'success',
                                                   'title' => '用户修改成功',
                                                   'message' => '用户修改成功，用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }

}
