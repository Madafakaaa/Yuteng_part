<?php
namespace App\Http\Controllers\School;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class UserController extends Controller
{
    /**
     * 显示所有用户记录
     * URL: GET /user
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 用户姓名
     * @param  $request->input('filter2'): 用户校区
     * @param  $request->input('filter3'): 用户岗位
     * @param  $request->input('filter4'): 用户等级
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_status', 1);
        // 添加筛选条件
        // 用户姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('user_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 用户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('user_department', $request->input('filter2'));
        }
        // 用户部门
        if ($request->filled('filter3')) {
            $rows = $rows->where('position_section', $request->input('filter3'));
        }
        // 用户岗位
        if ($request->filled('filter4')) {
            $rows = $rows->where('user_position', $request->input('filter4'));
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('user_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区、岗位、等级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $filter_sections = DB::table('section')->where('section_status', 1)->orderBy('section_createtime', 'asc')->get();
        $filter_positions = DB::table('position')->where('position_status', 1)->orderBy('position_createtime', 'asc')->get();

        // 返回列表视图
        return view('school/user/index', ['rows' => $rows,
                                          'currentPage' => $currentPage,
                                          'totalPage' => $totalPage,
                                          'startIndex' => $offset,
                                          'request' => $request,
                                          'totalNum' => $totalNum,
                                          'filter_departments' => $filter_departments,
                                          'filter_sections' => $filter_sections,
                                          'filter_positions' => $filter_positions]);
    }

    /**
     * 创建新用户页面
     * URL: GET /user/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取校区、岗位、等级信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $positions = DB::table('position')
                       ->join('section', 'position.position_section', '=', 'section.section_id')
                       ->where('position_status', 1)
                       ->where('section_status', 1)
                       ->orderBy('position_createtime', 'asc')
                       ->get();
        return view('school/user/create', ['departments' => $departments, 'positions' => $positions]);
    }

    /**
     * 创建新用户提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 用户姓名
     * @param  $request->input('input2'): 用户性别
     * @param  $request->input('input3'): 用户校区
     * @param  $request->input('input4'): 用户岗位
     * @param  $request->input('input5'): 入职日期
     * @param  $request->input('input6'): 是否可以跨校区上课
     * @param  $request->input('input7'): 用户手机
     * @param  $request->input('input8'): 用户微信
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 随机生成新用户ID
        $user_id=chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).substr(date('Ym'),2);
        // 获取表单输入
        $user_name = $request->input('input1');
        $user_gender = $request->input('input2');
        $user_department = $request->input('input3');
        $user_position = $request->input('input4');
        $user_dateOfEntry = $request->input('input5');
        $user_cross_teaching = $request->input('input6');
        $user_phone = $request->input('input7');
        $user_wechat = $request->input('input8');
        // 判断是否为空，为空设为NULL
        if($user_phone == ""){
            $user_phone = "NULL";
        }
        if($user_wechat == ""){
            $user_wechat = "NULL";
        }
        // 获取当前用户ID
        $user_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('user')->insert(
                ['user_id' => $user_id,
                 'user_name' => $user_name,
                 'user_gender' => $user_gender,
                 'user_department' => $user_department,
                 'user_position' => $user_position,
                 'user_dateOfEntry' => $user_dateOfEntry,
                 'user_cross_teaching' => $user_cross_teaching,
                 'user_phone' => $user_phone,
                 'user_wechat' => $user_wechat,
                 'user_createuser' => $user_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\UserController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户添加失败',
                                     'message' => '用户添加失败，请重新输入信息']);
        }
        // 返回用户列表
        return redirect()->action('School\UserController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '用户添加成功',
                                 'message' => '用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }

    /**
     * 显示单个用户详细信息
     * URL: GET /user/{id}
     * @param  int  $user_id
     */
    public function show($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户数据信息
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', $user_id)
                  ->get();
        if($user->count()!==1){
            // 未获取到数据
            return redirect()->action('School\UserController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户显示失败',
                                     'message' => '用户显示失败，请联系系统管理员']);
        }
        $user = $user[0];
        // 获取档案数据
        $rows = DB::table('archive')
                  ->where('archive_user', $user_id)
                  ->get();
        return view('school/user/show', ['user' => $user, 'rows' => $rows]);
    }

    /**
     * 修改单个用户
     * URL: GET /user/{id}/edit
     * @param  int  $user_id
     */
    public function edit($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $user = DB::table('user')->where('user_id', $user_id)->get();
        if($user->count()!==1){
            // 未获取到数据
            return redirect()->action('School\UserController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户显示失败',
                                     'message' => '用户显示失败，请联系系统管理员']);
        }
        $user = $user[0];
        // 获取校区、岗位信息
        $departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();
        $positions = DB::table('position')
                       ->join('section', 'position.position_section', '=', 'section.section_id')
                       ->where('position_status', 1)
                       ->where('section_status', 1)
                       ->orderBy('position_createtime', 'asc')
                       ->get();
        return view('school/user/edit', ['user' => $user, 'departments' => $departments, 'positions' => $positions]);
    }

    /**
     * 修改新用户提交数据库
     * URL: PUT /user/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 用户姓名
     * @param  $request->input('input2'): 用户性别
     * @param  $request->input('input3'): 用户校区
     * @param  $request->input('input4'): 用户岗位
     * @param  $request->input('input5'): 入职日期
     * @param  $request->input('input6'): 是否可以跨校区上课
     * @param  $request->input('input7'): 用户手机
     * @param  $request->input('input8'): 用户微信
     * @param  int  $user_id: 用户id
     */
    public function update(Request $request, $user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $user_name = $request->input('input1');
        $user_gender = $request->input('input2');
        $user_department = $request->input('input3');
        $user_position = $request->input('input4');
        $user_dateOfEntry = $request->input('input5');
        $user_cross_teaching = $request->input('input6');
        $user_phone = $request->input('input7');
        $user_wechat = $request->input('input8');
        // 判断是否为空，为空设为NULL
        if($user_phone == ""){
            $user_phone = "NULL";
        }
        if($user_wechat == ""){
            $user_wechat = "NULL";
        }
        // 更新数据库
        try{
            DB::table('user')
              ->where('user_id', $user_id)
              ->update(['user_name' => $user_name,
                        'user_gender' => $user_gender,
                        'user_department' => $user_department,
                        'user_position' => $user_position,
                        'user_dateOfEntry' => $user_dateOfEntry,
                        'user_cross_teaching' => $user_cross_teaching,
                        'user_phone' => $user_phone,
                        'user_wechat' => $user_wechat]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/user/{$user_id}/edit")->with(['notify' => true,
                                                            'type' => 'danger',
                                                            'title' => '用户修改失败',
                                                            'message' => '用户修改失败，请重新输入信息']);
        }
        return redirect("/user/{$user_id}")->with(['notify' => true,
                                                   'type' => 'success',
                                                   'title' => '用户修改成功',
                                                   'message' => '用户修改成功，用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }

    /**
     * 删除用户
     * URL: DELETE /user/{id}
     * @param  int  $user_id
     */
    public function destroy($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $user_name = DB::table('user')->where('user_id', $user_id)->value('user_name');
        // 删除数据
        try{
            DB::table('user')->where('user_id', $user_id)->update(['user_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\UserController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户删除失败',
                                     'message' => '用户删除失败，请联系系统管理员']);
        }
        // 返回用户列表
        return redirect()->action('School\UserController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '用户删除成功',
                                 'message' => '用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }
}