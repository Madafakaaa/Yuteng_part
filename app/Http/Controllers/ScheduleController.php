<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ScheduleController extends Controller
{
    /**
     * 显示所有课程安排记录
     * URL: GET /schedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
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
        return view('schedule/index', ['rows' => $rows,
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
     * 显示本校课程安排记录
     * URL: GET /departmentSchedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function department(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                  ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                  ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_attended', '=', 0)
                  ->where('schedule_department', '=', Session::get('user_department'));
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
        return view('schedule/department', ['rows' => $rows,
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
     * 显示我的课程安排记录
     * URL: GET /mySchedule
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 课程安排校区
     * @param  $request->input('filter2'): 课程安排学生/班级
     * @param  $request->input('filter3'): 课程安排年级
     * @param  $request->input('filter4'): 课程安排教师
     * @param  $request->input('filter5'): 课程安排日期
     */
    public function my(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('schedule')
                  ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                  ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                  ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
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
        return view('schedule/my', ['rows' => $rows,
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
     * 创建新课程安排页面
     * URL: GET /schedule/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('schedule/create');
    }

    /**
     * 创建新课程安排页面
     * URL: GET /schedule/createIrregular
     */
    public function createIrregular(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('schedule/createIrregular');
    }

    /**
     * 创建新课程安排页面2
     * URL: GET /schedule/create/step2
     * @param  Request  $request
     * @param  $request->input('input1'): 上课日期
     * @param  $request->input('input2'): 上课时间
     * @param  $request->input('input3'): 下课时间
     */
    public function createStep2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_date_start = $request->input('input1');
        $schedule_date_end = $request->input('input2');
        $schedule_days = $request->input('input3');
        $schedule_start = $request->input('input4');
        $schedule_end = $request->input('input5');
        // 判断Checkbox是否为空
        if(!isset($schedule_days)){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '未选择上课规律',
                                                       'message' => '至少选择一天上课规律，请重新输入！']);
        }
        // 表单输入数据处理
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
        // 如果输入日期为空返回上一页(不会发生)
        if($schedule_date_start>$schedule_date_end){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '开始日期在结束日期之后',
                                                       'message' => '开始日期应在结束日期之前，请重新输入！']);
        }
        // 如果输入日期为空返回上一页(不会发生)
        if($schedule_dates_str==""){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '请选择上课日期',
                                                       'message' => '至少选择一个上课日期！']);
        }
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        if($schedule_date_num>50){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '请选择重新上课日期',
                                                       'message' => '上课日期数量过多，超过最大上限50！']);
        }
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/schedule/create")->with(['notify' => true,
                                                           'type' => 'danger',
                                                           'title' => '请选择重新上课日期',
                                                           'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $time_start = date('H:i', strtotime($schedule_start));
        $time_end = date('H:i', strtotime($schedule_end));
        if($time_start>=$time_end){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '请重新选择上课、下课时间',
                                                       'message' => '上课时间须在下课时间前！']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);
        // 计算可选学生列表
        // 获取所有本校学生名单
        $db_students = DB::table('student')
                         ->where('student_department', Session::get('user_department'))
                         ->where('student_status', 1)
                         ->orderBy('student_createtime', 'asc')
                         ->get();
        $student_num = count($db_students);
        $students = array();
        for($i=0;$i<$student_num;$i++){
            $students[$db_students[$i]->student_id] = array($db_students[$i]->student_id, $db_students[$i]->student_name, 0);
        }
        // 获取所有本校班级名单
        $db_classes = DB::table('class')
                        ->where('class_department', Session::get('user_department'))
                        ->where('class_status', 1)
                        ->orderBy('class_createtime', 'asc')
                        ->get();
        $class_num = count($db_classes);
        $classes = array();
        for($i=0; $i<$class_num; $i++){
            $classes[$db_classes[$i]->class_id] = array($db_classes[$i]->class_id, $db_classes[$i]->class_name, 0);
        }
        // 获取所有本校教师名单
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($db_users)
                      ->get();
        $user_num = count($db_users);
        $users = array();
        for($i=0; $i<$user_num; $i++){
            $users[$db_users[$i]->user_id] = array($db_users[$i]->user_id, $db_users[$i]->user_name, 0, $db_users[$i]->department_name, $db_users[$i]->department_id, $db_users[$i]->position_name);
        }
        // 获取所有本校教室名单
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
        // 获取所选时间已有上课安排的学生、班级、教师、教室
        // 获取所选时间已有一对一上课安排的学生及其班级、教师、教室
        for($i=0; $i<$schedule_date_num; $i++){
            $rows = DB::table('schedule')
                      ->join('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->select('student.student_id', 'user.user_id', 'classroom.classroom_id')
                      ->where([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->distinct()
                      ->get();
            $row_num = count($rows);
            for($j=0; $j<$row_num; $j++){
                // 学生列表次数加一
                $students[$rows[$j]->student_id][2]=$students[$rows[$j]->student_id][2]+1;
                // 学生的班级次数加一
                $student_classes = DB::table('student')
                                     ->join('member', 'student.student_id', '=', 'member.member_student')
                                     ->join('class', 'member.member_class', '=', 'class.class_id')
                                     ->select('class.class_id')
                                     ->where('student.student_id', '=', $rows[$j]->student_id)
                                     ->get();
                $student_class_num = count($student_classes);
                for($k=0; $k<$student_class_num; $k++){
                    $classes[$student_classes[$k]->class_id][2]=$classes[$student_classes[$k]->class_id][2]+1;
                }
                // 教师列表次数加一
                $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
                // 教室列表次数加一
                $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
            }
        }
        // 获取所选时间已有班级上课安排的学生及其班级、班级、教师、教室
        for($i=0; $i<$schedule_date_num; $i++){
            $rows = DB::table('schedule')
                      ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('member', 'class.class_id', '=', 'member.member_class')
                      ->join('student', 'member.member_student', '=', 'student.student_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->select('class.class_id', 'student.student_id', 'user.user_id', 'classroom.classroom_id')
                      ->where([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->distinct()
                      ->get();
            $row_num = count($rows);
            for($j=0; $j<$row_num; $j++){
                // 学生列表次数加一
                $students[$rows[$j]->student_id][2]=$students[$rows[$j]->student_id][2]+1;
                // 学生的班级次数加一
                $student_classes = DB::table('student')
                                     ->join('member', 'student.student_id', '=', 'member.member_student')
                                     ->join('class', 'member.member_class', '=', 'class.class_id')
                                     ->select('class.class_id')
                                     ->where('student.student_id', '=', $rows[$j]->student_id)
                                     ->get();
                $student_class_num = count($student_classes);
                for($k=0; $k<$student_class_num; $k++){
                    $classes[$student_classes[$k]->class_id][2]=$classes[$student_classes[$k]->class_id][2]+1;
                }
                // 班级列表次数加一
                $classes[$rows[$j]->class_id][2]=$classes[$rows[$j]->class_id][2]+1;
                // 教师列表次数加一
                $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
                // 教室列表次数加一
                $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
            }
        }
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        return view('schedule/create2', ['schedule_dates_str' => $schedule_dates_str,
                                         'schedule_dates' => $schedule_dates,
                                         'schedule_start' => $schedule_start,
                                         'schedule_end' => $schedule_end,
                                         'schedule_time' => $schedule_time,
                                         'students' => $students,
                                         'classes' => $classes,
                                         'users' => $users,
                                         'classrooms' => $classrooms,
                                         'subjects' => $subjects,
                                         'courses' => $courses]);
    }

    /**
     * 创建新课程安排页面2
     * URL: GET /schedule/create/step2
     * @param  Request  $request
     * @param  $request->input('input1'): 上课日期
     * @param  $request->input('input2'): 上课时间
     * @param  $request->input('input3'): 下课时间
     */
    public function createStep2Irregular(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_dates_str = $request->input('input1');
        $schedule_start = $request->input('input2');
        $schedule_end = $request->input('input3');
        // 如果输入日期为空返回上一页(不会发生)
        if($schedule_dates_str==""){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '请选择上课日期',
                                                       'message' => '至少选择一个上课日期！']);
        }
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        if($schedule_date_num>50){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '请选择重新上课日期',
                                                       'message' => '上课日期数量过多，超过最大上限50！']);
        }
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/schedule/create")->with(['notify' => true,
                                                           'type' => 'danger',
                                                           'title' => '请选择重新上课日期',
                                                           'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $time_start = date('H:i', strtotime($schedule_start));
        $time_end = date('H:i', strtotime($schedule_end));
        if($time_start>=$time_end){
            return redirect("/schedule/create")->with(['notify' => true,
                                                       'type' => 'danger',
                                                       'title' => '请重新选择上课、下课时间',
                                                       'message' => '上课时间须在下课时间前！']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);
        // 计算可选学生列表
        // 获取所有本校学生名单
        $db_students = DB::table('student')
                         ->where('student_department', Session::get('user_department'))
                         ->where('student_status', 1)
                         ->orderBy('student_createtime', 'asc')
                         ->get();
        $student_num = count($db_students);
        $students = array();
        for($i=0;$i<$student_num;$i++){
            $students[$db_students[$i]->student_id] = array($db_students[$i]->student_id, $db_students[$i]->student_name, 0);
        }
        // 获取所有本校班级名单
        $db_classes = DB::table('class')
                        ->where('class_department', Session::get('user_department'))
                        ->where('class_status', 1)
                        ->orderBy('class_createtime', 'asc')
                        ->get();
        $class_num = count($db_classes);
        $classes = array();
        for($i=0; $i<$class_num; $i++){
            $classes[$db_classes[$i]->class_id] = array($db_classes[$i]->class_id, $db_classes[$i]->class_name, 0);
        }
        // 获取所有本校教师名单
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $db_users = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', Session::get('user_department'))
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($db_users)
                      ->get();
        $user_num = count($db_users);
        $users = array();
        for($i=0; $i<$user_num; $i++){
            $users[$db_users[$i]->user_id] = array($db_users[$i]->user_id, $db_users[$i]->user_name, 0, $db_users[$i]->department_name, $db_users[$i]->department_id, $db_users[$i]->position_name);
        }
        // 获取所有本校教室名单
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
        // 获取所选时间已有上课安排的学生、班级、教师、教室
        // 获取所选时间已有一对一上课安排的学生及其班级、教师、教室
        for($i=0; $i<$schedule_date_num; $i++){
            $rows = DB::table('schedule')
                      ->join('student', 'schedule.schedule_participant', '=', 'student.student_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->select('student.student_id', 'user.user_id', 'classroom.classroom_id')
                      ->where([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->distinct()
                      ->get();
            $row_num = count($rows);
            for($j=0; $j<$row_num; $j++){
                // 学生列表次数加一
                $students[$rows[$j]->student_id][2]=$students[$rows[$j]->student_id][2]+1;
                // 学生的班级次数加一
                $student_classes = DB::table('student')
                                     ->join('member', 'student.student_id', '=', 'member.member_student')
                                     ->join('class', 'member.member_class', '=', 'class.class_id')
                                     ->select('class.class_id')
                                     ->where('student.student_id', '=', $rows[$j]->student_id)
                                     ->get();
                $student_class_num = count($student_classes);
                for($k=0; $k<$student_class_num; $k++){
                    $classes[$student_classes[$k]->class_id][2]=$classes[$student_classes[$k]->class_id][2]+1;
                }
                // 教师列表次数加一
                $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
                // 教室列表次数加一
                $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
            }
        }
        // 获取所选时间已有班级上课安排的学生及其班级、班级、教师、教室
        for($i=0; $i<$schedule_date_num; $i++){
            $rows = DB::table('schedule')
                      ->join('class', 'schedule.schedule_participant', '=', 'class.class_id')
                      ->join('member', 'class.class_id', '=', 'member.member_class')
                      ->join('student', 'member.member_student', '=', 'student.student_id')
                      ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                      ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                      ->select('class.class_id', 'student.student_id', 'user.user_id', 'classroom.classroom_id')
                      ->where([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_department', '=', Session::get('user_department')],
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->distinct()
                      ->get();
            $row_num = count($rows);
            for($j=0; $j<$row_num; $j++){
                // 学生列表次数加一
                $students[$rows[$j]->student_id][2]=$students[$rows[$j]->student_id][2]+1;
                // 学生的班级次数加一
                $student_classes = DB::table('student')
                                     ->join('member', 'student.student_id', '=', 'member.member_student')
                                     ->join('class', 'member.member_class', '=', 'class.class_id')
                                     ->select('class.class_id')
                                     ->where('student.student_id', '=', $rows[$j]->student_id)
                                     ->get();
                $student_class_num = count($student_classes);
                for($k=0; $k<$student_class_num; $k++){
                    $classes[$student_classes[$k]->class_id][2]=$classes[$student_classes[$k]->class_id][2]+1;
                }
                // 班级列表次数加一
                $classes[$rows[$j]->class_id][2]=$classes[$rows[$j]->class_id][2]+1;
                // 教师列表次数加一
                $users[$rows[$j]->user_id][2]=$users[$rows[$j]->user_id][2]+1;
                // 教室列表次数加一
                $classrooms[$rows[$j]->classroom_id][2]=$classrooms[$rows[$j]->classroom_id][2]+1;
            }
        }
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        return view('schedule/create2', ['schedule_dates_str' => $schedule_dates_str,
                                         'schedule_dates' => $schedule_dates,
                                         'schedule_start' => $schedule_start,
                                         'schedule_end' => $schedule_end,
                                         'schedule_time' => $schedule_time,
                                         'students' => $students,
                                         'classes' => $classes,
                                         'users' => $users,
                                         'classrooms' => $classrooms,
                                         'subjects' => $subjects,
                                         'courses' => $courses]);
    }

    /**
     * 创建新课程安排页面3
     * URL: GET /schedule/create/step3
     * @param  Request  $request
     * @param  $request->input('input1'): 学生/班级
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程
     * @param  $request->input('input5'): 科目
     * @param  $request->input('input6'): 上课日期
     * @param  $request->input('input7'): 上课时间
     * @param  $request->input('input8'): 下课时间
     * @param  $request->input('input9'): 课程时长
     */
    public function createStep3(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_participant = $request->input('input1');
        $schedule_teacher = $request->input('input2');
        $schedule_classroom = $request->input('input3');
        $schedule_course = $request->input('input4');
        $schedule_subject = $request->input('input5');
        $schedule_dates_str = $request->input('input6');
        $schedule_start = $request->input('input7');
        $schedule_end = $request->input('input8');
        $schedule_time = $request->input('input9');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取学生或班级名称
        $student_name = DB::table('student')
                          ->select('student_name AS schedule_participant_name')
                          ->where('student_id', $schedule_participant);
        $class_name = DB::table('class')
                        ->select('class_name AS schedule_participant_name')
                        ->where('class_id', $schedule_participant);
        $schedule_participant_name = $student_name->union($class_name)->first()->schedule_participant_name;
        // 获取教师姓名
        $schedule_teacher_name = DB::table('user')
                                   ->select('user_name')
                                   ->where('user_id', $schedule_teacher)
                                   ->first()
                                   ->user_name;
        // 获取课程名称
        $schedule_course_name = DB::table('course')
                                   ->select('course_name')
                                   ->where('course_id', $schedule_course)
                                   ->first()
                                   ->course_name;
        // 获取科目名称
        $schedule_subject_name = DB::table('subject')
                                   ->select('subject_name')
                                   ->where('subject_id', $schedule_subject)
                                   ->first()
                                   ->subject_name;
        // 获取学生或班级年级
        $student_grade = DB::table('student')
                           ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                           ->select('grade.grade_id', 'grade.grade_name')
                           ->where('student_id', $schedule_participant);
        $class_grade = DB::table('class')
                         ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                         ->select('grade.grade_id', 'grade.grade_name')
                         ->where('class_id', $schedule_participant);
        $grade = $student_grade->union($class_grade)->first();
        $schedule_grade = $grade->grade_id;
        $schedule_grade_name = $grade->grade_name;
        // 获取教室名称
        $schedule_classroom_name = DB::table('classroom')
                                     ->select('classroom_name')
                                     ->where('classroom_id', $schedule_classroom)
                                     ->first()
                                     ->classroom_name;
        return view('schedule/create3', ['schedule_participant' => $schedule_participant,
                                         'schedule_participant_name' => $schedule_participant_name,
                                         'schedule_teacher' => $schedule_teacher,
                                         'schedule_teacher_name' => $schedule_teacher_name,
                                         'schedule_course' => $schedule_course,
                                         'schedule_course_name' => $schedule_course_name,
                                         'schedule_subject' => $schedule_subject,
                                         'schedule_subject_name' => $schedule_subject_name,
                                         'schedule_grade' => $schedule_grade,
                                         'schedule_grade_name' => $schedule_grade_name,
                                         'schedule_classroom' => $schedule_classroom,
                                         'schedule_classroom_name' => $schedule_classroom_name,
                                         'schedule_dates' => $schedule_dates,
                                         'schedule_dates_str' => $schedule_dates_str,
                                         'schedule_start' => $schedule_start,
                                         'schedule_end' => $schedule_end,
                                         'schedule_time' => $schedule_time]);
    }

    /**
     * 创建新课程安排提交数据库
     * URL: POST
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
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_participant = $request->input('input1');
        $schedule_teacher = $request->input('input2');
        $schedule_classroom = $request->input('input3');
        $schedule_subject = $request->input('input4');
        $schedule_dates_str = $request->input('input5');
        $schedule_start = $request->input('input6');
        $schedule_end = $request->input('input7');
        $schedule_time = $request->input('input8');
        $schedule_grade = $request->input('input9');
        $schedule_course = $request->input('input10');
        // 获取当前用户校区ID
        $schedule_department = Session::get('user_department');
        // 获取当前用户ID
        $schedule_createuser = Session::get('user_id');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        // 获取上课成员类型
        $schedule_participant_type = 0;
        if(substr($schedule_participant, 0, 1)=="C"){
            $schedule_participant_type = 1;
        }
        // 插入数据库
        DB::beginTransaction();
        try{
            for($i=0; $i<$schedule_date_num; $i++){
                DB::table('schedule')->insert(
                    ['schedule_department' => $schedule_department,
                     'schedule_participant' => $schedule_participant,
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
            return redirect("/departmentSchedule")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '课程安排添加失败',
                                                         'message' => '课程安排添加失败，请联系系统管理员。']);
        }
        DB::commit();
        // 返回本校课程安排列表
        return redirect("/departmentSchedule")->with(['notify' => true,
                                                     'type' => 'success',
                                                     'title' => '课程安排成功',
                                                     'message' => '课程安排成功！']);
    }

    /**
     * 显示单个课程安排详细信息
     * URL: GET /schedule/{id}
     * @param  int  $schedule_id
     */
    public function attend($schedule_id){
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
                      ->get();
        if($schedule->count()!==1){
            // 数据数量不为1
            return redirect()->action('ScheduleController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程考勤失败',
                                     'message' => '课程考勤失败，请联系系统管理员']);
        }
        $schedule = $schedule[0];
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
        // 获取所选时间已有上课安排的学生、班级、教师、教室
        // 获取所选时间已有一对一上课安排的学生及其班级、教师、教室
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
        return view('schedule/attend', ['schedule' => $schedule,
                                        'users' => $users,
                                        'classrooms' => $classrooms]);
    }

    /**
     * 填写上课成员详细信息页面2
     * URL: POST /schedule/{schedule_id}/attend/step2
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     */
    public function attendStep2(Request $request, $schedule_id){
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
                      ->get();
        if($schedule->count()!==1){
            // 数据数量不为1
            return redirect()->action('ScheduleController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程安排显示失败',
                                     'message' => '课程安排显示失败，请联系系统管理员']);
        }
        $schedule = $schedule[0];
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
        return view('schedule/attend2', ['schedule' => $schedule,
                                         'schedule_teacher' => $schedule_teacher,
                                         'schedule_teacher_name' => $schedule_teacher_name,
                                         'schedule_classroom' => $schedule_classroom,
                                         'schedule_classroom_name' => $schedule_classroom_name,
                                         'student_courses' => $student_courses]);
    }

    /**
     * 确认考勤信息页面
     * URL: POST /schedule/{schedule_id}/attend/step3
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     * @param  $request->input('input3'): 学生人数
     */
    public function attendStep3(Request $request, $schedule_id){
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
            if($participant_attend_status==0){
                $student_courses[] = array($participant_student, $participant_attend_status, 0, 0, $student_name, "无");
                continue;
            }
            $participant_hour = $request->input('input'.$i.'_2');
            $participant_amount = $request->input('input'.$i.'_3');
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
                      ->get();
        if($schedule->count()!==1){
            // 数据数量不为1
            return redirect()->action('ScheduleController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程考勤失败',
                                     'message' => '课程考勤失败，请联系系统管理员']);
        }
        $schedule = $schedule[0];
        return view('schedule/attend3', ['schedule' => $schedule,
                                         'schedule_teacher' => $schedule_teacher,
                                         'schedule_teacher_name' => $schedule_teacher_name,
                                         'schedule_classroom' => $schedule_classroom,
                                         'schedule_classroom_name' => $schedule_classroom_name,
                                         'student_courses' => $student_courses]);
    }

    /**
     * 提交考勤信息
     * URL: POST /schedule/{schedule_id}/attend/step4
     * @param  int  $schedule_id
     * @param  Request  $request
     * @param  $request->input('input1'): 任课教师
     * @param  $request->input('input2'): 上课教室
     * @param  $request->input('input3'): 学生人数
     */
    public function attendStep4(Request $request, $schedule_id){
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

        // 获取上传文件
        $file = $request->file('file1');
        // 获取文件大小(MB)
        $document_file_size = $file->getClientSize()/1024/1024+0.01;
        // 判断文件是否大于20MB
        if($document_file_size>20){
            // 返回第一步
            return redirect("/schedule/attend/{$schedule_id}")->with(['notify' => true,
                                                                     'type' => 'danger',
                                                                     'title' => '教案上传失败',
                                                                     'message' => '教案文件大于20MB，请重新上传文件']);
        }
        // 获取文件名称
        $document_file_name = $file->getClientOriginalName();
        // 获取文件扩展名
        $document_file_ext = $file->getClientOriginalExtension();
        // 生成随机文件名
        $document_path = "D".date('ymdHis').rand(1000000000,9999999999).".".$document_file_ext;

        // 统计上课人数
        $schedule_attended_num = 0; // 正常
        $schedule_leave_num = 0; // 请假
        $schedule_absence_num = 0; // 旷课

        DB::beginTransaction();
        try{
            $document_id = DB::table('document')->insertGetId(
                ['document_department' => $schedule->schedule_department,
                 'document_grade' => $schedule->schedule_grade,
                 'document_subject' => $schedule->schedule_subject,
                 'document_file_name' => $document_file_name,
                 'document_file_size' => $document_file_size,
                 'document_path' => $document_path,
                 'document_createuser' => $schedule_teacher]
            );
            for($i=1;$i<=$schedule_student_num;$i++){
                $participant_student = $request->input('input'.$i.'_0');
                $participant_attend_status = $request->input('input'.$i.'_1');
                if($participant_attend_status==0){ // 请假（不计课时）
                    $participant_hour = 0;
                    $participant_amount = 0;
                    $schedule_leave_num = $schedule_leave_num + 1; // 增加请假人数
                }else if($participant_attend_status==1){ // 正常（计课时）
                    $participant_hour = $request->input('input'.$i.'_2');
                    $participant_amount = $request->input('input'.$i.'_3');
                    $schedule_attended_num = $schedule_attended_num + 1; // 增加正常上课人数
                }else{ // 旷课（计课时）
                    $participant_hour = $request->input('input'.$i.'_2');
                    $participant_amount = $request->input('input'.$i.'_3');
                    $schedule_absence_num = $schedule_absence_num + 1; // 增加旷课人数
                }
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
                        'schedule_document' => $document_id,
                        'schedule_attended' => 1,
                        'schedule_attended_user' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            // 返回第一步
            return redirect("/schedule/attend/{$schedule_id}")->with(['notify' => true,
                                                                     'type' => 'danger',
                                                                     'title' => '学生剩余课时不足，请重新选择',
                                                                     'message' => '学生剩余课时不足，请重新选择']);
        }
        DB::commit();
        // 上传文件
        $file->move("files/document", $document_path);
        // 返回课程安排列表
        return redirect("/departmentSchedule")->with(['notify' => true,
                                                     'type' => 'success',
                                                     'title' => '课程考勤成功',
                                                     'message' => '课程考勤成功！']);
    }

    /**
     * 删除课程安排
     * URL: DELETE /schedule/{id}
     * @param  int  $schedule_id
     */
    public function destroy($schedule_id){
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
        // 删除数据
        try{
            DB::table('schedule')->where('schedule_id', $schedule_id)->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/departmentSchedule")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '课程安排删除失败',
                                                         'message' => '课程安排删除失败，请联系系统管理员']);
        }
        return redirect("/departmentSchedule")->with(['notify' => true,
                                                     'type' => 'success',
                                                     'title' => '课程安排删除成功',
                                                     'message' => '成员:'.$schedule->student_name.$schedule->class_name.",教师:".$schedule->user_name.",时间:".$schedule->schedule_date." ".date('H:i', strtotime($schedule->schedule_start))."-".date('H:i', strtotime($schedule->schedule_end))]);
    }
}
