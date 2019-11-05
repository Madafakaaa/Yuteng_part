<?php
    function dashboard_block($title, $content, $status){
        echo "<div class='col-xl-3 col-sm-6'>
                <div class='card card-stats'>
                  <!-- Card body -->
                  <div class='card-body'>
                    <div class='row'>
                      <div class='col'>
                        <h5 class='card-title text-uppercase text-muted mb-0'>{$title}</h5>
                        <span class='h2 font-weight-bold mb-0'>{$content}</span>
                      </div>
                      <div class='col-auto'>";
        if($status==0){
            echo "      <div class='icon icon-shape bg-gradient-red text-white rounded-circle shadow'>
                          <i class='ni ni-fat-delete'></i>
                        </div>";
        }else if($status==1){
            echo "      <div class='icon icon-shape bg-gradient-green text-white rounded-circle shadow'>
                          <i class='ni ni-check-bold'></i>
                        </div>";
        }else{
            echo "      <div class='icon icon-shape bg-gradient-red text-white rounded-circle shadow'>
                          <i class='ni ni-fat-remove'></i>
                        </div>";
        }
        echo "
                      </div>
                    </div>
                  </div>
                </div>
              </div>";
    }
    function posture_block($status){
        $path = asset(_ASSETS_);
        echo "<div class='col-xl-6 col-sm-0'>
                <div class='card'>
                  <div class='card-header'>
                    <h6 class='surtitle'>Realtime</h6>
                    <h5 class='h3 mb-0'>Sitting posture</h5>
                  </div>
                  <div class='card-body ml-auto mr-auto'>";
        if($status==0){
            echo "<img style='height:270px;width:270px;' src='{$path}/img/brand/posture0.png' />";
        }else if($status==1){
            echo "<img style='height:270px;width:270px;' src='{$path}/img/brand/posture1.png' />";
        }else{
            echo "<img style='height:270px;width:270px;' src='{$path}/img/brand/posture2.png' />";
        }
        echo "    </div>
                </div>
              </div>";
    }
    // Set timezone
    date_default_timezone_set('PRC');
    // Set timeout
    $timeout = 5 * 60;
    // Get last data
    $lastData = DB::table('comp5047')->orderBy('COMP5047_id', 'desc')->first();
    // Calculate the duration since last data
    $last_time =  $lastData->COMP5047_createtime;
    $time_1 = strtotime($last_time);
    $time_2 = strtotime(date('Y-m-d H:i:s'));
    $time_diff = $time_2 - $time_1;
    // Initiate data
    $status = $lastData->COMP5047_status;
    $sit_status = $lastData->COMP5047_sit_status;
    $posture_status = $lastData->COMP5047_posture_status;
    $dist0 = $lastData->COMP5047_dist0;
    $dist1 = $lastData->COMP5047_dist1;
    $dist2 = $lastData->COMP5047_dist2;
    $dist3 = $lastData->COMP5047_dist3;
    // Get time limit
    $dataSetup = DB::table('COMP5047_time')->first();
    $max_time_con = $dataSetup->COMP5047_time_con;
    $max_time_day = $dataSetup->COMP5047_time_day;
    // Get sitting time toady
    $today = date('Y-m-d');
    $time_day = 30 * DB::table('comp5047')->where([
                                                     ['COMP5047_status', '=', '1'],
                                                     ['COMP5047_sit_status', '=', '1'],
                                                     ['COMP5047_createtime', 'LIKE', $today.'%']
                                                 ])->count();
    if($time_day>=30){
        $time_day = $time_day - 30;
    }
    // Get continuous sitting time
    $time_con = 0;
    if($time_diff<$timeout&&$status==1&&$sit_status==1){
        $lastNotSittingData = DB::table('comp5047')->where('COMP5047_status', 0)
                                                   ->orWhere('COMP5047_sit_status', 0)
                                                   ->orderBy('COMP5047_id', 'desc')
                                                   ->first();
        $lastNotSittingId = 1 + $lastNotSittingData->COMP5047_id;
        $firstSittingData = DB::table('comp5047')->where('COMP5047_id', $lastNotSittingId)->first();
        $firstSittingTime = strtotime($firstSittingData->COMP5047_createtime);
        $time_con = $time_2 - $firstSittingTime;
    }
    $per_con = floor(100*$time_con/$max_time_con);
    $per_day = floor(100*$time_day/$max_time_day);
    // Display dashboard
    if($time_diff>$timeout||$status==0){
        //Device off line
        dashboard_block("Device status", "Offline", 0);
        dashboard_block("Sitting status", "Offline", 0);
        dashboard_block("Posture status", "Offline", 0);
        dashboard_block("Sitting user", "Offline", 0);
        dashboard_block("Distance sensor 1", "Offline", 0);
        dashboard_block("Distance sensor 2", "Offline", 0);
        dashboard_block("Distance sensor 3", "Offline", 0);
        dashboard_block("Distance sensor 4", "Offline", 0);
        posture_block(0);
    }else{
        //Device online line
        dashboard_block("Device status", "Online", 1);
        if($sit_status==1){
            // Get user id
            $lastUserData = DB::table('COMP5047_user')->orderBy('COMP5047_user_id', 'desc')->first();
            $lastUseId = $lastUserData->COMP5047_user_userid;
            $lastUseTime = strtotime($lastUserData->COMP5047_user_createtime);
            $user_time_diff = $time_2 - $lastUseTime;
            // User sitting
            dashboard_block("Sitting status", "Sitting", 1);
            if($posture_status==0){
                dashboard_block("Posture status", "Bad", 2);
            }else{
                dashboard_block("Posture status", "Good", 1);
            }
            if($user_time_diff>30){
                dashboard_block("Sitting user", "Not detected", 2);
            }else{
                dashboard_block("Sitting user", "User ".$lastUseId, 1);
            }
            if($dist0>=0){
                dashboard_block("Distance sensor 1", $dist0." mm", 1);
            }else{
                dashboard_block("Distance sensor 1", "Sensor Error", 2);
            }
            if($dist1>=0){
                dashboard_block("Distance sensor 2", $dist1." mm", 1);
            }else{
                dashboard_block("Distance sensor 2", "Sensor Error", 2);
            }
            if($dist2>=0){
                dashboard_block("Distance sensor 3", $dist2." mm", 1);
            }else{
                dashboard_block("Distance sensor 4", "Sensor Error", 2);
            }
            if($dist3>=0){
                dashboard_block("Distance sensor 5", $dist3." mm", 1);
            }else{
                dashboard_block("Distance sensor 6", "Sensor Error", 2);
            }
            posture_block(2-$posture_status);
        }else{
            // User not sitting
            dashboard_block("Sitting status", "Not Sitting", 0);
            dashboard_block("Posture status", "Not Sitting", 0);
            dashboard_block("Sitting user", "Not Sitting", 0);
            dashboard_block("Distance sensor 1", "Not Sitting", 0);
            dashboard_block("Distance sensor 2", "Not Sitting", 0);
            dashboard_block("Distance sensor 3", "Not Sitting", 0);
            dashboard_block("Distance sensor 4", "Not Sitting", 0);
            posture_block(0);
        }
    }
