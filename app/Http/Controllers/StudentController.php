<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class StudentController extends Controller
{

    /**
     * 显示单个学生详细信息
     * URL: GET /student/{id}
     * @param  int  $student_id
     */
    public function show(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = decode($request->input('id'), 'student_id');
        if($request->filled('selected')) {
            $selected = $request->input('selected');
        }else{
            $selected = "schedule";
        }
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                     ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                     ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                     ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                     ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                     ->select('student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'student.student_gender AS student_gender',
                              'student.student_guardian AS student_guardian',
                              'student.student_guardian_relationship AS student_guardian_relationship',
                              'student.student_phone AS student_phone',
                              'student.student_wechat AS student_wechat',
                              'student.student_source AS student_source',
                              'student.student_birthday AS student_birthday',
                              'student.student_remark AS student_remark',
                              'student.student_createtime AS student_createtime',
                              'student.student_follow_level AS student_follow_level',
                              'student.student_follow_num AS student_follow_num',
                              'student.student_contract_num AS student_contract_num',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'student.student_customer_status AS student_customer_status',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'school.school_name AS school_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->where('student_id', $student_id)
                     ->get();
        if($student->count()!==1){
            // 未获取到数据
            return redirect("/customer")->with(['notify' => true,
                                                'type' => 'danger',
                                                'title' => '客户显示失败',
                                                'message' => '客户显示失败，请联系系统管理员']);
        }
        $student = $student[0];

        // 获取学生所有班级
        $classes = DB::table('member')
                     ->join('student', 'member.member_student', '=', 'student.student_id')
                     ->join('class', 'member.member_class', '=', 'class.class_id')
                     ->join('user', 'class.class_teacher', '=', 'user.user_id')
                     ->join('department', 'class.class_department', '=', 'department.department_id')
                     ->join('grade', 'grade.grade_id', '=', 'class.class_grade')
                     ->join('subject', 'subject.subject_id', '=', 'class.class_subject')
                     ->where('member.member_student', '=', $student_id)
                     ->get();

        // 班级id和学生id数组
        $class_ids = array($student_id);
        foreach($classes as $class){
            $class_ids[] = $class->class_id;
        }
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
                       ->whereIn('schedule_participant', $class_ids)
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
                                ->whereIn('schedule_participant', $class_ids)
                                ->get();

        // 获取剩余课时
        $hours = DB::table('hour')
                   ->join('course', 'hour.hour_course', '=', 'course.course_id')
                   ->where('hour_student', '=', $student_id)
                   ->get();

        // 获取签约合同
        $contracts = DB::table('contract')
                   ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->where('contract_student', '=', $student_id)
                   ->get();

        // 获取学生动态
        $student_records = DB::table('student_record')
                             ->join('student', 'student_record.student_record_student', '=', 'student.student_id')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->join('user', 'student_record.student_record_createuser', '=', 'user.user_id')
                             ->where('student_record_student', $student_id)
                             ->orderBy('student_record_createtime', 'desc')
                             ->limit(50)
                             ->get();
        return view('student/show', ['selected' => $selected,
                                     'student' => $student,
                                     'classes' => $classes,
                                     'schedules' => $schedules,
                                     'attended_schedules' => $attended_schedules,
                                     'hours' => $hours,
                                     'contracts' => $contracts,
                                     'student_records' => $student_records,]);
    }

    /**
     * 修改学生信息
     * URL: GET /customer/{student_id}/edit
     * @param  int  $student_id        : 学生id
     */
    public function edit($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student = DB::table('student')->where('student_id', $student_id)->get();
        if($student->count()!==1){
            // 未获取到数据
            return redirect()->action('CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户显示失败',
                                     'message' => '客户显示失败，请联系系统管理员']);
        }
        $student = $student[0];
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取校区、来源、课程、用户、年级信息
        $departments = DB::table('department')
                         ->where('department_status', 1)
                         ->whereIn('department_id', $department_access)
                         ->orderBy('department_id', 'asc')
                         ->get();
        $sources = DB::table('source')->where('source_status', 1)->orderBy('source_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();
        return view('student/edit', ['student' => $student,
                                      'departments' => $departments,
                                      'sources' => $sources,
                                      'grades' => $grades,
                                      'schools' => $schools]);
    }

    /**
     * 修改学生提交
     * URL: PUT /student/{student_id}
     * @param  Request  $request
     * @param  $request->input('input1'): 学生姓名
     * @param  $request->input('input2'): 学生性别
     * @param  $request->input('input3'): 学生年级
     * @param  $request->input('input4'): 公立学校
     * @param  $request->input('input5'): 监护人姓名
     * @param  $request->input('input6'): 监护人关系
     * @param  $request->input('input7'): 联系电话
     * @param  $request->input('input8'): 微信
     * @param  $request->input('input9'): 来源类型
     * @param  $request->input('input10'): 学生生日
     * @param  int  $student_id        : 学生id
     */
    public function update(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_name = $request->input('input1');
        $student_department = $request->input('input2');
        $student_gender = $request->input('input3');
        $student_grade = $request->input('input4');
        if($request->filled('input5')) {
            $student_school = $request->input('input5');
        }else{
            $student_school = 0;
        }
        $student_guardian = $request->input('input6');
        $student_guardian_relationship = $request->input('input7');
        $student_phone = $request->input('input8');
        if($request->filled('input9')) {
            $student_wechat = $request->input('input9');
        }else{
            $student_wechat = '无';
        }
        $student_source = $request->input('input10');
        $student_birthday = $request->input('input11');
        //  获取学生信息
        $student = DB::table('student')
                     ->where('student_id', $student_id)
                     ->first();
        $student_name = $student->student_name;
        // 更新数据库
        DB::beginTransaction();
        try{
            // 更新学生信息
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_name' => $student_name,
                        'student_department' => $student_department,
                        'student_gender' => $student_gender,
                        'student_grade' => $student_grade,
                        'student_school' => $student_school,
                        'student_guardian' => $student_guardian,
                        'student_guardian_relationship' => $student_guardian_relationship,
                        'student_phone' => $student_phone,
                        'student_wechat' => $student_wechat,
                        'student_source' => $student_source,
                        'student_birthday' => $student_birthday]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '修改信息',
                 'student_record_content' => '修改学生信息，修改人：'.Session::get('user_name').'。',
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            return redirect("/student/{$student_id}/edit")->with(['notify' => true,
                                                                    'type' => 'danger',
                                                                    'title' => '学生修改失败',
                                                                    'message' => '学生修改失败，请重新输入信息']);
        }
        DB::commit();
        return redirect("/student/{$student_id}")->with(['notify' => true,
                                                        'type' => 'success',
                                                        'title' => '学生修改成功',
                                                        'message' => '学生修改成功，学生名称: '.$student_name]);
    }

    /**
     * 修改学生备注
     * URL: POST /student/{id}/remark
     * @param  Request  $request
     * @param  $request->input('input1'): 学生备注
     * @param  int  $student_id        : 学生id
     */
    public function remark(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_remark = $request->input('input1');
        // 更新数据
        DB::beginTransaction();
        try{
            // 更新学生备注
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_remark' =>  $student_remark]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '修改备注',
                 'student_record_content' => '修改学生备注：'.$student_remark,
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/student/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '修改学生备注失败',
                                                               'message' => '修改学生备注失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/student/{$student_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '修改学生备注成功',
                                                           'message' => '修改学生备注成功']);
    }

    /**
     * 学生跟进动态提交
     * URL: POST /student/{id}/record
     * @param  Request  $request
     * @param  $request->input('input1'): 跟进内容
     * @param  $request->input('input2'): 跟进方式
     * @param  $request->input('input3'): 跟进时间
     * @param  int  $student_id        : 学生id
     */
    public function record(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_record_content = "跟进方式：".$request->input('input2')."，跟进日期：".$request->input('input3')."。<br>".$request->input('input1');
        // 获取数据信息
        $student_record_student = $student_id;
        $student_record_type = "跟进记录";
        $student_record_createuser = Session::get('user_id');
        // 更新数据
        DB::beginTransaction();
        try{
            // 增加跟进次数
            DB::table('student')
              ->where('student_id', $student_id)
              ->increment('student_follow_num');
            // 更新跟进时间
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_last_follow_date' =>  $request->input('input3')]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_record_student,
                 'student_record_type' => '跟进记录',
                 'student_record_content' => $student_record_content,
                 'student_record_createuser' => $student_record_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/student/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '添加跟进动态失败',
                                                               'message' => '添加跟进动态失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/student/{$student_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '添加跟进动态成功',
                                                           'message' => '添加跟进动态成功']);
    }

    /**
     * 修改学生负责人
     * URL: POST /student/{id}/follower
     * @param  Request  $request
     * @param  $request->input('input1'): 负责人
     * @param  int  $student_id        : 学生id
     */
    public function follower(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        if($request->filled('input1')) {
            $student_new_follower = $request->input('input1');
        }else{
            $student_new_follower = "";
        }
        // 获取客户信息
        $student = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取学生姓名
        $student_name = $student->student_name;
        // 获取原负责人姓名
        $student_old_follower_name = $student->user_name;
        $student_old_follower = $student->user_id;
        if($student_old_follower_name==""){
            $student_old_follower_name="无(公共)";
        }
        // 原负责人和新负责人相同，返回上一级
        if($student_old_follower==$student_new_follower){
            return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '修改学生负责人失败',
                                                               'message' => '原负责人与新负责人相同，请重新选择']);
        }
        // 获取新跟进人姓名
        if($student_new_follower==""){
            $student_new_follower_name="无(公共)";
        }else{
            $student_new_follower_name = DB::table('user')
                                           ->where('user_id', $student_new_follower)
                                           ->first()
                                           ->user_name;
        }
        // 更新数据
        DB::beginTransaction();
        try{
            // 更新学生负责人
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_follower' =>  $student_new_follower,
                        'student_customer_status' =>  2]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '更换负责人',
                 'student_record_content' => '更换学生负责人。原负责人：'.$student_old_follower_name."，新负责人：".$student_new_follower_name."。",
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/student/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '修改学生跟负责失败',
                                                               'message' => '修改学生跟负责失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/student/{$student_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '修改学生负责人成功',
                                                           'message' => '学生名称: '.$student_name]);
    }

    /**
     * 清理课时
     * URL: GET /student/cleanHour/{hour_id}
     * @param  int  $hour_id        : Hour id
     */
    public function cleanHour($hour_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取课时信息
        $hour = DB::table('hour')
                     ->join('student', 'student.student_id', '=', 'hour.hour_student')
                     ->join('course', 'course.course_id', '=', 'hour.hour_course')
                     ->where('hour_id', $hour_id)
                     ->first();
        return view('student/cleanHour', ['hour' => $hour]);
    }

    /**
     * 清理课时提交
     * URL: POST /student/cleanHour/{hour_id}
     * @param  Request  $request
     * @param  int  $hour_id        : Hour id
     */
    public function cleanHourStore(Request $request, $hour_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $hour_cleaned_record_amount = $request->input('input_cleaned_record_amount');
        if($request->filled('input_remark')) {
            $hour_cleaned_record_remark = $request->input('input_remark');
        }else{
            $hour_cleaned_record_remark = "";
        }
        // 获取课时信息
        $hour = DB::table('hour')
                     ->join('student', 'student.student_id', '=', 'hour.hour_student')
                     ->join('course', 'course.course_id', '=', 'hour.hour_course')
                     ->where('hour_id', $hour_id)
                     ->first();
        // 更新数据
        DB::beginTransaction();
        try{
            if($hour->hour_remain>=$hour_cleaned_record_amount){
                // 更新剩余课时
                DB::table('hour')
                  ->where('hour_id', $hour_id)
                  ->decrement('hour_remain', $hour_cleaned_record_amount);
                DB::table('hour')
                  ->where('hour_id', $hour_id)
                  ->increment('hour_cleaned', $hour_cleaned_record_amount);
            }else{
                // 更新剩余课时
                DB::table('hour')
                  ->where('hour_id', $hour_id)
                  ->update(['hour_remain' =>  0]);
                $free_clean_amount = $hour_cleaned_record_amount-$hour->hour_remain;
                DB::table('hour')
                  ->where('hour_id', $hour_id)
                  ->decrement('hour_remain_free', $free_clean_amount);
                DB::table('hour')
                  ->where('hour_id', $hour_id)
                  ->increment('hour_cleaned', $hour_cleaned_record_amount);
            }
            // 添加课时清理记录
            DB::table('hour_cleaned_record')->insert(
                ['hour_cleaned_record_hour' => $hour->hour_student,
                 'hour_cleaned_record_amount' => $hour_cleaned_record_amount,
                 'hour_cleaned_record_remark' => $hour_cleaned_record_remark,
                 'hour_cleaned_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/student/{$hour->hour_student}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '学生课时清理失败',
                                                               'message' => '学生课时清理失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/student/{$hour->hour_student}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '学生课时清理成功',
                                                           'message' => '学生课时清理成功']);
    }
}
