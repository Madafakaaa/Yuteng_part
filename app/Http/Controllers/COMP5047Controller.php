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
    public function index(Request $request){
        $data = $request->json()->all();
        // 插入数据库
        try{
            DB::table('COMP5047')->insert(
                ['COMP5047_status' => '1',
                 'COMP5047_posture' => '1',]
            );
        }
        // 捕获异常
        catch(Exception $e){

        }
        return 1;
    }


    /**
     * URL: GET /COMP5047
     */
    public function show(Request $request){
        $data = $request->json()->all();
        // 插入数据库
        try{
            DB::table('COMP5047')->insert(
                ['COMP5047_status' => '1',
                 'COMP5047_posture' => '1',]
            );
        }
        // 捕获异常
        catch(Exception $e){

        }
        return 1;
    }

}
