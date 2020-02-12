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
     * 显示所有学生记录
     * URL: GET /student
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 学生名称
     * @param  $request->input('filter2'): 学生校区
     * @param  $request->input('filter3'): 学生年级
     * @param  $request->input('filter4'): 学生学校
     */
    public function index(Request $request){
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
                  ->join('user', 'student.student_follower', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);
        // 添加筛选条件
        // 学生名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 学生学校
        if ($request->filled('filter4')) {
            $rows = $rows->where('student_school', '=', $request->input('filter4'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('student_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、学校信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();

        // 返回列表视图
        return view('student/index', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_departments' => $filter_departments,
                                               'filter_grades' => $filter_grades,
                                               'filter_schools' => $filter_schools]);
    }

    /**
     * 显示本校学生记录
     * URL: GET /departmentStudent
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 学生名称
     * @param  $request->input('filter2'): 学生校区
     * @param  $request->input('filter3'): 学生年级
     * @param  $request->input('filter4'): 学生学校
     */
    public function department(Request $request){
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
                  ->join('user', 'student.student_follower', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 1)
                  ->where('student_department', Session::get('user_department'))
                  ->where('student_status', 1);
        // 添加筛选条件
        // 学生名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 学生学校
        if ($request->filled('filter4')) {
            $rows = $rows->where('student_school', '=', $request->input('filter4'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('student_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、学校信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();

        // 返回列表视图
        return view('student/department', ['rows' => $rows,
                                          'currentPage' => $currentPage,
                                          'totalPage' => $totalPage,
                                          'startIndex' => $offset,
                                          'request' => $request,
                                          'totalNum' => $totalNum,
                                          'filter_departments' => $filter_departments,
                                          'filter_grades' => $filter_grades,
                                          'filter_schools' => $filter_schools]);
    }

    /**
     * 显示所有学生记录
     * URL: GET /myStudent
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 学生名称
     * @param  $request->input('filter2'): 学生校区
     * @param  $request->input('filter3'): 学生年级
     * @param  $request->input('filter4'): 学生学校
     */
    public function my(Request $request){
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
                  ->join('user', 'student.student_follower', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 1)
                  ->where('student_follower', Session::get('user_id'))
                  ->where('student_status', 1);
        // 添加筛选条件
        // 学生名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 学生校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 学生年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 学生学校
        if ($request->filled('filter4')) {
            $rows = $rows->where('student_school', '=', $request->input('filter4'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('student_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、学校信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $filter_schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();

        // 返回列表视图
        return view('student/my', ['rows' => $rows,
                                        'currentPage' => $currentPage,
                                        'totalPage' => $totalPage,
                                        'startIndex' => $offset,
                                        'request' => $request,
                                        'totalNum' => $totalNum,
                                        'filter_departments' => $filter_departments,
                                        'filter_grades' => $filter_grades,
                                        'filter_schools' => $filter_schools]);
    }

    /**
     * 显示单个学生详细信息
     * URL: GET /student/{id}
     * @param  int  $student_id
     */
    public function show(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
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
        $student_department = $student->student_department;

        // 获取学生课程表
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
                       ->where('schedule_participant', '=', $student_id)
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

        // 获取学生动态
        $student_records = DB::table('student_record')
                             ->join('student', 'student_record.student_record_student', '=', 'student.student_id')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->join('user', 'student_record.student_record_createuser', '=', 'user.user_id')
                             ->where('student_record_student', $student_id)
                             ->orderBy('student_record_createtime', 'desc')
                             ->limit(50)
                             ->get();
        $users = DB::table('user')
                   ->where('user_department', $student_department)
                   ->where('user_status', 1)
                   ->orderBy('user_createtime', 'asc')
                   ->get();
        return view('student/show', ['student' => $student,
                                     'users' => $users,
                                     'student_records' => $student_records,
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
        // 获取校区、来源、课程、用户、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
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
     * 修改学生信息
     * URL: PUT /customer/{id}
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
        $student_gender = $request->input('input2');
        $student_grade = $request->input('input3');
        if($request->filled('input4')) {
            $student_school = $request->input('input4');
        }else{
            $student_school = 0;
        }
        $student_guardian = $request->input('input5');
        $student_guardian_relationship = $request->input('input6');
        $student_phone = $request->input('input7');
        if($request->filled('input8')) {
            $student_wechat = $request->input('input8');
        }else{
            $student_wechat = '无';
        }
        $student_source = $request->input('input9');
        $student_birthday = $request->input('input10');
        //  获取学生信息
        $student = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->first();
        $student_follower = $student->student_follower;
        $student_name = $student->student_name;
        // 更新数据库
        DB::beginTransaction();
        try{
            // 更新学生信息
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_name' => $student_name,
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
        // 获取数据信息
        $student = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取学生姓名
        $student_name = $student->student_name;
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
                                                           'message' => '学生名称: '.$student_name]);
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
     * 删除学生
     * URL: DELETE /student/{id}
     * @param  int  $student_id
     */
    public function destroy($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student_name = DB::table('student')->where('student_id', $student_id)->value('student_name');
        // 删除数据
        try{
            DB::table('student')->where('student_id', $student_id)->update(['student_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('StudentController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '学生删除失败',
                                     'message' => '学生删除失败，请联系系统管理员']);
        }
        // 返回学生列表
        return redirect()->action('StudentController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '学生删除成功',
                                 'message' => '学生名称: '.$student_name]);
    }
}
