<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CustomerController extends Controller
{

    /**
     * 显示全部客户记录
     * URL: GET /index
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                  ->leftJoin('position', 'user.user_position', '=', 'position.position_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 0)
                  ->where('student_status', 1);
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->orderBy('student_department', 'asc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('customer/index', ['rows' => $rows,
                                       'currentPage' => $currentPage,
                                       'totalPage' => $totalPage,
                                       'startIndex' => $offset,
                                       'request' => $request,
                                       'totalNum' => $totalNum,
                                       'filter_departments' => $filter_departments,
                                       'filter_grades' => $filter_grades]);
    }

    /**
     * 显示本校客户记录
     * URL: GET /departmentCustomer
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function department(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 0)
                  ->where('student_department', Session::get('user_department'))
                  ->where('student_status', 1);
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('customer/department', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum,
                                           'filter_departments' => $filter_departments,
                                           'filter_grades' => $filter_grades]);
    }

    /**
     * 显示我的客户记录
     * URL: GET /myCustomer
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     */
    public function my(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $user_level = Session::get('user_level');
        // 获取数据
        $rows = DB::table('student')
                  ->join('department', 'student.student_department', '=', 'department.department_id')
                  ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                  ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_follower', Session::get('user_id'))
                  ->where('student_customer_status', 0)
                  ->where('student_status', 1);
        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('student_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('student_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('student_grade', '=', $request->input('filter3'));
        }
        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);
        // 排序并获取数据对象
        $rows = $rows->orderBy('student_customer_status', 'desc')
                     ->orderBy('student_follow_level', 'desc')
                     ->orderBy('student_grade', 'desc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('customer/my', ['rows' => $rows,
                                   'currentPage' => $currentPage,
                                   'totalPage' => $totalPage,
                                   'startIndex' => $offset,
                                   'request' => $request,
                                   'totalNum' => $totalNum,
                                   'filter_departments' => $filter_departments,
                                   'filter_grades' => $filter_grades]);
    }

    /**
     * 创建新客户页面
     * URL: GET /customer/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取来源、用户、年级信息
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
        return view('customer/create', ['sources' => $sources,
                                          'users' => $users,
                                          'schools' => $schools,
                                          'grades' => $grades]);
    }

    /**
     * 创建新客户提交数据库
     * URL: POST
     * @param  Request  $request
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
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        if($request->filled('input1')) {
            $student_follower = $request->input('input1');
        }else{
            $student_follower = '';
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
                         ->where('student_department', Session::get('user_department'))
                         ->whereYear('student_createtime', date('Y'))
                         ->whereMonth('student_createtime', date('m'))
                         ->count()+1;
        $student_id = "S".substr(date('Ym'),2).sprintf("%02d", Session::get('user_department')).sprintf("%03d", $student_num);
        // 获取负责人姓名
        $follower_name = "无 (公共)";
        if($student_follower!=''){
            $follower_name = DB::table('user')
                               ->where('user_id', $student_follower)
                               ->value('user_name');
        }
        // 插入数据库
        DB::beginTransaction();
        try{
            DB::table('student')->insert(
                ['student_id' => $student_id,
                 'student_name' => $student_name,
                 'student_department' => Session::get('user_department'),
                 'student_grade' => $student_grade,
                 'student_gender' => $student_gender,
                 'student_birthday' => $student_birthday,
                 'student_school' => $student_school,
                 'student_guardian' => $student_guardian,
                 'student_guardian_relationship' => $student_guardian_relationship,
                 'student_phone' => $student_phone,
                 'student_wechat' => $student_wechat,
                 'student_follower' => $student_follower,
                 'student_source' => $student_source,
                 'student_follow_level' => $student_follow_level,
                 'student_remark' => $student_remark,
                 'student_last_follow_date' => date('Y-m-d'),
                 'student_createuser' => $student_createuser]
            );
            // 插入学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '新建学生',
                 'student_record_content' => "新建学生档案。新建人：".Session::get('user_name')."。跟进人：".$follower_name."。",
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/customer/create")->with(['notify' => true,
                                                     'type' => 'danger',
                                                     'title' => '客户添加失败',
                                                     'message' => '客户添加失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/myCustomer")->with(['notify' => true,
                                                 'type' => 'success',
                                                 'title' => '学生添加成功',
                                                 'message' => '学生名称: '.$student_name]);
    }

    /**
     * 显示单个客户详细信息
     * URL: GET /customer/{id}
     * @param  int  $customer_id        : 客户id
     */
    public function show($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student = DB::table('student')
                     ->join('department', 'student.student_department', '=', 'department.department_id')
                     ->join('grade', 'student.student_grade', '=', 'grade.grade_id')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->leftJoin('position', 'user.user_position', '=', 'position.position_id')
                     ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                     ->where('student_id', $student_id)
                     ->get();
        if($student->count()!==1){
            // 未获取到数据
            return redirect("/customer")->with(['notify' => true,
                                                'type' => 'danger',
                                                'title' => '客户显示失败',
                                                'message' => '客户显示失败，请联系系统管理员']);
        }
        $student = $student[0];
        $student_department = $student->student_department;
        // 获取学生动态
        $student_records = DB::table('student_record')
                             ->join('student', 'student_record.student_record_student', '=', 'student.student_id')
                             ->join('department', 'student.student_department', '=', 'department.department_id')
                             ->join('user', 'student_record.student_record_createuser', '=', 'user.user_id')
                             ->where('student_record_student', $student_id)
                             ->orderBy('student_record_createtime', 'desc')
                             ->limit(50)
                             ->get();
        $users = DB::table('user')
                   ->where('user_department', $student_department)
                   ->where('user_status', 1)
                   ->orderBy('user_createtime', 'asc')
                   ->get();
        return view('customer/show', ['student' => $student,
                                      'users' => $users,
                                      'student_records' => $student_records]);
    }

    /**
     * 修改客户信息
     * URL: GET /customer/{student_id}/edit
     * @param  int  $student_id        : 学生id
     */
    public function edit($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student = DB::table('student')->where('student_id', $student_id)->get();
        if($student->count()!==1){
            // 未获取到数据
            return redirect()->action('CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户显示失败',
                                     'message' => '客户显示失败，请联系系统管理员']);
        }
        $student = $student[0];
        // 获取校区、来源、课程、用户、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $sources = DB::table('source')->where('source_status', 1)->orderBy('source_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();
        return view('customer/edit', ['student' => $student,
                                      'departments' => $departments,
                                      'sources' => $sources,
                                      'grades' => $grades,
                                      'schools' => $schools]);
    }

    /**
     * 修改客户信息
     * URL: PUT /customer/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 学生姓名
     * @param  $request->input('input2'): 学生性别
     * @param  $request->input('input3'): 学生年级
     * @param  $request->input('input4'): 公立学校
     * @param  $request->input('input5'): 监护人姓名
     * @param  $request->input('input6'): 监护人关系
     * @param  $request->input('input7'): 联系电话
     * @param  $request->input('input8'): 微信
     * @param  $request->input('input9'): 来源类型
     * @param  $request->input('input10'): 学生生日
     * @param  int  $student_id        : 学生id
     */
    public function update(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_name = $request->input('input1');
        $student_gender = $request->input('input2');
        $student_grade = $request->input('input3');
        if($request->filled('input4')) {
            $student_school = $request->input('input4');
        }else{
            $student_school = 0;
        }
        $student_guardian = $request->input('input5');
        $student_guardian_relationship = $request->input('input6');
        $student_phone = $request->input('input7');
        if($request->filled('input8')) {
            $student_wechat = $request->input('input8');
        }else{
            $student_wechat = '无';
        }
        $student_source = $request->input('input9');
        $student_birthday = $request->input('input10');
        //  获取学生信息
        $student = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->first();
        $student_follower = $student->student_follower;
        $student_name = $student->student_name;
        // 更新数据库
        DB::beginTransaction();
        try{
            // 更新学生信息
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_name' => $student_name,
                        'student_gender' => $student_gender,
                        'student_grade' => $student_grade,
                        'student_school' => $student_school,
                        'student_guardian' => $student_guardian,
                        'student_guardian_relationship' => $student_guardian_relationship,
                        'student_phone' => $student_phone,
                        'student_wechat' => $student_wechat,
                        'student_source' => $student_source,
                        'student_birthday' => $student_birthday]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '修改信息',
                 'student_record_content' => '修改客户信息，修改人：'.Session::get('user_name').'。',
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/customer/{$student_id}/edit")->with(['notify' => true,
                                                                    'type' => 'danger',
                                                                    'title' => '客户修改失败',
                                                                    'message' => '客户修改失败，请重新输入信息']);
        }
        DB::commit();
        return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                        'type' => 'success',
                                                        'title' => '客户修改成功',
                                                        'message' => '客户修改成功，客户名称: '.$student_name]);
    }

    /**
     * 添加客户跟进动态
     * URL: POST /customer/{id}/record
     * @param  Request  $request
     * @param  $request->input('input1'): 跟进内容
     * @param  $request->input('input2'): 跟进方式
     * @param  $request->input('input3'): 跟进时间
     * @param  int  $student_id        : 学生id
     */
    public function record(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_record_content = "跟进方式：".$request->input('input2')."，跟进日期：".$request->input('input3')."。<br>".$request->input('input1');
        // 获取数据信息
        $student_record_student = $student_id;
        $student_record_type = "跟进记录";
        $student_record_createuser = Session::get('user_id');
        // 获取学生姓名
        $student_name = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->value("student_name");
        // 更新数据
        DB::beginTransaction();
        try{
            // 增加跟进次数
            DB::table('student')
              ->where('student_id', $student_id)
              ->increment('student_follow_num');
            // 更新跟进时间
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_last_follow_date' =>  $request->input('input3')]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_record_student,
                 'student_record_type' => '跟进记录',
                 'student_record_content' => $student_record_content,
                 'student_record_createuser' => $student_record_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '添加跟进动态失败',
                                                               'message' => '添加跟进动态失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '添加跟进动态成功',
                                                           'message' => '学生名称: '.$student_name]);
    }

    /**
     * 修改学生备注
     * URL: POST /customer/{id}/remark
     * @param  Request  $request
     * @param  $request->input('input1'): 学生备注
     * @param  int  $student_id        : 学生id
     */
    public function remark(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_remark = $request->input('input1');
        // 获取数据信息
        $student = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取学生姓名
        $student_name = $student->student_name;
        // 更新数据
        DB::beginTransaction();
        try{
            // 更新学生备注
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_remark' =>  $student_remark]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '修改备注',
                 'student_record_content' => '修改学生备注：'.$student_remark,
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '修改学生备注失败',
                                                               'message' => '修改学生备注失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '修改学生备注成功',
                                                           'message' => '学生名称: '.$student_name]);
    }

    /**
     * 修改学生跟进人
     * URL: POST /customer/{id}/follower
     * @param  Request  $request
     * @param  $request->input('input1'): 跟进人
     * @param  int  $student_id        : 学生id
     */
    public function follower(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        if($request->filled('input1')) {
            $student_new_follower = $request->input('input1');
        }else{
            $student_new_follower = "";
        }
        // 获取客户信息
        $student = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取学生姓名
        $student_name = $student->student_name;
        // 获取原负责人姓名
        $student_old_follower_name = $student->user_name;
        $student_old_follower = $student->user_id;
        if($student_old_follower_name==""){
            $student_old_follower_name="无(公共)";
        }
        // 原负责人和新负责人相同，返回上一级
        if($student_old_follower==$student_new_follower){
            return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '修改学生跟进人失败',
                                                               'message' => '原跟进人与新跟进人相同，请重新选择']);
        }
        // 获取新跟进人姓名
        if($student_new_follower==""){
            $student_new_follower_name="无(公共)";
        }else{
            $student_new_follower_name = DB::table('user')
                                           ->where('user_id', $student_new_follower)
                                           ->first()
                                           ->user_name;
        }
        // 更新数据
        DB::beginTransaction();
        try{
            // 更新学生负责人
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_follower' =>  $student_new_follower]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '更换跟进人',
                 'student_record_content' => '更换学生跟进人。原跟进人：'.$student_old_follower_name."，新跟进人：".$student_new_follower_name."。",
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '修改学生跟进人失败',
                                                               'message' => '修改学生跟进人失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '修改学生跟进人成功',
                                                           'message' => '学生名称: '.$student_name]);
    }

    /**
     * 修改学生跟进优先级
     * URL: POST /customer/{id}/followLevel
     * @param  Request  $request
     * @param  $request->input('input1'): 跟进人
     * @param  int  $student_id        : 学生id
     */
    public function followLevel(Request $request, $student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $student_new_follow_level = $request->input('input1');
        // 获取客户信息
        $student = DB::table('student')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取学生姓名
        $student_name = $student->student_name;
        // 获取原优先级
        $student_old_follow_level = $student->student_follow_level;
        // 原优先级和新优先级相同，返回上一级
        if($student_new_follow_level==$student_old_follow_level){
            return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '修改客户优先级失败',
                                                               'message' => '原优先级与新优先级相同，请重新选择']);
        }
        $follow_levels =  array('', '低', '中', '高', '重点');
        // 更新数据
        DB::beginTransaction();
        try{
            // 更新学生负责人
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_follow_level' =>  $student_new_follow_level]);
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '修改优先级',
                 'student_record_content' => '修改客户跟进优先级。原优先级：'.$follow_levels[$student_old_follow_level]."，新优先级：".$follow_levels[$student_new_follow_level]."。",
                 'student_record_createuser' => Session::get('user_id')]
            );
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '修改客户优先级失败',
                                                               'message' => '修改客户优先级失败，请重新输入信息']);
        }
        DB::commit();
        // 返回客户列表
        return redirect("/customer/{$student_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '修改客户优先级成功',
                                                           'message' => '学生名称: '.$student_name]);
    }

    /**
     * 删除客户
     * URL: DELETE /myCustomer/{id}
     * @param  int  $student_id        : 学生id
     */
    public function mydelete($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取学生姓名
        $student_name = $student->student_name;
        // 获取负责人姓名
        $student_follower = $student->user_name;
        // 删除数据
        DB::beginTransaction();
        try{
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '更换跟进人',
                 'student_record_content' => '更换学生跟进人。原跟进人：'.$student_follower."，新跟进人：无(公共)。",
                 'student_record_createuser' => Session::get('user_id')]);
            //修改学生状态
            DB::table('student')
              ->where('student_id', $student_id)
              ->update(['student_follower' => '']);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/myCustomer") ->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '客户删除失败',
                                                         'message' => '客户删除失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回客户列表
       return redirect("/myCustomer")->with(['notify' => true,
                                                   'type' => 'success',
                                                   'title' => '客户删除成功',
                                                   'message' => '客户已转为公共客户，客户名称: '.$student_name]);
    }

    /**
     * 删除客户
     * URL: DELETE /customer/{id}
     * @param  int  $student_id        : 学生id
     */
    public function destroy($student_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $student = DB::table('student')
                     ->leftJoin('user', 'student.student_follower', '=', 'user.user_id')
                     ->where('student_id', $student_id)
                     ->first();
        // 获取学生姓名
        $student_name = $student->student_name;
        // 获取负责人id
        $student_follower = $student->user_id;
        if($student_follower==""){
            $student_follower="";
        }
        // 删除数据
        DB::beginTransaction();
        try{
            // 添加学生动态
            DB::table('student_record')->insert(
                ['student_record_student' => $student_id,
                 'student_record_type' => '删除客户',
                 'student_record_content' => '删除客户信息。删除人：'.Session::get('user_name')."。",
                 'student_record_createuser' => Session::get('user_id')]);
            //修改学生状态
            DB::table('student')->where('student_id', $student_id)->update(['student_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/departmentCustomer") ->with(['notify' => true,
                                                         'type' => 'danger',
                                                         'title' => '客户删除失败',
                                                         'message' => '客户删除失败，请联系系统管理员']);
        }
        DB::commit();
        // 返回客户列表
       return redirect("/departmentCustomer")->with(['notify' => true,
                                                   'type' => 'success',
                                                   'title' => '客户删除成功',
                                                   'message' => '客户名称: '.$student_name]);
    }
}
