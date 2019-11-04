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
        $users = DB::table('user')->offset($offset)->limit($rowPerPage)->get();;
        return view('user/index', ['users' => $users, 'currentPage' => $currentPage, 'totalPage' => $totalPage, 'startIndex' => ($currentPage-1)*$rowPerPage]);
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
        $departments = DB::table('department')->get();
        $positions = DB::table('position')->get();
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
        // 获取表单输入
        $user_name = $request->input('input1');
        // 插入数据库
        try{
            $user_id = DB::table('user')->insertGetId(
                ['user_name' => $user_name]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('userController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户添加失败',
                                     'message' => '用户添加失败，请重新输入信息']);
        }
        // 返回用户列表
        return redirect()->action('userController@index')
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
        // 获取数据信息
        $user = DB::table('user')->where('user_id', $user_id)->get();
        if($user->count()!==1){
            // 未获取到数据
            return redirect()->action('userController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户显示失败',
                                     'message' => '用户显示失败，请联系系统管理员']);
        }
        $user = $user[0];
        return view('user/show', ['user' => $user]);
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
            return redirect()->action('userController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户显示失败',
                                     'message' => '用户显示失败，请联系系统管理员']);
        }
        $user = $user[0];
        return view('user/edit', ['user' => $user]);
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
        // 更新数据库
        try{
            DB::table('user')
                        ->where('user_id', $user_id)
                        ->update(['user_name' => $user_name]);
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
            return redirect()->action('userController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '用户删除失败',
                                     'message' => '用户删除失败，请联系系统管理员']);
        }
        // 返回用户列表
        return redirect()->action('userController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '用户删除成功',
                                 'message' => '用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }
}
