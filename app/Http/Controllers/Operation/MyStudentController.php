<?php
namespace App\Http\Controllers\Operation;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class MyStudentController extends Controller
{
    /**
     * 我的学生视图
     * URL: GET /operation/student/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function myStudent(Request $request){
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
                  ->where('student_contract_num', '>', 0)
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
        return view('operation/myStudent/myStudent', ['rows' => $rows,
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
     * 签约合同视图
     * URL: POST /operation/contract/create
     */
    public function myStudentContractCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
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
        $courses = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->join('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', $student->student_grade)
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_status', 1)
                     ->orderBy('course_type', 'asc')
                     ->orderBy('course_time', 'asc')
                     ->get();

        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        return view('operation/myStudent/myStudentContractCreate', ['student' => $student,
                                                              'hours' => $hours,
                                                              'courses' => $courses,
                                                              'payment_methods' => $payment_methods]);
    }

    /**
     * 签约合同提交
     * URL: POST /operation/contract/store
     * @param  Request  $request
     * @param  $request->input('student_id'): 购课学生
     * @param  $request->input('selected_course_num'): 购买课程数量
     */
    public function myStudentContractStore(Request $request){
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
                    $hour_cleaned = $hour->hour_cleaned;
                    $hour_average_price = $hour->hour_average_price;
                    $hour_total_price = ($hour_remain+$hour_used+$hour_cleaned)*$hour_average_price;
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
                         'hour_cleaned' => 0,
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
            return redirect("/operation/myStudent/contract/create?student_id={$request_student_id}")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '购课添加失败',
                         'message' => '购课添加失败，请重新添加']);
        }
        DB::commit();
        // 获取学生、课程名称
        $student_name = DB::table('student')
                          ->where('student_id', $contract_student)
                          ->value('student_name');
        // 返回购课列表
        return redirect("/operation/myStudent/contract/success?student_id=".encode($contract_student, 'student_id')."&contract_id=".encode($contract_id, 'contract_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '合同添加成功',
                       'message' => '合同添加成功']);
    }

    public function myStudentContractSuccess(Request $request){
        return view('operation/myStudent/myStudentContractCreateSuccess', ['student_id' => $request->input('student_id'), 'contract_id' => $request->input('contract_id')]);
    }


    /**
     * 安排学生课程视图
     * URL: GET /operation/studentSchedule/create
     */
    public function myStudentScheduleCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
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
                      ->orderBy('user_department', 'asc')
                      ->orderBy('position_level', 'desc');
        $teachers = DB::table('user')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->join('department', 'user.user_department', '=', 'department.department_id')
                      ->where('user_department', '=', $student->student_department)
                      ->where('user_status', 1)
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
        // 获取年级、科目、用户信息
        return view('operation/myStudent/myStudentScheduleCreate', ['student' => $student,
                                                                    'teachers' => $teachers,
                                                                    'classrooms' => $classrooms,
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
    public function myStudentScheduleCreate2(Request $request){
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
            return redirect("/operation/myStudent/schedule/create?id=".encode($schedule_student, 'student_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '未选择上课规律',
                           'message' => '至少选择一天上课，请重新输入！']);
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
            return redirect("/operation/myStudent/schedule/create?id=".encode($schedule_student, 'student_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请选择重新上课日期',
                           'message' => '上课日期数量过多，超过最大上限100节课！']);
        }
        // 验证日期格式
        for($i=0; $i<$schedule_date_num; $i++){
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/", $schedule_dates[$i])){
                return redirect("/operation/myStudent/schedule/create?id=".encode($schedule_student, 'student_id'))
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '请选择重新上课日期',
                               'message' => '上课日期格式有误！']);
            }
        }
        // 如果上课时间不在下课时间之前返回上一页
        $schedule_start = date('H:i', strtotime($schedule_start));
        $schedule_end = date('H:i', strtotime($schedule_end));
        if($schedule_start>=$schedule_end){
            return redirect("/operation/myStudent/schedule/create?id=".encode($schedule_student, 'student_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '请重新选择上课、下课时间',
                           'message' => '上课时间须在下课时间前！']);
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
        $class_name = $schedule_student->student_name." 1v1".$schedule_subject->subject_name;
        return view('operation/myStudent/myStudentScheduleCreate2', ['schedule_student' => $schedule_student,
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
     * URL: POST /operation/myStudentSchedule/store
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
    public function myStudentScheduleStore(Request $request){
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
        // 生成新班级ID
        $class_num = DB::table('class')
                       ->where('class_department', $schedule_department)
                       ->whereYear('class_createtime', date('Y'))
                       ->whereMonth('class_createtime', date('m'))
                       ->count()+1;
        $class_id = "C".substr(date('Ym'),2).sprintf("%02d", $schedule_department).sprintf("%03d", $class_num);
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
            return $e;
            return redirect("/operation/myStudent/schedule/create?id=".encode($schedule_student, 'student_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '学生课程安排失败',
                           'message' => '学生课程安排失败，请联系系统管理员。']);
        }
        DB::commit();
        // 返回本校课程安排列表
        return redirect("/operation/myStudent/schedule/success?id=".encode($schedule_student, 'student_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '课程安排成功',
                       'message' => '课程安排成功']);
    }

    public function myStudentScheduleCreateSuccess(Request $request){
        return view('operation/myStudent/myStudentScheduleCreateSuccess', ['id' => $request->input('id')]);
    }


    /**
     * 插入班级视图
     * URL: GET /operation/myStudent/joinClass
     */
    public function joinClass(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = decode($request->input('student_id'), 'student_id');
        $course_id = decode($request->input('course_id'), 'course_id');
        // 获取学生信息
        $student = DB::table('student')
                      ->join('grade', 'grade.grade_id', '=', 'student.student_grade')
                      ->where('student_id', $student_id)
                      ->first();

        // 获取剩余课时信息
        $hours = DB::table('hour')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->where('hour_student', $student_id)
                  ->get();

        // 获取班级信息
        $classes = DB::table('class')
                      ->join('subject', 'subject.subject_id', '=', 'class.class_subject')
                      ->join('user', 'user.user_id', '=', 'class.class_teacher')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->where('class_department', '=', $student->student_department)
                      ->where('class_grade', '=', $student->student_grade)
                      ->whereColumn('class_current_num', '<', 'class_max_num')
                      ->where('class_status', 1)
                      ->get();
        return view('operation/myStudent/joinClass', ['student' => $student,
                                                     'course_id' => $course_id,
                                                     'hours' => $hours,
                                                     'classes' => $classes]);
    }


    /**
     * 插入班级提交
     * URL: GET /operation/hour/joinClass/store
     */
    public function joinClassStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('input1');
        $course_id = $request->input('input2');
        $class_id = $request->input('input3');
        // 插入数据库
        DB::beginTransaction();
        try{
            // 添加班级成员
            DB::table('member')->insert(
                ['member_class' => $class_id,
                 'member_student' => $student_id,
                 'member_course' => $course_id,
                 'member_createuser' => Session::get('user_id')]
            );
            // 更新班级人数
            DB::table('class')
              ->where('class_id', $class_id)
              ->increment('class_current_num');
            // 插入学生动态
            //
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/myStudent")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '插入班级失败',
                           'message' => '插入班级失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/operation/myStudent")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '插入班级成功',
                      'message' => '插入班级成功']);
    }
}
