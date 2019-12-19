<?php

/**
 * Session过期，返回登陆视图。
 * @return 登陆视图
 */
function loginExpired(){
    return redirect()->action('LoginController@index')
                     ->with(['notify' => true,
                             'type' => 'danger',
                             'title' => '您尚未登录',
                             'message' => '请输入用户名及密码登陆系统']);
}
