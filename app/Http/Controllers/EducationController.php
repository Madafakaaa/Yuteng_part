<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class EducationController extends Controller
{

    /**
     * 全部学生视图
     * URL: GET /education/student/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function studentAll(Request $request){
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
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
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
                              'student.student_guardian_relationship AS student_guardian_relationship',
                              'student.student_phone AS student_phone',
                              'student.student_follow_level AS student_follow_level',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'student.student_customer_status AS student_customer_status',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('education/studentAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 我的学生视图
     * URL: GET /education/student/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function studentMy(Request $request){
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
                  ->where('student_class_adviser', Session::get('user_id'))
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 学生姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
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
                              'student.student_guardian_relationship AS student_guardian_relationship',
                              'student.student_phone AS student_phone',
                              'student.student_follow_level AS student_follow_level',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'student.student_customer_status AS student_customer_status',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('education/studentMy', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 全部班级视图
     * URL: GET /education/class/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function classAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('class')
                  ->join('department', 'class.class_department', '=', 'department.department_id')
                  ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                  ->leftJoin('subject', 'class.class_subject', '=', 'subject.subject_id')
                  ->join('user', 'class.class_teacher', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->whereIn('class_department', $department_access)
                  ->where('class_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('class_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 班级年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 班级科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter4'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('class_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('education/classAll', ['rows' => $rows,
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
     * 删除班级
     * URL: DELETE /operation/class/all/{class_id}
     * @param  int  $class_id
     */
    public function classDelete($class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $class_name = DB::table('class')->where('class_id', $class_id)->value('class_name');
        // 删除数据
        DB::beginTransaction();
        try{
            DB::table('class')->where('class_id', $class_id)->update(['class_status' => 0]);
            //删除上课安排
            DB::table('schedule')
              ->where('schedule_participant', $class_id)
              ->where('schedule_attended', 0)
              ->delete();
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/education/class/all")->with(['notify' => true,
                                                             'type' => 'danger',
                                                             'title' => '班级删除失败',
                                                             'message' => '班级删除失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回班级列表
        return redirect("/education/class/all")->with(['notify' => true,
                                                         'type' => 'success',
                                                         'title' => '班级删除成功',
                                                         'message' => '班级名称: '.$class_name]);
    }

    /**
     * 我的班级视图
     * URL: GET /education/class/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function classMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('class')
                  ->join('department', 'class.class_department', '=', 'department.department_id')
                  ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                  ->leftJoin('subject', 'class.class_subject', '=', 'subject.subject_id')
                  ->join('user', 'class.class_teacher', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->where('class_teacher', Session::get('user_id'))
                  ->where('class_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 班级校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('class_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 班级年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 班级科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter4'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('class_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('education/classMy', ['rows' => $rows,
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
     * 课程安排管理视图
     * URL: GET /education/schedule/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function scheduleAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('schedule')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->whereIn('schedule_department', $department_access)
                  ->where('schedule_attended', '=', 0);
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
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('education/scheduleAll', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_status' => $filter_status,
                                               'filter_departments' => $filter_departments,
                                               'filter_subjects' => $filter_subjects,
                                               'filter_grades' => $filter_grades]);
    }

    /**
     * 我的课程安排视图
     * URL: GET /education/schedule/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function scheduleMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('schedule')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_attended', '=', 0)
                  ->where('schedule_teacher', '=', Session::get('user_id'));
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
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('education/scheduleMy', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_status' => $filter_status,
                                               'filter_departments' => $filter_departments,
                                               'filter_subjects' => $filter_subjects,
                                               'filter_grades' => $filter_grades]);
    }

    /**
     * 上课记录管理视图
     * URL: GET /education/attendedSchedule/my
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
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('education/attendedScheduleAll', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_status' => $filter_status,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_subjects' => $filter_subjects,
                                                       'filter_grades' => $filter_grades]);
    }

    /**
     * 我的上课记录视图
     * URL: GET /education/attendedSchedule/my
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
                  ->where('schedule_teacher', '=', Session::get('user_id'));
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
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、班级、年级、科目信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        // 返回列表视图
        return view('education/attendedScheduleMy', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_status' => $filter_status,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_subjects' => $filter_subjects,
                                                       'filter_grades' => $filter_grades]);
    }

    /**
     * 教案上传视图
     * URL: GET /education/document/create
     */
    public function documentCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取年级、科目信息
        $grades = DB::table('grade')->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->orderBy('subject_id', 'asc')->get();
        return view('education/documentCreate', ['grades' => $grades, 'subjects' => $subjects]);
    }

    /**
     * 教案上传提交
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 档案名称
     * @param  $request->input('input2'): 科目
     * @param  $request->input('input3'): 年级
     * @param  $request->input('input4'): 学期
     * @param  $request->file('file');: 档案文件
     */
    public function documentStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取上传文件
        $file = $request->file('file');
        // 获取文件大小(MB)
        $document_file_size = $file->getClientSize()/1024/1024+0.01;
        // 判断文件是否大于10MB
        if($document_file_size>10){
            return redirect("/education/document/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '教案上传失败',
                           'message' => '文件大于10MB，教案上传失败，请重新选择文件']);
        }
        // 获取文件名称
        $document_file_name = $file->getClientOriginalName();
        // 获取文件扩展名
        $document_ext = $file->getClientOriginalExtension();
        // 生成随机文件名
        $document_path = "D".date('ymdHis').rand(1000000000,9999999999).".".$document_ext;
        // 获取表单输入
        $document_name = $request->input('input1');
        $document_subject = $request->input('input2');
        $document_grade = $request->input('input3');
        $document_semester = $request->input('input4');
        // 插入数据库
        try{
            DB::table('document')->insert(
                ['document_department' => Session::get('user_department'),
                 'document_name' => $document_name,
                 'document_subject' => $document_subject,
                 'document_grade' => $document_grade,
                 'document_semester' => $document_semester,
                 'document_file_name' => $document_file_name,
                 'document_file_size' => $document_file_size,
                 'document_path' => $document_path,
                 'document_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/education/document/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '教案上传失败',
                           'message' => '教案上传失败，请联系系统管理员！']);
        }
        // 上传文件
        $file->move("files/document", $document_path);
        // 返回用户列表
        return redirect("/education/document")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '教案上传成功',
                       'message' => '教案上传成功']);
    }

    /**
     * 教案中心视图
     * URL: GET /education/document
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 用户姓名
     * @param  $request->input('filter2'): 用户校区
     * @param  $request->input('filter3'): 档案名称
     */
    public function document(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据document
        $rows = DB::table('document')
                  ->join('department', 'document.document_department', '=', 'department.department_id')
                  ->join('subject', 'document.document_subject', '=', 'subject.subject_id')
                  ->join('grade', 'document.document_grade', '=', 'grade.grade_id')
                  ->join('user', 'document.document_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id');
        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 教案名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('document_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 年级
        if ($request->filled('filter2')) {
            $rows = $rows->where('document_grade', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 科目
        if ($request->filled('filter3')) {
            $rows = $rows->where('document_subject', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('document_semester', '=', $request->input('filter4'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('document_grade', 'asc')
                     ->orderBy('document_subject', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();

        // 返回列表视图
        return view('education/document', ['rows' => $rows,
                                          'currentPage' => $currentPage,
                                          'totalPage' => $totalPage,
                                          'startIndex' => $offset,
                                          'request' => $request,
                                          'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_subjects' => $filter_subjects,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 下载档案文件
     * URL: GET /education/document/{document_id}
     * @param  int  $document_id
     */
    public function documentDownload($document_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取教案数据信息
        $document = DB::table('document')->where('document_id', $document_id)->first();
        // 获取文件名和路径
        $file_path = "files/document/".$document->document_path;
        $file_ext = explode('.', $document->document_file_name);
        $file_ext = end($file_ext);
        $file_name = $document->document_name.".".$file_ext;
        // 下载文件
        if (file_exists($file_path)) {// 文件存在
            return response()->download($file_path, $file_name ,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
        }else{ // 文件不存在
            return redirect("/education/document")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '档案下载失败',
                         'message' => '档案文件已删除，下载失败']);
        }
    }

    /**
     * 删除教案
     * URL: DELETE /document/{id}
     * @param  int  $document_id
     */
    public function documentDelete($document_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据路径
        $document = DB::table('document')->where('document_id', $document_id)->first();
        $document_path = "files/document/".$document->document_path;
        // 删除数据
        DB::table('document')->where('document_id', $document_id)->delete();
        // 如果文件存在，删除文件
        if (file_exists($document_path)) {
            unlink($document_path);
        }
        // 返回教案视图
        return redirect("/education/document")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '教案删除成功',
                       'message' => '教案删除成功!']);
    }
}
