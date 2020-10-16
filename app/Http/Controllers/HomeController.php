<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * 主界面显示
     * URL: GET /home
     */
    public function home()
    {
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取当月月份
        $curr_month = date('Y-m');
        // 获取业绩排名
        $contracts = DB::table('contract')
                       ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                       ->join('department', 'user.user_department', '=', 'department.department_id')
                       ->select(DB::raw('user_id, user_name, department_name, count(*) as contract_num, sum(contract_total_price) as sum_contract_total_price, sum(contract_paid_price) as sum_contract_paid_price'))
                       ->whereIn('contract_department', $department_access)
                       ->where('contract_date', "like", $curr_month."%")
                       ->groupBy('contract_createuser')
                       ->orderBy('sum_contract_total_price', 'desc')
                       ->limit(10)
                       ->get();
        // 获取课消排名
        $consumptions = DB::table('participant')
                         ->join('schedule', 'schedule.schedule_id', '=', 'participant.participant_schedule')
                         ->join('user', 'schedule.schedule_teacher', '=', 'user.user_id')
                         ->join('department', 'user.user_department', '=', 'department.department_id')
                         ->select(DB::raw('user_id, user_name, department_name, count(distinct schedule_id) as schedule_num, sum(participant_amount) as sum_participant_amount'))
                         ->where('schedule_attended', 1)
                         ->whereIn('schedule_department', $department_access)
                         ->where('schedule_date', "like", $curr_month."%")
                         ->groupBy('schedule_teacher')
                         ->orderBy('sum_participant_amount', 'desc')
                         ->limit(10)
                         ->get();
        // 获取低课时
        $hours = DB::table('hour')
                   ->join('student', 'student.student_id', '=', 'hour.hour_student')
                   ->join('department', 'student.student_department', '=', 'department.department_id')
                   ->join('course', 'course.course_id', '=', 'hour.hour_course')
                   ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                   ->whereIn('student_department', $department_access)
                   ->where('student_status', 1)
                   ->where('hour_remain', '<=', 6)
                   ->orderBy('hour_remain', 'desc')
                   ->orderBy('student_department', 'asc')
                   ->orderBy('student_grade', 'desc')
                   ->get();
        // 已登录,返回主页视图
        return view('/dashboard', ['contracts' => $contracts, 'consumptions' => $consumptions, 'hours' => $hours]);
    }

}
