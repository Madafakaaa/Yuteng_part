<?php
namespace App\Http\Controllers\Finance;

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
        // 获取月份
        $month = date('Y-m');
        if ($request->filled('month')) {
            $month=$request->input("month");
        }
        //获取上一月月份
        $last_month = date('Y-m', strtotime ('-1 month', strtotime($month)));
        // 获取dashboard数据
        $dashboard = array();
        // 获取当月总合同金额
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
                                  ->orderBy('department_contract_num', 'desc')
                                  ->orderBy('department_total_hour', 'desc')
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
                            ->orderBy('user_contract_num', 'desc')
                            ->orderBy('user_total_hour', 'desc')
                            ->get();
        // 返回列表视图
        return view('finance/contract', ['dashboard' => $dashboard,
                                         'department_contracts' => $department_contracts,
                                         'user_contracts' => $user_contracts,
                                         'month' => $month]);
    }

    public function contractDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_type" => null,
                        "filter_date_start" => date('Y-m')."-01",
                        "filter_date_end" => date('Y-m-d')
                    );
        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->whereIn('contract_department', $department_access);
        // 校区
        if ($request->filled('filter_department')) {
            $rows = $rows->where('student_department', '=', $request->input("filter_department"));
            $filters['filter_department']=$request->input("filter_department");
        }
        // 类型
        if ($request->filled('filter_type')) {
            $rows = $rows->where('contract_type', '=', $request->input('filter_type')-1);
            $filters['filter_type']=$request->input("filter_type");
        }
        // 期限
        if ($request->filled('filter_date_start')) {
            $filters['filter_date_start']=$request->input("filter_date_start");
        }
        if ($request->filled('filter_date_end')) {
            $filters['filter_date_end']=$request->input("filter_date_end");
        }
        $rows = $rows->where('contract_date', '>=', $filters['filter_date_start']);
        $rows = $rows->where('contract_date', '<=', $filters['filter_date_end']);
        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_date', 'desc')
                     ->get();

        // 转为数组并获取详细课程信息
        $dashboard = array(
                             "dashboard_contract_num" => 0,
                             "dashboard_hour_total" => 0,
                             "dashboard_price_total" => 0,
                             "dashboard_paid_total" => 0,
                           );
        $contracts = array();
        foreach($rows as $row){
            $temp=array();
            $temp['department_name']=$row->department_name;
            $temp['contract_id']=$row->contract_id;
            $temp['contract_date']=$row->contract_date;
            $temp['user_id']=$row->user_id;
            $temp['user_name']=$row->user_name;
            $temp['student_id']=$row->student_id;
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
                $dashboard['dashboard_hour_total']+=$contract_course->contract_course_total_hour;
            }
            $temp['contract_course_num']=count($temp['contract_courses']);
            $contracts[]=$temp;
            // 更新dashboard
            $dashboard['dashboard_contract_num']++;
            $dashboard['dashboard_price_total']+=$temp['contract_total_price'];
            $dashboard['dashboard_paid_total']+=$temp['contract_paid_price'];
        }

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('finance/contractDepartment', ['contracts' => $contracts,
                                                   'dashboard' => $dashboard,
                                                   'filters' => $filters,
                                                   'filter_departments' => $filter_departments,
                                                   'filter_grades' => $filter_grades]);
    }

    public function contractUser(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 搜索条件
        $filters = array(
                        "filter_user" => null,
                        "filter_type" => null,
                        "filter_date_start" => date('Y-m')."-01",
                        "filter_date_end" => date('Y-m-d')
                    );
        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->whereIn('contract_department', $department_access);
        // 用户
        if ($request->filled('filter_user')) {
            $rows = $rows->where('contract_createuser', '=', $request->input("filter_user"));
            $filters['filter_user']=$request->input("filter_user");
        }
        // 类型
        if ($request->filled('filter_type')) {
            $rows = $rows->where('contract_type', '=', $request->input('filter_type')-1);
            $filters['filter_type']=$request->input("filter_type");
        }
        // 期限
        if ($request->filled('filter_date_start')) {
            $filters['filter_date_start']=$request->input("filter_date_start");
        }
        if ($request->filled('filter_date_end')) {
            $filters['filter_date_end']=$request->input("filter_date_end");
        }
        $rows = $rows->where('contract_date', '>=', $filters['filter_date_start']);
        $rows = $rows->where('contract_date', '<=', $filters['filter_date_end']);
        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_date', 'desc')
                     ->get();

        // 转为数组并获取详细课程信息
        $dashboard = array(
                             "dashboard_contract_num" => 0,
                             "dashboard_hour_total" => 0,
                             "dashboard_price_total" => 0,
                             "dashboard_paid_total" => 0,
                           );
        $contracts = array();
        foreach($rows as $row){
            $temp=array();
            $temp['department_name']=$row->department_name;
            $temp['contract_id']=$row->contract_id;
            $temp['contract_date']=$row->contract_date;
            $temp['user_id']=$row->user_id;
            $temp['user_name']=$row->user_name;
            $temp['student_id']=$row->student_id;
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
                $dashboard['dashboard_hour_total']+=$contract_course->contract_course_total_hour;
            }
            $temp['contract_course_num']=count($temp['contract_courses']);
            $contracts[]=$temp;
            // 更新dashboard
            $dashboard['dashboard_contract_num']++;
            $dashboard['dashboard_price_total']+=$temp['contract_total_price'];
            $dashboard['dashboard_paid_total']+=$temp['contract_paid_price'];
        }

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_users = DB::table('user')->where('user_status', 1)->whereIn('user_department', $department_access)->orderBy('user_department', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('finance/contractUser', ['contracts' => $contracts,
                                                   'dashboard' => $dashboard,
                                                   'filters' => $filters,
                                                   'filter_users' => $filter_users,
                                                   'filter_grades' => $filter_grades]);
    }

}
