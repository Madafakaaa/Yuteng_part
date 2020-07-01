<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ContractController extends Controller
{

    /**
     * 查看合同视图
     * URL: GET /contract
     * @param  int  $contract_id
     */
    public function show(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $contract_id = decode($request->input('id'), 'contract_id');
        // 获取数据信息
        $contract = DB::table('contract')
                      ->join('student', 'contract.contract_student', '=', 'student.student_id')
                      ->join('department', 'contract.contract_department', '=', 'department.department_id')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                      ->where('contract_id', $contract_id)
                      ->get();
        $contract_courses = DB::table('contract_course')
                              ->join('course', 'contract_course.contract_course_course', '=', 'course.course_id')
                              ->where('contract_course.contract_course_contract', $contract_id)
                              ->get();
        // 检验数据是否存在
        if($contract->count()!==1){
            // 未获取到数据
            return redirect()->action('ContractController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '合同显示失败',
                                     'message' => '合同显示失败，请联系系统管理员']);
        }
        // 获取数据对象
        $contract = $contract[0];
        return view('contract', ['contract' => $contract,
                                      'contract_courses' => $contract_courses]);
    }

}
