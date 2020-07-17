<?php
namespace App\Http\Controllers\Market;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CustomerController extends Controller
{

    public function customer(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                  ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                  ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                  ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->whereIn('student_department', $department_access)
                  ->where('student_contract_num', 0)
                  ->where('student_status', 1);
        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                        "filter_grade" => null,
                        "filter_name" => null,
                        "filter_consultant" => null,
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
        // 判断是否有搜索框内条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter_name')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter_name').'%');
            $filters['filter_name']=$request->input("filter_name");
            $filter_status = 1;
        }
        // 课程顾问
        if ($request->filled('filter_consultant')) {
            $rows = $rows->where('student_consultant', '=', $request->input('filter_consultant'));
            $filters['filter_consultant']=$request->input("filter_consultant");
            $filter_status = 1;
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->select('student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'student.student_gender AS student_gender',
                              'student.student_guardian AS student_guardian',
                              'student.student_guardian_relationship AS student_guardian_relationship',
                              'student.student_phone AS student_phone',
                              'student.student_follow_level AS student_follow_level',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'department.department_id AS department_id',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')
                                ->where('department_status', 1)
                                ->whereIn('department_id', $department_access)
                                ->orderBy('department_id', 'asc')
                                ->get();
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
        return view('market/customer/customer', ['rows' => $rows,
                                               'currentPage' => $currentPage,
                                               'totalPage' => $totalPage,
                                               'startIndex' => $offset,
                                               'filters' => $filters,
                                               'request' => $request,
                                               'totalNum' => $totalNum,
                                               'filter_status' => $filter_status,
                                               'filter_departments' => $filter_departments,
                                               'filter_grades' => $filter_grades,
                                               'filter_users' => $filter_users]);
    }

    public function customerCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取校区、来源、用户、年级信息
        $departments = DB::table('department')
                         ->where('department_status', 1)
                         ->where('department_id', Session::get('user_department'))
                         ->orderBy('department_id', 'asc')
                         ->get();
        $sources = DB::table('source')
                     ->where('source_status', 1)
                     ->orderBy('source_id', 'asc')
                     ->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->whereIn('user_department', $department_access)
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->get();
        $grades = DB::table('grade')
                    ->where('grade_status', 1)
                    ->orderBy('grade_id', 'asc')->get();
        $schools = DB::table('school')
                     ->whereIn('school_department', $department_access)
                     ->where('school_status', 1)
                     ->orderBy('school_id', 'asc')
                     ->get();
        return view('market/customer/customerCreate', ['departments' => $departments,
                                                    'sources' => $sources,
                                                    'users' => $users,
                                                    'schools' => $schools,
                                                    'grades' => $grades]);
    }

