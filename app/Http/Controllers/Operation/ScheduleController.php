<?php
namespace App\Http\Controllers\Operation;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ScheduleController extends Controller
{
    /**
     * 本校班级课程安排视图
     * URL: GET /operation/schedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function schedule(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/operation/schedule", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->Join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('user AS creator', 'schedule.schedule_createuser', '=', 'creator.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->whereIn('schedule_department', $department_access)
                  ->where('schedule_attended', '=', 0);

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_class" => null,
                        "filter_subject" => null,
                        "filter_teacher" => null,
                        "filter_date" => null,
                    );

        // 班级校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('class_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 班级年级
        if ($request->filled('filter_grade')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter_grade'));
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 班级科目
        if ($request->filled('filter_subject')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter_subject'));
            $filters['filter_subject']=$request->input("filter_subject");
        }
        // 班级
        if ($request->filled('filter_class')) {
            $rows = $rows->where('class_id', '=', $request->input('filter_class'));
            $filters['filter_class']=$request->input("filter_class");
        }
        // 负责教师
        if ($request->filled('filter_teacher')) {
            $rows = $rows->where('class_teacher', '=', $request->input('filter_teacher'));
            $filters['filter_teacher']=$request->input("filter_teacher");
        }
        // 上课日期
        if ($request->filled('filter_date')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter_date'));
            $filters['filter_date']=$request->input("filter_date");
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->select('schedule_id',
                              'schedule_date',
                              'schedule_start',
                              'schedule_end',
                              'class_id',
                              'class_name',
                              'class_max_num',
                              'class_current_num',
                              'teacher.user_id AS teacher_id',
                              'teacher.user_name AS teacher_name',
                              'creator.user_id AS creator_id',
                              'creator.user_name AS creator_name',
                              'department_name',
                              'subject_name',
                              'grade_name',
                              'classroom_name',
                              'course_name')
                     ->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        $filter_users = DB::table('user')
                          ->join('department', 'user.user_department', '=', 'department.department_id')
                          ->join('position', 'user.user_position', '=', 'position.position_id')
                          ->where('user_status', 1)
                          ->whereIn('user_department', $department_access)
                          ->orderBy('user_department', 'asc')
                          ->orderBy('user_position', 'desc')
                          ->get();
        $filter_classes = DB::table('class')
                          ->join('department', 'class.class_department', '=', 'department.department_id')
                          ->where('class_status', 1)
                          ->whereIn('class_department', $department_access)
                          ->orderBy('class_department', 'asc')
                          ->orderBy('class_grade', 'asc')
                          ->get();

        // 返回列表视图
        return view('operation/schedule/schedule', ['rows' => $rows,
                                                   'currentPage' => $currentPage,
                                                   'totalPage' => $totalPage,
                                                   'startIndex' => $offset,
                                                   'request' => $request,
                                                   'filters' => $filters,
                                                   'totalNum' => $totalNum,
                                                   'filter_departments' => $filter_departments,
                                                   'filter_grades' => $filter_grades,
                                                   'filter_subjects' => $filter_subjects,
                                                   'filter_classes' => $filter_classes,
                                                   'filter_users' => $filter_users]);
    }

    public function scheduleDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/operation/schedule/delete", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取schedule_id
        $request_ids=$request->input('id');
        $schedule_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $schedule_ids[]=decode($request_id, 'schedule_id');
            }
        }else{
            $schedule_ids[]=decode($request_ids, 'schedule_id');
        }
        // 删除数据
        try{
            foreach ($schedule_ids as $schedule_id){
                $schedule = DB::table('schedule')
                              ->where('schedule_id', $schedule_id)
                              ->first();
                DB::table('schedule')
                  ->where('schedule_id', $schedule_id)
                  ->delete();
                DB::table('class')
                  ->where('class_id', $schedule->schedule_participant)
                  ->decrement('class_schedule_num');
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/schedule")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '课程安排删除失败',
                         'message' => '课程安排删除失败，错误码:325']);
        }
        // 返回课程列表
        return redirect("/operation/schedule")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程安排删除成功',
                       'message' => '课程安排删除成功!']);
    }

    /**
     * 课程考勤视图
     * URL: GET /education/schedule/attend/{schedule_id}
     * @param  int  $schedule_id
     */
    public function scheduleAttend(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/operation/schedule/attend", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        $schedule_id = decode($request->input('id'), 'schedule_id');
        // 获取数据信息
        $schedule = DB::table('schedule')
                      ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                      ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->where('schedule_id', $schedule_id)
                      ->first();
        // 判断是否已经点名
        if($schedule->schedule_attended==1){
            return redirect("/operation/schedule/attend/success?id=".encode($schedule->schedule_participant, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '课程已经点名',
                           'message' => '课程已经点名']);
        }
        // 获取上课日期、时间
        $schedule_date = $schedule->schedule_date;
        $schedule_start = $schedule->schedule_start;
        $schedule_end = $schedule->schedule_end;
        $schedule_department = $schedule->schedule_department;
        // 获取所有可用教师
        $teachers = DB::table('user')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', $schedule_department)
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc');
        $teachers = DB::table('user')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', $schedule_department)
                      ->where('user_status', 1)
                      ->union($teachers)
                      ->get();
        // 获取所有可用教室名单
        $classrooms = DB::table('classroom')
                        ->where('classroom_department', $schedule_department)
                        ->where('classroom_status', 1)
                        ->orderBy('classroom_id', 'asc')
                        ->get();
        // 获取所有科目
        $subjects = DB::table('subject')
                        ->where('subject_status', 1)
                        ->orderBy('subject_id', 'asc')
                        ->get();
        // 获取班级成员
        $student_courses = array();
        // 获取班级学生
        $members = DB::table('member')
                     ->join('class', 'class.class_id', '=', 'member.member_class')
                     ->join('student', 'member.member_student', '=', 'student.student_id')
                     ->where('member_class', $schedule->schedule_participant)
                     ->get();
        foreach ($members as $member){
            // 获取学生信息
            $student = DB::table('student')
                         ->join('department', 'student.student_department', '=', 'department.department_id')
                         ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                         ->where('student_id', $member->student_id)
                         ->first();
            // 获取学生已购课程
            $courses = DB::table('hour')
                         ->join('student', 'student.student_id', '=', 'hour.hour_student')
                         ->join('course', 'hour.hour_course', '=', 'course.course_id')
                         ->where([
                             ['student.student_id', '=', $member->student_id],
                             ['hour.hour_remain', '>', '0'],
                         ])
                         ->get();
            $student_courses[] = array($student, $courses, $member->member_course, $member->member_amount);
        }
        return view('operation/schedule/scheduleAttend', ['schedule' => $schedule,
                                                          'teachers' => $teachers,
                                                          'classrooms' => $classrooms,
                                                          'subjects' => $subjects,
                                                          'student_courses' => $student_courses]);
    }

