<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ContractController extends Controller
{
    /**
     * 显示所有购课记录
     * URL: GET /contract
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id');
        // 添加筛选条件
        // 购课校区
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_department', '=', $request->input('filter1'));
        }
        // 购课学生
        if ($request->filled('filter2')) {
            $rows = $rows->where('contract_student', '=', $request->input('filter2'));
        }
        // 课程年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();

        // 返回列表视图
        return view('contract/index', ['rows' => $rows,
                                       'currentPage' => $currentPage,
                                       'totalPage' => $totalPage,
                                       'startIndex' => $offset,
                                       'request' => $request,
                                       'totalNum' => $totalNum,
                                       'filter_departments' => $filter_departments,
                                       'filter_students' => $filter_students,
                                       'filter_grades' => $filter_grades]);
    }

    /**
     * 创建新购课页面
     * URL: GET /contract/create
     */
    public function create(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        if($request->filled('student_id')) {
            $student_id = $request->input('student_id');
        }else{
            $student_id = '';
        }
        // 获取学生信息
        $students = DB::table('student')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->where('student_follower', Session::get('user_id'))
                      ->orderBy('student_customer_status', 'asc')
                      ->orderBy('student_grade', 'asc')
                      ->get();
        return view('contract/create', ['students' => $students,
                                        'student_id' => $student_id]);
    }

    /**
     * 创建新购课页面2
     * URL: POST /contract/create/step2
     */
    public function createStep2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('input1');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取已选课程数
        if($request->filled('selected_course_num')){
            $selected_course_num = $request->input('selected_course_num');
        }else{
            $selected_course_num = 0;
        }
        // 获取已选课程ID
        $selected_course_ids = array();
        for($i=0; $i<$selected_course_num; $i++){
            $selected_course_ids[]=$request->input("course{$i}");
        }
        // 获取新选课程
        if($request->filled('input2')){
            $selected_course_num = $selected_course_num + 1;
            $selected_course_ids[]=$request->input('input2');
        }
        // 获取删除课程
        if($request->filled('input3')){
            $selected_course_num = $selected_course_num - 1;
            $key = array_search($request->input('input3'), $selected_course_ids);
            unset($selected_course_ids[$key]);
            $selected_course_ids = array_values($selected_course_ids);
        }
        // 获取所有已选课程数据库信息
        $selected_courses = array();
        for($i=0; $i<$selected_course_num; $i++){
            $selected_courses[] = DB::table('course')
                                    ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                                    ->where('course_id', $selected_course_ids[$i])
                                    ->first();
        }
        // 获取课程信息
        $courses = DB::table('course')
                     ->join('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', $student->student_grade)
                     ->whereIn('course_department', [0, $student->student_department]);
        // 去除已选课程
        for($i=0; $i<$selected_course_num; $i++){
            $courses = $courses->where('course_id', '<>', $selected_course_ids[$i]);
        }
        $courses = $courses->where('course_status', 1)
                           ->orderBy('course_type', 'asc')
                           ->orderBy('course_grade', 'asc')
                           ->orderBy('course_time', 'asc')
                           ->get();
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        return view('contract/create2', ['student' => $student,
                                         'courses' => $courses,
                                         'payment_methods' => $payment_methods,
                                         'selected_course_ids' => $selected_course_ids,
                                         'selected_course_num' => $selected_course_num,
                                         'selected_courses' => $selected_courses]);
    }

    /**
     * 创建新购课提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('student_id'): 购课学生
     * @param  $request->input('selected_course_num'): 购买课程数量
     */
    public function store(Request $request){
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
        if($request->filled('remark')) {
            $request_contract_remark = $request->input('remark');
        }else{
            $request_contract_remark = "";
        }
        $request_contract_type = $request->input('contract_type');
        $request_contract_extra_fee = round((float)$request->input("extra_fee"), 2);
        $request_courses = array();
        // 生成新合同号
        $sub_student_id = substr($request_student_id , 1 , 10);
        $new_contract_num = DB::table('contract')
                              ->where('contract_student', $request_student_id)
                              ->count()+1;
        $contract_id = "H".$sub_student_id.sprintf("%02d", $new_contract_num);
        for($i=1; $i<=$request_selected_course_num; $i++){
            $temp = array();
            $temp[] = (int)$request->input("course_{$i}_0");
            $temp[] = round((float)$request->input("course_{$i}_1"), 2);
            $temp[] = (int)$request->input("course_{$i}_2");
            $temp[] = round((float)$request->input("course_{$i}_3"), 2);
            $temp[] = round((float)$request->input("course_{$i}_4"), 2);
            $temp[] = round((float)$request->input("course_{$i}_5"), 2);
            $temp[] = (int)$request->input("course_{$i}_6");
            $temp[] = (int)$request->input("course_{$i}_7");
            $temp[] = round((float)$request->input("course_{$i}_8"), 2);
            $request_courses[] = $temp;
        }
        // 计算合同总信息
        $contract_department = Session::get('user_department');
        $contract_student = $request_student_id;
        $contract_course_num = $request_selected_course_num;
        $contract_original_hour = 0;
        $contract_free_hour = 0;
        $contract_total_hour = 0;
        $contract_original_price = 0;
        $contract_discount_price = 0;
        $contract_total_price = 0;
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
        // 获取学生签约状态
        $student_customer_status = DB::table('student')
                                     ->where('student_id', $request_student_id)
                                     ->first()
                                     ->student_customer_status;
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入Contract表
            DB::table('contract')->insert(
                ['contract_id' => $contract_id,
                 'contract_department' => $contract_department,
                 'contract_student' => $contract_student,
                 'contract_course_num' => $contract_course_num,
                 'contract_original_hour' => $contract_original_hour,
                 'contract_free_hour' => $contract_free_hour,
                 'contract_total_hour' => $contract_total_hour,
                 'contract_original_price' => $contract_original_price,
                 'contract_discount_price' => $contract_discount_price,
                 'contract_total_price' => $contract_total_price,
                 'contract_date' => $contract_date,
                 'contract_payment_method' => $contract_payment_method,
                 'contract_remark' => $contract_remark,
                 'contract_type' => $request_contract_type,
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
                // 插入Hour表
                DB::table('hour')->insert(
                    ['hour_contract' => $contract_id,
                     'hour_student' => $contract_student,
                     'hour_course' => $request_course[0],
                     'hour_type' => 0,
                     'hour_original' => $request_course[2],
                     'hour_remain' => $request_course[2],
                     'hour_used' => 0,
                     'hour_createuser' => $contract_createuser]
                );
                DB::table('hour')->insert(
                    ['hour_contract' => $contract_id,
                     'hour_student' => $contract_student,
                     'hour_course' => $request_course[0],
                     'hour_type' => 1,
                     'hour_original' => $request_course[6],
                     'hour_remain' => $request_course[6],
                     'hour_used' => 0,
                     'hour_createuser' => $contract_createuser]
                );
            }
            // 增加学生签约次数
            DB::table('student')
              ->where('student_id', $request_student_id)
              ->increment('student_contract_num');
            // 更新客户状态、最后签约时间
            DB::table('student')
              ->where('student_id', $request_student_id)
              ->update(['student_customer_status' =>  1,
                        'student_last_contract_date' =>  date('Y-m-d')]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $request_student_id,
                 'student_record_type' => '签约合同',
                 'student_record_content' => '客户签约合同，合同号：'.$contract_id.'，课程种类：'.$contract_course_num.' 种，合计金额：'.$contract_total_price.' 元。签约人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            // 返回购课列表
            return redirect("/myCustomer")->with(['notify' => true,
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
        return redirect("/myContract")->with(['notify' => true,
                                             'type' => 'success',
                                             'title' => '购课添加成功',
                                             'message' => '购课学生: '.$student_name]);
    }

    /**
     * 显示合同详细信息
     * URL: GET /contract/{id}
     * @param  int  $contract_id
     */
    public function show($contract_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $contract = DB::table('contract')
                      ->join('student', 'contract.contract_student', '=', 'student.student_id')
                      ->join('department', 'contract.contract_department', '=', 'department.department_id')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                      ->where('contract_id', $contract_id)
                      ->get();
        $contract_courses = DB::table('contract_course')
                              ->join('course', 'contract_course.contract_course_course', '=', 'course.course_id')
                              ->where('contract_course.contract_course_contract', $contract_id)
                              ->get();
        // 检验数据是否存在
        if($contract->count()!==1){
            // 未获取到数据
            return redirect()->action('ContractController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '合同显示失败',
                                     'message' => '合同显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $contract = $contract[0];
        return view('contract/show', ['contract' => $contract,
                                      'contract_courses' => $contract_courses]);
    }

    /**
     * 删除购课
     * URL: DELETE /contract/{id}
     * @param  int  $contract_id
     */
    public function destroy($contract_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取Contract信息
        $contract = DB::table('contract')
                      ->where('contract_id', $contract_id)
                      ->first();
        // 获取课程包中已使用Hour信息数量
        $invalid_hour_num = DB::table('hour')
                              ->where('hour_contract', $contract_id)
                              ->whereColumn('hour_original', "<>", 'hour_remain')
                              ->count();
        // 非法课时为零
        if($invalid_hour_num==0){
            DB::beginTransaction();
            try{
                // 删除Hour表
                DB::table('hour')
                  ->where('hour_contract', $contract_id)
                  ->delete();
                // 删除Contract_course表
                DB::table('contract_course')
                  ->where('contract_course_contract', $contract_id)
                  ->delete();
                // 删除Contract表
                DB::table('contract')
                  ->where('contract_id', $contract_id)
                  ->delete();
            }
            // 捕获异常
            catch(Exception $e){
                DB::rollBack();
                return redirect()->action('ContractController@index')
                                 ->with(['notify' => true,
                                         'type' => 'danger',
                                         'title' => '购课记录删除失败',
                                         'message' => '购课记录删除失败，请联系系统管理员']);
            }
            DB::commit();
            // 返回购课列表
            return redirect()->action('ContractController@index')
                             ->with(['notify' => true,
                                     'type' => 'success',
                                     'title' => '购课记录删除成功',
                                     'message' => '购课记录删除成功']);
        }else{
            return redirect()->action('ContractController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '购课记录删除失败',
                                     'message' => '学生剩余课时不足，购课记录删除失败。']);
        }
    }
}
