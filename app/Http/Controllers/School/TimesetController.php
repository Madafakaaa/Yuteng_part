<?php
namespace App\Http\Controllers\School;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class TimesetController extends Controller
{
    /**
     * 显示所有时间记录
     * URL: GET /timeset
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
        // 获取数据库信息
        // 获取总数据数
        $totalRecord = DB::table('timeset')->where('timeset_status', 1);
        // 添加筛选条件
        // 时间名称
        if ($request->has('filter1')) {
            if($request->input('filter1')!=''){
                $totalRecord = $totalRecord->where('timeset_name', 'like', '%'.$request->input('filter1').'%');
            }
        }
        $totalRecord = $totalRecord->count();
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
        $rows = DB::table('timeset')->where('timeset_status', 1);
        // 添加筛选条件
        // 时间名称
        if ($request->has('filter1')) {
            if($request->input('filter1')!=''){
                $rows = $rows->where('timeset_name', 'like', '%'.$request->input('filter1').'%');
            }
        }
        $rows = $rows->orderBy('timeset_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)->get();
        return view('school/timeset/index', ['rows' => $rows,
                                             'currentPage' => $currentPage,
                                             'totalPage' => $totalPage,
                                             'startIndex' => ($currentPage-1)*$rowPerPage]);
    }

    /**
     * 创建新时间页面
     * URL: GET /timeset/create
     */
    public function create(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        return view('school/timeset/create');
    }

    /**
     * 创建新时间提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 上课时间
     * @param  $request->input('input1'): 下课时间
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $timeset_start = $request->input('input1');
        $timeset_end = $request->input('input2');
        // 获取当前用户ID
        $timeset_createuser = Session::get('user_id');
        // 起始时间大于等于截止时间
        if($timeset_start>=$timeset_end){
            return redirect()->action('School\TimesetController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '时间添加失败',
                                     'message' => '上课时间大于等于下课时间，时间添加失败，请重新输入信息']);
        }
        // 判断时间重叠
        $timesets_num = DB::table('timeset')
                          ->where('timeset_start', '<', $timeset_start)
                          ->where('timeset_end', '>', $timeset_start)
                          ->count();
        $timesets_num += DB::table('timeset')
                          ->where('timeset_start', '<', $timeset_end)
                          ->where('timeset_end', '>', $timeset_end)
                          ->count();
        $timesets_num += DB::table('timeset')
                          ->where('timeset_start', '>', $timeset_start)
                          ->where('timeset_end', '<', $timeset_end)
                          ->count();
        if($timesets_num>0){
            return redirect()->action('School\TimesetController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '时间添加失败',
                                     'message' => '新上课时间段与原有上课时间有重叠，时间添加失败，请重新输入信息']);
        }
        // 插入数据库
        try{
            DB::table('timeset')->insert(
                ['timeset_start' => $timeset_start,
                 'timeset_end' => $timeset_end,
                 'timeset_createuser' => $timeset_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\TimesetController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '时间添加失败',
                                     'message' => '时间添加失败，请重新输入信息']);
        }
        // 返回时间列表
        return redirect()->action('School\TimesetController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '时间添加成功',
                                 'message' => '上课时间: '.$timeset_start."，下课时间：".$timeset_end."。"]);
    }

    /**
     * 显示单个时间详细信息
     * URL: GET /timeset/{id}
     * @param  int  $timeset_id
     */
    public function show($timeset_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $timeset = DB::table('timeset')->where('timeset_id', $timeset_id)->get();
        if($timeset->count()!==1){
            // 未获取到数据
            return redirect()->action('School\TimesetController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '时间显示失败',
                                     'message' => '时间显示失败，请联系系统管理员']);
        }
        $timeset = $timeset[0];
        return view('school/timeset/show', ['timeset' => $timeset]);
    }

    /**
     * 修改单个时间
     * URL: GET /timeset/{id}/edit
     * @param  int  $timeset_id
     */
    public function edit($timeset_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $timeset = DB::table('timeset')->where('timeset_id', $timeset_id)->get();
        if($timeset->count()!==1){
            // 未获取到数据
            return redirect()->action('School\TimesetController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '时间显示失败',
                                     'message' => '时间显示失败，请联系系统管理员']);
        }
        $timeset = $timeset[0];
        return view('school/timeset/edit', ['timeset' => $timeset]);
    }

    /**
     * 修改新时间提交数据库
     * URL: PUT /timeset/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 时间名称
     * @param  int  $timeset_id
     */
    public function update(Request $request, $timeset_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $timeset_name = $request->input('input1');
        // 更新数据库
        try{
            DB::table('timeset')
                    ->where('timeset_id', $timeset_id)
                    ->update(['timeset_name' => $timeset_name]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/timeset/{$timeset_id}/edit")->with(['notify' => true,
                                                                   'type' => 'danger',
                                                                   'title' => '时间修改失败',
                                                                   'message' => '时间修改失败，请重新输入信息']);
        }
        return redirect("/timeset")->with(['notify' => true,
                                           'type' => 'success',
                                           'title' => '时间修改成功',
                                           'message' => '时间修改成功，时间名称: '.$timeset_name]);
    }

    /**
     * 删除时间
     * URL: DELETE /timeset/{id}
     * @param  int  $timeset_id
     */
    public function destroy($timeset_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $timeset_name = DB::table('timeset')->where('timeset_id', $timeset_id)->value('timeset_name');
        // 删除数据
        try{
            DB::table('timeset')->where('timeset_id', $timeset_id)->update(['timeset_status' => 0]);
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\TimesetController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '时间删除失败',
                                     'message' => '时间删除失败，请联系系统管理员']);
        }
        // 返回时间列表
        return redirect()->action('School\TimesetController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '时间删除成功',
                                 'message' => '时间名称: '.$timeset_name]);
    }
}
