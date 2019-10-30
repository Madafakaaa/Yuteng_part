<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class COMP5047Controller extends Controller
{

    /**
     * URL: POST /COMP5047
     */
    public function index(){
        // 插入数据库
        try{
            DB::table('COMP5047')->insertGetId(
                ['COMP5047_status' => '1',
                 'COMP5047_posture' => '1',]
            );
        }
        // 捕获异常
        catch(Exception $e){
            
        }
    }


    /**
     * URL: GET /COMP5047
     */
    public function show()
    {

    }
