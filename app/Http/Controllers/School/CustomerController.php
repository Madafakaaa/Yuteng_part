<?php
namespace App\Http\Controllers\School;

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
        // 获取数据库信息
        // 获取总数据数
        $totalRecord = DB::table('customer')->count();
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
        $rows = DB::table('customer')->orderBy('customer_createtime', 'asc')->offset($offset)->limit($rowPerPage)->get();
        return view('school/customer/index', ['rows' => $rows, 'currentPage' => $currentPage, 'totalPage' => $totalPage, 'startIndex' => ($currentPage-1)*$rowPerPage]);
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
        return view('school/customer/create');
    }

    /**
     * 创建新客户提交数据库
     * URL: POST
     * @param  Request  $request
     * @param  $request->input('input1'): 客户名称
     */
    public function store(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取表单输入
        $customer_name = $request->input('input1');
        // 获取当前用户ID
        $customer_createuser = Session::get('user_id');
        // 插入数据库
        try{
           DB::table('customer')->insert(
                ['customer_name' => $customer_name,
                 'customer_createuser' => $customer_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户添加失败',
                                     'message' => '客户添加失败，请重新输入信息']);
        }
        // 返回客户列表
        return redirect()->action('School\CustomerController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '客户添加成功',
                                 'message' => '客户名称: '.$customer_name]);
    }

    /**
     * 显示单个客户详细信息
     * URL: GET /customer/{id}
     * @param  int  $customer_id
     */
    public function show($customer_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取数据信息
        $customer = DB::table('customer')->where('customer_id', $customer_id)->get();
        if($customer->count()!==1){
            // 未获取到数据
            return redirect()->action('School\CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户显示失败',
                                     'message' => '客户显示失败，请联系系统管理员']);
        }
        $customer = $customer[0];
        return view('school/customer/show', ['customer' => $customer]);
    }

    /**
     * 修改单个客户
     * URL: GET /customer/{id}/edit
     * @param  int  $customer_id
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
            return redirect()->action('School\CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户显示失败',
                                     'message' => '客户显示失败，请联系系统管理员']);
        }
        $customer = $customer[0];
        return view('school/customer/edit', ['customer' => $customer]);
    }

    /**
     * 修改新客户提交数据库
     * URL: PUT /customer/{id}
     * @param  Request  $request
     * @param  $request->input('input1'): 客户id
     * @param  $request->input('input2'): 客户名称
     * @param  int  $customer_id
     */
    public function update(Request $request, $customer_id){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
         // 获取表单输入
        $customer_id = $request->input('input1');
        $customer_name = $request->input('input2');
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
        return redirect("/customer/{$customer_id}")->with(['notify' => true,
                                                           'type' => 'success',
                                                           'title' => '客户修改成功',
                                                           'message' => '客户修改成功，客户名称: '.$customer_name]);
    }

    /**
     * 删除客户
     * URL: DELETE /customer/{id}
     * @param  int  $customer_id
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
            DB::table('customer')->where('customer_id', $customer_id)->delete();
        }
        // 捕获异常
        catch(Exception $e){
            return redirect()->action('School\CustomerController@index')
                             ->with(['notify' => true,
                                     'type' => 'danger',
                                     'title' => '客户删除失败',
                                     'message' => '客户删除失败，请联系系统管理员']);
        }
        // 返回客户列表
        return redirect()->action('School\CustomerController@index')
                         ->with(['notify' => true,
                                 'type' => 'success',
                                 'title' => '客户删除成功',
                                 'message' => '客户名称: '.$customer_name]);
    }
}
