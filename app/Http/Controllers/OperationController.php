<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class OperationController extends Controller
{

    /**
     * 上课记录视图
     * URL: GET /operation/attendedSchedule/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function attendedScheduleAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('participant')
                  ->join('student', 'participant.participant_student', '=', 'student.student_id')
                  ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->leftJoin('hour', 'participant.participant_hour', '=', 'hour.hour_id')
                  ->leftJoin('course', 'hour.hour_course', '=', 'course.course_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->leftJoin('user AS checked_user', 'participant.participant_checked_user', '=', 'checked_user.user_id')
                  ->select('participant.participant_id AS participant_id',
                           'student.student_name AS student_name',
                           'subject.subject_name AS subject_name',
                           'grade.grade_name AS grade_name',
                           'classroom.classroom_name AS classroom_name',
                           'class.class_name AS class_name',
                           'teacher.user_name AS teacher_name',
                           'participant.participant_attend_status AS participant_attend_status',
                           'participant.participant_amount AS participant_amount',
                           'participant.participant_checked AS participant_checked',
                           'checked_user.user_name AS checked_user_name',
                           'schedule.schedule_id AS schedule_id',
                           'schedule.schedule_date AS schedule_date',
                           'schedule.schedule_start AS schedule_start',
                           'schedule.schedule_end AS schedule_end',
                           'course.course_name AS course_name')
                  ->whereIn('schedule_department', $department_access);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student.student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class.class_name', 'like', '%'.$request->input('filter2').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter4'));
            $filter_status = 1;
        }
        // 学生科目
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_subject', '=', $request->input('filter5'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('operation/attendedScheduleAll', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_status' => $filter_status,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_subjects' => $filter_subjects]);
    }

    /**
     * 我的学生课程安排视图
     * URL: GET /operation/schedule/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function ScheduleMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->leftJoin('member', 'member.member_class', '=', 'class.class_id')
                  ->leftJoin('student AS class_member', 'member.member_student', '=', 'class_member.student_id')
                  ->where([
                      ['schedule.schedule_attended', '=', 0],
                      ['student.student_class_adviser', '=', Session::get('user_id')],
                  ])
                  ->orWhere([
                      ['schedule.schedule_attended', '=', 0],
                      ['class_member.student_class_adviser', '=', Session::get('user_id')],
                  ]);
        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student.student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级名称
        if ($request->filled('filter2')) {
            $rows = $rows->where('class.class_name', 'like', '%'.$request->input('filter2').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter4'));
            $filter_status = 1;
        }
        // 学生科目
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_subject', '=', $request->input('filter5'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->select('schedule.schedule_id AS schedule_id',
                              'schedule.schedule_date AS schedule_date',
                              'schedule.schedule_start AS schedule_start',
                              'schedule.schedule_end AS schedule_end',
                              'schedule.schedule_time AS schedule_time',
                              'schedule.schedule_participant_type AS schedule_participant_type',
                              'department.department_name AS department_name',
                              'teacher.user_name AS teacher_name',
                              'course.course_name AS course_name',
                              'subject.subject_name AS subject_name',
                              'grade.grade_name AS grade_name',
                              'classroom.classroom_name AS classroom_name',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'class.class_id AS class_id',
                              'class.class_name AS class_name',
                              'class_member.student_id AS class_member_id',
                              'class_member.student_name AS class_member_name')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('operation/scheduleMy', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_status' => $filter_status,
                                               'filter_departments' => $filter_departments,
                                               'filter_grades' => $filter_grades,
                                               'filter_subjects' => $filter_subjects]);
    }

    /**
     * 我的学生课程安排删除
     * URL: DELETE /operation/schedule/my/{schedule_id}
     * @param  int  $schedule_id
     */
    public function myScheduleDelete($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 更新数据库
        try{
            DB::table('schedule')
              ->where('schedule_id', $schedule_id)
              ->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/operation/schedule/my")->with(['notify' => true,
                                                                    'type' => 'danger',
                                                                    'title' => '课程安排删除失败',
                                                                    'message' => '课程安排删除失败！']);
        }
        // 返回
        return redirect("/operation/schedule/my")->with(['notify' => true,
                                                                'type' => 'success',
                                                                'title' => '课程安排删除成功',
                                                                'message' => '课程安排删除成功！']);
    }

    /**
     * 我的学生上课记录视图
     * URL: GET /operation/attendedSchedule/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function attendedScheduleMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('participant')
                  ->join('student', 'participant.participant_student', '=', 'student.student_id')
                  ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                  ->join('user AS teacher', 'schedule.schedule_teacher', '=', 'teacher.user_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->leftJoin('hour', 'participant.participant_hour', '=', 'hour.hour_id')
                  ->leftJoin('course', 'hour.hour_course', '=', 'course.course_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->leftJoin('user AS checked_user', 'participant.participant_checked_user', '=', 'checked_user.user_id')
                  ->select('participant.participant_id AS participant_id',
                           'student.student_name AS student_name',
                           'subject.subject_name AS subject_name',
                           'grade.grade_name AS grade_name',
                           'classroom.classroom_name AS classroom_name',
                           'class.class_name AS class_name',
                           'teacher.user_name AS teacher_name',
                           'participant.participant_attend_status AS participant_attend_status',
                           'participant.participant_amount AS participant_amount',
                           'participant.participant_checked AS participant_checked',
                           'checked_user.user_name AS checked_user_name',
                           'schedule.schedule_id AS schedule_id',
                           'schedule.schedule_date AS schedule_date',
                           'schedule.schedule_start AS schedule_start',
                           'schedule.schedule_end AS schedule_end',
                           'course.course_name AS course_name')
                  ->where('student_class_adviser', '=', Session::get('user_id'));
        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student.student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class.class_name', 'like', '%'.$request->input('filter2').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter4'));
            $filter_status = 1;
        }
        // 学生科目
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_subject', '=', $request->input('filter5'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('schedule_date', 'asc')
                     ->orderBy('schedule_start', 'asc')
                     ->orderBy('schedule_time', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('operation/attendedScheduleMy', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_status' => $filter_status,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_subjects' => $filter_subjects]);
    }

    /**
     * 上课记录复核
     * URL: GET /attendedSchedule/{participant_id}/check
     * @param  int  $participant_id
     */
    public function attendedScheduleCheck($participant_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取上课成员数据信息
        $participants = DB::table('participant')
                          ->join('student', 'participant.participant_student', '=', 'student.student_id')
                          ->where('participant.participant_id', $participant_id)
                          ->first();
        if($participants->student_class_adviser!=Session::get('user_id')){
            return redirect("/operation/attendedSchedule/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '上课记录复核失败',
                          'message' => '非学生班主任操作，上课记录复核失败！']);
        }
        DB::beginTransaction();
        // 插入数据库
        try{
            DB::table('participant')
              ->where('participant.participant_id', $participant_id)
              ->update(['participant_checked' => 1,
                        'participant_checked_user' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return $e;
            // 返回我的学生上课记录
            return redirect("/operation/attendedSchedule/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '上课记录复核失败',
                          'message' => '上课记录复核失败，请联系系统管理员！']);
        }
        DB::commit();
        return redirect("/operation/attendedSchedule/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '上课记录复核成功',
                      'message' => '上课记录复核成功！']);
    }

    /**
     * 审核退课
     * URL: GET /operation/refund/{refund_id}
     * @param  int  $refund_id
     */
    public function refundCheck($refund_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        DB::beginTransaction();
        try{
            // 删除Refund表
            DB::table('refund')
              ->where('refund_id', $refund_id)
              ->update(['refund_checked' => 1,
                        'refund_checked_user' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/refund/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '退费记录审核失败！',
                          'message' => '退费记录审核失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/operation/refund/all")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费记录审核成功！',
                      'message' => '退费记录审核成功！']);
    }

    /**
     * 课程考勤视图
     * URL: GET /education/schedule/attend/{schedule_id}
     * @param  int  $schedule_id
     */
    public function scheduleAttend($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
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

        // 获取上课成员(学生/班级成员)
        $student_courses = array();
        // 获取成员ID
        $schedule_participant = $schedule->schedule_participant;
        // 获取成员ID首字母
        $schedule_type = substr($schedule_participant , 0 , 1);
        if($schedule_type=="S"){ // 上课成员为学生
            // 获取学生信息
            $student = DB::table('student')
                         ->join('department', 'student.student_department', '=', 'department.department_id')
                         ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                         ->where('student_id', $schedule_participant)
                         ->first();
            // 获取学生已购课程
            $courses = DB::table('student')
                         ->join('hour', 'student.student_id', '=', 'hour.hour_student')
                         ->join('course', 'hour.hour_course', '=', 'course.course_id')
                         ->where([
                             ['student.student_id', '=', $schedule_participant],
                             ['hour.hour_remain', '>', '0'],
                         ])
                         ->orWhere([
                             ['student.student_id', '=', $schedule_participant],
                             ['hour.hour_remain_free', '>', '0'],
                         ])
                         ->get();
            $student_courses[] = array($student, $courses);
        }else{ // 上课成员为班级
            // 获取班级学生
            $members = DB::table('class')
                         ->join('member', 'class.class_id', '=', 'member.member_class')
                         ->join('student', 'member.member_student', '=', 'student.student_id')
                         ->where('class_id', $schedule_participant)
                         ->get();
            foreach ($members as $member){
                // 获取学生信息
                $student = DB::table('student')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                             ->where('student_id', $member->student_id)
                             ->first();
                // 获取学生已购课程
                $courses = DB::table('student')
                             ->join('hour', 'student.student_id', '=', 'hour.hour_student')
                             ->join('course', 'hour.hour_course', '=', 'course.course_id')
                             ->where([
                                 ['student.student_id', '=', $member->student_id],
                                 ['hour.hour_remain', '>', '0'],
                             ])
                             ->orWhere([
                                 ['student.student_id', '=', $member->student_id],
                                 ['hour.hour_remain_free', '>', '0'],
                             ])
                             ->get();
                $student_courses[] = array($student, $courses);
            }
        }
        return view('operation/scheduleAttend', ['schedule' => $schedule,
                                                 'teachers' => $teachers,
                                                 'classrooms' => $classrooms,
                                                 'subjects' => $subjects,
                                                 'student_courses' => $student_courses]);
    }

    /**
     * 课程考勤视图2
     * URL: POST /education/schedule/attend/{schedule_id}/step2
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     * @param  $request->input('input3'): 学生人数
     */
    public function scheduleAttend2(Request $request, $schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_date = $request->input('input_date');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_subject = $request->input('input_subject');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_student_num = $request->input('input_student_num');
        // 判断时间合法性
        if($schedule_start>=$schedule_end){
            return redirect("/operation/schedule/attend/{$schedule_id}")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前！']);
        }
        // 获取教师姓名
        $schedule_teacher_name = DB::table('user')
                                   ->select('user_name')
                                   ->where('user_id', $schedule_teacher)
                                   ->first()
                                   ->user_name;
        // 获取科目名称
        $schedule_subject_name = DB::table('subject')
                                   ->select('subject_name')
                                   ->where('subject_id', $schedule_subject)
                                   ->first()
                                   ->subject_name;
        // 获取教室名称
        $schedule_classroom_name = DB::table('classroom')
                                   ->select('classroom_name')
                                   ->where('classroom_id', $schedule_classroom)
                                   ->first()
                                   ->classroom_name;
        // 声明数据数组
        $student_courses = array();
        for($i=1;$i<=$schedule_student_num;$i++){
            $participant_student = $request->input('input'.$i.'_0');
            $student_name = DB::table('student')
                              ->where('student_id', $participant_student)
                              ->first()
                              ->student_name;
            $participant_attend_status = $request->input('input'.$i.'_1');
            if($participant_attend_status==2){
                $student_courses[] = array($participant_student, $participant_attend_status, 0, 0, $student_name, "无");
                continue;
            }
            $participant_hour = $request->input('input'.$i.'_2');
            $participant_amount = $request->input('input'.$i.'_3');
            if($participant_attend_status!=2){
                // 查询剩余课时
                $hour = DB::table('hour')
                          ->join('course', 'hour.hour_course', '=', 'course.course_id')
                          ->where('hour_id', $participant_hour)
                          ->first();
                $hour_remain = $hour->hour_remain+$hour->hour_remain_free;
                $course_name = $hour->course_name;
                // 剩余课时不足
                if($participant_amount>$hour_remain){
                    // 查询学生名称
                    $student_name = DB::table('student')
                                      ->where('student_id', $participant_student)
                                      ->first()
                                      ->student_name;
                    // 返回第一步
                    return redirect("/operation/schedule/attend/{$schedule_id}")
                           ->with(['notify' => true,
                                   'type' => 'danger',
                                   'title' => '学生剩余课时不足，请重新选择',
                                   'message' => $student_name.'剩余课时不足，请重新选择']);

                }
            }else{
                $course_name = "无";
            }
            $student_courses[] = array($participant_student, $participant_attend_status, $participant_hour, $participant_amount, $student_name, $course_name);
        }
        // 获取数据信息
        $schedule = DB::table('schedule')
                      ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                      ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->where('schedule_id', $schedule_id)
                      ->first();
        return view('operation/scheduleAttend2', ['schedule' => $schedule,
                                                 'schedule_date' => $schedule_date,
                                                 'schedule_start' => $schedule_start,
                                                 'schedule_end' => $schedule_end,
                                                 'schedule_teacher' => $schedule_teacher,
                                                 'schedule_teacher_name' => $schedule_teacher_name,
                                                 'schedule_subject' => $schedule_subject,
                                                 'schedule_subject_name' => $schedule_subject_name,
                                                 'schedule_classroom' => $schedule_classroom,
                                                 'schedule_classroom_name' => $schedule_classroom_name,
                                                 'student_courses' => $student_courses]);
    }

    /**
     * 课程考勤提交
     * URL: POST /operation/schedule/attend/{schedule_id}/store
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     * @param  $request->input('input3'): 学生人数
     */
    public function scheduleAttendStore(Request $request, $schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取表单输入
        $schedule_date = $request->input('input_date');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_subject = $request->input('input_subject');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_student_num = $request->input('input_student_num');

        // 获取安排信息
        $schedule = DB::table('schedule')
                      ->where('schedule_id', $schedule_id)
                      ->first();

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
                    $participant_hour = $request->input('input'.$i.'_2');
                    $participant_amount = $request->input('input'.$i.'_3');
                    $schedule_attended_num = $schedule_attended_num + 1; // 增加正常上课人数
                }else if($participant_attend_status==2){ // 请假（不计课时）
                    $participant_hour = 0;
                    $participant_amount = 0;
                    $schedule_leave_num = $schedule_leave_num + 1; // 增加请假人数
                }else { // 旷课（计课时）
                    $participant_hour = $request->input('input'.$i.'_2');
                    $participant_amount = $request->input('input'.$i.'_3');
                    $schedule_absence_num = $schedule_absence_num + 1; // 增加旷课人数
                }
                // 扣除剩余课时
                if($participant_attend_status!=2){
                    // 获取剩余课时信息
                    $hour = DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->first();
                    // 有正常课时
                    if($hour->hour_remain>0){
                        //正常课时足够
                        if($hour->hour_remain>=$participant_amount){
                            // 扣除学生正常课时
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->decrement('hour_remain', $participant_amount);
                            // 增加已用正常课时数
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->increment('hour_used', $participant_amount);
                        }else{ //正常课时不足
                            // 扣除学生正常课时
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->decrement('hour_remain', $hour->hour_remain);
                            // 增加已用正常课时数
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->increment('hour_used', $hour->hour_remain);
                            // 剩余需扣除赠送课时
                            $participant_free_amount = $participant_amount-$hour->hour_remain;
                            // 扣除学生赠送课时
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->decrement('hour_remain_free', $participant_free_amount);
                            // 增加已用赠送课时数
                            DB::table('hour')
                              ->where('hour_id', $participant_hour)
                              ->increment('hour_used_free', $participant_free_amount);
                        }
                    }else{ // 没有正常课时
                        // 扣除学生正常课时
                        DB::table('hour')
                          ->where('hour_id', $participant_hour)
                          ->decrement('hour_remain_free', $participant_amount);
                        // 增加已用正常课时数
                        DB::table('hour')
                          ->where('hour_id', $participant_hour)
                          ->increment('hour_used_free', $participant_amount);
                    }
                }
                // 添加上课成员表
                DB::table('participant')->insert(
                    ['participant_schedule' => $schedule_id,
                     'participant_student' => $participant_student,
                     'participant_attend_status' => $participant_attend_status,
                     'participant_hour' => $participant_hour,
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
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            // 返回第一步
            return redirect("/operation/schedule/attend/{$schedule_id}")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '学生剩余课时不足，请重新选择',
                         'message' => '学生剩余课时不足，请重新选择']);
        }
        DB::commit();
        // 返回我的上课记录视图
        return redirect("/operation/schedule/attend/{$schedule_id}/result");
    }

    /**
     * 课程考勤提交成功
     * URL: GET /operation/schedule/attend/success
     */
    public function scheduleAttendResult(){
        // 返回课程考勤视图
        return view('operation/scheduleAttendResult');
    }
}
