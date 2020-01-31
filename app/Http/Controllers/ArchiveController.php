<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class ArchiveController extends Controller
{
    /**
     * 显示所有档案记录
     * URL: GET /archive
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 用户姓名
     * @param  $request->input('filter2'): 用户校区
     * @param  $request->input('filter3'): 档案名称
     */
    public function index(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }

        // 获取用户信息
        $user_level = Session::get('user_level');

        // 获取数据
        $rows = DB::table('archive')
                  ->join('user', 'archive.archive_user', '=', 'user.user_id')
                  ->join('department', 'user.user_department', '=', 'department.department_id');

        // 添加筛选条件
        // 用户姓名
        if ($request->filled('filter1')) {
            $rows = $rows->where('user_name', 'like', '%'.$request->input('filter1').'%');
        }
        // 用户校区
        if ($request->filled('filter2')) {
            $rows = $rows->where('user_department', $request->input('filter2'));
        }
        // 档案名称
        if ($request->filled('filter3')) {
            $rows = $rows->where('archive_name', 'like', '%'.$request->input('filter3').'%');
        }

        // 保存数据总数
        $totalNum = $rows->count();
        // 计算分页信息
        list ($offset, $rowPerPage, $currentPage, $totalPage) = pagination($totalNum, $request, 20);

        // 排序并获取数据对象
        $rows = $rows->orderBy('archive_createtime', 'asc')
                     ->offset($offset)
                     ->limit($rowPerPage)
                     ->get();

        // 获取校区信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->orderBy('department_createtime', 'asc')->get();

        // 返回列表视图
        return view('archive/index', ['rows' => $rows,
                                      'currentPage' => $currentPage,
                                      'totalPage' => $totalPage,
                                      'startIndex' => $offset,
                                      'request' => $request,
                                      'totalNum' => $totalNum,
                                      'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新档案页面
     * URL: GET /archive/create
     * @param  Request  $request
     * @param  $request->input('user_id'): 用户ID
     */
    public function create(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取用户信息
        $users = DB::table('user')->orderBy('user_createtime', 'asc')->get();
        // 获取用户ID
        if ($request->has('user_id')) {
            $user_id = $request->input('user_id');
        }else{
            $user_id = "NULL";
        }
        return view('archive/create', ['users' => $users, 'user_id' => $user_id]);
    }

    /**
     * 创建新档案提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 用户姓名
     * @param  $request->input('input2'): 档案名称
     * @param  $request->file('file');: 档案文件
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取上传文件
        $file = $request->file('file');
        // 获取文件大小(MB)
        $archive_file_size = $file->getClientSize()/1024/1024+0.01;
        // 判断文件是否大于20MB
        if($archive_file_size>20){
            return redirect("/archive")->with(['notify' => true,
                                               'type' => 'danger',
                                               'title' => '档案添加失败',
                                               'message' => '文件大于20MB，档案添加失败，请重新上传文件']);
        }
        // 获取文件名称
        $archive_file_name = $file->getClientOriginalName();
        // 获取文件扩展名
        $archive_ext = $file->getClientOriginalExtension();
        // 生成随机文件名
        $archive_path = date('ymdHis').rand(1000000000,9999999999).".".$archive_ext;
        // 获取表单输入
        $archive_user = $request->input('input1');
        $archive_name = $request->input('input2');
        // 获取当前用户ID
        $archive_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('archive')->insert(
                ['archive_user' => $archive_user,
                 'archive_name' => $archive_name,
                 'archive_file_name' => $archive_file_name,
                 'archive_file_size' => $archive_file_size,
                 'archive_path' => $archive_path,
                 'archive_createuser' => $archive_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\ArchiveController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '档案添加失败',
                                     'message' => '档案添加失败，请重新输入信息']);
        }
        // 上传文件
        $file->move("files/archive", $archive_path);
        // 获取用户名称
        $user_name = DB::table('user')
                       ->where('user_id', $archive_user)
                       ->value('user_name');
        // 返回用户列表
        return redirect("/user/{$archive_user}")
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '档案添加成功',
                                 'message' => '档案用户: '.$user_name.', 档案名称: '.$archive_name]);
    }

    /**
     * 下载档案文件
     * URL: GET /archive/{id}
     * @param  int  $archive_id
     */
    public function show($archive_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $archive = DB::table('archive')->where('archive_id', $archive_id)->get();
        if($archive->count()!==1){
            // 未获取到数据
            return redirect()->action('School\ArchiveController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '档案显示失败',
                                     'message' => '档案显示失败，请联系系统管理员']);
        }
        $archive = $archive[0];
        // 获取文件名和路径
        $file_path = "files/archive/".$archive->archive_path;
        $file_name = $archive->archive_file_name;
        // 下载文件
        if (file_exists($file_path)) {// 文件存在
            return response()->download($file_path, $file_name ,$headers = ['Content-Type'=>'application/zip;charset=utf-8']);
        }else{ // 文件不存在
            return redirect()->action('School\ArchiveController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '档案下载失败',
                                     'message' => '档案文件不存在，下载失败']);
        }
    }

    /**
     * 删除档案
     * URL: DELETE /archive/{id}
     * @param  int  $archive_id
     */
    public function destroy($archive_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $archive = DB::table('archive')->where('archive_id', $archive_id)->first();
        $archive_name = $archive->archive_name;
        $archive_path = "files/archive/".$archive->archive_path;
        $archive_user = $archive->archive_user;
        // 删除数据
        DB::table('archive')->where('archive_id', $archive_id)->delete();
        // 如果文件存在，删除文件
        if (file_exists($archive_path)) {
            unlink($archive_path);
        }
        // 获取用户名称
        $user_name = DB::table('user')
                       ->where('user_id', $archive_user)
                       ->value('user_name');
        // 返回用户列表
        return redirect("/user/{$archive_user}")
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '档案删除成功',
                                 'message' => '档案用户: '.$user_name.', 档案名称: '.$archive_name]);
    }
}
