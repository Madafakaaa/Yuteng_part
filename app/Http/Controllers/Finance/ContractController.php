<?php
namespace App\Http\Controllers\Finance;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ContractController extends Controller
{

    public function contractDepartment(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/finance/contract/department", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
        // 检测用户权限
        if(!in_array("/finance/contract/user", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
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
        $filter_users = DB::table('user')->join('department', 'user.user_department', '=', 'department.department_id')->where('user_status', 1)->whereIn('user_department', $department_access)->orderBy('user_department', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('finance/contractUser', ['contracts' => $contracts,
                                                   'dashboard' => $dashboard,
                                                   'filters' => $filters,
                                                   'filter_users' => $filter_users,
                                                   'filter_grades' => $filter_grades]);
    }

}
