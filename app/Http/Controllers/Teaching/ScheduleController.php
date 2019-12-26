<?php
namespace App\Http\Controllers\Teaching;

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
                  ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                  ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                  ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                  ->where('schedule_status', 1);

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

        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($rows->count(), $request, 20);

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
        return view('teaching/schedule/index', ['rows' => $rows,
                                                'currentPage' => $currentPage,
                                                'totalPage' => $totalPage,
                                                'startIndex' => $offset,
                                                'request' => $request,
                                                'filter_departments' => $filter_departments,
                                                'filter_students' => $filter_students,
                                                'filter_classes' => $filter_classes,
                                                'filter_grades' => $filter_grades,
                                                'filter_users' => $filter_users]);
    }

    /**
     * 课程表
     * URL: GET /calendar
     */
    public function calendar(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_schedule = Session::get('user_schedule');
        // 获取表单信息
        $date = date('Y-m-d');
        // 获取一周日期
        $diff=array(6, 0, 1, 2, 3, 4, 5);
        $first_day = date('Y-m-d', strtotime ("-".$diff[date("w",strtotime($date))]." day", strtotime($date)));
        $days = array();
        for($i=0; $i<7; $i++){
            $days[] = date('Y-m-d', strtotime ("+".$i." day", strtotime($first_day)));;
        }
        // 获取课程表信息
        $rows = DB::table('schedule')->get();
        // 获取学生信息(筛选)
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        return view('teaching/schedule/student', ['rows' => $rows,
                                                  'days' => $days,
                                                  'filter_students' => $filter_students]);
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
        return view('teaching/schedule/create');
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
        $schedule_dates_str = $request->input('input1');
        $schedule_start = $request->input('input2');
        $schedule_end = $request->input('input3');
        // 如果输入日期为空返回上一页(可以删除)
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
        // 获取所有学生名单
        $db_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $student_num = count($db_students);
        $students = array();
        for($i=0;$i<$student_num;$i++){
            $students[$db_students[$i]->student_id] = array($db_students[$i]->student_id, $db_students[$i]->student_name, 0);
        }
        // 获取所有班级名单
        $db_classes = DB::table('class')->where('class_status', 1)->orderBy('class_createtime', 'asc')->get();
        $class_num = count($db_classes);
        $classes = array();
        for($i=0; $i<$class_num; $i++){
            $classes[$db_classes[$i]->class_id] = array($db_classes[$i]->class_id, $db_classes[$i]->class_name, 0);
        }
        // 获取所有教师名单
        $db_users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();
        $user_num = count($db_users);
        $users = array();
        for($i=0; $i<$user_num; $i++){
            $users[$db_users[$i]->user_id] = array($db_users[$i]->user_id, $db_users[$i]->user_name, 0);
        }
        // 获取所有教室名单
        $db_classrooms = DB::table('classroom')->where('classroom_status', 1)->orderBy('classroom_createtime', 'asc')->get();
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
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['schedule_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['schedule_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['schedule_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['schedule_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['schedule_status', '=', 1],
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
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_start],
                                  ['schedule_end', '>', $schedule_start],
                                  ['schedule_status', '=', 1],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                              ])
                      ->orWhere([
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '<', $schedule_end],
                                  ['schedule_end', '>', $schedule_end],
                                  ['schedule_status', '=', 1],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '>', $schedule_start],
                                  ['schedule_end', '<', $schedule_end],
                                  ['schedule_status', '=', 1],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_start', '=', $schedule_start],
                                  ['schedule_status', '=', 1],
                                  ['class_status', '=', 1],
                                  ['student_status', '=', 1],
                                  ['user_status', '=', 1],
                                  ['classroom_status', '=', 1]
                                ])
                      ->orWhere([
                                  ['schedule_date', '=', $schedule_dates[$i]],
                                  ['schedule_end', '=', $schedule_end],
                                  ['schedule_status', '=', 1],
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
        return view('teaching/schedule/create2', ['schedule_dates_str' => $schedule_dates_str,
                                                  'schedule_dates' => $schedule_dates,
                                                  'schedule_start' => $schedule_start,
                                                  'schedule_end' => $schedule_end,
                                                  'schedule_time' => $schedule_time,
                                                  'students' => $students,
                                                  'classes' => $classes,
                                                  'users' => $users,
                                                  'classrooms' => $classrooms,
                                                  'subjects' => $subjects]);
    }

    /**
     * 创建新课程安排页面3
     * URL: GET /schedule/create/step3
     * @param  Request  $request
     * @param  $request->input('input1'): 学生/班级
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程科目
     * @param  $request->input('input5'): 上课日期
     * @param  $request->input('input6'): 上课时间
     * @param  $request->input('input7'): 下课时间
     * @param  $request->input('input8'): 课程时长
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
        $schedule_subject = $request->input('input4');
        $schedule_dates_str = $request->input('input5');
        $schedule_start = $request->input('input6');
        $schedule_end = $request->input('input7');
        $schedule_time = $request->input('input8');
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
        return view('teaching/schedule/create3', ['schedule_participant' => $schedule_participant,
                                                  'schedule_participant_name' => $schedule_participant_name,
                                                  'schedule_teacher' => $schedule_teacher,
                                                  'schedule_teacher_name' => $schedule_teacher_name,
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
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程科目
     * @param  $request->input('input5'): 上课日期
     * @param  $request->input('input6'): 上课时间
     * @param  $request->input('input7'): 下课时间
     * @param  $request->input('input8'): 课程时长
     * @param  $request->input('input9'): 课程年级
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
        // 获取当前用户校区ID
        $schedule_department = Session::get('user_department');
        // 获取当前用户ID
        $schedule_createuser = Session::get('user_id');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        // 插入数据库
        try{
            for($i=0; $i<$schedule_date_num; $i++){
                DB::table('schedule')->insert(
                    ['schedule_department' => $schedule_department,
                     'schedule_participant' => $schedule_participant,
                     'schedule_teacher' => $schedule_teacher,
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
            return redirect()->action('Teaching\ScheduleController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程安排添加失败',
                                     'message' => '课程安排添加失败，请联系系统管理员。']);
        }
        // 返回课程安排列表
        return redirect()->action('Teaching\ScheduleController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '课程安排成功',
                                 'message' => '课程安排成功！']);
    }

    /**
     * 显示单个课程安排详细信息
     * URL: GET /schedule/{id}
     * @param  int  $schedule_id
     */
    public function show($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
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
            // 未获取到数据
            return redirect()->action('Teaching\ScheduleController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程安排显示失败',
                                     'message' => '课程安排显示失败，请联系系统管理员']);
        }
        $schedule = $schedule[0];
        return view('teaching/schedule/show', ['schedule' => $schedule]);
    }

    /**
     * 修改单个课程安排
     * URL: GET /schedule/{id}/edit
     * @param  int  $schedule_id
     */
    public function edit($schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $schedule = DB::table('schedule')->where('schedule_id', $schedule_id)->get();
        if($schedule->count()!==1){
            // 未获取到数据
            return redirect()->action('Teaching\ScheduleController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程安排显示失败',
                                     'message' => '课程安排显示失败，请联系系统管理员']);
        }
        $schedule = $schedule[0];
        // 获取校区、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();
        return view('teaching/schedule/edit', ['schedule' => $schedule,
                                              'departments' => $departments,
                                              'grades' => $grades,
                                              'schools' => $schools]);
    }

    /**
     * 修改新课程安排提交数据库
     * URL: PUT /schedule/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 课程安排姓名
     * @param  $request->input('input2'): 课程安排校区
     * @param  $request->input('input3'): 课程安排年级
     * @param  $request->input('input4'): 课程安排性别
     * @param  $request->input('input5'): 课程安排生日
     * @param  $request->input('input6'): 课程安排学校
     * @param  $request->input('input7'): 联系电话
     * @param  int  $schedule_id
     */
    public function update(Request $request, $schedule_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $schedule_name = $request->input('input1');
        $schedule_department = $request->input('input2');
        $schedule_grade = $request->input('input3');
        $schedule_gender = $request->input('input4');
        $schedule_birthday = $request->input('input5');
        $schedule_school = $request->input('input6');
        $schedule_phone = $request->input('input7');
        // 更新数据库
        try{
            DB::table('schedule')
              ->where('schedule_id', $schedule_id)
              ->update(['schedule_name' => $schedule_name,
                        'schedule_department' => $schedule_department,
                        'schedule_grade' => $schedule_grade,
                        'schedule_gender' => $schedule_gender,
                        'schedule_birthday' => $schedule_birthday,
                        'schedule_school' => $schedule_school,
                        'schedule_phone' => $schedule_phone]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/schedule/{$schedule_id}/edit")->with(['notify' => true,
                                                                  'type' => 'danger',
                                                                  'title' => '课程安排修改失败',
                                                                  'message' => '课程安排修改失败，请重新输入信息']);
        }
        return redirect("/schedule")->with(['notify' => true,
                                            'type' => 'success',
                                            'title' => '课程安排修改成功',
                                            'message' => '课程安排修改成功，课程安排名称: '.$schedule_name]);
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
            DB::table('schedule')->where('schedule_id', $schedule_id)->update(['schedule_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('Teaching\ScheduleController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '课程安排删除失败',
                                     'message' => '课程安排删除失败，请联系系统管理员']);
        }
        // 返回课程安排列表
        return redirect()->action('Teaching\ScheduleController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '课程安排删除成功',
                                 'message' => '成员:'.$schedule->student_name.$schedule->class_name.",教师:".$schedule->user_name.",时间:".$schedule->schedule_date." ".date('H:i', strtotime($schedule->schedule_start))."-".date('H:i', strtotime($schedule->schedule_end))]);
    }
}