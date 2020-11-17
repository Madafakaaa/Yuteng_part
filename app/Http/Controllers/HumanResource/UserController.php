<?php
namespace App\Http\Controllers\HumanResource;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Exception;

class UserController extends Controller
{

    /**
     * 显示所有用户记录
     * URL: GET /humanResource/user
     * @param  Request  $request
     * @param  $request->input('page'): 页数
     * @param  $request->input('filter1'): 用户姓名
     * @param  $request->input('filter2'): 用户校区
     * @param  $request->input('filter3'): 用户岗位
     * @param  $request->input('filter4'): 用户等级
     */
    public function user(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/user", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }

        // 获取用户校区权限
        $department_access = Session::get('department_access');

        // 获取数据
        $rows = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_status', 1);


        // 搜索条件
        $filters = array(
                        "filter_department" => null,
                    );
        // 所属校区
        if($request->filled('filter_department')){
            $rows = $rows->where('department_id', '=', $request->input('filter_department'));
            $filters['filter_department']=$request->input("filter_department");
        }


        // 排序并获取数据对象
        $rows = $rows->orderBy('user_department', 'asc')
                     ->orderBy('position_level', 'asc')
                     ->get();

        // 获取校区、岗位、等级信息(筛选)
        $filter_departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();

        // 返回列表视图
        return view('humanResource/user/user', ['rows' => $rows,
                                                'request' => $request,
                                                'filters' => $filters,
                                                'filter_departments' => $filter_departments]);
    }

    /**
     * 创建新用户页面
     * URL: GET /humanResource/user/create
     */
    public function userCreate(){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/user/create", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取用户校区权限
        $department_access = Session::get('department_access');
        // 获取年级、科目、用户信息
        $departments = DB::table('department')->where('department_status', 1)->whereIn('department_id', $department_access)->orderBy('department_id', 'asc')->get();
        $positions = DB::table('position')
                       ->join('section', 'position.position_section', '=', 'section.section_id')
                       ->where('position_status', 1)
                       ->where('section_status', 1)
                       ->orderBy('position_id', 'asc')
                       ->get();
        return view('humanResource/user/userCreate', ['departments' => $departments, 'positions' => $positions]);
    }

