<?php
namespace App\Http\Controllers\School;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class LevelController extends Controller
{
    /**
     * 显示所有等级记录
     * URL: GET /level
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 等级名称
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('level')->where('level_status', 1);

        // 添加筛选条件
        // 等级名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('level_name', 'like', '%'.$request->input('filter1').'%');
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('level_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 返回列表视图
        return view('school/level/index', ['rows' => $rows,
                                           'currentPage' => $currentPage,
                                           'totalPage' => $totalPage,
                                           'startIndex' => $offset,
                                           'request' => $request,
                                           'totalNum' => $totalNum]);
    }

    /**
     * 创建新等级页面
     * URL: GET /level/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('school/level/create');
    }

    /**
     * 创建新等级提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 等级名称
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $level_name = $request->input('input1');
        // 获取当前用户ID
        $level_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('level')->insert(
                ['level_name' => $level_name,
                 'level_createuser' => $level_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\LevelController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '等级添加失败',
                                     'message' => '等级添加失败，请重新输入信息']);
        }
        // 返回等级列表
        return redirect()->action('School\LevelController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '等级添加成功',
                                 'message' => '等级名称: '.$level_name]);
    }

    /**
     * 显示单个等级详细信息
     * URL: GET /level/{id}
     * @param  int  $level_id
     */
    public function show($level_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $level = DB::table('level')->where('level_id', $level_id)->get();
        if($level->count()!==1){
            // 未获取到数据
            return redirect()->action('School\LevelController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '等级显示失败',
                                     'message' => '等级显示失败，请联系系统管理员']);
        }
        $level = $level[0];
        return view('school/level/show', ['level' => $level]);
    }

    /**
     * 修改单个等级
     * URL: GET /level/{id}/edit
     * @param  int  $level_id
     */
    public function edit($level_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $level = DB::table('level')->where('level_id', $level_id)->get();
        if($level->count()!==1){
            // 未获取到数据
            return redirect()->action('School\LevelController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '等级显示失败',
                                     'message' => '等级显示失败，请联系系统管理员']);
        }
        $level = $level[0];
        return view('school/level/edit', ['level' => $level]);
    }

    /**
     * 修改新等级提交数据库
     * URL: PUT /level/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 等级名称
     * @param  int  $level_id
     */
    public function update(Request $request, $level_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $level_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('level')
              ->where('level_id', $level_id)
              ->update(['level_name' => $level_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/level/{$level_id}/edit")->with(['notify' => true,
                                                              'type' => 'danger',
                                                              'title' => '等级修改失败',
                                                              'message' => '等级修改失败，请重新输入信息']);
        }
        return redirect("/level")->with(['notify' => true,
                                         'type' => 'success',
                                         'title' => '等级修改成功',
                                         'message' => '等级修改成功，等级名称: '.$level_name]);
    }

    /**
     * 删除等级
     * URL: DELETE /level/{id}
     * @param  int  $level_id
     */
    public function destroy($level_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $level_name = DB::table('level')->where('level_id', $level_id)->value('level_name');
        // 删除数据
        try{
            DB::table('level')->where('level_id', $level_id)->update(['level_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\LevelController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '等级删除失败',
                                     'message' => '等级删除失败，请联系系统管理员']);
        }
        // 返回等级列表
        return redirect()->action('School\LevelController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '等级删除成功',
                                 'message' => '等级名称: '.$level_name]);
    }
}
