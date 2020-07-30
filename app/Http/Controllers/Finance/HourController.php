<?php
namespace App\Http\Controllers\Finance;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class HourController extends Controller
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
    public function Hour(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取月份
        $month = date('Y-m');
        if ($request->filled('month')) {
            $month=$request->input("month");
        }
        //获取上一月月份
        $last_month = date('Y-m', strtotime ('-1 month', strtotime($month)));
        // 获取dashboard数据
        $dashboard = array();
        // 获取当月总上课次数
        $dashboard['sum_contract_price'] = DB::table('contract')
                                             ->join('student', 'contract.contract_student', '=', 'student.student_id')
                                             ->whereIn('contract_department', $department_access)
                                             ->where('contract_date', 'like', $month."%")
                                             ->sum('contract_total_price');
        // 获取上月总合同金额
        $sum_contract_price_last = DB::table('contract')
                                     ->join('student', 'contract.contract_student', '=', 'student.student_id')
                                     ->whereIn('contract_department', $department_access)
                                     ->where('contract_date', 'like', $last_month."%")
                                     ->sum('contract_total_price');
        if($sum_contract_price_last==0){
            $dashboard['sum_contract_price_change'] = 0;
        }else{
            $dashboard['sum_contract_price_change'] = round(100*($dashboard['sum_contract_price']-$sum_contract_price_last)/$sum_contract_price_last, 2);
        }
        // 获取当月总课时数量
        $dashboard['sum_hour_num'] = DB::table('contract')
                                       ->whereIn('contract_department', $department_access)
                                       ->where('contract_date', 'like', $month."%")
                                       ->sum('contract_total_hour');
        // 获取上月总课时数量
        $sum_hour_num_last = DB::table('contract')
                               ->whereIn('contract_department', $department_access)
                               ->where('contract_date', 'like', $last_month."%")
                               ->sum('contract_total_hour');
        if($sum_hour_num_last==0){
            $dashboard['sum_hour_num_change'] = 0;
        }else{
            $dashboard['sum_hour_num_change'] = round(100*($dashboard['sum_hour_num']-$sum_hour_num_last)/$sum_hour_num_last, 2);
        }
        // 获取当月总合同数量
        $dashboard['sum_contract_num'] = DB::table('contract')
                                           ->whereIn('contract_department', $department_access)
                                           ->where('contract_date', 'like', $month."%")
                                           ->count();
        // 获取上月总合同数量
        $sum_contract_num_last = DB::table('contract')
                                   ->whereIn('contract_department', $department_access)
                                   ->where('contract_date', 'like', $last_month."%")
                                   ->count();
        if($sum_contract_num_last==0){
            $dashboard['sum_contract_num_change'] = 0;
        }else{
            $dashboard['sum_contract_num_change'] = round(100*($dashboard['sum_contract_num']-$sum_contract_num_last)/$sum_contract_num_last, 2);
        }
        // 获取当月新签合同数量
        $dashboard['sum_new_contract_num'] = DB::table('contract')
                                           ->whereIn('contract_department', $department_access)
                                           ->where('contract_date', 'like', $month."%")
                                           ->where('contract_type', 0)
                                           ->count();
        // 获取上月新签合同数量
        $sum_new_contract_num_last = DB::table('contract')
                                   ->whereIn('contract_department', $department_access)
                                   ->where('contract_date', 'like', $last_month."%")
                                   ->where('contract_type', 0)
                                   ->count();
        if($sum_new_contract_num_last==0){
            $dashboard['sum_new_contract_num_change'] = 0;
        }else{
            $dashboard['sum_new_contract_num_change'] = round(100*($dashboard['sum_new_contract_num']-$sum_new_contract_num_last)/$sum_new_contract_num_last, 2);
        }
        // 获取每个校区签约信息
        $department_contracts = DB::table('department')
                                  ->leftJoin('contract', 'contract.contract_department', '=', 'department.department_id')
                                  ->select(DB::raw('count(*) as department_contract_num, sum(contract_total_price) as department_total_price, sum(contract_total_hour) as department_total_hour, department_id, department_name'))
                                  ->whereIn('contract_department', $department_access)
                                  ->where('contract_date', 'like', $month."%")
                                  ->groupBy('contract_department')
                                  ->orderBy('department_total_price', 'desc')
                                  ->get();
        // 获取每个用户签约信息
        $user_contracts = DB::table('contract')
                            ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                            ->join('department', 'user.user_department', '=', 'department.department_id')
                            ->select(DB::raw('count(*) as user_contract_num, sum(contract_total_price) as user_total_price, sum(contract_total_hour) as user_total_hour, user_id, user_name, department_name'))
                            ->whereIn('contract_department', $department_access)
                            ->where('contract_date', 'like', $month."%")
                            ->groupBy('contract_createuser')
                            ->orderBy('user_total_price', 'desc')
                            ->get();
        // 返回列表视图
        return view('finance/hour', ['dashboard' => $dashboard,
                                     'department_hours' => $department_hours,
                                     'user_hours' => $user_hours,
                                     'month' => $month]);
    }

}
