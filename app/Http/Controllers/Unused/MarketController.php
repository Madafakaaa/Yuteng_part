<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class MarketController extends Controller
{

    /**
     * 学生退费视图
     * URL: GET /market/refund/create
     */
    public function refundCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('student_id');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hours = DB::table('hour')
                   ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                   ->join('course', 'hour.hour_course', '=', 'course.course_id')
                   ->where('hour_remain', '>', 0)
                   ->where('hour_student', $student_id)
                   ->get();
        if($hours->count()==0){
            return redirect("/market/refund/create")->with(['notify' => true,
                                                             'type' => 'danger',
                                                             'title' => '请重新选择学生',
                                                             'message' => '学生没有可退课时,请重新选择学生.']);
        }
        return view('market/refundCreate', ['student' => $student,
                                             'hours' => $hours]);
    }

    /**
     * 学生退费视图2
     * URL: POST /market/refund/create2
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     */
    public function refundCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        $contract_id = $hour->contract_id;
        $course_id = $hour->course_id;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - (($hour->hour_used + $hour->hour_used_free) * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        // 获取退款原因
        $refund_reasons = DB::table('refund_reason')
                           ->where('refund_reason_status', 1)
                           ->get();
        return view('market/refundCreate2', ['student' => $student,
                                               'hour' => $hour,
                                               'refund_amount' => $refund_amount,
                                               'refund_reasons' => $refund_reasons,
                                               'payment_methods' => $payment_methods]);
    }

    /**
     * 学生退费视图3
     * URL: POST /market/refund/create3
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function refundCreate3(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        $refund_fine = round((float)$request->input('input3'), 2);
        $refund_reason = $request->input('input4');
        $refund_payment_method = $request->input('input5');
        $refund_date = $request->input('input6');
        $refund_remark = $request->input('input7');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        $contract_id = $hour->contract_id;
        $course_id = $hour->course_id;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - (($hour->hour_used + $hour->hour_used_free) * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        $refund_actual_amount = $refund_amount - $refund_fine;
        if($refund_actual_amount<0){
            $refund_actual_amount = 0;
        }
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        // 获取退款原因
        $refund_reasons = DB::table('refund_reason')
                           ->where('refund_reason_status', 1)
                           ->get();
        return view('market/refundCreate3', ['student' => $student,
                                               'hour' => $hour,
                                               'refund_actual_amount' => $refund_actual_amount,
                                               'refund_amount' => $refund_amount,
                                               'refund_fine' => $refund_fine,
                                               'refund_reason' => $refund_reason,
                                               'refund_payment_method' => $refund_payment_method,
                                               'refund_date' => $refund_date,
                                               'refund_remark' => $refund_remark]);
    }

    /**
     * 学生退费提交
     * URL: POST /market/refund/store
     * @param  Request  $request
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function refundStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单数据
        $student_id = $request->input('input1');
        $hour_id = $request->input('input2');
        $refund_fine = round((float)$request->input('input3'), 2);
        $refund_reason = $request->input('input4');
        $refund_payment_method = $request->input('input5');
        $refund_date = $request->input('input6');
        if($request->filled('input7')) {
            $refund_remark = $request->input('input7');
        }else{
        	$refund_remark = "";
        }
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取剩余课时
        $hour = DB::table('hour')
                  ->join('contract', 'hour.hour_contract', '=', 'contract.contract_id')
                  ->join('course', 'hour.hour_course', '=', 'course.course_id')
                  ->join('contract_course', [
                                                ['contract_course.contract_course_contract', '=', 'hour.hour_contract'],
                                                ['contract_course.contract_course_course', '=', 'hour.hour_course']
                                            ])
                  ->where('hour_id', $hour_id)
                  ->first();
        // 获取提交数据
        $refund_contract = $hour->contract_id;
        $refund_course = $hour->course_id;
        $refund_department = $student->student_department;
        $refund_student = $student_id;
        $refund_remain_hour = $hour->hour_remain;
        $refund_free_hour = $hour->hour_remain_free;
        $refund_total_hour = $refund_remain_hour + $refund_free_hour;
        $refund_used_hour = $hour->hour_used+$hour->hour_used_free;
        $refund_actual_total_price = $hour->contract_course_total_price;
        $refund_original_unit_price = $hour->contract_course_original_unit_price;
        // 计算可退金额
        $refund_amount = $hour->contract_course_total_price - ($refund_used_hour * $hour->contract_course_original_unit_price);
        if($refund_amount<0){
            $refund_amount = 0;
        }
        $refund_actual_amount = $refund_amount - $refund_fine;
        if($refund_actual_amount<0){
            $refund_actual_amount = 0;
        }
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入Refund表
            DB::table('refund')->insert(
                ['refund_type' => 0,
                 'refund_hour' => $hour_id,
                 'refund_contract' => $refund_contract,
                 'refund_course' => $refund_course,
                 'refund_department' => $refund_department,
                 'refund_student' => $refund_student,
                 'refund_remain_hour' => $refund_remain_hour,
                 'refund_free_hour' => $refund_free_hour,
                 'refund_total_hour' => $refund_total_hour,
                 'refund_used_hour' => $refund_used_hour,
                 'refund_actual_total_price' => $refund_actual_total_price,
                 'refund_original_unit_price' => $refund_original_unit_price,
                 'refund_fine' => $refund_fine,
                 'refund_amount' => $refund_amount,
                 'refund_actual_amount' => $refund_actual_amount,
                 'refund_reason' => $refund_reason,
                 'refund_payment_method' => $refund_payment_method,
                 'refund_date' => $refund_date,
                 'refund_remark' => $refund_remark,
                 'refund_createuser' => Session::get('user_id')]
            );
            // 更新Contract_course表
            DB::table('contract_course')
              ->where('contract_course_contract', $refund_contract)
              ->where('contract_course_course', $refund_course)
              ->update(['contract_course_status' => 0]);
            // 更新Hour表
            DB::table('hour')
              ->where('hour_contract', $refund_contract)
              ->where('hour_course', $refund_course)
              ->update(['hour_remain' => 0,
                        'hour_remain_free' =>0,
                        'hour_refunded' => $refund_total_hour]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '学生退费',
                 'student_record_content' => '学生退费，合同号：'.$refund_contract.'，课程名称：'.$hour->course_name.'，共退课时：'.$refund_total_hour.' 课时，共退金额：'.$refund_actual_amount.' 元。退费人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            // 返回购课列表
            return redirect("/market/refund/create")
                   ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '退费失败',
                             'message' => '退费失败，请联系系统管理员']);
        }
        DB::commit();
        // 获取学生名称
        $student_name = DB::table('student')
                          ->where('student_id', $refund_student)
                          ->value('student_name');
        // 返回购课列表
        return redirect("/market/refund/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费成功',
                      'message' => '退费学生: '.$student_name]);
    }

    /**
     * 审核退课
     * URL: GET /market/refund/check/{refund_id}
     * @param  int  $refund_id
     */
    public function refundCheck($refund_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        DB::beginTransaction();
        try{
            // 删除Refund表
            DB::table('refund')
              ->where('refund_id', $refund_id)
              ->update(['refund_checked' => 1,
                        'refund_checked_user' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/market/refund/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '退费记录审核失败！',
                          'message' => '退费记录审核失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/market/refund/all")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费记录审核成功！',
                      'message' => '退费记录审核成功！']);
    }

    /**
     * 删除退课
     * URL: DELETE /market/refund/{refund_id}
     * @param  int  $refund_id
     */
    public function refundDelete($refund_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取refund信息
        $refund = DB::table('refund')
                    ->join('course', 'refund.refund_course', '=', 'course.course_id')
                    ->where('refund_id', $refund_id)
                    ->first();
        DB::beginTransaction();
        try{
            // 更新Hour表
            DB::table('hour')
              ->where('hour_id', $refund->refund_hour)
              ->update(['hour_refunded' => 0,
                        'hour_remain' => $refund->refund_remain_hour,
                        'hour_remain_free' => $refund->refund_free_hour]);
            // 更新Contract_course表
            DB::table('contract_course')
              ->where('contract_course_contract', $refund->refund_contract)
              ->where('contract_course_course', $refund->refund_course)
              ->update(['contract_course_status' => 1]);
            // 删除Refund表
            DB::table('refund')
              ->where('refund_id', $refund_id)
              ->delete();
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $refund->refund_student,
                 'student_record_type' => '删除学生退费',
                 'student_record_content' => '删除学生退费记录，合同号：'.$refund->refund_contract.
                                             '，课程名称：'.$refund->course_name.
                                             '，恢复正常课时：'.$refund->refund_remain_hour.' 课时，恢复赠送课时：'.$refund->refund_free_hour.' 课时。
                                             删除人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/market/refund/my")
                   ->with(['notify' => true,
                          'type' => 'danger',
                          'title' => '退费记录删除失败！',
                          'message' => '退费记录删除失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回购课列表
        return redirect("/market/refund/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '退费记录删除成功！',
                      'message' => '退费记录删除成功！']);
    }
}
