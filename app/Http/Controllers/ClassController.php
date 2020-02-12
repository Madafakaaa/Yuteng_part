<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ClassController extends Controller
{
    /**
     * 显示所有班级记录
     * URL: GET /class
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function index(Request $request){
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
        return view('class/index', ['rows' => $rows,
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
     * 显示本校班级记录
     * URL: GET /departmentClass
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function department(Request $request){
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
                  ->where('class_department', Session::get('user_department'))
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
        return view('class/department', ['rows' => $rows,
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
     * 显示我的班级记录
     * URL: GET /myClass
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function my(Request $request){
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
        return view('class/my', ['rows' => $rows,
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
     * 创建新班级页面
     * URL: GET /class/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取年级、科目、用户信息
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_cross_teaching', '=', 1)
                   ->where('user_department', '<>', Session::get('user_department'))
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->orderBy('user_department', 'asc');
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_department', '=', Session::get('user_department'))
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->union($users)
                   ->get();
        return view('class/create', ['grades' => $grades,
                                               'subjects' => $subjects,
                                               'users' => $users]);
    }

    /**
     * 创建新班级提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 班级名称
     * @param  $request->input('input2'): 班级校区
     * @param  $request->input('input3'): 班级年级
     * @param  $request->input('input4'): 班级科目
     * @param  $request->input('input5'): 负责教师
     * @param  $request->input('input6'): 班级人数
     * @param  $request->input('input7'): 班级备注
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $class_name = $request->input('input1');
        $class_department = $request->input('input2');
        $class_grade = $request->input('input3');
        $class_subject = $request->input('input4');
        $class_teacher = $request->input('input5');
        $class_max_num = $request->input('input6');
        if($request->filled('input7')) {
            $class_remark = $request->input('input7');
        }else{
            $class_remark = '无';
        }
        // 获取当前用户ID
        $class_createuser = Session::get('user_id');
        // 生成新班级ID
        $class_num = DB::table('class')
                       ->where('class_department', $class_department)
                       ->whereYear('class_createtime', date('Y'))
                       ->whereMonth('class_createtime', date('m'))
                       ->count()+1;
        $class_id = "C".substr(date('Ym'),2).sprintf("%02d", $class_department).sprintf("%03d", $class_num);
        // 插入数据库
        try{
            DB::table('class')->insert(
                ['class_id' => $class_id,
                 'class_name' => $class_name,
                 'class_department' => $class_department,
                 'class_grade' => $class_grade,
                 'class_subject' => $class_subject,
                 'class_teacher' => $class_teacher,
                 'class_max_num' => $class_max_num,
                 'class_remark' => $class_remark,
                 'class_createuser' => $class_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/departmentClass")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '班级添加失败',
                                                         'message' => '班级添加失败，请重新输入信息']);
        }
        // 返回班级列表
        return redirect("/departmentClass")->with(['notify' => true,
                                                     'type' => 'success',
                                                     'title' => '班级添加成功',
                                                     'message' => '班级名称: '.$class_name.', 班级学号: '.$class_id]);
    }

    /**
     * 显示单个班级详细信息
     * URL: GET /class/{id}
     * @param  int  $class_id
     */
    public function show(Request $request, $class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $class = DB::table('class')
                   ->join('department', 'class.class_department', '=', 'department.department_id')
                   ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                   ->join('subject', 'class.class_subject', '=', 'subject.subject_id')
                   ->join('user', 'class.class_teacher', '=', 'user.user_id')
                   ->where('class_id', $class_id)
                   ->get();
        if($class->count()!==1){
            // 未获取到数据
            return redirect()->action('ClassController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '班级显示失败',
                                     'message' => '班级显示失败，请联系系统管理员']);
        }
        $class = $class[0];

        // 获取班级年级
        $student_grade = $class->class_grade;
        $student_department = $class->class_department;

        // 获取成员数据
        $members = DB::table('member')
                  ->join('class', 'member.member_class', '=', 'class.class_id')
                  ->join('student', 'member.member_student', '=', 'student.student_id')
                  ->where('member.member_class', $class_id)
                  ->get();
        // 获取学生信息
        $students = DB::table('student')
                      ->where('student_grade', $student_grade)
                      ->where('student_department', $student_department)
                      ->where('student_customer_status', 1)
                      ->where('student_status', 1)
                      ->orderBy('student_createtime', 'asc')
                      ->get();

        // 获取班级课程表
        // 获取课程表日期
        if ($request->filled('filter1')) {
            $date = $request->input('filter1');
        }else{
            $date = date('Y-m-d');
        }
        // 获取一周日期数组
        $diff = array(6, 0, 1, 2, 3, 4, 5);
        $first_day = date('Y-m-d', strtotime ("-".$diff[date("w",strtotime($date))]." day", strtotime($date)));
        $days = array();
        for($i=0; $i<7; $i++){
            $days[] = date('Y-m-d', strtotime ("+".$i." day", strtotime($first_day)));
        }
        // 获取上周周一日期
        $first_day_prev = date('Y-m-d', strtotime ("-7 day", strtotime($first_day)));
        // 获取下周周一日期
        $first_day_next = date('Y-m-d', strtotime ("+7 day", strtotime($first_day)));
        // 获取反转日期数组
        $days_fliped = array_flip($days);
        // 创建上课时间数组
        $times = array("08:00:00", "08:30:00", "09:00:00", "09:30:00",
                       "10:00:00", "10:30:00", "11:00:00", "11:30:00",
                       "12:00:00", "12:30:00", "13:00:00", "13:30:00",
                       "14:00:00", "14:30:00", "15:00:00", "15:30:00",
                       "16:00:00", "16:30:00", "17:00:00", "17:30:00",
                       "18:00:00", "18:30:00", "19:00:00", "19:30:00",
                       "20:00:00", "20:30:00", "21:00:00", "21:30:00",
                       "22:00:00");
        // 获取反转时间数组
        $times_fliped = array_flip($times);
        // 获取课程表信息
        $schedules = DB::table('schedule')
                       ->join('department', 'schedule.schedule_department', '=', 'department.department_id')
                       ->leftJoin('student', 'schedule.schedule_participant', '=', 'student.student_id')
                       ->leftJoin('class', 'schedule.schedule_participant', '=', 'class.class_id')
                       ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                       ->join('course', 'schedule.schedule_course', '=', 'course.course_id')
                       ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                       ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                       ->join('grade', 'schedule.schedule_grade', '=', 'grade.grade_id')
                       ->join('classroom', 'schedule.schedule_classroom', '=', 'classroom.classroom_id')
                       ->where('schedule_date', '>=', $days[0])
                       ->where('schedule_date', '<=', $days[6])
                       ->where('schedule_participant', '=', $class_id)
                       ->get();
        // 创建课程表数据
        $calendar = array(); // -1：没有课程，-2：已有课程占位，其它：$schedules中index
        for($i=0; $i<29; $i++){
            for($j=0; $j<7; $j++){
                $calendar[$i][$j] = -1;
            }
        }
        // 课程安排插入课程表
        foreach($schedules as $index => $schedule){
            $date_index = $days_fliped[$schedule->schedule_date];
            $start_time_index = $times_fliped[$schedule->schedule_start];
            $end_time_index = $times_fliped[$schedule->schedule_end];
            $calendar[$start_time_index][$date_index] = $index;
            for($i=$start_time_index+1; $i<$end_time_index; $i++){
                $calendar[$i][$date_index] = -2;
            }
        }
        // 日期数字转中文数组
        $numToStr = array('零', '周一', '周二', '周三', '周四', '周五', '周六', '周日');
        // 生成链接url
        $request_url = "?";
        $request_url_prev = $request_url."filter1=".$first_day_prev."&";
        $request_url_today = $request_url."filter1=".date('Y-m-d')."&";
        $request_url_next = $request_url."filter1=".$first_day_next."&";

        return view('class/show', ['class' => $class,
                                   'students' => $students,
                                   'members' => $members,
                                   'schedules' => $schedules,
                                   'calendar' => $calendar,
                                   'days' => $days,
                                   'times' => $times,
                                   'numToStr' => $numToStr,
                                   'request' => $request,
                                   'request_url_prev' => $request_url_prev,
                                   'request_url_today' => $request_url_today,
                                   'request_url_next' => $request_url_next]);
    }

    /**
     * 修改单个班级
     * URL: GET /class/{id}/edit
     * @param  int  $class_id
     */
    public function edit($class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $class = DB::table('class')
                   ->join('department', 'class.class_department', '=', 'department.department_id')
                   ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                   ->where('class_id', $class_id)
                   ->get();
        if($class->count()!==1){
            // 未获取到数据
            return redirect()->action('ClassController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '班级显示失败',
                                     'message' => '班级显示失败，请联系系统管理员']);
        }
        $class = $class[0];
        // 获取年级、科目、用户信息
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_createtime', 'asc')->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_cross_teaching', '=', 1)
                   ->where('user_department', '<>', Session::get('user_department'))
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->orderBy('user_department', 'asc');
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_department', '=', Session::get('user_department'))
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->union($users)
                   ->get();
        return view('class/edit', ['class' => $class,
                                    'grades' => $grades,
                                    'subjects' => $subjects,
                                    'users' => $users]);
    }

    /**
     * 修改新班级提交数据库
     * URL: PUT /class/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 名称
     * @param  $request->input('input2'): 年级
     * @param  $request->input('input3'): 科目
     * @param  $request->input('input4'): 负责教师
     * @param  $request->input('input5'): 最大人数
     * @param  $request->input('input6'): 备注
     * @param  int  $class_id
     */
    public function update(Request $request, $class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $class_name = $request->input('input1');
        $class_grade = $request->input('input2');
        $class_subject = $request->input('input3');
        $class_teacher = $request->input('input4');
        $class_max_num = $request->input('input5');
        if($request->filled('input6')) {
            $class_remark = $request->input('input6');
        }else{
            $class_remark = '无';
        }
        // 更新数据库
        try{
            DB::table('class')
              ->where('class_id', $class_id)
              ->update(['class_name' => $class_name,
                        'class_grade' => $class_grade,
                        'class_subject' => $class_subject,
                        'class_teacher' => $class_teacher,
                        'class_max_num' => $class_max_num,
                        'class_remark' => $class_remark]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/class/{$class_id}/edit")->with(['notify' => true,
                                                              'type' => 'danger',
                                                              'title' => '班级修改失败',
                                                              'message' => '班级修改失败，请重新输入信息']);
        }
        return redirect("/class/{$class_id}")->with(['notify' => true,
                                         'type' => 'success',
                                         'title' => '班级修改成功',
                                         'message' => '班级修改成功，班级名称: '.$class_name]);
    }

    /**
     * 修改班级备注
     * URL: POST /class/{id}/remark
     * @param  Request  $request
     * @param  $request->input('input1'): 班级备注
     * @param  int  $student_id         : 班级id
     */
    public function remark(Request $request, $class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $class_remark = $request->input('input1');
        // 更新数据
        DB::beginTransaction();
        try{
            // 更新学生备注
            DB::table('class')
              ->where('class_id', $class_id)
              ->update(['class_remark' =>  $class_remark]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/class/{$class_id}")->with(['notify' => true,
                                                           'type' => 'danger',
                                                           'title' => '修改班级备注失败',
                                                           'message' => '修改班级备注失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/class/{$class_id}")->with(['notify' => true,
                                                       'type' => 'success',
                                                       'title' => '修改班级备注成功',
                                                       'message' => '修改班级备注成功！']);
    }


    /**
     * 删除班级
     * URL: DELETE /class/{id}
     * @param  int  $class_id
     */
    public function destroy($class_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $class_name = DB::table('class')->where('class_id', $class_id)->value('class_name');
        // 删除数据
        try{
            DB::table('class')->where('class_id', $class_id)->update(['class_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('ClassController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '班级删除失败',
                                     'message' => '班级删除失败，请联系系统管理员']);
        }
        // 返回班级列表
        return redirect()->action('ClassController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '班级删除成功',
                                 'message' => '班级名称: '.$class_name]);
    }
}
