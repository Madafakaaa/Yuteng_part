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
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('education/studentAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
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
        // 获取用户信息
        $user_level = Session::get('user_level');
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
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('education/studentMy', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
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

        // 添加筛选条件
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 班级校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('class_department', '=', $request->input('filter2'));
        }
        // 班级年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter3'));
        }
        // 班级科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter4'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();

        // 返回列表视图
        return view('education/classAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades,
                                           'filter_subjects' => $filter_subjects]);
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

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('class')
                  ->join('department', 'class.class_department', '=', 'department.department_id')
                  ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                  ->leftJoin('subject', 'class.class_subject', '=', 'subject.subject_id')
                  ->join('user', 'class.class_teacher', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->where('class_teacher', Session::get('user_id'))
                  ->where('class_status', 1);

        // 添加筛选条件
        // 班级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 班级校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('class_department', '=', $request->input('filter2'));
        }
        // 班级年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('class_grade', '=', $request->input('filter3'));
        }
        // 班级科目
        if ($request->filled('filter4')) {
            $rows = $rows->where('class_subject', '=', $request->input('filter4'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();

        // 返回列表视图
        return view('education/classMy', ['rows' => $rows,
                                          'currentPage' => $currentPage,
                                          'totalPage' => $totalPage,
                                          'startIndex' => $offset,
                                          'request' => $request,
                                          'totalNum' => $totalNum,
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
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('education/scheduleAll', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_students' => $filter_students,
                                                       'filter_classes' => $filter_classes,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_users' => $filter_users]);
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
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('education/scheduleMy', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_departments' => $filter_departments,
                                               'filter_students' => $filter_students,
                                               'filter_classes' => $filter_classes,
                                               'filter_grades' => $filter_grades,
                                               'filter_users' => $filter_users]);
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
        // 计算可用教师和教师
        // 获取所有本校教师名单
        $db_users = DB::table('user')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc');
        $db_users = DB::table('user')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->union($db_users)
                      ->get();
        $user_num = count($db_users);
        $users = array();
        for($i=0; $i<$user_num; $i++){
            $users[$db_users[$i]->user_id] = array($db_users[$i]->user_id, $db_users[$i]->user_name, 0, $db_users[$i]->department_name, $db_users[$i]->department_id);
        }
        // 获取所有教室名单
        $db_classrooms = DB::table('classroom')
                           ->where('classroom_department', Session::get('user_department'))
                           ->where('classroom_status', 1)
                           ->orderBy('classroom_createtime', 'asc')
                           ->get();
        $classroom_num = count($db_classrooms);
        $classrooms = array();
        for($i=0; $i<$classroom_num; $i++){
            $classrooms[$db_classrooms[$i]->classroom_id] = array($db_classrooms[$i]->classroom_id, $db_classrooms[$i]->classroom_name, 0);
        }
        // 获取所选时间已有上课安排的教师、教室
        $rows = DB::table('schedule')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->select('user.user_id', 'classroom.classroom_id')
                  ->where([
                              ['schedule_department', '=', Session::get('user_department')],
                              ['schedule_date', '=', $schedule_date],
                              ['schedule_start', '<', $schedule_start],
                              ['schedule_end', '>', $schedule_start],
                              ['user_status', '=', 1],
                              ['classroom_status', '=', 1]
                          ])
                  ->orWhere([
                              ['schedule_department', '=', Session::get('user_department')],
                              ['schedule_date', '=', $schedule_date],
                              ['schedule_start', '<', $schedule_end],
                              ['schedule_end', '>', $schedule_end],
                              ['user_status', '=', 1],
                              ['classroom_status', '=', 1]
                            ])
                  ->orWhere([
                              ['schedule_department', '=', Session::get('user_department')],
                              ['schedule_date', '=', $schedule_date],
                              ['schedule_start', '>', $schedule_start],
                              ['schedule_end', '<', $schedule_end],
                              ['user_status', '=', 1],
                              ['classroom_status', '=', 1]
                            ])
                  ->orWhere([
                              ['schedule_department', '=', Session::get('user_department')],
                              ['schedule_date', '=', $schedule_date],
                              ['schedule_start', '=', $schedule_start],
                              ['user_status', '=', 1],
                              ['classroom_status', '=', 1]
                            ])
                  ->orWhere([
                              ['schedule_department', '=', Session::get('user_department')],
                              ['schedule_date', '=', $schedule_date],
                              ['schedule_end', '=', $schedule_end],
                              ['user_status', '=', 1],
                              ['classroom_status', '=', 1]
                            ])
                  ->distinct()
                  ->get();
        $row_num = count($rows);
        for($j=0; $j<$row_num; $j++){
            // 教师列表次数加一
            $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
            // 教室列表次数加一
            $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
        }
        return view('education/scheduleAttend', ['schedule' => $schedule,
                                                'users' => $users,
                                                'classrooms' => $classrooms]);
    }

    /**
     * 课程考勤视图2
     * URL: POST /education/schedule/attend/{schedule_id}/step2
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     */
    public function scheduleAttend2(Request $request, $schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_teacher = $request->input('input1');
        $schedule_classroom = $request->input('input2');
        // 获取教师姓名
        $schedule_teacher_name = DB::table('user')
                                   ->select('user_name')
                                   ->where('user_id', $schedule_teacher)
                                   ->first()
                                   ->user_name;
        // 获取教室名称
        $schedule_classroom_name = DB::table('classroom')
                                   ->select('classroom_name')
                                   ->where('classroom_id', $schedule_classroom)
                                   ->first()
                                   ->classroom_name;
        // 获取数据信息
        $schedule = DB::table('schedule')
                      ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                      ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->where('schedule_id', $schedule_id)
                      ->first();
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
        return view('education/scheduleAttend2', ['schedule' => $schedule,
                                                 'schedule_teacher' => $schedule_teacher,
                                                 'schedule_teacher_name' => $schedule_teacher_name,
                                                 'schedule_classroom' => $schedule_classroom,
                                                 'schedule_classroom_name' => $schedule_classroom_name,
                                                 'student_courses' => $student_courses]);
    }

    /**
     * 课程考勤视图3
     * URL: POST /education/schedule/attend/{schedule_id}/step3
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     * @param  $request->input('input3'): 学生人数
     */
    public function scheduleAttend3(Request $request, $schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_teacher = $request->input('input1');
        $schedule_classroom = $request->input('input2');
        $schedule_student_num = $request->input('input3');
        // 获取教师姓名
        $schedule_teacher_name = DB::table('user')
                                   ->select('user_name')
                                   ->where('user_id', $schedule_teacher)
                                   ->first()
                                   ->user_name;
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
                    return redirect("/schedule/attend/{$schedule_id}")->with(['notify' => true,
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
                      ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                      ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                      ->where('schedule_id', $schedule_id)
                      ->first();
        return view('education/scheduleAttend3', ['schedule' => $schedule,
                                                 'schedule_teacher' => $schedule_teacher,
                                                 'schedule_teacher_name' => $schedule_teacher_name,
                                                 'schedule_classroom' => $schedule_classroom,
                                                 'schedule_classroom_name' => $schedule_classroom_name,
                                                 'student_courses' => $student_courses]);
    }

    /**
     * 课程考勤提交
     * URL: POST /education/schedule/attend/{schedule_id}/store
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
        $schedule_teacher = $request->input('input1');
        $schedule_classroom = $request->input('input2');
        $schedule_student_num = $request->input('input3');

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
              ->update(['schedule_teacher' => $schedule_teacher,
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
            return $e;
            // 返回第一步
            return redirect("/education/schedule/attend/{$schedule_id}")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '学生剩余课时不足，请重新选择',
                         'message' => '学生剩余课时不足，请重新选择']);
        }
        DB::commit();
        // 返回我的上课记录视图
        return redirect("/education/attendedSchedule/my")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程考勤成功',
                       'message' => '课程考勤成功！']);
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
                           'course.course_name AS course_name');
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
        }
        // 课程安排学生/班级
        if ($request->filled('filter2')) {
            $rows = $rows->where('schedule_participant', '=', $request->input('filter2'));
        }
        // 课程安排年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('schedule_grade', '=', $request->input('filter3'));
        }
        // 课程安排教师
        if ($request->filled('filter4')) {
            $rows = $rows->where('schedule_teacher', '=', $request->input('filter4'));
        }
        // 课程安排日期
        if ($request->filled('filter5')) {
            $rows = $rows->where('schedule_date', '=', $request->input('filter5'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();

        // 返回列表视图
        return view('education/attendedScheduleAll', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_classes' => $filter_classes,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_users' => $filter_users]);
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
        // 添加筛选条件
        // 课程安排校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('schedule_department', '=', $request->input('filter1'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();
        // 返回列表视图
        return view('education/attendedScheduleMy', ['rows' => $rows,
                                                       'currentPage' => $currentPage,
                                                       'totalPage' => $totalPage,
                                                       'startIndex' => $offset,
                                                       'request' => $request,
                                                       'totalNum' => $totalNum,
                                                       'filter_departments' => $filter_departments,
                                                       'filter_classes' => $filter_classes,
                                                       'filter_grades' => $filter_grades,
                                                       'filter_users' => $filter_users]);
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

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据document
        $rows = DB::table('document')
                  ->join('department', 'document.document_department', '=', 'department.department_id')
                  ->join('subject', 'document.document_subject', '=', 'subject.subject_id')
                  ->join('grade', 'document.document_grade', '=', 'grade.grade_id')
                  ->join('user', 'document.document_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id');

        // 添加筛选条件
        // 用户姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('user_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 用户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('user_department', $request->input('filter2'));
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
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();

        // 返回列表视图
        return view('education/document', ['rows' => $rows,
                                          'currentPage' => $currentPage,
                                          'totalPage' => $totalPage,
                                          'startIndex' => $offset,
                                          'request' => $request,
                                          'totalNum' => $totalNum,
                                          'filter_departments' => $filter_departments]);
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