    /**
     * 创建新用户提交数据库
     * URL: POST /humanResource/user/create
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
    public function userStore(Request $request){
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
        $user_entry_date = $request->input('input5');
        $user_cross_teaching = $request->input('input6');
        // 判断是否为空，为空设为""
        if($request->filled('input7')) {
            $user_phone = $request->input('input7');
        }else{
            $user_phone = "无";
        }
        if($request->filled('input8')) {
            $user_wechat = $request->input('input8');
        }else{
            $user_wechat = "无";
        }
        if($user_gender=='男'){
            $user_photo="male.png";
        }else{
            $user_photo="female.png";
        }
        // 获取当前用户ID
        $user_createuser = Session::get('user_id');
        // 插入数据库
        try{
            DB::table('user')->insert(
                ['user_id' => $user_id,
                 'user_name' => $user_name,
                 'user_gender' => $user_gender,
                 'user_photo' => $user_photo,
                 'user_department' => $user_department,
                 'user_position' => $user_position,
                 'user_entry_date' => $user_entry_date,
                 'user_cross_teaching' => $user_cross_teaching,
                 'user_phone' => $user_phone,
                 'user_wechat' => $user_wechat,
                 'user_createuser' => $user_createuser]
            );
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/humanResource/user/create")
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '用户添加失败',
                             'message' => '用户添加失败，错误码:113']);
        }
        // 返回用户列表
        return redirect("/humanResource/user")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '用户添加成功',
                       'message' => '用户序号: '.$user_id.', 用户名称: '.$user_name]);
    }

    /**
     * 用户权限视图
     * URL: GET /humanResource/user/access/{user_id}
     * @param  int  $user_id
     */
    public function userAccess(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/user/access", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取user_id
        $user_id = decode($request->input('id'), 'user_id');
        // 获取全部校区
        $user = DB::table('user')
                  ->join('department', 'user.user_department', '=', 'department.department_id')
                  ->join('position', 'user.user_position', '=', 'position.position_id')
                  ->join('section', 'position.position_section', '=', 'section.section_id')
                  ->where('user_id', $user_id)
                  ->first();
        // 获取全部校区
        $departments = DB::table('department')
                          ->where('department_status', 1)
                          ->orderBy('department_id', 'asc')
                          ->get();
        $department_array = array();
        foreach($departments AS $department){
            $department_array[$department->department_id] = array($department->department_id, $department->department_name, 0);
        }
        // 获取用户校区权限
        $user_departments = DB::table('user_department')
                              ->where('user_department_user', $user_id)
                              ->get();
        foreach($user_departments AS $user_department){
            $department_array[$user_department->user_department_department][2]=1;
        }

        // 获取全部页面种类及其页面
        $accesses = array();
        $db_access_categories = DB::table('access')->select('access_category')->distinct()->get();
        foreach($db_access_categories as $db_access_category){
            $db_access_pages = DB::table('access')
                                 ->select('access_page')
                                 ->where('access_category', $db_access_category->access_category)
                                 ->distinct()
                                 ->get();
            foreach($db_access_pages as $db_access_page){
                $db_access_features = DB::table('access')
                                        ->where('access_category', $db_access_category->access_category)
                                        ->where('access_page', $db_access_page->access_page)
                                        ->get();
                foreach($db_access_features as $db_access_feature){
                    $temp = array();
                    $temp['access_url']=$db_access_feature->access_url;
                    $temp['access_feature']=$db_access_feature->access_feature;
                    $accesses[$db_access_category->access_category][$db_access_page->access_page][] = $temp;
                }
            }
        }
        // 获取用户页面权限
        $user_accesses = array();
        $db_user_accesses = DB::table('user_access')
                              ->where('user_access_user', $user_id)
                              ->get();
        foreach($db_user_accesses AS $db_user_access){
            $user_accesses[] = $db_user_access->user_access_access;
        }

        // 获取主页权限
        $dashboard_accesses = DB::table('dashboard_access')->get();
        // 获取用户主页权限
        $db_user_dashboards = DB::table('user_dashboard')
                                ->where('user_dashboard_user', $user_id)
                                ->get();
        $user_dashboards = array();
        foreach($db_user_dashboards AS $db_user_dashboard){
            $user_dashboards[] = $db_user_dashboard->user_dashboard_dashboard;
        }

        return view('humanResource/user/userAccess', ['user' => $user,
                                                      'department_array' => $department_array,
                                                      'dashboard_accesses' => $dashboard_accesses,
                                                      'user_dashboards' => $user_dashboards,
                                                      'accesses' => $accesses,
                                                      'user_accesses' => $user_accesses]);
    }

