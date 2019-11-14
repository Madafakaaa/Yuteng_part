<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class PositionController extends Controller
{
    /**
     * 显示所有岗位记录
     * URL: GET /position
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
        $totalRecord = DB::table('position')->count();
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
        $rows = DB::table('position')->orderBy('position_createtime', 'asc')->offset($offset)->limit($rowPerPage)->get();
        return view('position/index', ['rows' => $rows, 'currentPage' => $currentPage, 'totalPage' => $totalPage, 'startIndex' => ($currentPage-1)*$rowPerPage]);
    }

    /**
     * 创建新岗位页面
     * URL: GET /position/create
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
        return view('position/create');
    }

    /**
     * 创建新岗位提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 岗位名称
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
        $position_name = $request->input('input1');
        // 获取当前用户ID
        $position_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('position')->insert(
                ['position_name' => $position_name,
                 'position_createuser' => $position_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('PositionController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '岗位添加失败',
                                     'message' => '岗位添加失败，请重新输入信息']);
        }
        // 返回岗位列表
        return redirect()->action('PositionController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '岗位添加成功',
                                 'message' => '岗位名称: '.$position_name]);
    }

    /**
     * 显示单个岗位详细信息
     * URL: GET /position/{id}
     * @param  int  $position_id
     */
    public function show($position_id){
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
        $position = DB::table('position')->where('position_id', $position_id)->get();
        if($position->count()!==1){
            // 未获取到数据
            return redirect()->action('PositionController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '岗位显示失败',
                                     'message' => '岗位显示失败，请联系系统管理员']);
        }
        $position = $position[0];
        return view('position/show', ['position' => $position]);
    }

    /**
     * 修改单个岗位
     * URL: GET /position/{id}/edit
     * @param  int  $position_id
     */
    public function edit($position_id){
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
        $position = DB::table('position')->where('position_id', $position_id)->get();
        if($position->count()!==1){
            // 未获取到数据
            return redirect()->action('PositionController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '岗位显示失败',
                                     'message' => '岗位显示失败，请联系系统管理员']);
        }
        $position = $position[0];
        return view('position/edit', ['position' => $position]);
    }

    /**
     * 修改新岗位提交数据库
     * URL: PUT /position/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 岗位id
     * @param  $request->input('input2'): 岗位名称
     * @param  int  $position_id
     */
    public function update(Request $request, $position_id){
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
        $position_id = $request->input('input1');
        $position_name = $request->input('input2');
        // 更新数据库
        try{
            DB::table('position')
                    ->where('position_id', $position_id)
                    ->update(['position_name' => $position_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/position/{$position_id}/edit")->with(['notify' => true,
                                                                   'type' => 'danger',
                                                                   'title' => '岗位修改失败',
                                                                   'message' => '岗位修改失败，请重新输入信息']);
        }
        return redirect("/position/{$position_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '岗位修改成功',
                                                           'message' => '岗位修改成功，岗位名称: '.$position_name]);
    }

    /**
     * 删除岗位
     * URL: PATCH /position/{id}
     * @param  int  $position_id
     */
    public function destroy($position_id){
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
        $position_name = DB::table('position')->where('position_id', $position_id)->value('position_name');
        // 删除数据
        try{
            DB::table('position')->where('position_id', $position_id)->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('PositionController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '岗位删除失败',
                                     'message' => '岗位删除失败，请联系系统管理员']);
        }
        // 返回岗位列表
        return redirect()->action('PositionController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '岗位删除成功',
                                 'message' => '岗位名称: '.$position_name]);
    }
}
