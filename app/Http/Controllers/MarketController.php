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
     * 公共客户录入视图
     * URL: GET /market/publicCustomer/create
     */
    public function publicCustomerCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取校区、来源、用户、年级信息
        $departments = DB::table('department')
                         ->where('department_status', 1)
                         ->whereIn('department_id', $department_access)
                         ->orderBy('department_id', 'asc')
                         ->get();
        $sources = DB::table('source')
                     ->where('source_status', 1)
                     ->orderBy('source_id', 'asc')
                     ->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->where('user_department', Session::get('user_department'))
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->get();
        $grades = DB::table('grade')
                    ->where('grade_status', 1)
                    ->orderBy('grade_id', 'asc')->get();
        $schools = DB::table('school')
                     ->where('school_department', Session::get('user_department'))
                     ->where('school_status', 1)
                     ->orderBy('school_id', 'asc')
                     ->get();
        return view('market/publicCustomerCreate', ['departments' => $departments,
                                                    'sources' => $sources,
                                                    'users' => $users,
                                                    'schools' => $schools,
                                                    'grades' => $grades]);
    }

    /**
     * 公共客户录入提交
     * URL: POST /market/publicCustomer/create
     * @param  Request  $request
     * @param  $request->input('input0'): 校区
     * @param  $request->input('input1'): 负责人
     * @param  $request->input('input2'): 学生姓名
     * @param  $request->input('input3'): 学生性别
     * @param  $request->input('input4'): 学生年级
     * @param  $request->input('input5'): 公立学校
     * @param  $request->input('input6'): 监护人姓名
     * @param  $request->input('input7'): 监护人关系
     * @param  $request->input('input8'): 联系电话
     * @param  $request->input('input9'): 微信号
     * @param  $request->input('input10'): 来源类型
     * @param  $request->input('input11'): 学生生日
     * @param  $request->input('input12'): 跟进优先级
     * @param  $request->input('input13'): 备注
     */
    public function publicCustomerStore(Request $request){
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
        $student_num = DB::table('student')
                         ->where('student_department', $student_department)
                         ->whereYear('student_createtime', date('Y'))
                         ->whereMonth('student_createtime', date('m'))
                         ->count()+1;
        $student_id = "S".substr(date('Ym'),2).sprintf("%02d", $student_department).sprintf("%03d", $student_num);
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
            return redirect("/market/publicCustomer/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '客户添加失败',
                           'message' => '客户添加失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/market/customer/all")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '客户添加成功',
                      'message' => '客户添加成功']);
    }

    /**
     * 我的客户录入视图
     * URL: GET /market/myCustomer/create
     */
    public function myCustomerCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取校区、来源、用户、年级信息
        $departments = DB::table('department')
                         ->where('department_status', 1)
                         ->whereIn('department_id', $department_access)
                         ->orderBy('department_id', 'asc')
                         ->get();
        $sources = DB::table('source')
                     ->where('source_status', 1)
                     ->orderBy('source_id', 'asc')
                     ->get();
        $users = DB::table('user')
                   ->join('position', 'user.user_position', '=', 'position.position_id')
                   ->where('user_department', Session::get('user_department'))
                   ->where('user_status', 1)
                   ->orderBy('position_level', 'desc')
                   ->get();
        $grades = DB::table('grade')
                    ->where('grade_status', 1)
                    ->orderBy('grade_id', 'asc')->get();
        $schools = DB::table('school')
                     ->where('school_department', Session::get('user_department'))
                     ->where('school_status', 1)
                     ->orderBy('school_id', 'asc')
                     ->get();
        return view('market/myCustomerCreate', ['departments' => $departments,
                                                'sources' => $sources,
                                                'users' => $users,
                                                'schools' => $schools,
                                                'grades' => $grades]);
    }

    /**
     * 我的客户录入提交
     * URL: POST /market/myCustomer/create
     * @param  Request  $request
     * @param  $request->input('input0'): 校区
     * @param  $request->input('input1'): 负责人
     * @param  $request->input('input2'): 学生姓名
     * @param  $request->input('input3'): 学生性别
     * @param  $request->input('input4'): 学生年级
     * @param  $request->input('input5'): 公立学校
     * @param  $request->input('input6'): 监护人姓名
     * @param  $request->input('input7'): 监护人关系
     * @param  $request->input('input8'): 联系电话
     * @param  $request->input('input9'): 微信号
     * @param  $request->input('input10'): 来源类型
     * @param  $request->input('input11'): 学生生日
     * @param  $request->input('input12'): 跟进优先级
     * @param  $request->input('input13'): 备注
     */
    public function myCustomerStore(Request $request){
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
        $student_num = DB::table('student')
                         ->where('student_department', $student_department)
                         ->whereYear('student_createtime', date('Y'))
                         ->whereMonth('student_createtime', date('m'))
                         ->count()+1;
        $student_id = "S".substr(date('Ym'),2).sprintf("%02d", $student_department).sprintf("%03d", $student_num);
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
            return redirect("/market/publicCustomer/create")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '客户添加失败',
                           'message' => '客户添加失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/market/customer/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '客户添加成功',
                      'message' => '客户添加成功']);
    }

    /**
     * 修改负责人视图
     * URL: GET /market/follower/edit
     */
    public function followerEdit(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取表单输入
        if($request->filled('student_id')) {
            $student_id = $request->input('student_id');
        }else{
            $student_id = '';
        }
        // 获取学生信息
        $students = DB::table('student')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->whereIn('student_department', $department_access)
                      ->orderBy('student_grade', 'asc')
                      ->get();
        return view('market/followerEdit', ['students' => $students, 'student_id' => $student_id]);
    }

    /**
     * 修改负责人视图2
     * URL: GET /market/follower/edit2
     */
    public function followerEdit2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('input1');
        // 获取学生信息
        $student = DB::table('student')
                      ->leftJoin('user AS consultant', 'student.student_consultant', '=', 'consultant.user_id')
                      ->leftJoin('position AS consultant_position', 'consultant.user_position', '=', 'consultant_position.position_id')
                      ->leftJoin('user AS class_adviser', 'student.student_class_adviser', '=', 'class_adviser.user_id')
                      ->leftJoin('position AS class_adviser_position', 'class_adviser.user_position', '=', 'class_adviser_position.position_id')
                      ->where('student_id', $student_id)
                      ->select('student.student_id AS student_id',
                                'student.student_name AS student_name',
                                'student.student_customer_status AS student_customer_status',
                                'student.student_consultant AS student_consultant',
                                'student.student_class_adviser AS student_class_adviser',
                                'consultant.user_name AS consultant_name',
                                'consultant_position.position_name AS consultant_position_name',
                                'class_adviser.user_name AS class_adviser_name',
                                'class_adviser_position.position_name AS class_adviser_position_name')
                      ->first();
        // 获取负责人信息
        $users = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_department', Session::get('user_department'))
                  ->where('user_status', 1)
                  ->get();
        return view('market/followerEdit2', ['student' => $student, 'users' => $users]);
    }

    /**
     * 修改负责人提交
     * URL: GET /market/follower/store
     */
    public function followerStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        $student_id = $request->input('input1');
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
                           'message' => '负责人修改失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/market/customer/all")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '负责人修改成功',
                      'message' => '负责人修改成功']);
    }

    /**
     * 客户管理视图
     * URL: GET /market/customer/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function customerAll(Request $request){
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
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 客户优先级
        if ($request->filled('filter4')) {
            $rows = $rows->where('student_follow_level', '=', $request->input('filter4'));
            $filter_status = 1;
        }
        // 客户签约状态
        if ($request->filled('filter5')) {
            $rows = $rows->where('student_customer_status', '=', $request->input('filter5'));
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
                              'student.student_customer_status AS student_customer_status',
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
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('market/customerAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 我的客户视图
     * URL: GET /market/customer/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function customerMy(Request $request){
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
                  ->where('student_consultant', Session::get('user_id'))
                  ->where('student_customer_status', 0)
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }
        // 客户优先级
        if ($request->filled('filter4')) {
            $rows = $rows->where('student_follow_level', '=', $request->input('filter4'));
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
                              'student.student_customer_status AS student_customer_status',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('market/customerMy', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 我的学生视图
     * URL: GET /market/student/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function studentMy(Request $request){
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
                  ->where('student_consultant', Session::get('user_id'))
                  ->where('student_customer_status', 1)
                  ->where('student_status', 1);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
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
                              'student.student_first_contract_date AS student_first_contract_date',
                              'student.student_last_follow_date AS student_last_follow_date',
                              'student.student_customer_status AS student_customer_status',
                              'department.department_name AS department_name',
                              'grade.grade_name AS grade_name',
                              'consultant.user_name AS consultant_name',
                              'consultant_position.position_name AS consultant_position_name',
                              'class_adviser.user_name AS class_adviser_name',
                              'class_adviser_position.position_name AS class_adviser_position_name')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 获取一个月前日期
        $date = date('Y-m-d');
        $date_month_ago = date('Y-m-d', strtotime ("-1 month", strtotime($date)));
        // 返回列表视图
        return view('market/studentMy', ['rows' => $rows,
                                         'date_month_ago' => $date_month_ago,
                                         'currentPage' => $currentPage,
                                         'totalPage' => $totalPage,
                                         'startIndex' => $offset,
                                         'request' => $request,
                                         'totalNum' => $totalNum,
                                         'filter_status' => $filter_status,
                                         'filter_departments' => $filter_departments,
                                         'filter_grades' => $filter_grades]);
    }

    /**
     * 签约合同视图
     * URL: GET /market/contract/create
     */
    public function contractCreate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        if($request->filled('student_id')) {
            $student_id = $request->input('student_id');
        }else{
            $student_id = '';
        }
        // 获取一个月前日期
        $date = date('Y-m-d');
        $date_month_ago = date('Y-m-d', strtotime ("-1 month", strtotime($date)));
        // 获取学生信息(未签约或一个月之内新签约)
        $students = DB::table('student')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->where([
                                 ['student_consultant', Session::get('user_id')],
                                 ['student_customer_status', 0],
                              ])
                      ->orWhere([
                                 ['student_consultant', Session::get('user_id')],
                                 ['student_first_contract_date', '>=', $date_month_ago],
                              ])
                      ->orderBy('student_grade', 'asc')
                      ->get();
        return view('market/contractCreate', ['students' => $students, 'student_id' => $student_id]);
    }

    /**
     * 签约合同视图2
     * URL: POST /market/contract/create2
     */
    public function contractCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('input1');
        // 获取学生信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取已购课程
        $hours = DB::table('hour')
                   ->join('course', 'course.course_id', '=', 'hour.hour_course')
                   ->join('contract', 'contract.contract_id', '=', 'hour.hour_contract')
                   ->where('hour_student', $student_id)
                   ->get();
        // 获取已选课程数
        if($request->filled('selected_course_num')){
            $selected_course_num = $request->input('selected_course_num');
        }else{
            $selected_course_num = 0;
        }
        // 获取已选课程ID
        $selected_course_ids = array();
        for($i=0; $i<$selected_course_num; $i++){
            $selected_course_ids[]=$request->input("course{$i}");
        }
        // 获取新选课程
        if($request->filled('input2')){
            foreach($request->input('input2') as $new_course){
                $selected_course_num = $selected_course_num + 1;
                $selected_course_ids[]=$new_course;
            }
        }
        // 获取删除课程
        if($request->filled('input3')){
            $selected_course_num = $selected_course_num - 1;
            $key = array_search($request->input('input3'), $selected_course_ids);
            unset($selected_course_ids[$key]);
            $selected_course_ids = array_values($selected_course_ids);
        }
        // 获取所有已选课程数据库信息
        $selected_courses = array();
        for($i=0; $i<$selected_course_num; $i++){
            $selected_courses[] = DB::table('course')
                                    ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                                    ->where('course_id', $selected_course_ids[$i])
                                    ->first();
        }
        // 获取课程信息
        $courses = DB::table('course')
                     ->join('course_type', 'course.course_type', '=', 'course_type.course_type_name')
                     ->join('grade', 'course.course_grade', '=', 'grade.grade_id')
                     ->leftJoin('subject', 'course.course_subject', '=', 'subject.subject_id')
                     ->where('course_grade', $student->student_grade)
                     ->whereIn('course_department', [0, $student->student_department]);
        // 去除已选课程
        for($i=0; $i<$selected_course_num; $i++){
            $courses = $courses->where('course_id', '<>', $selected_course_ids[$i]);
        }
        $courses = $courses->where('course_status', 1)
                           ->orderBy('course_type', 'asc')
                           ->orderBy('course_grade', 'asc')
                           ->orderBy('course_time', 'asc')
                           ->get();
        // 获取支付方式
        $payment_methods = DB::table('payment_method')
                             ->where('payment_method_status', 1)
                             ->get();
        return view('market/contractCreate2', ['student' => $student,
                                             'hours' => $hours,
                                             'courses' => $courses,
                                             'payment_methods' => $payment_methods,
                                             'selected_course_ids' => $selected_course_ids,
                                             'selected_course_num' => $selected_course_num,
                                             'selected_courses' => $selected_courses]);
    }

    /**
     * 签约合同提交
     * URL: POST /market/contract/store
     * @param  Request  $request
     * @param  $request->input('student_id'): 购课学生
     * @param  $request->input('selected_course_num'): 购买课程数量
     */
    public function contractStore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取当前用户ID
        $contract_createuser = Session::get('user_id');
        // 获取表单输入
        $request_student_id = $request->input('student_id');
        $request_selected_course_num = $request->input('selected_course_num');
        $request_contract_payment_method = $request->input('payment_method');
        $request_contract_date = $request->input('contract_date');
        $request_contract_paid_price = $request->input('contract_paid_price');
        if($request->filled('remark')) {
            $request_contract_remark = $request->input('remark');
        }else{
            $request_contract_remark = "";
        }
        $request_contract_type = $request->input('contract_type');
        $request_contract_extra_fee = round((float)$request->input("extra_fee"), 2);
        $request_courses = array();
        // 生成新合同号(上一个合同号加一或新合同号001)
        $sub_student_id = substr($request_student_id , 1 , 10);
        if(DB::table('contract')->where('contract_student', $request_student_id)->exists()){
            $pre_contract_num = DB::table('contract')
                                  ->where('contract_student', $request_student_id)
                                  ->orderBy('contract_createtime', 'desc')
                                  ->limit(1)
                                  ->first();
            $new_contract_num = intval(substr($pre_contract_num->contract_id , 10 , 12))+1;
        }else{
            $new_contract_num = 1;
        }
        $contract_id = "H".$sub_student_id.sprintf("%02d", $new_contract_num);
        for($i=1; $i<=$request_selected_course_num; $i++){
            $temp = array();
            $temp[] = (int)$request->input("course_{$i}_0");
            $temp[] = round((float)$request->input("course_{$i}_1"), 2);
            $temp[] = round((float)$request->input("course_{$i}_2"), 1);
            $temp[] = round((float)$request->input("course_{$i}_3"), 2);
            $temp[] = round((float)$request->input("course_{$i}_4")/100, 2);
            $temp[] = round((float)$request->input("course_{$i}_5"), 2);
            $temp[] = round((float)$request->input("course_{$i}_6"), 1);
            $temp[] = round((float)$request->input("course_{$i}_7"), 1);
            $temp[] = round((float)$request->input("course_{$i}_8"), 2);
            $request_courses[] = $temp;
        }
        // 计算合同总信息
        $contract_student = $request_student_id;
        $contract_course_num = $request_selected_course_num;
        $contract_original_hour = 0;
        $contract_free_hour = 0;
        $contract_total_hour = 0;
        $contract_original_price = 0;
        $contract_discount_price = 0;
        $contract_total_price = 0;
        $contract_paid_price = $request_contract_paid_price;
        $contract_date = $request_contract_date;
        $contract_remark = $request_contract_remark;
        $contract_payment_method = $request_contract_payment_method;
        foreach($request_courses as $request_course){
            $contract_original_hour += $request_course[2];
            $contract_free_hour += $request_course[6];
            $contract_total_hour += $request_course[7];
            $contract_original_price += $request_course[3];
            $contract_total_price += $request_course[8];
        }
        $contract_discount_price = $contract_original_price - $contract_total_price;
        $contract_original_price = round($contract_original_price, 2);
        $contract_discount_price = round($contract_discount_price, 2);
        $contract_total_price = round($contract_total_price+$request_contract_extra_fee, 2);
        // 获取学生校区
        $student_department = DB::table('student')
                                ->where('student_id', $request_student_id)
                                ->first()
                                ->student_department;
        // 获取学生签约状态
        $student_customer_status = DB::table('student')
                                     ->where('student_id', $request_student_id)
                                     ->first()
                                     ->student_customer_status;
        DB::beginTransaction();
        // 插入数据库
        try{
            // 插入Contract表
            DB::table('contract')->insert(
                ['contract_id' => $contract_id,
                 'contract_department' => $student_department,
                 'contract_student' => $contract_student,
                 'contract_course_num' => $contract_course_num,
                 'contract_original_hour' => $contract_original_hour,
                 'contract_free_hour' => $contract_free_hour,
                 'contract_total_hour' => $contract_total_hour,
                 'contract_original_price' => $contract_original_price,
                 'contract_discount_price' => $contract_discount_price,
                 'contract_total_price' => $contract_total_price,
                 'contract_paid_price' => $contract_paid_price,
                 'contract_date' => $contract_date,
                 'contract_payment_method' => $contract_payment_method,
                 'contract_remark' => $contract_remark,
                 'contract_type' => $request_contract_type,
                 'contract_section' => 0,
                 'contract_extra_fee' => $request_contract_extra_fee,
                 'contract_createuser' => $contract_createuser]
            );
            foreach($request_courses as $request_course){
                $contract_course_discount_total = round(($request_course[3] - $request_course[8]), 2);
                $contract_course_actual_unit_price = round(($request_course[8]/$request_course[7]), 2);
                // 插入Contract_course表
                DB::table('contract_course')->insert(
                    ['contract_course_contract' => $contract_id,
                     'contract_course_course' => $request_course[0],
                     'contract_course_original_hour' => $request_course[2],
                     'contract_course_free_hour' => $request_course[6],
                     'contract_course_total_hour' => $request_course[7],
                     'contract_course_discount_rate' => $request_course[4],
                     'contract_course_discount_amount' => $request_course[5],
                     'contract_course_discount_total' => $contract_course_discount_total,
                     'contract_course_original_unit_price' => $request_course[1],
                     'contract_course_actual_unit_price' => $contract_course_actual_unit_price,
                     'contract_course_original_price' => $request_course[3],
                     'contract_course_total_price' => $request_course[8],
                     'contract_course_createuser' => $contract_createuser]
                );
                // 插入Hour表
                DB::table('hour')->insert(
                    ['hour_contract' => $contract_id,
                     'hour_student' => $contract_student,
                     'hour_course' => $request_course[0],
                     'hour_original' => $request_course[2]+$request_course[6],
                     'hour_remain' => $request_course[2],
                     'hour_used' => 0,
                     'hour_remain_free' => $request_course[6],
                     'hour_used_free' => 0,
                     'hour_createuser' => $contract_createuser]
                );
            }
            // 增加学生签约次数
            DB::table('student')
              ->where('student_id', $request_student_id)
              ->increment('student_contract_num');
            // 更新客户状态、最后签约时间
            DB::table('student')
              ->where('student_id', $request_student_id)
              ->update(['student_customer_status' =>  1,
                        'student_last_contract_date' =>  date('Y-m-d')]);
            // 更新学生首次签约时间
            if($request->input('contract_type')==0){
                DB::table('student')
                  ->where('student_id', $request_student_id)
                  ->update(['student_first_contract_date' =>  date('Y-m-d')]);
            }
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $request_student_id,
                 'student_record_type' => '签约合同',
                 'student_record_content' => '客户首次签约合同，合同号：'.$contract_id.'，课程种类：'.$contract_course_num.' 种，合计金额：'.$contract_total_price.' 元。签约人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            // 返回购课列表
            return redirect("/market/contract/create")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '购课添加失败',
                         'message' => '购课添加失败，请重新添加']);
        }
        DB::commit();
        // 获取学生、课程名称
        $student_name = DB::table('student')
                          ->where('student_id', $contract_student)
                          ->value('student_name');
        // 返回购课列表
        return redirect("/market/contract/my")
               ->with(['notify' => true,
                      'type' => 'success',
                      'title' => '购课添加成功',
                      'message' => '购课学生: '.$student_name]);
    }

    /**
     * 删除购课
     * URL: DELETE /market/contract/{contract_id}
     * @param  int  $contract_id
     */
    public function contractDelete($contract_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生信息
        $contract_student = DB::table('contract')
                              ->where('contract_id', $contract_id)
                              ->first()
                              ->contract_student;
        // 获取课程包中已使用Hour信息数量
        $invalid_hour_num = DB::table('hour')
                              ->where([
                                        ['hour_contract', '=', $contract_id],
                                        ['hour_used', ">", 0],
                                       ])
                              ->orWhere([
                                        ['hour_contract', '=', $contract_id],
                                        ['hour_used_free', ">", 0],
                                       ])
                              ->orWhere([
                                        ['hour_contract', '=', $contract_id],
                                        ['hour_refunded', ">", 0],
                                       ])
                              ->count();
        // 没有使用过课时
        if($invalid_hour_num==0){
            DB::beginTransaction();
            try{
                // 删除Hour表
                DB::table('hour')
                  ->where('hour_contract', $contract_id)
                  ->delete();
                // 删除Contract_course表
                DB::table('contract_course')
                  ->where('contract_course_contract', $contract_id)
                  ->delete();
                // 删除Contract表
                DB::table('contract')
                  ->where('contract_id', $contract_id)
                  ->delete();
                // 减少学生签约次数
                DB::table('student')
                  ->where('student_id', $contract_student)
                  ->decrement('student_contract_num');
                $student_contract_num = DB::table('student')
                                          ->where('student_id', $contract_student)
                                          ->first()
                                          ->student_contract_num;
                // 更新学生状态
                if($student_contract_num==0){
                    // 更新客户状态、最后签约时间
                    DB::table('student')
                      ->where('student_id', $contract_student)
                      ->update(['student_customer_status' =>  0,
                                'student_last_contract_date' => '2000-01-01']);
                }
            }
            // 捕获异常
            catch(Exception $e){
                DB::rollBack();
                return redirect("/market/contract/my")
                       ->with(['notify' => true,
                               'type' => 'danger',
                               'title' => '购课记录删除失败',
                               'message' => '购课记录删除失败，请联系系统管理员']);
            }
            DB::commit();
            // 返回购课列表
            return redirect("/market/contract/my")
                   ->with(['notify' => true,
                           'type' => 'success',
                           'title' => '购课记录删除成功',
                           'message' => '购课记录删除成功']);
        }else{
            return redirect("/market/contract/my")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '购课记录删除失败',
                           'message' => '学生剩余课时不足，购课记录删除失败。']);
        }
    }

    /**
     * 补缴费用
     * URL: GET /market/contract/{contract_id}
     * @param  int  $contract_id
     */
    public function contractEdit($contract_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据
        $contract = DB::table('contract')
                      ->join('student', 'contract.contract_student', '=', 'student.student_id')
                      ->join('department', 'student.student_department', '=', 'department.department_id')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                      ->join('position', 'user.user_position', '=', 'position.position_id')
                      ->where('contract_id', '=', $contract_id)
                      ->first();
        // 返回列表视图
        return view('market/contractEdit', ['contract' => $contract]);
    }

    /**
     * 更新费用
     * URL: POST /market/contract/{contract_id}
     * @param  int  $contract_id
     */
    public function contractUpdate(Request $request, $contract_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        if ($request->filled('input2')) {
            $contract_remark = $request->input('input2');
        }else{
            $contract_remark = "";
        }
        DB::beginTransaction();
        try{
            DB::table('contract')
              ->where('contract_id', $contract_id)
              ->update(['contract_paid_price' =>  $request->input('input1'),
                        'contract_remark' => $contract_remark]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/market/contract/my")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '缴费提交失败',
                           'message' => '缴费提交失败，请联系系统管理员']);
        }
        DB::commit();
        return redirect("/market/contract/my")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '缴费提交成功',
                       'message' => '缴费提交成功，请联系系统管理员']);
    }

    /**
     * 签约管理视图
     * URL: GET /market/contract/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function contractAll(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->whereIn('contract_department', $department_access)
                  ->where('contract_section', '=', 0);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();

        // 返回列表视图
        return view('market/contractAll', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_students' => $filter_students,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 我的签约视图
     * URL: GET /market/contract/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function contractMy(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('contract')
                  ->join('student', 'contract.contract_student', '=', 'student.student_id')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->join('user', 'contract.contract_createuser', '=', 'user.user_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->where('contract_section', '=', 0)
                  ->where('contract_createuser', '=', Session::get('user_id'));

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('contract_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、学生、课程、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();

        // 返回列表视图
        return view('market/contractMy', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_status' => $filter_status,
                                           'filter_departments' => $filter_departments,
                                           'filter_students' => $filter_students,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 学生退费视图
     * URL: GET /market/refund/create
     */
    public function refundCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生信息
        $students = DB::table('student')
                      ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                      ->where('student_consultant', Session::get('user_id'))
                      ->where('student_customer_status', 1)
                      ->orderBy('student_grade', 'asc')
                      ->get();
        return view('market/refundCreate', ['students' => $students]);
    }

    /**
     * 学生退费视图2
     * URL: POST /market/refund/create2
     * @param  $request->input('input1'): 退课学生
     */
    public function refundCreate2(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取学生id
        $student_id = $request->input('input1');
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
        return view('market/refundCreate2', ['student' => $student,
                                             'hours' => $hours]);
    }

    /**
     * 学生退费视图3
     * URL: POST /market/refund/create3
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     */
    public function refundCreate3(Request $request){
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
        return view('market/refundCreate3', ['student' => $student,
                                               'hour' => $hour,
                                               'refund_amount' => $refund_amount,
                                               'refund_reasons' => $refund_reasons,
                                               'payment_methods' => $payment_methods]);
    }

    /**
     * 学生退费视图4
     * URL: POST /market/refund/create4
     * @param  $request->input('input1'): 退课学生
     * @param  $request->input('input2'): HourID
     * @param  $request->input('input3'): 违约金
     * @param  $request->input('input4'): 退款原因
     * @param  $request->input('input5'): 退款方式
     * @param  $request->input('input6'): 退款日期
     * @param  $request->input('input7'): 备注
     */
    public function refundCreate4(Request $request){
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
        return view('market/refundCreate4', ['student' => $student,
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
     * 退费管理视图
     * URL: GET /market/refund/all
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function refundAll(Request $request){
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
                  ->whereIn('refund_department', $department_access)
                  ->where('refund_type', '=', 0);

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->select('refund.refund_id AS refund_id',
                              'refund.refund_contract AS refund_contract',
                              'refund.refund_total_hour AS refund_total_hour',
                              'refund.refund_fine AS refund_fine',
                              'refund.refund_actual_amount AS refund_actual_amount',
                              'refund.refund_date AS refund_date',
                              'refund.refund_checked AS refund_checked',
                              'refund.refund_createuser AS refund_createuser',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'department.department_name AS department_name',
                              'course.course_name AS course_name',
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
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();

        // 返回列表视图
        return view('market/refundAll', ['rows' => $rows,
                                         'currentPage' => $currentPage,
                                         'totalPage' => $totalPage,
                                         'startIndex' => $offset,
                                         'request' => $request,
                                         'totalNum' => $totalNum,
                                         'filter_status' => $filter_status,
                                         'filter_departments' => $filter_departments,
                                         'filter_students' => $filter_students,
                                         'filter_grades' => $filter_grades]);
    }

    /**
     * 我的退费视图
     * URL: GET /market/refund/my
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 校区
     * @param  $request->input('filter2'): 学生
     * @param  $request->input('filter3'): 年级
     */
    public function refundMy(Request $request){
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
                  ->where('refund_type', '=', 0)
                  ->where('refund_createuser', Session::get('user_id'));

        // 搜索条件
        // 判断是否有搜索条件
        $filter_status = 0;
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
            $filter_status = 1;
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
            $filter_status = 1;
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
            $filter_status = 1;
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->select('refund.refund_id AS refund_id',
                              'refund.refund_contract AS refund_contract',
                              'refund.refund_total_hour AS refund_total_hour',
                              'refund.refund_fine AS refund_fine',
                              'refund.refund_actual_amount AS refund_actual_amount',
                              'refund.refund_date AS refund_date',
                              'refund.refund_checked AS refund_checked',
                              'student.student_id AS student_id',
                              'student.student_name AS student_name',
                              'department.department_name AS department_name',
                              'course.course_name AS course_name',
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
        $filter_students = DB::table('student')->where('student_status', 1)->orderBy('student_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();

        // 返回列表视图
        return view('market/refundMy', ['rows' => $rows,
                                         'currentPage' => $currentPage,
                                         'totalPage' => $totalPage,
                                         'startIndex' => $offset,
                                         'request' => $request,
                                         'totalNum' => $totalNum,
                                         'filter_status' => $filter_status,
                                         'filter_departments' => $filter_departments,
                                         'filter_students' => $filter_students,
                                         'filter_grades' => $filter_grades]);
    }

    /**
     * 审核退课
     * URL: GET /market/refund/{refund_id}
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
