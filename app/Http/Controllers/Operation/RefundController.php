<?php
namespace App\Http\Controllers\Operation;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class RefundController extends Controller
{
    /**
     * 部门退费视图
     * URL: GET /operation/refund
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function refund(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('refund')
                  ->join('student', 'refund.refund_student', '=', 'student.student_id')
                  ->join('course', 'refund.refund_course', '=', 'course.course_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('user AS createuser', 'refund.refund_createuser', '=', 'createuser.user_id')
                  ->join('position AS createuser_position', 'createuser.user_position', '=', 'createuser_position.position_id')
                  ->leftJoin('user AS checked_user', 'refund.refund_checked_user', '=', 'checked_user.user_id')
                  ->leftJoin('position AS checked_user_position', 'checked_user.user_position', '=', 'checked_user_position.position_id')
                  ->whereIn('student_department', $department_access);

        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_student" => null,
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

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->select('refund.refund_id',
                              'refund.refund_remain',
                              'refund.refund_used',
                              'refund.refund_amount',
                              'refund.refund_unit_price',
                              'refund.refund_date',
                              'refund.refund_checked',
                              'refund.refund_createuser',
                              'student.student_id',
                              'student.student_name AS student_name',
                              'student.student_gender',
                              'department.department_name AS department_name',
                              'course.course_name AS course_name',
                              'createuser.user_id AS createuser_id',
                              'createuser.user_name AS createuser_name',
                              'createuser_position.position_name AS createuser_position_name',
                              'checked_user.user_name AS checked_user_name',
                              'checked_user_position.position_name AS checked_user_position_name')
                     ->orderBy('refund_date', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();
        $filter_students = DB::table('student')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->where('student_status', 1)
                             ->where('student_contract_num', '>', 0)
                             ->whereIn('student_department', $department_access)
                             ->orderBy('student_department', 'asc')
                             ->orderBy('student_grade', 'asc')
                             ->get();

        // 返回列表视图
        return view('operation/refund/refund', ['rows' => $rows,
                                                 'currentPage' => $currentPage,
                                                 'totalPage' => $totalPage,
                                                 'startIndex' => $offset,
                                                 'request' => $request,
                                                 'filters' => $filters,
                                                 'totalNum' => $totalNum,
                                                 'filter_departments' => $filter_departments,
                                                 'filter_students' => $filter_students,
                                                 'filter_grades' => $filter_grades]);
    }

    /**
     * 删除退课
     * URL: DELETE /operation/refund/delete
     * @param  int  $refund_id
     */
    public function refundDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        $refund_id = decode($request->input('id'), 'refund_id');

        // 获取refund信息
        $refund = DB::table('refund')
                    ->where('refund_id', $refund_id)
                    ->first();
        DB::beginTransaction();
        try{
            if(DB::table('hour')->where('hour_student', $refund->refund_student)->where('hour_course', $refund->refund_course)->exists()){
                $hour = DB::table('hour')
                          ->where('hour_student', $refund->refund_student)
                          ->where('hour_course', $refund->refund_course)
                          ->first();
                // 计算hour中总价值
                $hour_total_price = ($hour->hour_remain+$hour->hour_used)*$hour->hour_average_price;
                // 增加总价值
                $hour_total_price += ($refund->refund_remain+$refund->refund_used)*$refund->refund_unit_price;
                // 计算新单价
                if($hour->hour_remain+$hour->hour_used+$refund->refund_remain+$refund->refund_used!=0){
                    $hour_average_price = $hour_total_price/($hour->hour_remain+$hour->hour_used+$refund->refund_remain+$refund->refund_used);
                }else{
                    $hour_average_price = 0;
                }
                DB::table('hour')
                  ->where('hour_student', $refund->refund_student)
                  ->where('hour_course', $refund->refund_course)
                  ->update(['hour_remain' => $hour->hour_remain+$refund->refund_remain,
                            'hour_used' => $hour->hour_used+$refund->refund_used,
                            'hour_average_price' => $hour_average_price]);
            }else{
                DB::table('hour')->insert(
                    ['hour_student' => $refund->refund_student,
                     'hour_course' => $refund->refund_course,
                     'hour_remain' => $refund->refund_remain,
                     'hour_used' => $refund->refund_used,
                     'hour_average_price' => $refund->refund_unit_price]
                );
            }
            // 删除Refund表
            DB::table('refund')
              ->where('refund_id', $refund_id)
              ->delete();
            // 添加学生动态
            //DB::table('student_record')->insert(
            //    ['student_record_student' => $refund->refund_student,
            //     'student_record_type' => '删除学生退费',
            //     'student_record_content' => '删除学生退费记录，合同号：'.$refund->refund_contract.
            //                                 '，课程名称：'.$refund->course_name.
            //                                 '，恢复正常课时：'.$refund->refund_remain_hour.' 课时，恢复赠送课时：'.$refund->refund_free_hour.' 课时。
            //                                 删除人：'.Session::get('user_name')."。",
            //     'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/operation/refund")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '退费记录删除失败！',
                          'message' => '退费记录删除失败，错误码:349']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/operation/refund")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费记录删除成功！',
                      'message' => '退费记录删除成功！']);
    }

}
