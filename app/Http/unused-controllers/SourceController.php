<?php
namespace App\Http\Controllers\Market;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class SourceController extends Controller
{
    /**
     * 显示所有来源记录
     * URL: GET /source
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
        $rows = DB::table('source')->where('source_status', 1);
        // 添加筛选条件
        // 来源名称
        if ($request->filled('filter1')) {
            $rows = $rows->where('source_name', 'like', '%'.$request->input('filter1').'%');
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('source_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 返回列表视图
        return view('market/source/index', ['rows' => $rows,
                                            'currentPage' => $currentPage,
                                            'totalPage' => $totalPage,
                                            'startIndex' => $offset,
                                            'request' => $request,
                                            'totalNum' => $totalNum]);
    }

    /**
     * 创建新来源页面
     * URL: GET /source/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('market/source/create');
    }

    /**
     * 创建新来源提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 来源名称
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $source_name = $request->input('input1');
        // 获取当前用户ID
        $source_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('source')->insert(
                ['source_name' => $source_name,
                 'source_createuser' => $source_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('Market\SourceController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '来源添加失败',
                                     'message' => '来源添加失败，请重新输入信息']);
        }
        // 返回来源列表
        return redirect()->action('Market\SourceController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '来源添加成功',
                                 'message' => '来源名称: '.$source_name]);
    }

    /**
     * 显示单个来源详细信息
     * URL: GET /source/{id}
     * @param  int  $source_id
     */
    public function show($source_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $source = DB::table('source')->where('source_id', $source_id)->get();
        if($source->count()!==1){
            // 未获取到数据
            return redirect()->action('Market\SourceController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '来源显示失败',
                                     'message' => '来源显示失败，请联系系统管理员']);
        }
        $source = $source[0];
        return view('market/source/show', ['source' => $source]);
    }

    /**
     * 修改单个来源
     * URL: GET /source/{id}/edit
     * @param  int  $source_id
     */
    public function edit($source_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $source = DB::table('source')->where('source_id', $source_id)->get();
        if($source->count()!==1){
            // 未获取到数据
            return redirect()->action('Market\SourceController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '来源显示失败',
                                     'message' => '来源显示失败，请联系系统管理员']);
        }
        $source = $source[0];
        return view('market/source/edit', ['source' => $source]);
    }

    /**
     * 修改新来源提交数据库
     * URL: PUT /source/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 来源名称
     * @param  int  $source_id
     */
    public function update(Request $request, $source_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $source_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('source')
              ->where('source_id', $source_id)
              ->update(['source_name' => $source_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/source/{$source_id}/edit")->with(['notify' => true,
                                                                'type' => 'danger',
                                                                'title' => '来源修改失败',
                                                                'message' => '来源修改失败，请重新输入信息']);
        }
        return redirect("/source")->with(['notify' => true,
                                          'type' => 'success',
                                          'title' => '来源修改成功',
                                          'message' => '来源修改成功，来源名称: '.$source_name]);
    }

    /**
     * 删除来源
     * URL: DELETE /source/{id}
     * @param  int  $source_id
     */
    public function destroy($source_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $source_name = DB::table('source')->where('source_id', $source_id)->value('source_name');
        // 删除数据
        try{
            DB::table('source')->where('source_id', $source_id)->update(['source_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('Market\SourceController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '来源删除失败',
                                     'message' => '来源删除失败，请联系系统管理员']);
        }
        // 返回来源列表
        return redirect()->action('Market\SourceController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '来源删除成功',
                                 'message' => '来源名称: '.$source_name]);
    }
}
