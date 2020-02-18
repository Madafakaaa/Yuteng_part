<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class HomeController extends Controller
{
    /**
     * 主界面显示
     * URL: GET /home
     */
    public function home()
    {
        // 检查登录状态
        if(Session::has('login')){
            // 已登录,返回主页视图
            return view('/main');
        }else{
            // 未登录，返回登陆视图
            return redirect('/')->with(['notify' => true,
                                        'type' => 'danger',
                                        'title' => '您尚未登录',
                                        'message' => '请输入用户名及密码登陆系统']);
        }
    }

}
