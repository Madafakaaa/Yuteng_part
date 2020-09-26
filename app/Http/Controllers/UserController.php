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
    public function user(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/user", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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

        // 获取所有用户动态
        $user_records = DB::table('user_record')
                          ->join('user', 'user_record.user_record_createuser', '=', 'user.user_id')
                          ->where('user_record_user', $user_id)
                          ->orderBy('user_record_id', 'desc')
                          ->get();

        // 获取所有用户档案
        $archives = DB::table('archive')
                      ->where('archive_user', $user_id)
                      ->orderBy('archive_id', 'desc')
                      ->get();

        // 修改所需信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_id', 'asc')->get();
        $positions = DB::table('position')
                       ->join('section', 'position.position_section', '=', 'section.section_id')
                       ->where('position_status', 1)
                       ->where('section_status', 1)
                       ->orderBy('position_id', 'asc')
                       ->get();
        return view('user/user', ['user' => $user,
                                  'user_records' => $user_records,
                                  'archives' => $archives,
                                  'schedules' => $schedules,
                                  'attended_schedules' => $attended_schedules,
                                  'students' => $students,
                                  'classes' => $classes,
                                  'contracts' => $contracts,
                                  'dashboard' => $dashboard,
                                  'departments' => $departments,
                                  'positions' => $positions]);
    }

    public function record(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_record_user = $request->input('user_id');
        $user_record_type = "用户动态";
        $user_record_content = $request->input('user_record_content');
        // 更新数据库
        try{
            // 添加用户动态
            DB::table('user_record')->insert(
                ['user_record_user' => $user_record_user,
                 'user_record_type' => $user_record_type,
                 'user_record_content' => $user_record_content,
                 'user_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return back()->with(['notify' => true,
                                'type' => 'danger',
                                'title' => '动态添加失败',
                                'message' => '动态添加失败，请重新输入信息']);
        }
        return redirect("/user?id=".encode($request->input('user_id'), 'user_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '动态添加成功',
                       'message' => '动态添加成功']);
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
    public function update(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $user_id = decode($request->input('id'), 'user_id');
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
            return redirect("/user?id=".$request->input('id'))->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '用户修改失败',
                                                            'message' => '用户修改失败，请重新输入信息']);
        }
        return redirect("/user?id=".$request->input('id'))->with(['notify' => true,
                                                   'type' => 'success',
                                                   'title' => '用户修改成功',
                                                   'message' => '用户修改成功']);
    }
    public function archive(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取上传文件
        $file = $request->file('file');
        // 获取文件大小(MB)
        $archive_file_size = $file->getClientSize()/1024/1024+0.01;
        // 判断文件是否大于10MB
        if($archive_file_size>10){
            return redirect("/humanResource/archive/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '简历文件上传失败',
                           'message' => '文件大于10MB，错误码:401']);
        }

        // 获取文件名称
        $archive_file_name = $file->getClientOriginalName();
        // 获取文件扩展名
        $archive_ext = $file->getClientOriginalExtension();
        // 生成随机文件名
        $archive_path = "A".date('ymdHis').rand(1000000000,9999999999).".".$archive_ext;

        // 获取表单输入
        $archive_user = $request->input('archive_user');
        $archive_name = $request->input('archive_name');

        DB::beginTransaction();
        // 插入数据库
        try{
            DB::table('archive')
              ->insert(['archive_user' => $archive_user,
                        'archive_name' => $archive_name,
                        'archive_file_name' => $archive_file_name,
                        'archive_path' => $archive_path,
                        'archive_createuser' => Session::get('user_id')]);
            // 添加用户动态
            DB::table('user_record')->insert(
                ['user_record_user' => $archive_user,
                 'user_record_type' => "上传用户档案",
                 'user_record_content' => "上传用户档案，档案名：".$archive_name."。",
                 'user_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return back()->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '用户档案添加失败',
                             'message' => '用户档案添加失败，错误码:113']);
        }
        DB::commit();
        // 上传文件
        $file->move("files/archive", $archive_path);
        // 返回用户列表
        return back()->with(['notify' => true,
                             'type' => 'success',
                             'title' => '用户档案添加成功',
                             'message' => '用户档案添加成功']);
    }

}
