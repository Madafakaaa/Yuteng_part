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
        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_month" => date('Y-m')
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
        // 年级
        //if ($request->filled('filter_grade')) {
            //$rows = $rows->where('student_grade', '=', $request->input('filter_grade'));
            //$filters['filter_grade']=$request->input("filter_grade");
        //}

        // 月份
        if ($request->filled('filter_month')) {
            $filters['filter_month']=$request->input("filter_month");
        }
        $rows = $rows->where('contract_date', 'like', $filters['filter_month']."%");
        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_date', 'desc')
                     ->get();

        // 转为数组并获取详细课程信息
        $dashboard = array(
                             "dashboard_contract_num" => 0,
                             "dashboard_contract_num_today" => 0,
                             "dashboard_price_total" => 0,
                             "dashboard_price_total_today" => 0
                           );
        $contracts = array();
        foreach($rows as $row){
            $temp=array();
            $temp['department_name']=$row->department_name;
            $temp['contract_date']=$row->contract_date;
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
            // 更新dashboard
            $dashboard['dashboard_contract_num']++;
            $dashboard['dashboard_price_total']+=$temp['contract_total_price'];
            if($temp['contract_date']==date('Y-m-d')){
                $dashboard['dashboard_contract_num_today']++;
                $dashboard['dashboard_price_total_today']+=$temp['contract_total_price'];
            }
        }

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_id', 'asc')->get();

        // 返回列表视图
        return view('finance/contract', ['contracts' => $contracts,
                                         'dashboard' => $dashboard,
                                         'filters' => $filters,
                                         'filter_departments' => $filter_departments,
                                         'filter_grades' => $filter_grades]);
    }

}
