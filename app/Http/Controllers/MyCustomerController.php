<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class MyCustomerController extends Controller
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
                  ->join('user', 'student.student_follower', '=', 'user.user_id')
                  ->leftJoin('school', 'student.student_school', '=', 'school.school_id')
                  ->where('student_customer_status', 0)
                  ->where('student_status', 1)
                  ->where('student_follower', Session::get('user_id'));
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
                     ->orderBy('student_department', 'asc')
                     ->orderBy('student_grade', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();
        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        // 返回列表视图
        return view('myCustomer/index', ['rows' => $rows,
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
        // 获取校区、来源、课程、用户、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $sources = DB::table('source')->where('source_status', 1)->orderBy('source_createtime', 'asc')->get();
        $courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        $schools = DB::table('school')->where('school_status', 1)->orderBy('school_createtime', 'asc')->get();
        return view('myCustomer/create', ['departments' => $departments,
                                          'sources' => $sources,
                                          'courses' => $courses,
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
            return redirect("/myCustomer")->with(['notify' => true,
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

}
