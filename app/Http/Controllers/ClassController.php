<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ClassController extends Controller
{

    /**
     * 显示单个班级详细信息
     * URL: GET /class/{id}
     * @param  int  $class_id
     */
    public function class(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/class", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        $class_id = decode($request->input('id'), 'class_id');
        // 获取数据信息
        $class = DB::table('class')
                   ->join('department', 'class.class_department', '=', 'department.department_id')
                   ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                   ->join('subject', 'class.class_subject', '=', 'subject.subject_id')
                   ->join('user', 'class.class_teacher', '=', 'user.user_id')
                   ->where('class_id', $class_id)
                   ->get();
        if($class->count()!==1){
            // 未获取到数据
            return redirect()->action('ClassController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '班级显示失败',
                                     'message' => '班级显示失败，请联系系统管理员']);
        }
        $class = $class[0];

        // 获取成员数据
        $members = DB::table('member')
                  ->join('class', 'member.member_class', '=', 'class.class_id')
                  ->join('student', 'member.member_student', '=', 'student.student_id')
                  ->where('member.member_class', $class_id)
                  ->get();

        // 生成已有学生ID数组
        $member_student_ids = array();
        foreach($members as $member){
            $member_student_ids[] = $member->member_student;
        }

        // 获取学生信息
        $same_grade_students = DB::table('student')
                                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                                  ->where('student_grade', $class->class_grade)
                                  ->where('student_department', $class->class_department)
                                  ->where('student_contract_num', '>', 0)
                                  ->where('student_status', 1)
                                  ->whereNotIn('student_id', $member_student_ids)
                                  ->orderBy('student_id', 'asc')
                                  ->get();
        $diff_grade_students = DB::table('student')
                                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                                  ->where('student_grade', '!=',$class->class_grade)
                                  ->where('student_department', $class->class_department)
                                  ->where('student_contract_num', '>', 0)
                                  ->where('student_status', 1)
                                  ->whereNotIn('student_id', $member_student_ids)
                                  ->orderBy('student_grade', 'asc')
                                  ->orderBy('student_id', 'asc')
                                  ->get();

        // 获取所有课程安排
        $schedules = DB::table('schedule')
                          ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                          ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                          ->join('position', 'user.user_position', '=', 'position.position_id')
                          ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                          ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                          ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                          ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                          ->where('schedule_attended', '=', 0)
                          ->where('schedule_participant', '=', $class_id)
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
                                      ->where('schedule_attended', '=', 1)
                                      ->where('schedule_participant', '=', $class_id)
                                      ->get();

        // 获取年级、科目、用户信息
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        $same_department_users = DB::table('user')
                                   ->join('position', 'user.user_position', '=', 'position.position_id')
                                   ->join('department', 'user.user_department', '=', 'department.department_id')
                                   ->where('user_department', '=', $class->class_department)
                                   ->where('user_status', 1)
                                   ->orderBy('position_level', 'desc')
                                   ->get();
        $diff_department_users = DB::table('user')
                                   ->join('position', 'user.user_position', '=', 'position.position_id')
                                   ->join('department', 'user.user_department', '=', 'department.department_id')
                                   ->where('user_cross_teaching', 1)
                                   ->where('user_department', '<>', $class->class_department)
                                   ->where('user_status', 1)
                                   ->orderBy('user_department', 'desc')
                                   ->orderBy('position_level', 'desc')
                                   ->get();
        return view('class/class', ['class' => $class,
                                   'same_grade_students' => $same_grade_students,
                                   'diff_grade_students' => $diff_grade_students,
                                   'members' => $members,
                                   'schedules' =>$schedules,
                                   'attended_schedules' =>$attended_schedules,
                                   'grades' => $grades,
                                   'subjects' => $subjects,
                                   'same_department_users' => $same_department_users,
                                   'diff_department_users' => $diff_department_users]);
    }

    /**
     * 修改班级提交
     * URL: PUT /class/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 年级
     * @param  $request->input('input3'): 科目
     * @param  $request->input('input4'): 负责教师
     * @param  $request->input('input5'): 最大人数
     * @param  $request->input('input6'): 备注
     * @param  int  $class_id
     */
    public function update(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $class_id = decode($request->input('id'), 'class_id');
         // 获取表单输入
        $class_name = $request->input('input1');
        $class_grade = $request->input('input2');
        $class_subject = $request->input('input3');
        $class_teacher = $request->input('input4');
        $class_max_num = $request->input('input5');
        if($request->filled('input6')) {
            $class_remark = $request->input('input6');
        }else{
            $class_remark = '无';
        }
        // 更新数据库
        DB::beginTransaction();
        try{
            // 更新班级信息
            DB::table('class')
              ->where('class_id', $class_id)
              ->update(['class_name' => $class_name,
                        'class_grade' => $class_grade,
                        'class_subject' => $class_subject,
                        'class_teacher' => $class_teacher,
                        'class_max_num' => $class_max_num,
                        'class_remark' => $class_remark]);
            // 更新课程安排教师
            DB::table('schedule')
              ->where('schedule_participant', $class_id)
              ->where('schedule_attended', 0)
              ->update(['schedule_teacher' => $class_teacher]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/class/edit?id=".encode($class_id, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '班级修改失败',
                           'message' => '班级修改失败，请重新输入信息']);
        }
        DB::commit();
        return redirect("/class?id=".encode($class_id, 'class_id'))
               ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '班级修改成功',
                         'message' => '班级修改成功，班级名称: '.$class_name]);
    }

    /**
     * 插入班级提交
     * URL: GET /operation/member/store
     */
    public function memberAdd(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('input_student_id');
        $class_id = $request->input('input_class_id');
        // 插入数据库
        DB::beginTransaction();
        try{
            // 添加班级成员
            DB::table('member')->insert(
                ['member_class' => $class_id,
                 'member_student' => $student_id,
                 'member_createuser' => Session::get('user_id')]
            );
            // 更新班级人数
            DB::table('class')
              ->where('class_id', $class_id)
              ->increment('class_current_num');
            // 插入学生动态
            //
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/class?id=".encode($class_id, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '添加学生失败',
                           'message' => '添加学生失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/class?id=".encode($class_id, 'class_id'))
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '添加学生成功',
                      'message' => '添加学生成功']);
    }

    /**
     * 班级成员删除
     * URL: DELETE/class/{class_id}
     */
    public function memberDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $class_id = decode($request->input('input_class_id'), 'class_id');
        $student_id = decode($request->input('input_student_id'), 'student_id');
        // 插入数据库
        DB::beginTransaction();
        try{
            // 删除班级成员
            DB::table('member')
              ->where('member_class', $class_id)
              ->where('member_student', $student_id)
              ->delete();
            // 更新班级人数
            DB::table('class')
              ->where('class_id', $class_id)
              ->decrement('class_current_num');
            // 插入学生动态
            //
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/class?id=".encode($class_id, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '删除成员失败',
                           'message' => '删除成员失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/class?id=".encode($class_id, 'class_id'))
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '删除成员成功',
                      'message' => '删除成员成功']);
    }
}
