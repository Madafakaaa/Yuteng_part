<?php
namespace App\Http\Controllers\Market;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ContractController extends Controller
{
    /**
     * 签约管理视图
     * URL: GET /market/contract
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
                  ->whereIn('contract_department', $department_access)
                  ->where('contract_section', '=', 0);

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
        // 签约人
        if ($request->filled('filter4')) {
            $rows = $rows->where('contract_createuser', '=', $request->input('filter4'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_date', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

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
        return view('market/contract/contract', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_students' => $filter_students,
                                           'filter_grades' => $filter_grades,
                                           'filter_users' => $filter_users]);
    }

    /**
     * 删除购课
     * URL: DELETE /market/contract/delete
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
                return redirect("/market/contract")
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '购课记录删除失败',
                               'message' => '购课记录删除失败，错误码:214']);
            }
            DB::commit();
            // 返回购课列表
            return redirect("/market/contract")
                   ->with(['notify' => true,
                           'type' => 'success',
                           'title' => '购课记录删除成功',
                           'message' => '购课记录删除成功']);
        }else{
            return redirect("/market/contract")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '购课记录删除失败',
                           'message' => '学生剩余课时不足，错误码:215']);
        }
    }

    /**
     * 补缴费用
     * URL: GET /market/contract/{contract_id}
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
        return view('market/contract/contractEdit', ['contract' => $contract]);
    }

    /**
     * 更新费用
     * URL: POST /market/contract/{contract_id}
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
            return redirect("/market/contract")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '缴费提交失败',
                           'message' => '缴费提交失败，错误码:216']);
        }
        DB::commit();
        return redirect("/market/contract")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '缴费提交成功',
                       'message' => '缴费提交成功']);
    }
}
