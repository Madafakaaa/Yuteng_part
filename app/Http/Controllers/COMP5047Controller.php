<?php
namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class COMP5047Controller extends Controller
{

    /**
     * URL: POST /COMP5047/dashboard
     */
    public function getJson(Request $request){
        $data = $request->json()->all();
        if($data["节点输入"]["deviceContext"]["deviceName"]=="cushion"){
            $props = $data["节点输入"]["props"];
            // Get 3 status info (bool 0-1)
            $status = $props["Status"]["value"];
            $sitStatus = $props["SitStatus"]["value"];
            $gestureStatus = $props["GestureStatus"]["value"];
            // Get 4 distance info (int)
            $dist0 = $props["Dist0"]["value"];
            $dist1 = $props["Dist1"]["value"];
            $dist2 = $props["Dist2"]["value"];
            $dist3 = $props["Dist3"]["value"];
            // Get 1 data info (string)
            $text = $props["Data"]["value"];
            // Insert into database
            try{
                DB::table('COMP5047')->insert(
                    ['COMP5047_status' => $status,
                     'COMP5047_sit_status' => $sitStatus,
                     'COMP5047_posture_status' => $gestureStatus,
                     'COMP5047_dist0' => $dist0,
                     'COMP5047_dist1' => $dist1,
                     'COMP5047_dist2' => $dist2,
                     'COMP5047_dist3' => $dist3,
                     'COMP5047_text' => $text]
                );
            }
            // 捕获异常
            catch(Exception $e){}
        }else if($data["节点输入"]["deviceContext"]["deviceName"]=="camera"){
            $props = $data["节点输入"]["props"];
            // Get User Id
            $userId = $props["FaceID"]["value"];
            // Insert into database
            try{
                DB::table('COMP5047_user')->insert(
                    ['COMP5047_user_userid' => $userId]
                );
            }
            // 捕获异常
            catch(Exception $e){}
        }
    }


    /**
     * URL: GET /COMP5047/dashboard
     */
    public function index(){
        return view("comp5047dashboard");
    }

    /**
     * URL: GET /COMP5047/getData
     */
    public function getData(){
        return view("comp5047refresh");
    }

    /**
     * URL: GET /COMP5047/setting
     */
    public function setting(){
        $timeData = DB::table('COMP5047_time')->first();
        $con_limit = $timeData->COMP5047_time_con;
        $day_limit = $timeData->COMP5047_time_day;
        return view("comp5047setting", ['con_limit' => $con_limit, 'day_limit' => $day_limit]);
    }

    /**
     * URL: POST /COMP5047/update
     */
    public function update(Request $request){
        $con_limit = $request->input('input1');
        $day_limit = $request->input('input2');
        DB::table('COMP5047_time')->where('COMP5047_time_id', 1)->update(['COMP5047_time_con' => $con_limit, 'COMP5047_time_day' => $day_limit]);
        return redirect("/comp5047/dashboard");
    }

}