?>
<div class='col-xl-6 col-md-12'>
  <div class='row'>
    <div class='col-xl-12 col-md-12'>
      <div class='card bg-gradient-info border-0'>
        <div class='card-body'>
          <div class='row'>
            <div class='col'>
              <h5 class='card-title text-uppercase text-muted mb-0 text-white'>Continuous sitting time</h5>
              <?php
                if($time_con>$max_time_con){
                    echo "<span class='h2 font-weight-bold mb-0 text-red'>100 %</span>";
                }else{
                    echo "<span class='h2 font-weight-bold mb-0 text-white'>{$per_con} %</span>";
                }
              ?>
              <div class='progress progress-xs mt-3 mb-0'>
                <div class='progress-bar bg-success' role='progressbar' aria-valuenow='30' aria-valuemin='0' aria-valuemax='100' style="width: {{$per_con}}%;"></div>
              </div>
            </div>
            <div class='col-auto'>
              <a href="/comp5047/setting">
                <button type='button' class='btn btn-sm btn-neutral mr-0'>
                  Update
                </button>
              </a>
            </div>
          </div>
          <p class='mt-3 mb-0 text-sm'>
            <?php
                $time_con_min = floor($time_con/60);
                $max_time_con_min = floor($max_time_con/60);
                if($time_con>$max_time_con){
                    echo "<a href='#' class='text-nowrap text-red font-weight-600'>{$time_con_min} / {$max_time_con_min} mins</a>";
                }else{
                    echo "<a href='#' class='text-nowrap text-white font-weight-600'>{$time_con_min} / {$max_time_con_min} mins</a>";
                }
            ?>
          </p>
        </div>
      </div>
    </div>
    <div class='col-xl-12 col-md-12'>
      <div class='card bg-gradient-info border-0'>
        <div class='card-body'>
          <div class='row'>
            <div class='col'>
              <h5 class='card-title text-uppercase text-muted mb-0 text-white'>Sitting time today</h5>
              <?php
                if($time_day>$max_time_day){
                    echo "<span class='h2 font-weight-bold mb-0 text-red'>100 %</span>";
                }else{
                    echo "<span class='h2 font-weight-bold mb-0 text-white'>{$per_day} %</span>";
                }
              ?>
              <div class='progress progress-xs mt-3 mb-0'>
                <div class='progress-bar bg-success' role='progressbar' aria-valuenow='30' aria-valuemin='0' aria-valuemax='100' style="width: {{$per_day}}%;"></div>
              </div>
            </div>
            <div class='col-auto'>
              <a href="/comp5047/setting">
                <button type='button' class='btn btn-sm btn-neutral mr-0'>
                  Update
                </button>
              </a>
            </div>
          </div>
          <p class='mt-3 mb-0 text-sm'>
            <?php
                $time_day_min = floor($time_day/60);
                $max_time_day_min = floor($max_time_day/60);
                if($time_day>$max_time_day){
                    echo "<a href='#' class='text-nowrap text-red font-weight-600'>{$time_day_min} / {$max_time_day_min} mins</a>";
                }else{
                    echo "<a href='#' class='text-nowrap text-white font-weight-600'>{$time_day_min} / {$max_time_day_min} mins</a>";
                }
            ?>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
<?php
    if($time_con>$max_time_con||$time_day>$max_time_day){
        echo "<script>
              alert('Stand up!!!!');
              </script>";
    }
?>
