<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class FinanceController extends Controller
{
    /**
     * 签约统计视图
     * URL: GET /finance/contract
     */
    public function contract(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取用户可用校区信息
        $user_departments = DB::table('department')
                         ->where('department_status', 1)
                         ->whereIn('department_id', $department_access)
                         ->orderBy('department_id', 'asc')
                         ->get();
        $department_array = array();
        $department_query = array();
        if($request->filled('start_date')) { // 表单有输入
            // 检查输入
            if($request->input('start_date')>$request->input('end_date')){
                return redirect("/finance/contract")->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '时间输入错误',
                                                            'message' => '起始日期在截止日期之后，请重新选择！']);
            }
            if(!$request->filled('departments')){
                return redirect("/finance/contract")->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '未选择校区',
                                                            'message' => '应至少选择一个校区,请重新选择！']);
            }
            // 获取起始日期
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            // 获取部门选择
            foreach($user_departments AS $user_department){
                $department_array[$user_department->department_id] = array($user_department->department_id, $user_department->department_name, 0);
            }
            foreach($request->input('departments') AS $department_id){
                $department_array[$department_id][2] = 1;
                $department_query[] = $department_id;
            }
            // 获取统计方式
            $analysis_method = $request->input('analysis_method');
            // 获取统计方式
        }else{ // 初次打开页面
            // 获取起始日期
            $start_date=date('Y-m-01',strtotime(date('Y-m')));
            $end_date=date('Y-m-t',strtotime(date('Y-m')));
            // 全部部门选择
            foreach($user_departments AS $user_department){
                $department_array[$user_department->department_id] = array($user_department->department_id, $user_department->department_name, 1);
                $department_query[] = $user_department->department_id;
            }
            // 获取统计方式
            $analysis_method = 1;
        }

        // 计算总收入
        $total_income = DB::table('contract')
                          ->whereIn('contract_department', $department_query)
                          ->where('contract_date','>=' , $start_date)
                          ->where('contract_date','<=' , $end_date)
                          ->sum('contract_total_price');
        // 计算签约数量
        $total_contract_num = DB::table('contract')
                                ->whereIn('contract_department', $department_query)
                                ->where('contract_date','>=' , $start_date)
                                ->where('contract_date','<=' , $end_date)
                                ->count();
        // 计算售出课时
        $total_hour = DB::table('contract')
                        ->whereIn('contract_department', $department_query)
                        ->where('contract_date','>=' , $start_date)
                        ->where('contract_date','<=' , $end_date)
                        ->sum('contract_total_hour');
        // 表格数据
        $ids = array();
        $incomes = array();
        $contract_nums = array();
        $hours = array();
        if($analysis_method==1){ // 日期
            $temp_date = $end_date;
            $i = 0;
            while($temp_date>=$start_date&&$i<30){
                // 当日收入
                $temp_income = DB::table('contract')
                                 ->whereIn('contract_department', $department_query)
                                 ->where('contract_date', '=', $temp_date)
                                 ->sum('contract_total_price');
                // 计算签约数量
                $temp_contract_num = DB::table('contract')
                                       ->whereIn('contract_department', $department_query)
                                       ->where('contract_date', '=', $temp_date)
                                       ->count();
                // 计算售出课时
                $temp_hour = DB::table('contract')
                               ->whereIn('contract_department', $department_query)
                               ->where('contract_date', '=', $temp_date)
                               ->sum('contract_total_hour');
                // 存入数组
                $ids[] = date('m-d', strtotime($temp_date));
                $incomes[] = $temp_income;
                $contract_nums[] = $temp_contract_num;
                $hours[] = $temp_hour;
                // 日期加一
                $temp_date = date('Y-m-d', strtotime ("-1 day", strtotime($temp_date)));
                $i++;
            }
        }else{ // 校区
            foreach($department_query AS $department_id){
                // 计算总收入
                $temp_income = DB::table('contract')
                                  ->where('contract_department', $department_id)
                                  ->where('contract_date','>=' , $start_date)
                                  ->where('contract_date','<=' , $end_date)
                                  ->sum('contract_total_price');
                // 计算签约数量
                $temp_contract_num = DB::table('contract')
                                        ->where('contract_department', $department_id)
                                        ->where('contract_date','>=' , $start_date)
                                        ->where('contract_date','<=' , $end_date)
                                        ->count();
                // 计算售出课时
                $temp_hour = DB::table('contract')
                                ->where('contract_department', $department_id)
                                ->where('contract_date','>=' , $start_date)
                                ->where('contract_date','<=' , $end_date)
                                ->sum('contract_total_hour');
                $ids[] = $department_array[$department_id][1];
                $incomes[] = $temp_income;
                $contract_nums[] = $temp_contract_num;
                $hours[] = $temp_hour;
            }
        }
        return view('finance/contract', ['start_date' => $start_date,
                                         'end_date' => $end_date,
                                         'user_departments' => $user_departments,
                                         'department_array' => $department_array,
                                         'analysis_method' => $analysis_method,
                                         'total_income' => $total_income,
                                         'total_contract_num' => $total_contract_num,
                                         'total_hour' => $total_hour,
                                         'ids' => $ids,
                                         'incomes' => $incomes,
                                         'contract_nums' => $contract_nums,
                                         'hours' => $hours]);
    }

    /**
     * 课时消耗视图
     * URL: GET /finance/consumption
     */
    public function consumption(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取用户可用校区信息
        $user_departments = DB::table('department')
                         ->where('department_status', 1)
                         ->whereIn('department_id', $department_access)
                         ->orderBy('department_id', 'asc')
                         ->get();
        $department_array = array();
        $department_query = array();
        if($request->filled('start_date')) { // 表单有输入
            // 检查输入
            if($request->input('start_date')>$request->input('end_date')){
                return redirect("/finance/consumption")->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '时间输入错误',
                                                            'message' => '起始日期在截止日期之后，请重新选择！']);
            }
            if(!$request->filled('departments')){
                return redirect("/finance/consumption")->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '未选择校区',
                                                            'message' => '应至少选择一个校区,请重新选择！']);
            }
            // 获取起始日期
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            // 获取部门选择
            foreach($user_departments AS $user_department){
                $department_array[$user_department->department_id] = array($user_department->department_id, $user_department->department_name, 0);
            }
            foreach($request->input('departments') AS $department_id){
                $department_array[$department_id][2] = 1;
                $department_query[] = $department_id;
            }
            // 获取统计方式
            $analysis_method = $request->input('analysis_method');
            // 获取统计方式
        }else{ // 初次打开页面
            // 获取起始日期
            $start_date=date('Y-m-01',strtotime(date('Y-m')));
            $end_date=date('Y-m-t',strtotime(date('Y-m')));
            // 全部部门选择
            foreach($user_departments AS $user_department){
                $department_array[$user_department->department_id] = array($user_department->department_id, $user_department->department_name, 1);
                $department_query[] = $user_department->department_id;
            }
            // 获取统计方式
            $analysis_method = 1;
        }

        // 计算总课时消耗
        $total_hour = DB::table('participant')
                          ->join('schedule', 'participant.participant_schedule', '=', 'schedule.schedule_id')
                          ->whereIn('schedule_department', $department_query)
                          ->where('schedule_date','>=' , $start_date)
                          ->where('schedule_date','<=' , $end_date)
                          ->sum('participant_amount');

        // 计算上课人数
        $total_student_num = DB::table('participant')
                               ->join('schedule', 'participant.participant_schedule', '=', 'schedule.schedule_id')
                               ->whereIn('schedule_department', $department_query)
                               ->where('schedule_date','>=' , $start_date)
                               ->where('schedule_date','<=' , $end_date)
                               ->count();

        // 计算总上课人数
        $total_schedule_num = DB::table('schedule')
                                ->whereIn('schedule_department', $department_query)
                                ->where('schedule_date','>=' , $start_date)
                                ->where('schedule_date','<=' , $end_date)
                                ->count();
        // 表格数据
        $ids = array();
        $hours = array();
        $student_nums = array();
        $schedule_nums = array();
        if($analysis_method==1){ // 日期
            $temp_date = $end_date;
            $i = 0;
            while($temp_date>=$start_date&&$i<30){
                // 计算当日课时消耗
                $temp_hour = DB::table('participant')
                               ->join('schedule', 'participant.participant_schedule', '=', 'schedule.schedule_id')
                               ->whereIn('schedule_department', $department_query)
                               ->where('schedule_date','=' , $temp_date)
                               ->sum('participant_amount');

                // 计算当日上课人数
                $temp_student_num = DB::table('participant')
                                       ->join('schedule', 'participant.participant_schedule', '=', 'schedule.schedule_id')
                                       ->whereIn('schedule_department', $department_query)
                                       ->where('schedule_date','=' , $temp_date)
                                       ->count();

                // 计算当日上课次数
                $temp_schedule_num = DB::table('schedule')
                                        ->whereIn('schedule_department', $department_query)
                                        ->where('schedule_date','=' , $temp_date)
                                        ->count();
                // 存入数组
                $ids[] = date('m-d', strtotime($temp_date));
                $hours[] = $temp_hour;
                $student_nums[] = $temp_student_num;
                $schedule_nums[] = $temp_schedule_num;
                // 日期加一
                $temp_date = date('Y-m-d', strtotime ("-1 day", strtotime($temp_date)));
                $i++;
            }
        }else{ // 校区
            foreach($department_query AS $department_id){

                // 计算当日课时消耗
                $temp_hour = DB::table('participant')
                                  ->join('schedule', 'participant.participant_schedule', '=', 'schedule.schedule_id')
                                  ->where('schedule_department', $department_id)
                                  ->where('schedule_date','>=' , $start_date)
                                  ->where('schedule_date','<=' , $end_date)
                                  ->sum('participant_amount');

                // 计算当日上课人数
                $temp_student_num = DB::table('participant')
                                       ->join('schedule', 'participant.participant_schedule', '=', 'schedule.schedule_id')
                                       ->where('schedule_department', $department_id)
                                       ->where('schedule_date','>=' , $start_date)
                                       ->where('schedule_date','<=' , $end_date)
                                       ->count();

                // 计算当日上课次数
                $temp_schedule_num = DB::table('schedule')
                                        ->where('schedule_department', $department_id)
                                        ->where('schedule_date','>=' , $start_date)
                                        ->where('schedule_date','<=' , $end_date)
                                        ->count();
                $ids[] = $department_array[$department_id][1];
                $hours[] = $temp_hour;
                $student_nums[] = $temp_student_num;
                $schedule_nums[] = $temp_schedule_num;
            }
        }
        return view('finance/consumption', ['start_date' => $start_date,
                                         'end_date' => $end_date,
                                         'user_departments' => $user_departments,
                                         'department_array' => $department_array,
                                         'analysis_method' => $analysis_method,
                                         'total_hour' => $total_hour,
                                         'total_student_num' => $total_student_num,
                                         'total_schedule_num' => $total_schedule_num,
                                         'ids' => $ids,
                                         'hours' => $hours,
                                         'student_nums' => $student_nums,
                                         'schedule_nums' => $schedule_nums]);
    }

    /**
     * 退费统计视图
     * URL: GET /finance/refund
     */
    public function refund(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取用户可用校区信息
        $user_departments = DB::table('department')
                         ->where('department_status', 1)
                         ->whereIn('department_id', $department_access)
                         ->orderBy('department_id', 'asc')
                         ->get();
        $department_array = array();
        $department_query = array();
        if($request->filled('start_date')) { // 表单有输入
            // 检查输入
            if($request->input('start_date')>$request->input('end_date')){
                return redirect("/finance/refund")->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '时间输入错误',
                                                            'message' => '起始日期在截止日期之后，请重新选择！']);
            }
            if(!$request->filled('departments')){
                return redirect("/finance/refund")->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '未选择校区',
                                                            'message' => '应至少选择一个校区,请重新选择！']);
            }
            // 获取起始日期
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');
            // 获取部门选择
            foreach($user_departments AS $user_department){
                $department_array[$user_department->department_id] = array($user_department->department_id, $user_department->department_name, 0);
            }
            foreach($request->input('departments') AS $department_id){
                $department_array[$department_id][2] = 1;
                $department_query[] = $department_id;
            }
            // 获取统计方式
            $analysis_method = $request->input('analysis_method');
            // 获取统计方式
        }else{ // 初次打开页面
            // 获取起始日期
            $start_date=date('Y-m-01',strtotime(date('Y-m')));
            $end_date=date('Y-m-t',strtotime(date('Y-m')));
            // 全部部门选择
            foreach($user_departments AS $user_department){
                $department_array[$user_department->department_id] = array($user_department->department_id, $user_department->department_name, 1);
                $department_query[] = $user_department->department_id;
            }
            // 获取统计方式
            $analysis_method = 1;
        }

        // 计算总退费金额
        $total_refund_amount = DB::table('refund')
                                  ->whereIn('refund_department', $department_query)
                                  ->where('refund_date','>=' , $start_date)
                                  ->where('refund_date','<=' , $end_date)
                                  ->sum('refund_actual_amount');

        // 计算退费次数
        $total_refund_num = DB::table('refund')
                              ->whereIn('refund_department', $department_query)
                              ->where('refund_date','>=' , $start_date)
                              ->where('refund_date','<=' , $end_date)
                              ->count();

        // 计算退费课时数量
        $total_hour_num = DB::table('refund')
                            ->whereIn('refund_department', $department_query)
                            ->where('refund_date','>=' , $start_date)
                            ->where('refund_date','<=' , $end_date)
                            ->sum('refund_total_hour');
        // 表格数据
        $ids = array();
        $refund_amounts = array();
        $refund_nums = array();
        $hour_nums = array();
        if($analysis_method==1){ // 日期
            $temp_date = $end_date;
            $i = 0;
            while($temp_date>=$start_date&&$i<30){
                // 计算当日退费金额
                $temp_refund_amount = DB::table('refund')
                                          ->whereIn('refund_department', $department_query)
                                          ->where('refund_date','=' , $temp_date)
                                          ->sum('refund_actual_amount');

                // 计算当日退费次数
                $temp_refund_num = DB::table('refund')
                                      ->whereIn('refund_department', $department_query)
                                          ->where('refund_date','=' , $temp_date)
                                      ->count();

                // 计算当日退费课时数量
                $temp_hour_num = DB::table('refund')
                                    ->whereIn('refund_department', $department_query)
                                          ->where('refund_date','=' , $temp_date)
                                    ->sum('refund_total_hour');
                // 存入数组
                $ids[] = date('m-d', strtotime($temp_date));
                $refund_amounts[] = $temp_refund_amount;
                $refund_nums[] = $temp_refund_num;
                $hour_nums[] = $temp_hour_num;
                // 日期加一
                $temp_date = date('Y-m-d', strtotime ("-1 day", strtotime($temp_date)));
                $i++;
            }
        }else{ // 校区
            foreach($department_query AS $department_id){
                // 计算总退费金额
                $temp_refund_amount = DB::table('refund')
                                          ->where('refund_department', $department_id)
                                          ->where('refund_date','>=' , $start_date)
                                          ->where('refund_date','<=' , $end_date)
                                          ->sum('refund_actual_amount');

                // 计算退费次数
                $temp_refund_num = DB::table('refund')
                                      ->where('refund_department', $department_id)
                                      ->where('refund_date','>=' , $start_date)
                                      ->where('refund_date','<=' , $end_date)
                                      ->count();

                // 计算退费课时数量
                $temp_hour_num = DB::table('refund')
                                    ->where('refund_department', $department_id)
                                    ->where('refund_date','>=' , $start_date)
                                    ->where('refund_date','<=' , $end_date)
                                    ->sum('refund_total_hour');
                $ids[] = $department_array[$department_id][1];
                $refund_amounts[] = $temp_refund_amount;
                $refund_nums[] = $temp_refund_num;
                $hour_nums[] = $temp_hour_num;
            }
        }
        return view('finance/refund', ['start_date' => $start_date,
                                         'end_date' => $end_date,
                                         'user_departments' => $user_departments,
                                         'department_array' => $department_array,
                                         'analysis_method' => $analysis_method,
                                         'total_refund_amount' => $total_refund_amount,
                                         'total_refund_num' => $total_refund_num,
                                         'total_hour_num' => $total_hour_num,
                                         'ids' => $ids,
                                         'refund_amounts' => $refund_amounts,
                                         'refund_nums' => $refund_nums,
                                         'hour_nums' => $hour_nums]);
    }
}
