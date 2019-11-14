<?php
namespace App\Http\Controllers;

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
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            // 未登录，返回登陆视图
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '您尚未登录',
                                     'message' => '请输入用户名及密码登陆系统']);

        }
        // 获取用户信息
        $user_level = Session::get('user_level');
        // 获取数据库信息
        // 获取总数据数
        $totalRecord = DB::table('user')->count();
        // 设置每页数据(20数据/页)
        $rowPerPage = 20;
        // 获取总页数
        if($totalRecord==0){
            $totalPage = 1;
        }else{
            $totalPage = ceil($totalRecord/$rowPerPage);
        }
        // 获取当前页数
        if ($request->has('page')) {
            $currentPage = $request->input('page');
            if($currentPage<1)
                $currentPage = 1;
            if($currentPage>$totalPage)
                $currentPage = $totalPage;
        }else{
            $currentPage = 1;
        }
        // 获取数据
        $offset = ($currentPage-1)*$rowPerPage;
        $rows = DB::table('user')
                    ->join('department', 'user.user_department', '=', 'department.department_id')
                    ->join('position', 'user.user_position', '=', 'position.position_id')
                    ->orderBy('user_createtime', 'asc')
                    ->offset($offset)
                    ->limit($rowPerPage)
                    ->get();;
        return view('user/index', ['rows' => $rows, 'currentPage' => $currentPage, 'totalPage' => $totalPage, 'startIndex' => ($currentPage-1)*$rowPerPage]);
    }

    /**
     * 创建新用户页面
     * URL: GET /user/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            // 未登录，返回登陆视图
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '您尚未登录',
                                     'message' => '请输入用户名及密码登陆系统']);

        }
        // 获取校区、岗位信息
        $departments = DB::table('department')->orderBy('department_createtime', 'asc')->get();
        $positions = DB::table('position')->orderBy('position_createtime', 'asc')->get();
        return view('user/create', ['departments' => $departments, 'positions' => $positions]);
    }

    /**
     * 创建新用户提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 用户名称
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            // 未登录，返回登陆视图
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '您尚未登录',
                                     'message' => '请输入用户名及密码登陆系统']);
        }
        // 随机生成新用户ID
        $user_id=chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).chr(rand(97,122)).substr(date('Ym'),2);
        // 获取表单输入
        $user_name = $request->input('input1');
        $user_gender = $request->input('input2');
        $user_department = $request->input('input3');
        $user_position = $request->input('input4');
        $user_level = $request->input('input5');
        $user_dateOfEntry = $request->input('input6');
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
                 'user_level' => $user_level,
                 'user_dateOfEntry' => $user_dateOfEntry,
                 'user_phone' => $user_phone,
                 'user_wechat' => $user_wechat,
                 'user_createuser' => $user_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('UserController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户添加失败',
                                     'message' => '用户添加失败，请重新输入信息']);
        }
        // 返回用户列表
        return redirect()->action('UserController@index')
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
            // 未登录，返回登陆视图
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '您尚未登录',
                                     'message' => '请输入用户名及密码登陆系统']);

        }
        // 获取用户数据信息
        $user = DB::table('user')
                    ->join('department', 'user.user_department', '=', 'department.department_id')
                    ->join('position', 'user.user_position', '=', 'position.position_id')
                    ->where('user_id', $user_id)
                    ->get();
        if($user->count()!==1){
            // 未获取到数据
            return redirect()->action('UserController@index')
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
        return view('user/show', ['user' => $user, 'rows' => $rows]);
    }

    /**
     * 修改单个用户
     * URL: GET /user/{id}/edit
     * @param  int  $user_id
     */
    public function edit($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            // 未登录，返回登陆视图
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '您尚未登录',
                                     'message' => '请输入用户名及密码登陆系统']);

        }
        // 获取数据信息
        $user = DB::table('user')->where('user_id', $user_id)->get();
        if($user->count()!==1){
            // 未获取到数据
            return redirect()->action('UserController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户显示失败',
                                     'message' => '用户显示失败，请联系系统管理员']);
        }
        $user = $user[0];
        // 获取校区、岗位信息
        $departments = DB::table('department')->get();
        $positions = DB::table('position')->get();
        return view('user/edit', ['user' => $user, 'departments' => $departments, 'positions' => $positions]);
    }

    /**
     * 修改新用户提交数据库
     * URL: PUT /user/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 用户id
     * @param  $request->input('input2'): 用户名称
     * @param  int  $user_id
     */
    public function update(Request $request, $user_id){
        // 检查登录状态
        if(!Session::has('login')){
            // 未登录，返回登陆视图
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '您尚未登录',
                                     'message' => '请输入用户名及密码登陆系统']);

        }
        // 获取表单输入
        $user_id = $request->input('input1');
        $user_name = $request->input('input2');
        $user_gender = $request->input('input3');
        $user_department = $request->input('input4');
        $user_position = $request->input('input5');
        $user_level = $request->input('input6');
        $user_dateOfEntry = $request->input('input7');
        $user_phone = $request->input('input8');
        $user_wechat = $request->input('input9');
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
                                  'user_level' => $user_level,
                                  'user_dateOfEntry' => $user_dateOfEntry,
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
     * URL: PATCH /user/{id}
     * @param  int  $user_id
     */
    public function destroy($user_id){
        // 检查登录状态
        if(!Session::has('login')){
            // 未登录，返回登陆视图
            return redirect()->action('LoginController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '您尚未登录',
                                     'message' => '请输入用户名及密码登陆系统']);

        }
        // 获取数据信息
        $user_name = DB::table('user')->where('user_id', $user_id)->value('user_name');
        // 删除数据
        try{
            DB::table('user')->where('user_id', $user_id)->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('UserController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户删除失败',
                                     'message' => '用户删除失败，请联系系统管理员']);
        }
        // 返回用户列表
        return redirect()->action('UserController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '用户删除成功',
                                 'message' => '用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }
}