    /**
     * 课程考勤视图2
     * URL: POST /education/schedule/attend2
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     * @param  $request->input('input3'): 学生人数
     */
    public function scheduleAttendStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_id = $request->input('input_schedule');
        $schedule_date = $request->input('input_date');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_subject = $request->input('input_subject');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_student_num = $request->input('input_student_num');
        // 判断时间合法性
        if($schedule_start>=$schedule_end){
            return redirect("/operation/schedule/attend?id=".encode($schedule_id, 'schedule_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前，错误码:326']);
        }
        // 判断是否有学生
        if($schedule_student_num==0){
            return redirect("/operation/schedule/attend?id=".encode($schedule_id, 'schedule_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '课程安排点名失败',
                           'message' => '班级内没有学生，错误码:326']);
        }

        // 获取安排信息
        $schedule = DB::table('schedule')
                      ->where('schedule_id', $schedule_id)
                      ->first();

        // 判断是否已经点名
        if($schedule->schedule_attended==1){
            return redirect("/operation/schedule/attend/success?id=".encode($schedule->schedule_participant, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '课程已经点名',
                           'message' => '课程已经点名']);
        }

        // 声明数据数组
        for($i=1;$i<=$schedule_student_num;$i++){
            $participant_student = $request->input('input'.$i.'_0');
            $participant_attend_status = $request->input('input'.$i.'_1');
            $participant_course = $request->input('input'.$i.'_2');
            $participant_amount = $request->input('input'.$i.'_3');
            if($participant_attend_status!=2){
                // 查询剩余课时
                $hour = DB::table('hour')
                          ->join('course', 'hour.hour_course', '=', 'course.course_id')
                          ->where('hour_course', $participant_course)
                          ->where('hour_student', $participant_student)
                          ->first();
                // 剩余课时不足
                if($participant_amount>$hour->hour_remain){
                    // 查询学生名称
                    $student_name = DB::table('student')
                                      ->where('student_id', $participant_student)
                                      ->first()
                                      ->student_name;
                    // 返回第一步
                    return redirect("/operation/schedule/attend?id=".encode($schedule_id, 'schedule_id'))
                           ->with(['notify' => true,
                                   'type' => 'danger',
                                   'title' => '学生剩余课时不足',
                                   'message' => $student_name.'剩余课时不足，错误码:327']);

                }
            }
        }

