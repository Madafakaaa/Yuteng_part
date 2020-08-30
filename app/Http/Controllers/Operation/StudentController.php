<?php
namespace App\Http\Controllers\Operation;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class StudentController extends Controller
{
    /**
     * 全部学生视图
     * URL: GET /operation/student
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function student(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                  ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                  ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                  ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->whereIn('student_department', $department_access)
                  ->where('student_contract_num', '>', 0)
                  ->where('student_status', 1);

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_student" => null,
                        "filter_consultant" => null,
                        "filter_class_adviser" => null,
                    );

        // 客户校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('student_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 客户年级
        if ($request->filled('filter_grade')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter_grade'));
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 客户名称
        if ($request->filled('filter_student')) {
            $rows = $rows->where('student_id', '=', $request->input('filter_student'));
            $filters['filter_student']=$request->input("filter_student");
        }
        // 课程顾问
        if ($request->filled('filter_consultant')) {
            $rows = $rows->where('student_consultant', '=', $request->input('filter_consultant'));
            $filters['filter_consultant']=$request->input("filter_consultant");
        }
        // 班主任
        if ($request->filled('filter_class_adviser')) {
            $rows = $rows->where('student_class_adviser', '=', $request->input('filter_class_adviser'));
            $filters['filter_class_adviser']=$request->input("filter_class_adviser");
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->select('student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'student.student_gender AS student_gender',
                              'student.student_guardian AS student_guardian',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_id AS consultant_id',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_id AS class_adviser_id',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 转为数组并获取学生课时信息
        $students = array();
        foreach($rows as $row){
            $temp = array();
            $temp['student_id']=$row->student_id;
            $temp['student_name']=$row->student_name;
            $temp['student_gender']=$row->student_gender;
            $temp['student_guardian']=$row->student_guardian;
            $temp['department_name']=$row->department_name;
            $temp['grade_name']=$row->grade_name;
            $temp['consultant_id']=$row->consultant_id;
            $temp['consultant_name']=$row->consultant_name;
            $temp['consultant_position_name']=$row->consultant_position_name;
            $temp['class_adviser_id']=$row->class_adviser_id;
            $temp['class_adviser_name']=$row->class_adviser_name;
            $temp['class_adviser_position_name']=$row->class_adviser_position_name;
            $temp['student_hour_num'] = 0;
            $student_hours = array();
            $hours = DB::table('hour')
                       ->join('course', 'hour.hour_course', '=', 'course.course_id')
                       ->where('hour_student', '=', $row->student_id)
                       ->get();
            foreach($hours as $hour){
                $hour_temp = array();
                $hour_temp['course_id']=$hour->course_id;
                $hour_temp['course_name']=$hour->course_name;
                $hour_temp['hour_remain']=$hour->hour_remain;
                $hour_temp['hour_used']=$hour->hour_used;
                $temp['student_hour_num']++;
                $student_hours[] = $hour_temp;
            }
            $temp['student_hours'] = $student_hours;
            $students[] = $temp;
        }

        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_users = DB::table('user')
                          ->join('department', 'user.user_department', '=', 'department.department_id')
                          ->join('position', 'user.user_position', '=', 'position.position_id')
                          ->where('user_status', 1)
                          ->whereIn('user_department', $department_access)
                          ->orderBy('user_department', 'asc')
                          ->orderBy('user_position', 'desc')
                          ->get();
        $filter_students = DB::table('student')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->where('student_status', 1)
                             ->where('student_contract_num', '>', 0)
                             ->whereIn('student_department', $department_access)
                             ->orderBy('student_department', 'asc')
                             ->orderBy('student_grade', 'asc')
                             ->get();
        // 返回列表视图
        return view('operation/student/student', ['students' => $students,
                                                  'currentPage' => $currentPage,
                                                  'totalPage' => $totalPage,
                                                  'startIndex' => $offset,
                                                  'request' => $request,
                                                  'filters' => $filters,
                                                  'totalNum' => $totalNum,
                                                  'filter_departments' => $filter_departments,
                                                  'filter_grades' => $filter_grades,
                                                  'filter_students' => $filter_students,
                                                  'filter_users' => $filter_users]);
    }

    public function studentDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取student_id
        $request_ids=$request->input('id');
        $student_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $student_ids[]=decode($request_id, 'student_id');
            }
        }else{
            $student_ids[]=decode($request_ids, 'student_id');
        }
        // 删除数据
        try{
            foreach ($student_ids as $student_id){
                DB::table('student')
                  ->where('student_id', $student_id)
                  ->update(['student_status' => 0]);
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/student")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '学生删除失败',
                         'message' => '学生删除失败，错误码:301']);
        }
        // 返回课程列表
        return redirect("/operation/student")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '学生删除成功',
                       'message' => '学生删除成功!']);
    }

    /**
     * 修改负责人视图
     * URL: GET /operation/follower/edit
     */
    public function followerEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = decode($request->input('id'), 'student_id');
        // 获取学生信息
        $student = DB::table('student')
                      ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                      ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                      ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                      ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                      ->where('student_id', $student_id)
                      ->select('student.student_id AS student_id',
                                'student.student_department AS student_department',
                                'student.student_name AS student_name',
                                'student.student_consultant AS student_consultant',
                                'student.student_class_adviser AS student_class_adviser',
                                'student.student_contract_num AS student_contract_num',
                                'consultant.user_name AS consultant_name',
                                'consultant_position.position_name AS consultant_position_name',
                                'class_adviser.user_name AS class_adviser_name',
                                'class_adviser_position.position_name AS class_adviser_position_name')
                      ->first();
        // 获取负责人信息
        $users = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_department', $student->student_department)
                  ->where('user_status', 1)
                  ->get();
        return view('operation/student/followerEdit', ['student' => $student, 'users' => $users]);
    }

    /**
     * 修改负责人提交
     * URL: GET /operation/follower/store
     */
    public function followerUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = decode($request->input('input1'), 'student_id');
        if($request->filled('input2')) {
            $student_consultant = $request->input('input2');
        }else{
            $student_consultant = "";
        }
        if($request->filled('input3')) {
            $student_class_adviser = $request->input('input3');
        }else{
            $student_class_adviser = "";
        }
        // 插入数据库
        DB::beginTransaction();
        try{
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_consultant' =>  $student_consultant,
                        'student_class_adviser' =>  $student_class_adviser]);
            // 插入学生动态
            //
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/student")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '负责人修改失败',
                           'message' => '负责人修改失败，错误码:302']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/operation/student")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '负责人修改成功',
                      'message' => '负责人修改成功']);
    }

    /**
     * 安排学生课程视图
     * URL: GET /operation/studentSchedule/create
     */
    public function studentScheduleCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = decode($request->input('id'), 'student_id');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取教师名单
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', $student->student_department)
                      ->where('user_status', 1)
                      ->where('department_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', $student->student_department)
                      ->where('user_status', 1)
                      ->where('department_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($teachers)
                      ->get();
        // 获取教室名单
        $classrooms = DB::table('classroom')
                        ->where('classroom_department', $student->student_department)
                        ->where('classroom_status', 1)
                        ->orderBy('classroom_id', 'asc')
                        ->get();
        // 获取科目
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        // 获取课程
        $courses = DB::table('hour')
                     ->join('course', 'hour.hour_course', '=', 'course.course_id')
                     ->where('hour_student', $student->student_id)
                     ->get();

        // 获取已有一对一班级
        $classes = DB::table('member')
                     ->join('class', 'member.member_class', '=', 'class.class_id')
                     ->join('subject', 'subject.subject_id', '=', 'class.class_subject')
                     ->join('user', 'user.user_id', '=', 'class.class_teacher')
                     ->where('member.member_student', '=', $student_id)
                     ->where('class.class_max_num', '=', 1)
                     ->where('class.class_status', '=', 1)
                     ->get();

        // 获取年级、科目、用户信息
        return view('operation/student/studentScheduleCreate', ['student' => $student,
                                                                'teachers' => $teachers,
                                                                'classrooms' => $classrooms,
                                                                'classes' => $classes,
                                                                'subjects' => $subjects,
                                                                'courses' => $courses]);
    }

    /**
     * 安排学生课程视图2
     * URL: GET /operation/studentSchedule/create2
     * @param  Request  $request
     * @param  $request->input('input_student'): 学生/班级
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程
     * @param  $request->input('input5'): 科目
     * @param  $request->input('input6'): 上课日期
     * @param  $request->input('input7'): 上课时间
     * @param  $request->input('input8'): 下课时间
     * @param  $request->input('input9'): 课程时长
     */
    public function studentScheduleCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取表单输入
        $schedule_student = $request->input('input_student');
        $schedule_date_start = $request->input('input_date_start');
        $schedule_date_end = $request->input('input_date_end');
        $schedule_days = $request->input('input_days');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_course = $request->input('input_course');
        $schedule_subject = $request->input('input_subject');
        // 判断Checkbox是否为空
        if(!isset($schedule_days)){
            return redirect("/operation/student/schedule/create?id=".encode($schedule_student, 'student_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '未选择上课规律',
                           'message' => '至少选择一天上课，错误码:304']);
        }

        // 日期数据处理
        $schedule_date_start = date('Y-m-d', strtotime( $schedule_date_start));
        $schedule_date_end = date('Y-m-d', strtotime( $schedule_date_end));
        $schedule_date_temp = $schedule_date_start;
        $schedule_dates_str = "";
        while($schedule_date_temp <= $schedule_date_end){
            foreach($schedule_days as $schedule_day){
                if(date("w", strtotime($schedule_date_temp))==$schedule_day){
                    if($schedule_dates_str==""){
                        $schedule_dates_str.=$schedule_date_temp;
                    }else{
                        $schedule_dates_str.=",".$schedule_date_temp;
                    }
                    break;
                }
            }
            $schedule_date_temp = date('Y-m-d', strtotime ("+1 day", strtotime($schedule_date_temp)));
        }

        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        // 判断日期数量是否大于50
        if($schedule_date_num>100){
            return redirect("/operation/student/schedule/create?id=".encode($schedule_student, 'student_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请选择重新上课日期',
                           'message' => '上课日期数量过多，超过最大上限100节课，错误码:305']);
        }
        // 验证日期格式
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/operation/student/schedule/create?id=".encode($schedule_student, 'student_id'))
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '请选择重新上课日期',
                               'message' => '上课日期格式有误，错误码:306']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $schedule_start = date('H:i', strtotime($schedule_start));
        $schedule_end = date('H:i', strtotime($schedule_end));
        if($schedule_start>=$schedule_end){
            return redirect("/operation/student/schedule/create?id=".encode($schedule_student, 'student_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前，错误码:307']);
        }

        // 判断是否已有一对一班级
        // $classes = DB::table('member')->join('class', 'member.member_class', '=', 'class.class_id')->where('class.class_subject', '=', $schedule_subject)->where('member.member_student', '=', $schedule_student)->where('class.class_max_num', '=', 1)->where('class.class_status', '=', 1)->get();

        // 判断是否有冲突课程
        // 获取教师信息
        $teacher = DB::table('user')
                     ->where('user_id', '=', $schedule_teacher)
                     ->first();
        // 获取学生名单
        $student = DB::table('student')
                     ->where('student_id', '=', $schedule_student)
                     ->first();

        // 教师冲突课程
        $teacher_schedules = array();
        // 学生冲突课程
        $student_schedules = array();

        foreach($schedule_dates as $schedule_date){
            // 查询教师冲突课程
            $teacher_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->where('schedule_teacher', $schedule_teacher)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_start', '>=', $schedule_start)
                                        ->where('schedule_start', '<', $schedule_end);
            $teacher_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->where('schedule_teacher', $schedule_teacher)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_end', '>', $schedule_start)
                                        ->where('schedule_end', '<=', $schedule_end)
                                        ->union($teacher_schedules_temp)
                                        ->get();
            foreach($teacher_schedules_temp as $teacher_schedule_temp){
                $teacher_schedules[] = array($teacher->user_name, $teacher_schedule_temp->class_name, $teacher_schedule_temp->schedule_date, $teacher_schedule_temp->schedule_start, $teacher_schedule_temp->schedule_end, $teacher_schedule_temp->schedule_attended);
            }
            // 查询学生冲突课程
            $student_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->join('member', 'member.member_class', '=', 'schedule.schedule_participant')
                                        ->where('member_student', $student->student_id)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_start', '>=', $schedule_start)
                                        ->where('schedule_start', '<', $schedule_end);
            $student_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->join('member', 'member.member_class', '=', 'schedule.schedule_participant')
                                        ->where('member_student', $student->student_id)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_end', '>', $schedule_start)
                                        ->where('schedule_end', '<=', $schedule_end)
                                        ->union($student_schedules_temp)
                                        ->get();
            foreach($student_schedules_temp as $student_schedule_temp){
                $student_schedules[] = array($student->student_name, $student_schedule_temp->class_name, $student_schedule_temp->schedule_date, $student_schedule_temp->schedule_start, $student_schedule_temp->schedule_end, $student_schedule_temp->schedule_attended);
            }
        }

        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);

        // 获取学生信息
        $schedule_student = DB::table('student')
                              ->where('student_id', $schedule_student)
                              ->join('department', 'student.student_department', '=', 'department.department_id')
                              ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                              ->first();
        // 获取教师信息
        $schedule_teacher = DB::table('user')
                              ->where('user_id', $schedule_teacher)
                              ->first();
        // 获取课程信息
        $schedule_course = DB::table('course')
                             ->where('course_id', $schedule_course)
                             ->first();
        // 获取科目名称
        $schedule_subject = DB::table('subject')
                              ->where('subject_id', $schedule_subject)
                              ->first();
        // 获取教室名称
        $schedule_classroom = DB::table('classroom')
                                ->where('classroom_id', $schedule_classroom)
                                ->first();

        // 生成班级名称
        $class_name = $schedule_student->student_name." 1v1 ".$schedule_subject->subject_name;

        return view('operation/student/studentScheduleCreate2', ['teacher_schedules' => $teacher_schedules,
                                                                 'student_schedules' => $student_schedules,
                                                                 'schedule_student' => $schedule_student,
                                                                 'schedule_teacher' => $schedule_teacher,
                                                                 'schedule_course' => $schedule_course,
                                                                 'schedule_subject' => $schedule_subject,
                                                                 'schedule_classroom' => $schedule_classroom,
                                                                 'schedule_dates' => $schedule_dates,
                                                                 'schedule_dates_str' => $schedule_dates_str,
                                                                 'schedule_start' => $schedule_start,
                                                                 'schedule_end' => $schedule_end,
                                                                 'schedule_time' => $schedule_time,
                                                                 'class_name' => $class_name]);
    }

    /**
     * 安排学生课程提交
     * URL: POST /operation/studentSchedule/store
     * @param  Request  $request
     * @param  $request->input('input1'): 学生/班级
     * @param  $request->input('input2'): 教师
     * @param  $request->input('input3'): 教室
     * @param  $request->input('input4'): 科目
     * @param  $request->input('input5'): 上课日期
     * @param  $request->input('input6'): 上课时间
     * @param  $request->input('input7'): 下课时间
     * @param  $request->input('input8'): 课程时长
     * @param  $request->input('input9'): 年级
     * @param  $request->input('input10'): 课程
     */
    public function studentScheduleStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_department = $request->input('input_department');
        $schedule_student = $request->input('input_student');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_subject = $request->input('input_subject');
        $schedule_dates_str = $request->input('input_dates_str');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_time = $request->input('input_time');
        $schedule_grade = $request->input('input_grade');
        $schedule_course = $request->input('input_course');
        $schedule_class_name = $request->input('input_class_name');
        // 获取学生信息
        $student = DB::table('student')
                              ->where('student_id', $schedule_student)
                              ->join('department', 'student.student_department', '=', 'department.department_id')
                              ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                              ->first();
        // 获取当前用户ID
        $schedule_createuser = Session::get('user_id');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        // 获取上课成员类型
        $schedule_participant_type = 1;
        // 生成班级ID
        if(DB::table('class')->where('class_department', '=', $schedule_department)->exists()){
            // 获取上一个班级班号
            $pre_class_id = DB::table('class')
                                ->where('class_department', '=', $schedule_department)
                                ->orderBy('class_id', 'desc')
                                ->limit(1)
                                ->first();
            if(intval(substr($pre_class_id->class_id , 7 , 10))==999){
                return redirect("/education/class/create")
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '客户添加失败',
                               'message' => '本校本月添加学生数量已超过超出上限，错误码:201']);
            }
            $new_class_num = intval(substr($pre_class_id->class_id , 7 , 10))+1;
            $class_id = "C".substr(date('Ym'),2).sprintf("%02d", $schedule_department).sprintf("%03d", $new_class_num);
        }else{
            // 生成新班级ID
            $class_id = "C".substr(date('Ym'),2).sprintf("%02d", $schedule_department).sprintf("%03d", 1);
        }

        // 插入数据库
        DB::beginTransaction();
        try{
            // 新建班级
            DB::table('class')->insert(
                ['class_id' => $class_id,
                 'class_name' => $schedule_class_name,
                 'class_department' => $schedule_department,
                 'class_grade' => $student->student_grade,
                 'class_subject' => $schedule_subject,
                 'class_teacher' => $schedule_teacher,
                 'class_max_num' => 1,
                 'class_current_num' => 1,
                 'class_schedule_num' => $schedule_date_num,
                 'class_remark' => $schedule_class_name,
                 'class_createuser' => $schedule_createuser]
            );
            // 添加班级成员
            DB::table('member')->insert(
                ['member_class' => $class_id,
                 'member_student' => $schedule_student,
                 'member_course' => $schedule_course,
                 'member_createuser' => $schedule_createuser]
            );
            //
            for($i=0; $i<$schedule_date_num; $i++){
                DB::table('schedule')->insert(
                    ['schedule_department' => $schedule_department,
                     'schedule_participant' => $class_id,
                     'schedule_participant_type' => $schedule_participant_type,
                     'schedule_teacher' => $schedule_teacher,
                     'schedule_course' => $schedule_course,
                     'schedule_subject' => $schedule_subject,
                     'schedule_grade' => $schedule_grade,
                     'schedule_classroom' => $schedule_classroom,
                     'schedule_date' => $schedule_dates[$i],
                     'schedule_start' => $schedule_start,
                     'schedule_end' => $schedule_end,
                     'schedule_time' => $schedule_time,
                     'schedule_createuser' => $schedule_createuser]
                );
            }
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/student/schedule/create?id=".encode($schedule_student, 'student_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '学生课程安排失败',
                           'message' => '学生课程安排失败，错误码:308']);
        }
        DB::commit();
        // 返回本校课程安排列表
        return redirect("/operation/student/schedule/success?id=".encode($schedule_student, 'student_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程安排成功',
                       'message' => '课程安排成功']);
    }

    public function studentScheduleCreateSuccess(Request $request){
        return view('operation/student/studentScheduleCreateSuccess', ['id' => $request->input('id')]);
    }


    /**
     * 插入班级视图
     * URL: GET /operation/student/joinClass
     */
    public function joinClass(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = decode($request->input('student_id'), 'student_id');
        $course_id = decode($request->input('course_id'), 'course_id');
        // 获取学生信息
        $student = DB::table('student')
                      ->join('grade', 'grade.grade_id', '=', 'student.student_grade')
                      ->where('student_id', $student_id)
                      ->first();

        // 获取剩余课时信息
        $hours = DB::table('hour')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->where('hour_student', $student_id)
                  ->get();

        // 获取学生已有班级
        $members = DB::table('member')
                      ->where('member_student', '=', $student_id)
                      ->get();

        $curr_classes=array();
        foreach($members as $member){
            $curr_classes[]=$member->member_class;
        }

        // 获取班级信息
        $classes = DB::table('class')
                      ->join('subject', 'subject.subject_id', '=', 'class.class_subject')
                      ->join('user', 'user.user_id', '=', 'class.class_teacher')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->where('class_department', '=', $student->student_department)
                      ->where('class_grade', '=', $student->student_grade)
                      ->whereColumn('class_current_num', '<', 'class_max_num')
                      ->whereNotIn('class_id', $curr_classes)
                      ->where('class_status', 1)
                      ->get();
        return view('operation/student/joinClass', ['student' => $student,
                                                     'course_id' => $course_id,
                                                     'hours' => $hours,
                                                     'classes' => $classes]);
    }


    /**
     * 插入班级提交
     * URL: GET /operation/hour/joinClass/store
     */
    public function joinClassStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('input1');
        $course_id = $request->input('input2');
        $class_id = $request->input('input3');
        // 插入数据库
        DB::beginTransaction();
        try{
            // 添加班级成员
            DB::table('member')->insert(
                ['member_class' => $class_id,
                 'member_student' => $student_id,
                 'member_course' => $course_id,
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
            return redirect("/operation/student")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '插入班级失败',
                           'message' => '插入班级失败，错误码:309']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/operation/student")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '插入班级成功',
                      'message' => '插入班级成功']);
    }
}
