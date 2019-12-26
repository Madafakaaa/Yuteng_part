<?php
namespace App\Http\Controllers\Market;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class CustomerController extends Controller
{
    /**
     * 显示所有客户记录
     * URL: GET /customer
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
        $rows = DB::table('customer')
                  ->leftJoin('department', 'customer.customer_department', '=', 'department.department_id')
                  ->leftJoin('source', 'customer.customer_source', '=', 'source.source_id')
                  ->leftJoin('course', 'customer.customer_course', '=', 'course.course_id')
                  ->leftJoin('user', 'customer.customer_follower', '=', 'user.user_id')
                  ->leftJoin('grade', 'customer.customer_student_grade', '=', 'grade.grade_id')
                  ->where('customer_status', 1);

        // 添加筛选条件
        // 客户名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('customer_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 客户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('customer_department', '=', $request->input('filter2'));
        }
        // 客户年级
        if ($request->filled('filter3')) {
            $rows = $rows->where('customer_student_grade', '=', $request->input('filter3'));
        }

        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($rows->count(), $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('customer_conversed', 'asc')
                     ->orderBy('customer_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、年级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();

        // 返回列表视图
        return view('market/customer/index', ['rows' => $rows,
                                              'currentPage' => $currentPage,
                                              'totalPage' => $totalPage,
                                              'startIndex' => $offset,
                                              'request' => $request,
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
        $users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        return view('market/customer/create', ['departments' => $departments,
                                               'sources' => $sources,
                                               'courses' => $courses,
                                               'users' => $users,
                                               'grades' => $grades]);
    }

    /**
     * 创建新客户提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 客户姓名
     * @param  $request->input('input2'): 联系电话
     * @param  $request->input('input3'): 客户校区
     * @param  $request->input('input4'): 来源类型
     * @param  $request->input('input5'): 微信号
     * @param  $request->input('input6'): 客户关系
     * @param  $request->input('input7'): 意向课程
     * @param  $request->input('input8'): 跟进人
     * @param  $request->input('input9'): 学生姓名
     * @param  $request->input('input10'): 学生年级
     * @param  $request->input('input11'): 备注
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $customer_name = $request->input('input1');
        $customer_phone = $request->input('input2');
        $customer_department = $request->input('input3');
        $customer_source = $request->input('input4');
        $customer_wechat = $request->input('input5');
        $customer_relationship = $request->input('input6');
        $customer_course = $request->input('input7');
        $customer_follower = $request->input('input8');
        $customer_student_name = $request->input('input9');
        $customer_student_grade = $request->input('input10');
        $customer_remark = $request->input('input11');
        // 获取当前用户ID
        $customer_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('customer')->insert(
                ['customer_name' => $customer_name,
                 'customer_phone' => $customer_phone,
                 'customer_department' => $customer_department,
                 'customer_source' => $customer_source,
                 'customer_wechat' => $customer_wechat,
                 'customer_relationship' => $customer_relationship,
                 'customer_course' => $customer_course,
                 'customer_follower' => $customer_follower,
                 'customer_student_name' => $customer_student_name,
                 'customer_student_grade' => $customer_student_grade,
                 'customer_remark' => $customer_remark,
                 'customer_createuser' => $customer_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('Market\CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户添加失败',
                                     'message' => '客户添加失败，请重新输入信息']);
        }
        // 返回客户列表
        return redirect()->action('Market\CustomerController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '客户添加成功',
                                 'message' => '客户名称: '.$customer_name]);
    }

    /**
     * 显示单个客户详细信息
     * URL: GET /customer/{id}
     * @param  int  $customer_id        : 客户id
     */
    public function show($customer_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $customer = DB::table('customer')
                      ->leftJoin('department', 'customer.customer_department', '=', 'department.department_id')
                      ->leftJoin('source', 'customer.customer_source', '=', 'source.source_id')
                      ->leftJoin('course', 'customer.customer_course', '=', 'course.course_id')
                      ->leftJoin('user', 'customer.customer_follower', '=', 'user.user_id')
                      ->leftJoin('grade', 'customer.customer_student_grade', '=', 'grade.grade_id')
                      ->where('customer_id', $customer_id)
                      ->get();
        $customer_follow_records = DB::table('customer_follow_record')
                                     ->leftJoin('user', 'customer_follow_record.customer_follow_record_follower', '=', 'user.user_id')
                                     ->where('customer_follow_record_customer', $customer_id)
                                     ->orderBy('customer_follow_record_createtime', 'desc')
                                     ->get();
        if($customer->count()!==1){
            // 未获取到数据
            return redirect()->action('Market\CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户显示失败',
                                     'message' => '客户显示失败，请联系系统管理员']);
        }
        $customer = $customer[0];
        // 获取用户信息
        $users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();
        return view('market/customer/show', ['customer' => $customer,
                                             'customer_follow_records' => $customer_follow_records,
                                             'users' => $users]);
    }

    /**
     * 修改单个客户
     * URL: GET /customer/{id}/edit
     * @param  int  $customer_id        : 客户id
     */
    public function edit($customer_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $customer = DB::table('customer')->where('customer_id', $customer_id)->get();
        if($customer->count()!==1){
            // 未获取到数据
            return redirect()->action('Market\CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户显示失败',
                                     'message' => '客户显示失败，请联系系统管理员']);
        }
        $customer = $customer[0];
        // 获取校区、来源、课程、用户、年级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $sources = DB::table('source')->where('source_status', 1)->orderBy('source_createtime', 'asc')->get();
        $courses = DB::table('course')->where('course_status', 1)->orderBy('course_createtime', 'asc')->get();
        $users = DB::table('user')->where('user_status', 1)->orderBy('user_createtime', 'asc')->get();
        $grades = DB::table('grade')->where('grade_status', 1)->orderBy('grade_createtime', 'asc')->get();
        return view('market/customer/edit', ['customer' => $customer,
                                             'departments' => $departments,
                                             'sources' => $sources,
                                             'courses' => $courses,
                                             'users' => $users,
                                             'grades' => $grades]);
    }

    /**
     * 修改新客户提交数据库
     * URL: PUT /customer/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 客户名称
     * @param  int  $customer_id        : 客户id
     */
    public function update(Request $request, $customer_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $customer_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('customer')
              ->where('customer_id', $customer_id)
              ->update(['customer_name' => $customer_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/customer/{$customer_id}/edit")->with(['notify' => true,
                                                                    'type' => 'danger',
                                                                    'title' => '客户修改失败',
                                                                    'message' => '客户修改失败，请重新输入信息']);
        }
        return redirect("/customer")->with(['notify' => true,
                                            'type' => 'success',
                                            'title' => '客户修改成功',
                                            'message' => '客户修改成功，客户名称: '.$customer_name]);
    }

    /**
     * 添加客户跟进动态
     * URL: POST /customer/{id}/record
     * @param  Request  $request
     * @param  $request->input('input1'): 跟进人
     * @param  $request->input('input2'): 跟进状态
     * @param  $request->input('input3'): 跟进记录
     * @param  int  $customer_id        : 客户id
     */
    public function record(Request $request, $customer_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $customer_follow_record_follower = $request->input('input1');
        $customer_follow_record_conversed = $request->input('input2');
        $customer_follow_record_remark = $request->input('input3');
        $customer_follow_record_customer = $customer_id;
        // 获取当前用户ID
        $customer_follow_record_createuser = Session::get('user_id');
        // 获取数据信息
        $customer_name = DB::table('customer')->where('customer_id', $customer_id)->value('customer_name');
        // 更新数据
        try{
            DB::table('customer')->where('customer_id', $customer_id)->update(['customer_conversed' => $customer_follow_record_conversed]);
            DB::table('customer')->where('customer_id', $customer_id)->increment('customer_follow_time');
            DB::table('customer_follow_record')->insert(
                ['customer_follow_record_customer' => $customer_follow_record_customer,
                 'customer_follow_record_follower' => $customer_follow_record_follower,
                 'customer_follow_record_conversed' => $customer_follow_record_conversed,
                 'customer_follow_record_remark' => $customer_follow_record_remark,
                 'customer_follow_record_createuser' => $customer_follow_record_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/customer/{$customer_id}")->with(['notify' => true,
                                                               'type' => 'danger',
                                                               'title' => '添加跟进动态失败',
                                                               'message' => '添加跟进动态失败，请重新输入信息']);
        }
        // 返回客户列表
        return redirect("/customer/{$customer_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '添加跟进动态成功',
                                                           'message' => '客户名称: '.$customer_name]);
    }

    /**
     * 删除客户
     * URL: DELETE /customer/{id}
     * @param  int  $customer_id        : 客户id
     */
    public function destroy($customer_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $customer_name = DB::table('customer')->where('customer_id', $customer_id)->value('customer_name');
        // 删除数据
        try{
            DB::table('customer')->where('customer_id', $customer_id)->update(['customer_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('Market\CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户删除失败',
                                     'message' => '客户删除失败，请联系系统管理员']);
        }
        // 返回客户列表
        return redirect()->action('Market\CustomerController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '客户删除成功',
                                 'message' => '客户名称: '.$customer_name]);
    }
}