    /**
     * 修改用户权限提交
     * URL: POST /user/access/{user_id}
     * @param  Request  $request
     * @param  $request->input('departments'): 校区权限
     * @param  $request->input('pages'): 页面权限
     * @param  int  $user_id: 用户id
     */
    public function userAccessUpdate(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 获取user_id
        $user_id = decode($request->input('id'), 'user_id');
        // 获取表单输入
        $departments = $request->input('departments');
        $accesses = $request->input('accesses');
        $user_access_self = $request->input('user_access_self');
        $dashboards = $request->input('dashboards');
        // 更新数据库
        DB::beginTransaction();
        try{
            // 删除原有权限
            DB::table('user_department')
              ->where('user_department_user', $user_id)
              ->delete();
            DB::table('user_access')
              ->where('user_access_user', $user_id)
              ->delete();
            DB::table('user_dashboard')
              ->where('user_dashboard_user', $user_id)
              ->delete();

            if($dashboards!=NULL){
                // 添加主页权限
                foreach($dashboards as $dashboard){
                    DB::table('user_dashboard')->insert(
                        ['user_dashboard_user' => $user_id,
                         'user_dashboard_dashboard' => $dashboard]
                    );
                }
            }
            if($departments!=NULL){
                // 添加校区权限
                foreach($departments as $department){
                    DB::table('user_department')->insert(
                        ['user_department_user' => $user_id,
                         'user_department_department' => $department]
                    );
                }
            }
            if($accesses!=NULL){
                // 添加页面权限
                foreach($accesses as $access){
                    DB::table('user_access')->insert(
                        ['user_access_user' => $user_id,
                         'user_access_access' => $access]
                    );
                }
            }
            DB::table('user')->where('user_id', $user_id)->update(['user_access_self' => $user_access_self]);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/humanResource/user/access?id=".encode($user_id, 'user_id'))
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '用户权限修改失败',
                           'message' => '用户权限修改失败，错误码:114']);
        }
        DB::commit();
        return redirect("/humanResource/user/access?id=".encode($user_id, 'user_id'))
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '用户权限修改成功',
                       'message' => '用户权限修改成功,新权限将在重新登录后生效！']);
    }

    /**
     * 恢复用户默认密码
     * URL: GET /humanResource/user/password/restore/{user_id}
     * @param  Request  $request
     * @param  $request->input('departments'): 校区权限
     * @param  $request->input('pages'): 页面权限
     * @param  int  $user_id: 用户id
     */
    public function userPasswordRestore(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/user/password/restore", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取user_id
        $user_id = decode($request->input('id'), 'user_id');
        // 更新数据库
        DB::beginTransaction();
        try{
            // 更改密码为000000
            DB::table('user')->where('user_id', $user_id)->update(['user_password' => '000000']);
        }
        // 捕获异常
        catch(Exception $e){
            DB::rollBack();
            return redirect("/humanResource/user")
                   ->with(['notify' => true,
                           'type' => 'danger',
                           'title' => '恢复用户默认密码失败',
                           'message' => '恢复用户默认密码失败，错误码:115']);
        }
        DB::commit();
        return redirect("/humanResource/user")
               ->with(['notify' => true,
                       'type' => 'success',
                       'title' => '恢复用户默认密码成功',
                       'message' => '恢复用户默认密码成功！']);
    }

    /**
     * 删除用户
     * URL: DELETE /humanResource/user/{id}
     * @param  int  $user_id
     */
    public function userDelete(Request $request){
        // 检查登录状态
        if(!Session::has('login')){
            return loginExpired(); // 未登录，返回登陆视图
        }
        // 检测用户权限
        if(!in_array("/humanResource/user/delete", Session::get('user_accesses'))){
           return back()->with(['notify' => true,'type' => 'danger','title' => '您的账户没有访问权限']);
        }
        // 获取user_id
        $request_ids=$request->input('id');
        $user_ids = array();
        if(is_array($request_ids)){
            foreach ($request_ids as $request_id) {
                $user_ids[]=decode($request_id, 'user_id');
            }
        }else{
            $user_ids[]=decode($request_ids, 'user_id');
        }
        // 删除数据
        try{
            foreach ($user_ids as $user_id){
                DB::table('user')
                  ->where('user_id', $user_id)
                  ->update(['user_status' => 0]);
            }
        }
        // 捕获异常
        catch(Exception $e){
            return redirect("/humanResource/user")
                   ->with(['notify' => true,
                         'type' => 'danger',
                         'title' => '用户离职失败',
                         'message' => '用户离职失败，错误码:116']);
        }
        // 返回用户列表
        return redirect("/humanResource/user")
                 ->with(['notify' => true,
                         'type' => 'success',
                         'title' => '用户离职成功',
                         'message' => '用户离职成功']);
    }


}