    public function customerStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_department = $request->input('input0');
        if($request->filled('input1')) {
            $student_consultant = $request->input('input1');
        }else{
            $student_consultant = '';
        }
        $student_name = $request->input('input2');
        $student_gender = $request->input('input3');
        $student_grade = $request->input('input4');
        if($request->filled('input5')) {
            $student_school = $request->input('input5');
        }else{
            $student_school = 0;
        }
        $student_guardian = $request->input('input6');
        $student_guardian_relationship = $request->input('input7');
        $student_phone = $request->input('input8');
        if($request->filled('input9')) {
            $student_wechat = $request->input('input9');
        }else{
            $student_wechat = '无';
        }
        $student_source = $request->input('input10');
        $student_birthday = $request->input('input11');
        $student_follow_level = $request->input('input12');
        if($request->filled('input13')) {
            $student_remark = $request->input('input13');
        }else{
            $student_remark = '无';
        }
        // 获取当前用户ID
        $student_createuser = Session::get('user_id');
        // 生成新学生ID
        // 获取本校区本月学生数量
        $student_num = DB::table('student')
                         ->where('student_id', 'like', "S".substr(date('Ym'),2).sprintf("%02d", $student_department)."%")
                         ->count();
        if($student_num>900){
            return redirect("/market/customer/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '客户添加失败',
                           'message' => '本校本月已经添加超过900学生，超出本月上限，错误码:201']);
        }
        if($student_num==0){
            $student_id = "S".substr(date('Ym'),2).sprintf("%02d", $student_department).sprintf("%03d", 1);
        }else{
            //获取上一个学生学号
            $pre_student_id = DB::table('student')
                                ->where('student_id', 'like', "S".substr(date('Ym'),2).sprintf("%02d", $student_department)."%")
                                ->orderBy('student_id', 'desc')
                                ->limit(1)
                                ->first();
            $new_student_num = intval(substr($pre_student_id->student_id , 7 , 10))+1;
            $student_id = "S".substr(date('Ym'),2).sprintf("%02d", $student_department).sprintf("%03d", $new_student_num);
        }
        // 获取课程顾问姓名
        $consultant_name = "无 (公共)";
        if($student_consultant!=''){
            $consultant_name = DB::table('user')
                               ->where('user_id', $student_consultant)
                               ->value('user_name');
        }
        // 插入数据库
        DB::beginTransaction();
        try{
            DB::table('student')->insert(
                ['student_id' => $student_id,
                 'student_name' => $student_name,
                 'student_department' => $student_department,
                 'student_grade' => $student_grade,
                 'student_gender' => $student_gender,
                 'student_birthday' => $student_birthday,
                 'student_school' => $student_school,
                 'student_guardian' => $student_guardian,
                 'student_guardian_relationship' => $student_guardian_relationship,
                 'student_phone' => $student_phone,
                 'student_wechat' => $student_wechat,
                 'student_consultant' => $student_consultant,
                 'student_class_adviser' => '',
                 'student_source' => $student_source,
                 'student_follow_level' => $student_follow_level,
                 'student_remark' => $student_remark,
                 'student_last_follow_date' => date('Y-m-d'),
                 'student_createuser' => $student_createuser]
            );
            // 插入学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '新建档案',
                 'student_record_content' => "新建学生档案。新建人：".Session::get('user_name')."。课程顾问：".$consultant_name."。",
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/market/customer/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '客户添加失败',
                           'message' => '客户添加失败，该学生已经存在于本校区，错误码:202']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/market/customer/success?id=".encode($student_id, 'student_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '客户添加成功',
                       'message' => '客户添加成功']);
    }


    public function customerSuccess(Request $request){
        return view('market/customer/customerCreateSuccess', ['id' => $request->input('id')]);
    }

    /**
     * 修改客户课程顾问视图
     * URL: GET /market/follower/edit2
     */
    public function consultantEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = decode($request->input('id'), 'student_id');
        // 获取学生信息
        $student = DB::table('student')
                      ->leftJoin('user', 'student.student_consultant', '=', 'user.user_id')
                      ->leftJoin('position', 'user.user_position', '=', 'position.position_id')
                      ->where('student_id', $student_id)
                      ->first();
        // 获取课程顾问信息
        $users = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_department', $student->student_department)
                  ->where('user_status', 1)
                  ->get();
        return view('market/customer/consultantEdit', ['student' => $student, 'users' => $users]);
    }

    /**
     * 修改负责人提交
     * URL: POST /market/customer/consultant/store
     */
    public function consultantUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = decode($request->input('input1'), 'student_id');
        if($request->filled('input2')) {
            $student_consultant = $request->input('input2');
        }else{
            $student_consultant = "";
        }
        if($request->filled('input3')) {
            $student_class_adviser = $request->input('input3');
        }else{
            $student_class_adviser = "";
        }
        // 插入数据库
        DB::beginTransaction();
        try{
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_consultant' =>  $student_consultant,
                        'student_class_adviser' =>  $student_class_adviser]);
            // 插入学生动态
            //
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/market/follower/edit")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '负责人修改失败',
                           'message' => '负责人修改失败，错误码:203']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/market/customer")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '负责人修改成功',
                      'message' => '负责人修改成功']);
    }

    public function customerDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取student_id
        $request_ids=$request->input('id');
        $student_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $student_ids[]=decode($request_id, 'student_id');
            }
        }else{
            $student_ids[]=decode($request_ids, 'student_id');
        }
        // 删除数据
        try{
            foreach ($student_ids as $student_id){
                DB::table('student')
                  ->where('student_id', $student_id)
                  ->delete();
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/market/customer")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '客户删除失败',
                         'message' => '客户删除失败，错误码:204']);
        }
        // 返回课程列表
        return redirect("/market/customer")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '客户删除成功',
                       'message' => '客户删除成功!']);
    }
}