        // 统计上课人数
        $schedule_attended_num = 0; // 正常
        $schedule_leave_num = 0; // 请假
        $schedule_absence_num = 0; // 旷课

        DB::beginTransaction();
        try{
            for($i=1;$i<=$schedule_student_num;$i++){
                $participant_student = $request->input('input'.$i.'_0');
                $participant_attend_status = $request->input('input'.$i.'_1');
                if($participant_attend_status==1){ // 正常（计课时）
                    $participant_course = $request->input('input'.$i.'_2');
                    $participant_amount = $request->input('input'.$i.'_3');
                    $schedule_attended_num = $schedule_attended_num + 1; // 增加正常上课人数
                }else if($participant_attend_status==2){ // 请假（不计课时）
                    $participant_course = 0;
                    $participant_amount = 0;
                    $schedule_leave_num = $schedule_leave_num + 1; // 增加请假人数
                }else { // 旷课（计课时）
                    $participant_course = $request->input('input'.$i.'_2');
                    $participant_amount = $request->input('input'.$i.'_3');
                    $schedule_absence_num = $schedule_absence_num + 1; // 增加旷课人数
                }
                // 扣除剩余课时
                if($participant_attend_status!=2){
                    // 扣除学生正常课时
                    DB::table('hour')
                      ->where('hour_course', $participant_course)
                      ->where('hour_student', $participant_student)
                      ->decrement('hour_remain', $participant_amount);
                    // 增加已用正常课时数
                    DB::table('hour')
                      ->where('hour_course', $participant_course)
                      ->where('hour_student', $participant_student)
                      ->increment('hour_used', $participant_amount);
                    // 更新班级成员信息
                    DB::table('member')
                      ->where('member_student', $participant_student)
                      ->where('member_class', $schedule->schedule_participant)
                      ->update(['member_course' => $participant_course,
                                'member_amount' => $participant_amount]);
                }
                // 添加上课成员表
                DB::table('participant')->insert(
                    ['participant_schedule' => $schedule_id,
                     'participant_student' => $participant_student,
                     'participant_attend_status' => $participant_attend_status,
                     'participant_course' => $participant_course,
                     'participant_amount' => $participant_amount,
                     'participant_createuser' => Session::get('user_id')]
                );
            }
            DB::table('schedule')
              ->where('schedule_id', $schedule_id)
              ->update(['schedule_date' => $schedule_date,
                        'schedule_start' => $schedule_start,
                        'schedule_end' => $schedule_end,
                        'schedule_teacher' => $schedule_teacher,
                        'schedule_subject' => $schedule_subject,
                        'schedule_classroom' => $schedule_classroom,
                        'schedule_student_num' => $schedule_student_num,
                        'schedule_attended_num' => $schedule_attended_num,
                        'schedule_leave_num' => $schedule_leave_num,
                        'schedule_absence_num' => $schedule_absence_num,
                        'schedule_attended' => 1,
                        'schedule_attended_user' => Session::get('user_id')]);
            // 更新班级信息
            DB::table('class')
              ->where('class_id', $schedule->schedule_participant)
              ->increment('class_attended_num');
            DB::table('class')
              ->where('class_id', $schedule->schedule_participant)
              ->decrement('class_schedule_num');
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            // 返回第一步
            return redirect("/operation/schedule/attend?id=".encode($schedule_id, 'schedule_id'))
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '学生剩余课时不足',
                         'message' => '学生剩余课时不足，错误码:328']);
        }
        DB::commit();
        // 返回我的上课记录视图
        return redirect("/operation/schedule/attend/success?id=".encode($schedule->schedule_participant, 'class_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程点名成功',
                       'message' => '课程点名成功']);
    }

    /**
     * 课程考勤提交成功
     * URL: GET /operation/schedule/attend/success
     */
    public function scheduleAttendSuccess(Request $request){
        return view('operation/schedule/scheduleAttendSuccess', ['id' => $request->input('id')]);
    }


}
