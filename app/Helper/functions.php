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

function pagination($totalRecord, $request, $rowPerPage=20){
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
    // 计算offset偏移
    $offset = ($currentPage-1)*$rowPerPage;
    return array($offset, $rowPerPage, $currentPage, $totalPage);
}


function numberToCh($num){
    $ch=array('零','一','二','三','四','五','六','七','八','九');
    return $ch[$num];
}
