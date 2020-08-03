<?php
namespace App\Http\Controllers\Operation;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ContractController extends Controller
{
    /**
     * 签约管理视图
     * URL: GET /operation/contract
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function contract(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->whereIn('contract_department', $department_access);

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_name" => null,
                        "filter_user" => null,
                        "filter_month" => date('Y-m')
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
        // 月份
        if ($request->filled('filter_month')) {
            $filters['filter_month']=$request->input("filter_month");
        }
        $rows = $rows->where('contract_date', 'like', $filters['filter_month']."%");
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter_name')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter_name').'%');
            $filters['filter_name']=$request->input("filter_name");
            $filter_status = 1;
        }
        // 签约人
        if ($request->filled('filter_user')) {
            $rows = $rows->where('contract_createuser', '=', $request->input('filter_user'));
            $filters['filter_user']=$request->input("filter_user");
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_date', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 转为数组并获取详细课程信息
        $contracts = array();
        foreach($rows as $row){
            $temp=array();
            $temp['student_id']=$row->student_id;
            $temp['contract_id']=$row->contract_id;
            $temp['department_name']=$row->department_name;
            $temp['contract_date']=$row->contract_date;
            $temp['user_id']=$row->user_id;
            $temp['user_name']=$row->user_name;
            $temp['student_name']=$row->student_name;
            $temp['student_gender']=$row->student_gender;
            $temp['grade_name']=$row->grade_name;
            $temp['contract_type']=$row->contract_type;
            $temp['contract_total_price']=$row->contract_total_price;
            $temp['contract_paid_price']=$row->contract_paid_price;
            $temp['contract_courses']=array();
            // 获取合同课程
            $contract_courses = DB::table('contract_course')
                                  ->join('course', 'contract_course.contract_course_course', '=', 'course.course_id')
                                  ->where('contract_course_contract', $row->contract_id)
                                  ->get();
            foreach($contract_courses as $contract_course){
                $temp_course = array();
                $temp_course['course_name']=$contract_course->course_name;
                $temp_course['course_type']=$contract_course->course_type;
                $temp_course['contract_course_original_hour']=$contract_course->contract_course_original_hour;
                $temp_course['contract_course_original_unit_price']=$contract_course->contract_course_original_unit_price;
                $temp_course['contract_course_discount_rate']=$contract_course->contract_course_discount_rate;
                $temp_course['contract_course_discount_amount']=$contract_course->contract_course_discount_amount;
                $temp_course['contract_course_free_hour']=$contract_course->contract_course_free_hour;
                $temp_course['contract_course_total_hour']=$contract_course->contract_course_total_hour;
                $temp_course['contract_course_total_price']=$contract_course->contract_course_total_price;
                $temp['contract_courses'][]=$temp_course;
            }
            $temp['contract_course_num']=count($temp['contract_courses']);
            $contracts[]=$temp;
        }

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_users = DB::table('user')
                          ->join('department', 'user.user_department', '=', 'department.department_id')
                          ->join('position', 'user.user_position', '=', 'position.position_id')
                          ->where('user_status', 1)
                          ->whereIn('user_department', $department_access)
                          ->orderBy('user_department', 'asc')
                          ->orderBy('user_position', 'desc')
                          ->get();

        // 返回列表视图
        return view('operation/contract/contract', ['contracts' => $contracts,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'request' => $request,
                                               'filters' => $filters,
                                               'totalNum' => $totalNum,
                                               'filter_status' => $filter_status,
                                               'filter_departments' => $filter_departments,
                                               'filter_students' => $filter_students,
                                               'filter_grades' => $filter_grades,
                                               'filter_users' => $filter_users]);
    }

    /**
     * 删除购课
     * URL: DELETE /operation/contract/delete
     * @param  int  $contract_id
     */
    public function contractDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $contract_id = decode($request->input('id'), 'contract_id');
        // 获取学生信息
        $contract_student = DB::table('contract')
                              ->where('contract_id', $contract_id)
                              ->first()
                              ->contract_student;
        // 获取购课信息
        $contract_courses = DB::table('contract_course')
                              ->where('contract_course_contract', $contract_id)
                              ->get();
        $valid = 1;
        foreach ($contract_courses as $contract_course) {
            // 判断课时是否存在
            $exist= DB::table('hour')
                      ->where('hour_student', $contract_student)
                      ->where('hour_course', $contract_course->contract_course_course)
                      ->exists();
            if($exist){
                $hour = DB::table('hour')
                          ->where('hour_student', $contract_student)
                          ->where('hour_course', $contract_course->contract_course_course)
                          ->first();
                if($hour->hour_remain<$contract_course->contract_course_total_hour){
                    $valid=0;
                    break;
                }
            }else{
                $valid=0;
                break;
            }
        }
        // 没有使用过课时
        if($valid==1){
            DB::beginTransaction();
            try{
                foreach ($contract_courses as $contract_course) {
                    $hour = DB::table('hour')
                              ->where('hour_student', $contract_student)
                              ->where('hour_course', $contract_course->contract_course_course)
                              ->first();

                    $hour_remain = $hour->hour_remain;
                    $hour_used = $hour->hour_used;
                    $hour_average_price = $hour->hour_average_price;
                    $hour_total_price = ($hour_remain+$hour_used)*$hour_average_price;
                    $hour_remain-=$contract_course->contract_course_total_hour;
                    $hour_total_price-=$contract_course->contract_course_total_price;
                    if($hour_remain==0){
                        DB::table('hour')
                          ->where('hour_student', $contract_student)
                          ->where('hour_course', $contract_course->contract_course_course)
                          ->delete();
                    }else{
                        $hour_average_price = $hour_total_price/($hour_remain+$hour_used);
                        // 更新Hour表
                        DB::table('hour')
                          ->where('hour_student', $contract_student)
                          ->where('hour_course', $contract_course->contract_course_course)
                          ->update(['hour_remain' => $hour_remain,
                                    'hour_average_price' => $hour_average_price]);
                    }
                }
                // 删除Contract_course表
                DB::table('contract_course')
                  ->where('contract_course_contract', $contract_id)
                  ->delete();
                // 删除Contract表
                DB::table('contract')
                  ->where('contract_id', $contract_id)
                  ->delete();
                // 减少学生签约次数
                DB::table('student')
                  ->where('student_id', $contract_student)
                  ->decrement('student_contract_num');
                $student_contract_num = DB::table('student')
                                          ->where('student_id', $contract_student)
                                          ->first()
                                          ->student_contract_num;
                // 更新学生状态
                if($student_contract_num==0){
                    // 更新客户状态、最后签约时间
                    DB::table('student')
                      ->where('student_id', $contract_student)
                      ->update(['student_last_contract_date' => '2000-01-01']);
                }
            }
            // 捕获异常
            catch(Exception $e){
                DB::rollBack();
                return redirect("/operation/contract")
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '购课记录删除失败',
                               'message' => '购课记录删除失败，错误码:343']);
            }
            DB::commit();
            // 返回购课列表
            return redirect("/operation/contract")
                   ->with(['notify' => true,
                           'type' => 'success',
                           'title' => '购课记录删除成功',
                           'message' => '购课记录删除成功']);
        }else{
            return redirect("/operation/contract")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '购课记录删除失败',
                           'message' => '学生剩余课时不足，错误码:344']);
        }
    }

    /**
     * 补缴费用
     * URL: GET /operation/contract/{contract_id}
     * @param  int  $contract_id
     */
    public function contractEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $contract_id = decode($request->input('id'), 'contract_id');
        // 获取数据
        $contract = DB::table('contract')
                      ->join('student', 'contract.contract_student', '=', 'student.student_id')
                      ->join('department', 'student.student_department', '=', 'department.department_id')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->where('contract_id', '=', $contract_id)
                      ->first();
        // 返回列表视图
        return view('operation/contract/contractEdit', ['contract' => $contract]);
    }

    /**
     * 更新费用
     * URL: POST /operation/contract/{contract_id}
     * @param  int  $contract_id
     */
    public function contractUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $contract_id = decode($request->input('id'), 'contract_id');
        if ($request->filled('input2')) {
            $contract_remark = $request->input('input2');
        }else{
            $contract_remark = "";
        }
        DB::beginTransaction();
        try{
            DB::table('contract')
              ->where('contract_id', $contract_id)
              ->update(['contract_paid_price' =>  $request->input('input1'),
                        'contract_remark' => $contract_remark]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/contract")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '缴费提交失败',
                           'message' => '缴费提交失败，错误码:345']);
        }
        DB::commit();
        return redirect("/operation/contract")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '缴费提交成功',
                       'message' => '缴费提交成功']);
    }

}
