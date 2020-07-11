<?php
namespace App\Http\Controllers\Market;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class MyCustomerController extends Controller
{
    /**
     * 我的客户视图
     * URL: GET /market/customer/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function myCustomer(Request $request){
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
                  ->where('student_consultant', Session::get('user_id'))
                  ->where('student_contract_num', 0)
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 客户优先级
        if ($request->filled('filter4')) {
            $rows = $rows->where('student_follow_level', '=', $request->input('filter4'));
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
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        // 返回列表视图
        return view('market/myCustomer/myCustomer', ['rows' => $rows,
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
     * 我的客户录入视图
     * URL: GET /market/myCustomer/create
     */
    public function myCustomerCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取校区、来源、用户、年级信息
        $departments = DB::table('department')
                         ->where('department_status', 1)
                         ->where('department_id', Session::get('user_department'))
                         ->orderBy('department_id', 'asc')
                         ->get();
        $sources = DB::table('source')
                     ->where('source_status', 1)
                     ->orderBy('source_id', 'asc')
                     ->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->whereIn('user_department', $department_access)
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->get();
        $grades = DB::table('grade')
                    ->where('grade_status', 1)
                    ->orderBy('grade_id', 'asc')->get();
        $schools = DB::table('school')
                     ->whereIn('school_department', $department_access)
                     ->where('school_status', 1)
                     ->orderBy('school_id', 'asc')
                     ->get();
        return view('market/myCustomer/myCustomerCreate', ['departments' => $departments,
                                                'sources' => $sources,
                                                'users' => $users,
                                                'schools' => $schools,
                                                'grades' => $grades]);
    }

    /**
     * 我的客户录入提交
     * URL: POST /market/myCustomer/create
     * @param  Request  $request
     * @param  $request->input('input0'): 校区
     * @param  $request->input('input1'): 负责人
     * @param  $request->input('input2'): 学生姓名
     * @param  $request->input('input3'): 学生性别
     * @param  $request->input('input4'): 学生年级
     * @param  $request->input('input5'): 公立学校
     * @param  $request->input('input6'): 监护人姓名
     * @param  $request->input('input7'): 监护人关系
     * @param  $request->input('input8'): 联系电话
     * @param  $request->input('input9'): 微信号
     * @param  $request->input('input10'): 来源类型
     * @param  $request->input('input11'): 学生生日
     * @param  $request->input('input12'): 跟进优先级
     * @param  $request->input('input13'): 备注
     */
    public function myCustomerStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_department = $request->input('input0');
        if($request->filled('input1')) {
            $student_consultant = $request->input('input1');
        }else{
            $student_consultant = '';
        }
        $student_name = $request->input('input2');
        $student_gender = $request->input('input3');
        $student_grade = $request->input('input4');
        if($request->filled('input5')) {
            $student_school = $request->input('input5');
        }else{
            $student_school = 0;
        }
        $student_guardian = $request->input('input6');
        $student_guardian_relationship = $request->input('input7');
        $student_phone = $request->input('input8');
        if($request->filled('input9')) {
            $student_wechat = $request->input('input9');
        }else{
            $student_wechat = '无';
        }
        $student_source = $request->input('input10');
        $student_birthday = $request->input('input11');
        $student_follow_level = $request->input('input12');
        if($request->filled('input13')) {
            $student_remark = $request->input('input13');
        }else{
            $student_remark = '无';
        }
        // 获取当前用户ID
        $student_createuser = Session::get('user_id');
        // 生成新学生ID
        // 获取本校区本月学生数量
        $student_num = DB::table('student')
                         ->where('student_id', 'like', "S".substr(date('Ym'),2).sprintf("%02d", $student_department)."%")
                         ->count();
        if($student_num>900){
            return redirect("/market/myCustomer/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '客户添加失败',
                           'message' => '本校本月已经添加超过900学生，超出本月上限，错误码:209']);
        }
        if($student_num==0){
            $student_id = "S".substr(date('Ym'),2).sprintf("%02d", $student_department).sprintf("%03d", 1);
        }else{
            //获取上一个学生学号
            $pre_student_id = DB::table('student')
                                ->where('student_id', 'like', "S".substr(date('Ym'),2).sprintf("%02d", $student_department)."%")
                                ->orderBy('student_id', 'desc')
                                ->limit(1)
                                ->first();
            $new_student_num = intval(substr($pre_student_id->student_id , 7 , 10))+1;
            $student_id = "S".substr(date('Ym'),2).sprintf("%02d", $student_department).sprintf("%03d", $new_student_num);
        }
        // 获取课程顾问姓名
        $consultant_name = "无 (公共)";
        if($student_consultant!=''){
            $consultant_name = DB::table('user')
                               ->where('user_id', $student_consultant)
                               ->value('user_name');
        }
        // 插入数据库
        DB::beginTransaction();
        try{
            DB::table('student')->insert(
                ['student_id' => $student_id,
                 'student_name' => $student_name,
                 'student_department' => $student_department,
                 'student_grade' => $student_grade,
                 'student_gender' => $student_gender,
                 'student_birthday' => $student_birthday,
                 'student_school' => $student_school,
                 'student_guardian' => $student_guardian,
                 'student_guardian_relationship' => $student_guardian_relationship,
                 'student_phone' => $student_phone,
                 'student_wechat' => $student_wechat,
                 'student_consultant' => $student_consultant,
                 'student_class_adviser' => '',
                 'student_source' => $student_source,
                 'student_follow_level' => $student_follow_level,
                 'student_remark' => $student_remark,
                 'student_last_follow_date' => date('Y-m-d'),
                 'student_createuser' => $student_createuser]
            );
            // 插入学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '新建档案',
                 'student_record_content' => "新建学生档案。新建人：".Session::get('user_name')."。课程顾问：".$consultant_name."。",
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/market/myCustomer/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '客户添加失败',
                           'message' => '客户添加失败，该学生已经存在于本校区，错误码:210']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/market/myCustomer/success?id=".encode($student_id, 'student_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '客户添加成功',
                       'message' => '客户添加成功']);
    }

    public function myCustomerSuccess(Request $request){
        return view('market/myCustomer/myCustomerCreateSuccess', ['id' => $request->input('id')]);
    }

    public function myCustomerDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
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
                  ->delete();
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/market/myCustomer")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '客户删除失败',
                         'message' => '客户删除失败，请联系系统管理员，错误码:211']);
        }
        // 返回课程列表
        return redirect("/market/myCustomer")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '客户删除成功',
                       'message' => '客户删除成功!']);
    }

    /**
     * 签约合同视图
     * URL: POST /market/contract/create
     */
    public function myCustomerContractCreate(Request $request){
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
        return view('market/myCustomer/myCustomerContractCreate', ['student' => $student,
                                                                  'hours' => $hours,
                                                                  'courses' => $courses,
                                                                  'payment_methods' => $payment_methods]);
    }

    /**
     * 签约合同提交
     * URL: POST /market/contract/store
     * @param  Request  $request
     * @param  $request->input('student_id'): 购课学生
     * @param  $request->input('selected_course_num'): 购买课程数量
     */
    public function myCustomerContractStore(Request $request){
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
            return redirect("/market/myCustomer/contract/create?student_id={$request_student_id}")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '购课添加失败',
                         'message' => '购课添加失败，错误码:212']);
        }
        DB::commit();
        // 获取学生、课程名称
        $student_name = DB::table('student')
                          ->where('student_id', $contract_student)
                          ->value('student_name');
        // 返回购课列表

        return redirect("/market/myCustomer/contract/success?student_id=".encode($contract_student, 'student_id')."&contract_id=".encode($contract_id, 'contract_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '合同添加成功',
                       'message' => '合同添加成功']);
    }

    public function myCustomerContractSuccess(Request $request){
        return view('market/myCustomer/myCustomerContractCreateSuccess', ['student_id' => $request->input('student_id'), 'contract_id' => $request->input('contract_id')]);
    }
}
