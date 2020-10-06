<?php
namespace App\Http\Controllers\Market;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class StudentController extends Controller
{

    /**
     * 全部学生视图
     * URL: GET /operation/student
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function student(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/market/student", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
                  ->where('student_contract_num', '>', 0)
                  ->where('student_status', 1);
        // 数据范围权限
        if (Session::get('user_access_self')==1) {
            $rows = $rows->where('student_consultant', '=', Session::get('user_id'));
        }
        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_student" => null,
                        "filter_consultant" => null,
                        "filter_class_adviser" => null,
                    );

        // 客户校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('student_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 客户年级
        if ($request->filled('filter_grade')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter_grade'));
            $filters['filter_grade']=$request->input("filter_grade");
        }
        // 客户名称
        if ($request->filled('filter_student')) {
            $rows = $rows->where('student_id', '=', $request->input('filter_student'));
            $filters['filter_student']=$request->input("filter_student");
        }
        // 课程顾问
        if ($request->filled('filter_consultant')) {
            $rows = $rows->where('student_consultant', '=', $request->input('filter_consultant'));
            $filters['filter_consultant']=$request->input("filter_consultant");
        }
        // 班主任
        if ($request->filled('filter_class_adviser')) {
            $rows = $rows->where('student_class_adviser', '=', $request->input('filter_class_adviser'));
            $filters['filter_class_adviser']=$request->input("filter_class_adviser");
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
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_id AS consultant_id',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_id AS class_adviser_id',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 转为数组并获取学生课时信息
        $students = array();
        foreach($rows as $row){
            $temp = array();
            $temp['student_id']=$row->student_id;
            $temp['student_name']=$row->student_name;
            $temp['student_gender']=$row->student_gender;
            $temp['student_guardian']=$row->student_guardian;
            $temp['department_name']=$row->department_name;
            $temp['grade_name']=$row->grade_name;
            $temp['consultant_id']=$row->consultant_id;
            $temp['consultant_name']=$row->consultant_name;
            $temp['consultant_position_name']=$row->consultant_position_name;
            $temp['class_adviser_id']=$row->class_adviser_id;
            $temp['class_adviser_name']=$row->class_adviser_name;
            $temp['class_adviser_position_name']=$row->class_adviser_position_name;
            $temp['student_hour_num'] = 0;
            $student_hours = array();
            $hours = DB::table('hour')
                       ->join('course', 'hour.hour_course', '=', 'course.course_id')
                       ->where('hour_student', '=', $row->student_id)
                       ->get();
            foreach($hours as $hour){
                $hour_temp = array();
                $hour_temp['course_id']=$hour->course_id;
                $hour_temp['course_name']=$hour->course_name;
                $hour_temp['hour_remain']=$hour->hour_remain;
                $hour_temp['hour_used']=$hour->hour_used;
                $temp['student_hour_num']++;
                $student_hours[] = $hour_temp;
            }
            $temp['student_hours'] = $student_hours;
            $students[] = $temp;
        }

        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_users = DB::table('user')
                          ->join('department', 'user.user_department', '=', 'department.department_id')
                          ->join('position', 'user.user_position', '=', 'position.position_id')
                          ->where('user_status', 1)
                          ->whereIn('user_department', $department_access)
                          ->orderBy('user_department', 'asc')
                          ->orderBy('user_position', 'desc')
                          ->get();
        $filter_students = DB::table('student')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->where('student_status', 1)
                             ->where('student_contract_num', '>', 0)
                             ->whereIn('student_department', $department_access)
                             ->orderBy('student_department', 'asc')
                             ->orderBy('student_grade', 'asc')
                             ->get();
        // 返回列表视图
        return view('market/student/student', ['students' => $students,
                                                  'currentPage' => $currentPage,
                                                  'totalPage' => $totalPage,
                                                  'startIndex' => $offset,
                                                  'request' => $request,
                                                  'filters' => $filters,
                                                  'totalNum' => $totalNum,
                                                  'filter_departments' => $filter_departments,
                                                  'filter_grades' => $filter_grades,
                                                  'filter_students' => $filter_students,
                                                  'filter_users' => $filter_users]);
    }

    public function studentDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/market/student/delete", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取student_id
        $request_ids=$request->input('id');
        $student_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $student_ids[]=decode($request_id, 'student_id');
            }
        }else{
            $student_ids[]=decode($request_ids, 'student_id');
        }
        // 删除数据
        try{
            foreach ($student_ids as $student_id){
                DB::table('student')
                  ->where('student_id', $student_id)
                  ->update(['student_status' => 0]);
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/market/student")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '学生删除失败',
                         'message' => '学生删除失败，错误码:206']);
        }
        // 返回课程列表
        return redirect("/market/student")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '学生删除成功',
                       'message' => '学生删除成功!']);
    }


    /**
     * 签约合同视图
     * URL: POST /market/contract/create
     */
    public function studentContractCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/market/student/contract/create", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
        $courses_same_grade = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', $student->student_grade)
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_status', 1)
                     ->orderBy('course_type', 'asc')
                     ->orderBy('course_time', 'asc')
                     ->get();

        $courses_all_grade = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', 0)
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_status', 1)
                     ->orderBy('course_type', 'asc')
                     ->orderBy('course_time', 'asc')
                     ->get();

        $courses_diff_grade = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->leftJoin('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', "!=", $student->student_grade)
                     ->where('course_grade', "!=", 0)
                     ->whereIn('course_department', [0, $student->student_department])
                     ->where('course_status', 1)
                     ->orderBy('course_grade', 'asc')
                     ->orderBy('course_type', 'asc')
                     ->orderBy('course_time', 'asc')
                     ->get();

        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        return view('market/student/studentContractCreate', ['student' => $student,
                                                                 'hours' => $hours,
                                                                 'courses_same_grade' => $courses_same_grade,
                                                                 'courses_all_grade' => $courses_all_grade,
                                                                 'courses_diff_grade' => $courses_diff_grade,
                                                                 'payment_methods' => $payment_methods]);
    }

    /**
     * 签约合同提交
     * URL: POST /market/contract/store
     * @param  Request  $request
     * @param  $request->input('student_id'): 购课学生
     * @param  $request->input('selected_course_num'): 购买课程数量
     */
    public function studentContractStore(Request $request){
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
                 'contract_section' => 0,
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
                    $hour_average_price = $hour->hour_average_price;
                    $hour_total_price = ($hour_remain+$hour_used)*$hour_average_price;
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
            return redirect("/market/student/contract/create?id=".encode($request_student_id, 'student_id'))
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '购课添加失败',
                         'message' => '购课添加失败，错误码:213']);
        }
        DB::commit();
        // 获取学生、课程名称
        $student_name = DB::table('student')
                          ->where('student_id', $contract_student)
                          ->value('student_name');
        // 返回购课列表
        return redirect("/market/student/contract/success?student_id=".encode($contract_student, 'student_id')."&contract_id=".encode($contract_id, 'contract_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '合同添加成功',
                       'message' => '合同添加成功']);
    }

    public function studentContractSuccess(Request $request){
        return view('market/student/studentContractCreateSuccess', ['student_id' => $request->input('student_id'), 'contract_id' => $request->input('contract_id')]);
    }
}
