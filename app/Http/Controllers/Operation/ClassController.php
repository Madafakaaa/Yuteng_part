<?php
namespace App\Http\Controllers\Operation;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ClassController extends Controller
{
    /**
     * 全部班级视图
     * URL: GET /operation/class
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function class(Request $request){
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
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_name" => null,
                        "filter_subject" => null,
                        "filter_teacher" => null,
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
        // 判断是否有搜索条件
        $filter_status = 0;
        // 班级名称
        if ($request->filled('filter_name')) {
            $rows = $rows->where('class_name', 'like', '%'.$request->input('filter_name').'%');
            $filters['filter_name']=$request->input("filter_name");
            $filter_status = 1;
        }
        // 负责教师
        if ($request->filled('filter_teacher')) {
            $rows = $rows->where('class_teacher', '=', $request->input('filter_teacher'));
            $filters['filter_teacher']=$request->input("filter_teacher");
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('class_id', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级、科目信息(筛选)
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

        $members = array();
        $schedules = array();
        foreach($rows as $row){
            //获取班级成员
            $temp_member = array();
            $db_members = DB::table('member')
                            ->join('student', 'member.member_student', '=', 'student.student_id')
                            ->where('member_class', $row->class_id)
                            ->get();
            foreach($db_members as $db_member){
                $temp = array();
                $temp['student_name'] = $db_member->student_name;
                $temp['student_id'] = $db_member->student_id;
                $temp_member[] = $temp;
            }
            $members[] = $temp_member;
            //获取课程安排
            $temp_schedule = array();
            $db_schedules = DB::table('schedule')
                              ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                              ->join('subject', 'schedule.schedule_subject', '=', 'subject.subject_id')
                              ->where('schedule_participant', $row->class_id)
                              ->where('schedule_attended', 0)
                              ->get();
            foreach($db_schedules as $db_schedule){
                $temp = array();
                $temp['schedule_id'] = $db_schedule->schedule_id;
                $temp['schedule_date'] = $db_schedule->schedule_date;
                $temp['schedule_start'] = $db_schedule->schedule_start;
                $temp['schedule_end'] = $db_schedule->schedule_end;
                $temp['user_name'] = $db_schedule->user_name;
                $temp_schedule[] = $temp;
            }
            $schedules[] = $temp_schedule;
        }

        // 返回列表视图
        return view('operation/class/class', ['rows' => $rows,
                                              'members' => $members,
                                              'schedules' => $schedules,
                                              'currentPage' => $currentPage,
                                              'totalPage' => $totalPage,
                                              'startIndex' => $offset,
                                              'request' => $request,
                                              'filters' => $filters,
                                              'totalNum' => $totalNum,
                                              'filter_status' => $filter_status,
                                              'filter_departments' => $filter_departments,
                                              'filter_grades' => $filter_grades,
                                              'filter_subjects' => $filter_subjects,
                                              'filter_users' => $filter_users]);
    }

    /**
     * 新建班级视图
     * URL: GET /operation/class/create
     */
    public function classCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->where('user_cross_teaching', '=', 1)
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->orderBy('user_department', 'asc');
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->join('department', 'user.user_department', '=', 'department.department_id')
                   ->whereIn('user_department', $department_access)
                   ->where('user_cross_teaching', '=', 0)
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->union($users)
                   ->get();
        return view('operation/class/classCreate', ['departments' => $departments,
                                                  'grades' => $grades,
                                                  'subjects' => $subjects,
                                                  'users' => $users]);
    }

    /**
     * 新建班级提交
     * URL: POST /operation/class/store
     * @param  Request  $request
     * @param  $request->input('input1'): 班级名称
     * @param  $request->input('input2'): 班级校区
     * @param  $request->input('input3'): 班级年级
     * @param  $request->input('input4'): 班级科目
     * @param  $request->input('input5'): 负责教师
     * @param  $request->input('input6'): 班级人数
     * @param  $request->input('input7'): 班级备注
     */
    public function classStore(Request $request){
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
            return redirect("/operation/class/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '班级添加失败',
                           'message' => '班级添加失败，错误码:318']);
        }
        // 返回班级列表
        return redirect("/operation/class")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '班级添加成功',
                       'message' => '班级名称: '.$class_name.', 班级学号: '.$class_id]);
    }

    /**
     * 删除班级
     * URL: DELETE /operation/class/delete
     * @param  int  $class_id
     */
    public function classDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取class_id
        $request_ids=$request->input('id');
        $class_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $class_ids[]=decode($request_id, 'class_id');
            }
        }else{
            $class_ids[]=decode($request_ids, 'class_id');
        }
        // 删除数据
        DB::beginTransaction();
        try{
            foreach ($class_ids as $class_id){
                DB::table('class')->where('class_id', $class_id)->update(['class_status' => 0]);
                //删除上课安排
                DB::table('schedule')
                  ->where('schedule_participant', $class_id)
                  ->where('schedule_attended', 0)
                  ->delete();
            }
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/class")->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '班级删除失败',
                                                         'message' => '班级删除失败，错误码:319']);
        }
        DB::commit();
        // 返回班级列表
        return redirect("/operation/class")->with(['notify' => true,
                                                     'type' => 'success',
                                                     'title' => '班级删除成功',
                                                     'message' => '班级删除成功']);
    }

    /**
     * 安排班级课程视图
     * URL: GET /operation/classSchedule/create
     */
    public function classScheduleCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取班级id
        $class_id = decode($request->input('id'), 'class_id');
        // 获取班级信息
        $class = DB::table('class')
                     ->join('department', 'class.class_department', '=', 'department.department_id')
                     ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
                     ->where('class_id', $class_id)
                     ->first();
        // 获取教师名单
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_cross_teaching', '=', 1)
                      ->where('user_department', '<>', $class->class_department)
                      ->where('user_status', 1)
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', $class->class_department)
                      ->where('user_status', 1)
                      ->orderBy('position_level', 'desc')
                      ->union($teachers)
                      ->get();
        // 获取教室名单
        $classrooms = DB::table('classroom')
                        ->where('classroom_department', $class->class_department)
                        ->where('classroom_status', 1)
                        ->orderBy('classroom_id', 'asc')
                        ->get();
        // 获取科目
        $subjects = DB::table('subject')->where('subject_status', 1)->orderBy('subject_id', 'asc')->get();
        // 获取课程
        $courses = DB::table('course')->where('course_grade', $class->class_grade)->where('course_status', 1)->orderBy('course_id', 'asc')->get();
        // 获取年级、科目、用户信息
        return view('operation/class/classScheduleCreate', ['class' => $class,
                                                            'teachers' => $teachers,
                                                            'classrooms' => $classrooms,
                                                            'subjects' => $subjects,
                                                            'courses' => $courses]);
    }

    /**
     * 安排班级课程视图2
     * URL: GET /operation/classSchedule/create2
     * @param  Request  $request
     * @param  $request->input('input_class'): 班级
     * @param  $request->input('input2'): 上课教师
     * @param  $request->input('input3'): 上课教室
     * @param  $request->input('input4'): 课程
     * @param  $request->input('input5'): 科目
     * @param  $request->input('input6'): 上课日期
     * @param  $request->input('input7'): 上课时间
     * @param  $request->input('input8'): 下课时间
     * @param  $request->input('input9'): 课程时长
     */
    public function classScheduleCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取表单输入
        $schedule_class = $request->input('input_class');
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
            return redirect("/operation/class/schedule/create?id=".encode($schedule_class, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '未选择上课规律',
                           'message' => '至少选择一天上课，错误码:320']);
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
        // 判断日期数量是否大于100
        if($schedule_date_num>100){
            return redirect("/operation/class/schedule/create?id=".encode($schedule_class, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请选择重新上课日期',
                           'message' => '上课日期数量过多，超过最大上限100，错误码:321']);
        }
        // 验证日期格式
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/operation/class/schedule/create?id=".encode($schedule_class, 'class_id'))
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '请选择重新上课日期',
                               'message' => '上课日期格式有误，错误码:322']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $schedule_start = date('H:i', strtotime($schedule_start));
        $schedule_end = date('H:i', strtotime($schedule_end));
        if($schedule_start>=$schedule_end){
            return redirect("/operation/class/schedule/create?id=".encode($schedule_class, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前，错误码:323']);
        }
        // 计算课程时长
        $schedule_time = 60*(intval(explode(':', $schedule_end)[0])-intval(explode(':', $schedule_start)[0]))+intval(explode(':', $schedule_end)[1])-intval(explode(':', $schedule_start)[1]);

        // 获取班级信息
        $schedule_class = DB::table('class')
                              ->where('class_id', $schedule_class)
                              ->join('department', 'class.class_department', '=', 'department.department_id')
                              ->join('grade', 'class.class_grade', '=', 'grade.grade_id')
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
        return view('operation/class/classScheduleCreate2', ['schedule_class' => $schedule_class,
                                                             'schedule_teacher' => $schedule_teacher,
                                                             'schedule_course' => $schedule_course,
                                                             'schedule_subject' => $schedule_subject,
                                                             'schedule_classroom' => $schedule_classroom,
                                                             'schedule_dates' => $schedule_dates,
                                                             'schedule_dates_str' => $schedule_dates_str,
                                                             'schedule_start' => $schedule_start,
                                                             'schedule_end' => $schedule_end,
                                                             'schedule_time' => $schedule_time]);
    }

    /**
     * 安排班级课程提交
     * URL: POST /operation/classSchedule/store
     * @param  Request  $request
     * @param  $request->input('input1'): 班级
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
    public function classScheduleStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $schedule_department = $request->input('input_department');
        $schedule_participant = $request->input('input_class');
        $schedule_teacher = $request->input('input_teacher');
        $schedule_classroom = $request->input('input_classroom');
        $schedule_subject = $request->input('input_subject');
        $schedule_dates_str = $request->input('input_dates_str');
        $schedule_start = $request->input('input_start');
        $schedule_end = $request->input('input_end');
        $schedule_time = $request->input('input_time');
        $schedule_grade = $request->input('input_grade');
        $schedule_course = $request->input('input_course');
        // 获取当前用户ID
        $schedule_createuser = Session::get('user_id');
        // 拆分上课日期字符串
        $schedule_dates = explode(',', $schedule_dates_str);
        // 获取所选日期数量
        $schedule_date_num = count($schedule_dates);
        // 获取上课成员类型
        $schedule_participant_type = 0;
        // 插入数据库
        DB::beginTransaction();
        try{
            // 添加课程记录
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
            // 更新班级信息
            DB::table('class')
              ->where('class_id', $schedule_participant)
              ->increment('class_schedule_num', $schedule_date_num);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/class/schedule/create?id=".encode($schedule_participant, 'class_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '班级课程安排失败',
                           'message' => '班级课程安排失败，错误码:324']);
        }
        DB::commit();
        // 返回本校课程安排列表
        return redirect("/operation/class/schedule/success?id=".encode($schedule_participant, 'class_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程安排成功',
                       'message' => '课程安排成功']);
    }

    public function classScheduleCreateSuccess(Request $request){
        return view('operation/class/classScheduleCreateSuccess', ['id' => $request->input('id')]);
    }

}
