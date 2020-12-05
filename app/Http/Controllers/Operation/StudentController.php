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
        // 检测用户权限
        if(!in_array("/operation/student", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
        // 数据范围权限
        if (Session::get('user_access_self')==1) {
            $rows = $rows->where('student_class_adviser', '=', Session::get('user_id'));
        }

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
                     ->orderBy('student_id', 'desc')
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
        // 检测用户权限
        if(!in_array("/operation/student/delete", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
     * 安排学生课程视图
     * URL: GET /operation/studentSchedule/create
     */
    public function studentScheduleCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/operation/student/schedule/create", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
                                        ->where('schedule_start', '<', $schedule_start)
                                        ->where('schedule_end', '>', $schedule_end)
                                        ->get();
            foreach($teacher_schedules_temp as $teacher_schedule_temp){
                $teacher_schedules[] = array($teacher->user_name, $teacher_schedule_temp->class_name, $teacher_schedule_temp->schedule_date, $teacher_schedule_temp->schedule_start, $teacher_schedule_temp->schedule_end, $teacher_schedule_temp->schedule_attended);
            }
            $teacher_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->where('schedule_teacher', $schedule_teacher)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_start', '>', $schedule_start)
                                        ->where('schedule_end', '<', $schedule_end)
                                        ->get();
            foreach($teacher_schedules_temp as $teacher_schedule_temp){
                $teacher_schedules[] = array($teacher->user_name, $teacher_schedule_temp->class_name, $teacher_schedule_temp->schedule_date, $teacher_schedule_temp->schedule_start, $teacher_schedule_temp->schedule_end, $teacher_schedule_temp->schedule_attended);
            }
            $teacher_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->where('schedule_teacher', $schedule_teacher)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_start', '>=', $schedule_start)
                                        ->where('schedule_start', '<', $schedule_end)
                                        ->where('schedule_end', '>', $schedule_end)
                                        ->get();
            foreach($teacher_schedules_temp as $teacher_schedule_temp){
                $teacher_schedules[] = array($teacher->user_name, $teacher_schedule_temp->class_name, $teacher_schedule_temp->schedule_date, $teacher_schedule_temp->schedule_start, $teacher_schedule_temp->schedule_end, $teacher_schedule_temp->schedule_attended);
            }
            $teacher_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->where('schedule_teacher', $schedule_teacher)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_end', '>', $schedule_start)
                                        ->where('schedule_end', '<=', $schedule_end)
                                        ->where('schedule_start', '<', $schedule_start)
                                        ->get();
            foreach($teacher_schedules_temp as $teacher_schedule_temp){
                $teacher_schedules[] = array($teacher->user_name, $teacher_schedule_temp->class_name, $teacher_schedule_temp->schedule_date, $teacher_schedule_temp->schedule_start, $teacher_schedule_temp->schedule_end, $teacher_schedule_temp->schedule_attended);
            }
            // 查询学生冲突课程
            // 未上课课程
            $student_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->join('member', 'member.member_class', '=', 'class.class_id')
                                        ->where('member_student', $student->student_id)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_start', '<', $schedule_start)
                                        ->where('schedule_end', '>', $schedule_end)
                                        ->where('schedule_attended', 0)
                                        ->get();
            foreach($student_schedules_temp as $student_schedule_temp){
                $student_schedules[] = array($student->student_name, $student_schedule_temp->class_name, $student_schedule_temp->schedule_date, $student_schedule_temp->schedule_start, $student_schedule_temp->schedule_end, $student_schedule_temp->schedule_attended);
            }
            $student_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->join('member', 'member.member_class', '=', 'class.class_id')
                                        ->where('member_student', $student->student_id)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_start', '>', $schedule_start)
                                        ->where('schedule_end', '<', $schedule_end)
                                        ->where('schedule_attended', 0)
                                        ->get();
            foreach($student_schedules_temp as $student_schedule_temp){
                $student_schedules[] = array($student->student_name, $student_schedule_temp->class_name, $student_schedule_temp->schedule_date, $student_schedule_temp->schedule_start, $student_schedule_temp->schedule_end, $student_schedule_temp->schedule_attended);
            }
            $student_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->join('member', 'member.member_class', '=', 'class.class_id')
                                        ->where('member_student', $student->student_id)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_start', '>=', $schedule_start)
                                        ->where('schedule_start', '<', $schedule_end)
                                        ->where('schedule_end', '>', $schedule_end)
                                        ->where('schedule_attended', 0)
                                        ->get();
            foreach($student_schedules_temp as $student_schedule_temp){
                $student_schedules[] = array($student->student_name, $student_schedule_temp->class_name, $student_schedule_temp->schedule_date, $student_schedule_temp->schedule_start, $student_schedule_temp->schedule_end, $student_schedule_temp->schedule_attended);
            }
            $student_schedules_temp = DB::table('schedule')
                                        ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                        ->join('member', 'member.member_class', '=', 'class.class_id')
                                        ->where('member_student', $student->student_id)
                                        ->where('schedule_date', $schedule_date)
                                        ->where('schedule_end', '>', $schedule_start)
                                        ->where('schedule_end', '<=', $schedule_end)
                                        ->where('schedule_start', '<', $schedule_start)
                                        ->where('schedule_attended', 0)
                                        ->get();
            foreach($student_schedules_temp as $student_schedule_temp){
                $student_schedules[] = array($student->student_name, $student_schedule_temp->class_name, $student_schedule_temp->schedule_date, $student_schedule_temp->schedule_start, $student_schedule_temp->schedule_end, $student_schedule_temp->schedule_attended);
            }
            // 已上课课程
            $attended_schedules_temp = DB::table('schedule')
                                         ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                         ->join('participant', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                                         ->where('participant_student', $student->student_id)
                                         ->where('schedule_date', $schedule_date)
                                         ->where('schedule_start', '<', $schedule_start)
                                         ->where('schedule_end', '>', $schedule_end)
                                         ->where('participant_attend_status', 1)
                                         ->get();
            foreach($attended_schedules_temp as $attended_schedule_temp){
                $student_schedules[] = array($student->student_name, $attended_schedule_temp->class_name, $attended_schedule_temp->schedule_date, $attended_schedule_temp->schedule_start, $attended_schedule_temp->schedule_end, $attended_schedule_temp->schedule_attended);
            }
            $attended_schedules_temp = DB::table('schedule')
                                         ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                         ->join('participant', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                                         ->where('participant_student', $student->student_id)
                                         ->where('schedule_date', $schedule_date)
                                         ->where('schedule_start', '>', $schedule_start)
                                         ->where('schedule_end', '<', $schedule_end)
                                         ->where('participant_attend_status', 1)
                                         ->get();
            foreach($attended_schedules_temp as $attended_schedule_temp){
                $student_schedules[] = array($student->student_name, $attended_schedule_temp->class_name, $attended_schedule_temp->schedule_date, $attended_schedule_temp->schedule_start, $attended_schedule_temp->schedule_end, $attended_schedule_temp->schedule_attended);
            }
            $attended_schedules_temp = DB::table('schedule')
                                         ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                         ->join('participant', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                                         ->where('participant_student', $student->student_id)
                                         ->where('schedule_date', $schedule_date)
                                         ->where('schedule_start', '>=', $schedule_start)
                                         ->where('schedule_start', '<', $schedule_end)
                                         ->where('schedule_end', '>', $schedule_end)
                                         ->where('participant_attend_status', 1)
                                         ->get();
            foreach($attended_schedules_temp as $attended_schedule_temp){
                $student_schedules[] = array($student->student_name, $attended_schedule_temp->class_name, $attended_schedule_temp->schedule_date, $attended_schedule_temp->schedule_start, $attended_schedule_temp->schedule_end, $attended_schedule_temp->schedule_attended);
            }
            $attended_schedules_temp = DB::table('schedule')
                                         ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                                         ->join('participant', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                                         ->where('participant_student', $student->student_id)
                                         ->where('schedule_date', $schedule_date)
                                         ->where('schedule_end', '>', $schedule_start)
                                         ->where('schedule_end', '<=', $schedule_end)
                                         ->where('schedule_start', '<', $schedule_start)
                                         ->where('participant_attend_status', 1)
                                         ->get();
            foreach($attended_schedules_temp as $attended_schedule_temp){
                $student_schedules[] = array($student->student_name, $attended_schedule_temp->class_name, $attended_schedule_temp->schedule_date, $attended_schedule_temp->schedule_start, $attended_schedule_temp->schedule_end, $attended_schedule_temp->schedule_attended);
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
     * 签约合同视图
     * URL: POST /operation/contract/create
     */
    public function studentContractCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/operation/student/contract/create", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取学生id
        $student_id = decode($request->input('id'), 'student_id');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取已购课程
        $hours = DB::table('hour')
                   ->join('course', 'course.course_id', '=', 'hour.hour_course')
                   ->where('hour_student', $student_id)
                   ->get();

        // 获取课程信息
        $courses_same_grade = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', $student->student_grade)
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_status', 1)
                     ->orderBy('course_type', 'asc')
                     ->orderBy('course_time', 'asc')
                     ->get();

        $courses_all_grade = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', 0)
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_status', 1)
                     ->orderBy('course_type', 'asc')
                     ->orderBy('course_time', 'asc')
                     ->get();

        $courses_diff_grade = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', "!=", $student->student_grade)
                     ->where('course_grade', "!=", 0)
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_status', 1)
                     ->orderBy('course_grade', 'asc')
                     ->orderBy('course_type', 'asc')
                     ->orderBy('course_time', 'asc')
                     ->get();

        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        return view('operation/student/studentContractCreate', ['student' => $student,
                                                                 'hours' => $hours,
                                                                 'courses_same_grade' => $courses_same_grade,
                                                                 'courses_all_grade' => $courses_all_grade,
                                                                 'courses_diff_grade' => $courses_diff_grade,
                                                                 'payment_methods' => $payment_methods]);
    }

    /**
     * 签约合同提交
     * URL: POST /operation/contract/store
     * @param  Request  $request
     * @param  $request->input('student_id'): 购课学生
     * @param  $request->input('selected_course_num'): 购买课程数量
     */
    public function studentContractStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取当前用户ID
        $contract_createuser = Session::get('user_id');
        // 获取表单输入
        $request_student_id = $request->input('student_id');
        $request_selected_course_num = $request->input('selected_course_num');
        $request_contract_payment_method = $request->input('payment_method');
        $request_contract_date = $request->input('contract_date');
        $request_contract_paid_price = $request->input('contract_paid_price');
        if($request->filled('remark')) {
            $request_contract_remark = $request->input('remark');
        }else{
            $request_contract_remark = "";
        }
        $request_contract_type = $request->input('contract_type');
        $request_contract_extra_fee = round((float)$request->input("extra_fee"), 2);
        $request_courses = array();
        // 生成新合同号(上一个合同号加一或新合同号001)
        $sub_student_id = substr($request_student_id , 1 , 10);
        if(DB::table('contract')->where('contract_student', $request_student_id)->exists()){
            $pre_contract_num = DB::table('contract')
                                  ->where('contract_student', $request_student_id)
                                  ->orderBy('contract_createtime', 'desc')
                                  ->limit(1)
                                  ->first();
            $new_contract_num = intval(substr($pre_contract_num->contract_id , 10 , 12))+1;
        }else{
            $new_contract_num = 1;
        }
        $contract_id = "H".$sub_student_id.sprintf("%02d", $new_contract_num);
        for($i=1; $i<=$request_selected_course_num; $i++){
            $temp = array();
            $temp[] = (int)$request->input("course_{$i}_0");
            $temp[] = round((float)$request->input("course_{$i}_1"), 2);
            $temp[] = round((float)$request->input("course_{$i}_2"), 1);
            $temp[] = round((float)$request->input("course_{$i}_3"), 2);
            $temp[] = round((float)$request->input("course_{$i}_4")/100, 2);
            $temp[] = round((float)$request->input("course_{$i}_5"), 2);
            $temp[] = round((float)$request->input("course_{$i}_6"), 1);
            $temp[] = round((float)$request->input("course_{$i}_7"), 1);
            $temp[] = round((float)$request->input("course_{$i}_8"), 2);
            $request_courses[] = $temp;
        }
        // 计算合同总信息
        $contract_student = $request_student_id;
        $contract_course_num = $request_selected_course_num;
        $contract_original_hour = 0;
        $contract_free_hour = 0;
        $contract_total_hour = 0;
        $contract_original_price = 0;
        $contract_discount_price = 0;
        $contract_total_price = 0;
        $contract_paid_price = $request_contract_paid_price;
        $contract_date = $request_contract_date;
        $contract_remark = $request_contract_remark;
        $contract_payment_method = $request_contract_payment_method;
        foreach($request_courses as $request_course){
            $contract_original_hour += $request_course[2];
            $contract_free_hour += $request_course[6];
            $contract_total_hour += $request_course[7];
            $contract_original_price += $request_course[3];
            $contract_total_price += $request_course[8];
        }
        $contract_discount_price = $contract_original_price - $contract_total_price;
        $contract_original_price = round($contract_original_price, 2);
        $contract_discount_price = round($contract_discount_price, 2);
        $contract_total_price = round($contract_total_price+$request_contract_extra_fee, 2);
        // 获取学生校区
        $student_department = DB::table('student')
                                ->where('student_id', $request_student_id)
                                ->first()
                                ->student_department;
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入Contract表
            DB::table('contract')->insert(
                ['contract_id' => $contract_id,
                 'contract_department' => $student_department,
                 'contract_student' => $contract_student,
                 'contract_course_num' => $contract_course_num,
                 'contract_original_hour' => $contract_original_hour,
                 'contract_free_hour' => $contract_free_hour,
                 'contract_total_hour' => $contract_total_hour,
                 'contract_original_price' => $contract_original_price,
                 'contract_discount_price' => $contract_discount_price,
                 'contract_total_price' => $contract_total_price,
                 'contract_paid_price' => $contract_paid_price,
                 'contract_date' => $contract_date,
                 'contract_payment_method' => $contract_payment_method,
                 'contract_remark' => $contract_remark,
                 'contract_type' => $request_contract_type,
                 'contract_section' => 1,
                 'contract_extra_fee' => $request_contract_extra_fee,
                 'contract_createuser' => $contract_createuser]
            );
            foreach($request_courses as $request_course){
                $contract_course_discount_total = round(($request_course[3] - $request_course[8]), 2);
                $contract_course_actual_unit_price = round(($request_course[8]/$request_course[7]), 2);
                // 插入Contract_course表
                DB::table('contract_course')->insert(
                    ['contract_course_contract' => $contract_id,
                     'contract_course_course' => $request_course[0],
                     'contract_course_original_hour' => $request_course[2],
                     'contract_course_free_hour' => $request_course[6],
                     'contract_course_total_hour' => $request_course[7],
                     'contract_course_discount_rate' => $request_course[4],
                     'contract_course_discount_amount' => $request_course[5],
                     'contract_course_discount_total' => $contract_course_discount_total,
                     'contract_course_original_unit_price' => $request_course[1],
                     'contract_course_actual_unit_price' => $contract_course_actual_unit_price,
                     'contract_course_original_price' => $request_course[3],
                     'contract_course_total_price' => $request_course[8],
                     'contract_course_createuser' => $contract_createuser]
                );
                // 更新Hour表
                if(DB::table('hour')->where('hour_student', $contract_student)->where('hour_course', $request_course[0])->exists()){
                    $hour = DB::table('hour')
                              ->where('hour_student', $contract_student)
                              ->where('hour_course', $request_course[0])
                              ->first();
                    $hour_remain = $hour->hour_remain;
                    $hour_used = $hour->hour_used;
                    $hour_average_price = $hour->hour_average_price;
                    $hour_total_price = ($hour_remain+$hour_used)*$hour_average_price;
                    $hour_remain+=$request_course[2]+$request_course[6];
                    $hour_total_price+=$request_course[8];
                    $hour_average_price = $hour_total_price/$hour_remain;
                    DB::table('hour')
                      ->where('hour_student', $contract_student)
                      ->where('hour_course', $request_course[0])
                      ->update(['hour_remain' => $hour_remain,
                                'hour_average_price' => $hour_average_price]);
                }else{
                    DB::table('hour')->insert(
                        ['hour_student' => $contract_student,
                         'hour_course' => $request_course[0],
                         'hour_remain' => $request_course[2]+$request_course[6],
                         'hour_used' => 0,
                         'hour_average_price' => $contract_course_actual_unit_price]
                    );
                }
            }
            // 增加学生签约次数
            DB::table('student')
              ->where('student_id', $request_student_id)
              ->increment('student_contract_num');
            // 更新客户状态、最后签约时间
            DB::table('student')
              ->where('student_id', $request_student_id)
              ->update(['student_last_contract_date' =>  date('Y-m-d')]);
            // 更新学生首次签约时间
            if($request->input('contract_type')==0){
                DB::table('student')
                  ->where('student_id', $request_student_id)
                  ->update(['student_first_contract_date' =>  date('Y-m-d')]);
            }
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $request_student_id,
                 'student_record_type' => '签约合同',
                 'student_record_content' => '客户首次签约合同，合同号：'.$contract_id.'，课程种类：'.$contract_course_num.' 种，合计金额：'.$contract_total_price.' 元。签约人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            // 返回购课界面
            return redirect("/operation/student/contract/create?id=".encode($request_student_id, 'student_id'))
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '购课添加失败',
                         'message' => '购课添加失败，错误码:213']);
        }
        DB::commit();
        // 获取学生、课程名称
        $student_name = DB::table('student')
                          ->where('student_id', $contract_student)
                          ->value('student_name');
        // 返回购课列表
        return redirect("/operation/student/contract/success?student_id=".encode($contract_student, 'student_id')."&contract_id=".encode($contract_id, 'contract_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '合同添加成功',
                       'message' => '合同添加成功']);
    }

    public function studentContractSuccess(Request $request){
        return view('operation/student/studentContractCreateSuccess', ['student_id' => $request->input('student_id'), 'contract_id' => $request->input('contract_id')]);
    }

    public function studentGrade(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/operation/student/grade", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->whereIn('student_department', $department_access)
                  ->where('student_contract_num', '>', 0)
                  ->where('student_status', 1);

        // 数据范围权限
        if (Session::get('user_access_self')==1) {
            $rows = $rows->where('student_class_adviser', '=', Session::get('user_id'));
        }

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
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

        // 排序并获取数据对象
        $rows = $rows->orderBy('student_department', 'asc')
                     ->orderBy('student_grade', 'asc')
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
            $students[] = $temp;
        }

        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('operation/student/studentGrade', ['students' => $students,
                                                        'filters' => $filters,
                                                        'filter_departments' => $filter_departments,
                                                        'filter_grades' => $filter_grades]);
    }

    public function studentGradeStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取student_id
        $request_ids=$request->input('student_id');
        $student_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $student_ids[]=decode($request_id, 'student_id');
            }
        }else{
            $student_ids[]=decode($request_ids, 'student_id');
        }
        $upgrade_type=intval($request->input('upgrade_type'));
        // 更新数据
        try{
            if($upgrade_type==1){ // 升一年级
                foreach ($student_ids as $student_id){
                    if(DB::table('student')->where('student_id', $student_id)->first()->student_grade<12){
                        DB::table('student')
                          ->where('student_id', $student_id)
                          ->increment('student_grade');
                    }
                }
            }else{  // 降一年级
                foreach ($student_ids as $student_id){
                    if(DB::table('student')->where('student_id', $student_id)->first()->student_grade>1){
                        DB::table('student')
                          ->where('student_id', $student_id)
                          ->decrement('student_grade');
                    }
                }
            }
        }
        // 捕获异常
        catch(Exception $e){
            return back()->with(['notify' => true,
                                 'type' => 'danger',
                                 'title' => '升降年级失败',
                                 'message' => '升降年级失败，错误码:116']);
        }
        // 返回用户列表
        return redirect("/operation/student")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '升降年级成功',
                         'message' => '升降年级成功']);


    }

    public function studentDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/operation/student/department", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        $student_id = decode($request->input('id'), 'student_id');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取可转校区
        $departments = DB::table('department')
                                ->where('department_status', 1)
                                ->where('department_id', '!=', $student->student_department)
                                ->orderBy('department_id', 'asc')
                                ->get();
        return view('operation/student/studentDepartment', ['student' => $student,
                                                            'departments' => $departments]);
    }

    public function studentUser(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('student_id');
        $student_department = $request->input('student_department');
        // 获取学生信息
        $student = DB::table('student')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取校区信息
        $department = DB::table('department')
                         ->where('department_id', $student_department)
                         ->first();
        // 获取负责人信息
        $users = DB::table('user')
                    ->join('position', 'user.user_position', '=', 'position.position_id')
                    ->where('user_department', $student_department)
                    ->where('user_status', 1)
                    ->orderBy('user_position', 'desc')
                    ->get();

        return view('operation/student/studentUser', ['student' => $student,
                                                      'department' => $department,
                                                      'users' => $users]);
    }

    public function studentDepartmentStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('student_id');
        $student_department = $request->input('student_department');
        $student_consultant = $request->input('student_consultant');
        $student_class_adviser = $request->input('student_class_adviser');
        // 更新数据
        DB::beginTransaction();
        try{
            // 班级人数减少1
            DB::table('member')
              ->join('class', 'member.member_class', '=', 'class.class_id')
              ->where('member_student', $student_id)
              ->decrement('class_current_num');
            // 退出班级
            DB::table('member')
              ->where('member_student', $student_id)
              ->delete();
            // 更新学生数据
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_department' => $student_department,
                        'student_consultant' => $student_consultant,
                        'student_class_adviser' => $student_class_adviser]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            return back()->with(['notify' => true,
                                 'type' => 'danger',
                                 'title' => '转校区失败',
                                 'message' => '转校区失败，错误码:116']);
        }
        DB::commit();
        return redirect("/operation/student")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '转校区成功',
                       'message' => '转校区成功']);
    }
}
