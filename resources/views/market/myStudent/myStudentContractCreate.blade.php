@extends('main')

@section('nav')
<h2 class="text-white d-inline-block mb-0">签约合同</h2>
<nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
  <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">招生中心</li>
    <li class="breadcrumb-item"><a href="/market/myStudent">我的学生</a></li>
    <li class="breadcrumb-item active">签约合同</li>
  </ol>
</nav>
@endsection

@section('content')
<div class="container-fluid mt-4">
  <form action="/market/myCustomer/contract/store" method="post" id="form1" name="form1" onsubmit="submitButtonDisable('submit_button')">
    @csrf
    <div class="row">
      <div class="col-4">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">学生信息</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                <div class="form-group row mb-0">
                  <label class="col-md-4 col-form-label form-control-label">姓名</label>
                  <label class="col-md-8 col-form-label form-control-label">{{ $student->student_name }}</label>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row mb-0">
                  <label class="col-md-4 col-form-label form-control-label">校区</label>
                  <label class="col-md-8 col-form-label form-control-label">{{ $student->department_name }}</label>
                </div>
              </div>
              <div class="col-12">
                <div class="form-group row mb-0">
                  <label class="col-md-4 col-form-label form-control-label">年级</label>
                  <label class="col-md-8 col-form-label form-control-label">{{ $student->grade_name }}</label>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-8">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">已购课时</h3>
          </div>
          <div class="card-body" style="height:180px; overflow:auto;">
            <ul class="list-group list-group-flush list my--3">
              @foreach ($hours as $hour)
              <li class="list-group-item px-0">
                <div class="row align-items-center">
                  <div class="col">
                    <small>课程:</small>
                    <h5 class="mb-0">{{ $hour->course_name }}</h5>
                  </div>
                  <div class="col">
                    <small>剩余</small>
                    <h5 class="mb-0">{{ $hour->hour_remain }} 课时</h5>
                  </div>
                  <div class="col">
                    <small>已用</small>
                    <h5 class="mb-0">{{ $hour->hour_used }} 课时</h5>
                  </div>
                  <div class="col">
                    <small>课时单价</small>
                    <h5 class="mb-0">{{ $hour->hour_average_price }} 元</h5>
                  </div>
                </div>
              </li>
              @endforeach
            </ul>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header">
            <div class="row align-items-center">
              <div class="col-8">
                <h3 class="mb-0">购买课程</h3>
              </div>
              <div class="col-4 text-right">
                <a href="/market/myStudent/contract/create?id={{ encode($student->student_id, 'student_id') }}"><button type="button" class="btn btn-sm btn-outline-primary">
                  重新选择
                </button></a>
                <button type="button" class="btn btn-sm btn-warning" data-toggle="modal" data-target="#inputBox">
                  添加课程
                </button>
              </div>
            </div>
          </div>
          <div class="modal fade" id="inputBox" tabindex="-1" role="dialog" aria-labelledby="inputBoxLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header p-3">
                  <h5 class="h3 mb-0">添加课程</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body p--3" style="border-bottom:1px solid #DDD;border-top:1px solid #DDD;max-height:400px; overflow-x:auto;">
                  <ul class="list-group list-group-flush list my--3" id="ul_courses">
                    @foreach ($courses_same_grade as $course)
                    <li class="list-group-item px-0" id="li_{{ $course->course_id }}">
                      <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{{ asset(_ASSETS_.$course->course_type_icon_path) }}" />
                        </div>
                        <div class="col-8 ml--2">
                          <h4 class="mb-0">
                            {{ $course->course_name }}
                          </h4>
                          <span class="text-success">●</span>
                          <small>[{{ $course->grade_name }}] {{ $course->course_unit_price }}元/课时</small>
                        </div>
                        <div class="col-2">
                          <button type="button" class="btn btn-sm btn-warning" onclick="addCourse('{{ $course->course_id }}', '{{ $course->course_name }}', '{{ $course->course_type }}', '{{ $course->course_unit_price }}', '{{ asset(_ASSETS_.$course->course_type_icon_path) }}');">添加</button>
                        </div>
                      </div>
                    </li>
                    @endforeach
                    @foreach ($courses_all_grade as $course)
                    <li class="list-group-item px-0" id="li_{{ $course->course_id }}">
                      <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{{ asset(_ASSETS_.$course->course_type_icon_path) }}" />
                        </div>
                        <div class="col-8 ml--2">
                          <h4 class="mb-0">
                            {{ $course->course_name }}
                          </h4>
                          <span class="text-success">●</span>
                          <small>[全年级] {{ $course->course_unit_price }}元/课时</small>
                        </div>
                        <div class="col-2">
                          <button type="button" class="btn btn-sm btn-warning" onclick="addCourse('{{ $course->course_id }}', '{{ $course->course_name }}', '{{ $course->course_type }}', '{{ $course->course_unit_price }}', '{{ asset(_ASSETS_.$course->course_type_icon_path) }}');">添加</button>
                        </div>
                      </div>
                    </li>
                    @endforeach
                    @foreach ($courses_diff_grade as $course)
                    <li class="list-group-item px-0" id="li_{{ $course->course_id }}">
                      <div class="row align-items-center">
                        <div class="col-auto">
                            <img src="{{ asset(_ASSETS_.$course->course_type_icon_path) }}" />
                        </div>
                        <div class="col-8 ml--2">
                          <h4 class="mb-0">
                            {{ $course->course_name }}
                          </h4>
                          <span class="text-success">●</span>
                          <small>[{{ $course->grade_name }}] {{ $course->course_unit_price }}元/课时</small>
                        </div>
                        <div class="col-2">
                          <button type="button" class="btn btn-sm btn-warning" onclick="addCourse('{{ $course->course_id }}', '{{ $course->course_name }}', '{{ $course->course_type }}', '{{ $course->course_unit_price }}', '{{ asset(_ASSETS_.$course->course_type_icon_path) }}');">添加</button>
                        </div>
                      </div>
                    </li>
                    @endforeach
                  <ul>
                </div>
              </div>
            </div>
          </div>
          <div class="table-responsive">
            <table class="table align-items-center table-hover text-left table-bordered">
              <thead class="thead-light">
                <tr>
                  <th style='width:180px;'>课程</th>
                  <th style='width:90px;'>类型</th>
                  <th style='width:120px;'>单价</th>
                  <th style='width:90px;'>数量</th>
                  <th style='width:130px;'>总金额</th>
                  <th style='width:110px;'>折扣优惠%</th>
                  <th style='width:110px;'>金额优惠</th>
                  <th style='width:90px;'>赠送课时</th>
                  <th style='width:90px;'>总课时</th>
                  <th style='width:142px;'>应收金额</th>
                </tr>
              </thead>
              <tbody id="table-body">
                <tr id='initial-tr' style="height:100px;color:rgb(250,100,60);cursor: pointer;"><td colspan="10" class="text-center" data-toggle="modal" data-target="#inputBox"><strong>点击添加课程 +</strong></td></tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col-8">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">合同信息</h3>
          </div>
          <div class="card-body">
            <div class="row">
              <div class="col-6">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label form-control-label">签约类型<span style="color:red">*</span></label>
                  <div class="col-md-8">
                    @if($student->student_contract_num==0)
                      <input class="form-control" type="text" value="首次签约" readonly>
                      <input type="hidden" value="0" name="contract_type">
                    @else
                      <input class="form-control" type="text" value="续签签约" readonly>
                      <input type="hidden" value="1" name="contract_type">
                    @endif
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label form-control-label">综合服务费<span style="color:red">*</span></label>
                  <div class="col-md-8">
                    <input class="form-control" name="extra_fee" id="extra_fee" type="number" value="0" autocomplete='off' required min="0.00" step="0.01" oninput="update()">
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label form-control-label">支付方式<span style="color:red">*</span></label>
                  <div class="col-md-8">
                    <select class="form-control" name="payment_method" data-toggle="select" required>
                      <option value=''>请选择支付方式...</option>
                      @foreach ($payment_methods as $payment_method)
                        <option value="{{ $payment_method->payment_method_name }}">{{ $payment_method->payment_method_name }}</option>
                      @endforeach
                    </select>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label form-control-label">签约日期<span style="color:red">*</span></label>
                  <div class="col-md-8">
                    <input class="form-control datepicker" name="contract_date" type="text" value="{{ date('Y-m-d') }}" required>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label form-control-label">应收金额</label>
                  <div class="col-md-8">
                    <input class="form-control" type="number" value="0" id="total_price_sum_input" disabled>
                  </div>
                </div>
              </div>
              <div class="col-6">
                <div class="form-group row">
                  <label class="col-md-4 col-form-label form-control-label">实收金额<span style="color:red">*</span></label>
                  <div class="col-md-8">
                    <input class="form-control" name="contract_paid_price" id="contract_paid_price" type="number" value="0" autocomplete='off' required min="0.00" step="0.01">
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-12">
                <div class="form-group">
                  <textarea class="form-control" name="remark" rows="3" resize="none" placeholder="合同备注..." maxlength="140"></textarea>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-4">
        <div class="card">
          <div class="card-header">
            <h3 class="mb-0">结算</h3>
          </div>
          <div class="card-body">
            <!--<div class="row">
              <div class="col-6 text-right"><h3>课程种类</h3></div>
              <div class="col-6 text-right"><h3><span id="selected_course_num_span"></span> 种</h3></div>
            </div>-->
            <div class="row">
              <div class="col-6 text-right">购买课时</div>
              <div class="col-6 text-right"><span id="original_hour"></span> 课</div>
            </div>
            <div class="row">
              <div class="col-6 text-right">赠送课时</div>
              <div class="col-6 text-right"><span style="color:#00CC00;">+ <span id="free_hour_sum"></span> 课</span></div>
            </div>
            <div class="row">
              <div class="col-6 text-right"><h3>总课时</h3></div>
              <div class="col-6 text-right"><h3><span id="total_hour_sum"></span> 课</h3></div>
            </div>
            <div class="row">
              <div class="col-6 text-right">原总金额</div>
              <div class="col-6 text-right"><span id="original_price_sum"></span> 元</div>
            </div>
            <div class="row">
              <div class="col-6 text-right">优惠金额</div>
              <div class="col-6 text-right"><span style="color:red;">- <span id="discount_sum"></span> 元</span></div>
            </div>
            <div class="row">
              <div class="col-6 text-right">综合服务费</div>
              <div class="col-6 text-right"><span id="contract_extra_fee"></span> 元</span></div>
            </div>
            <div class="row">
              <div class="col-6 text-right"><h3>应收金额</h3></div>
              <div class="col-6 text-right"><h3><span id="total_price_sum"></span> 元</h3></div>
            </div>
          </div>
          <div class="card-footer">
            <div class="row">
              <div class="col-lg-5 col-md-5 col-sm-12">
                <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">返回</button></a>
              </div>
              <div class="col-lg-2 col-md-2 col-sm-12 my-2"></div>
              <div class="col-lg-5 col-md-5 col-sm-12">
                <input type="hidden" name="student_id" value="{{ $student->student_id }}">
                <input type="hidden" name="selected_course_num" id="selected_course_num" value="0">
                <input type="submit" class="btn btn-warning btn-block" id="submit_button" value="提交" disabled="true">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@section('sidebar_status')
<script type="text/javascript">

function addCourse(course_id, course_name, course_type, course_unit_price, course_type_icon_path){
    // 删除初始行tr
    $("#initial-tr").remove();
    // 获取已选课程数量
    var selected_course_num = parseInt($("#selected_course_num").val());
    // 增加已选课程数量
    selected_course_num = selected_course_num + 1;
    // 更新已选课程数量
    $("#selected_course_num").val(selected_course_num);
    // 更新已选课程id
    // 删除课程选项<li>
    $("#li_"+course_id).remove();
    // 增加课程表格<tr>
    var tr = "<tr id='tr_"+course_id+"'>";
    tr += "<td>"+course_name+"</td>";
    tr += "<td><img src='"+course_type_icon_path+"'/>&nbsp;"+course_type+"<input type='hidden' value='"+course_id+"' name='course_"+selected_course_num+"_0'></td>";
    tr += "<td><input class='form-control form-control-sm' type='number' readonly value='"+course_unit_price+"' name='course_"+selected_course_num+"_1' id='course_"+selected_course_num+"_1'></td>";
    tr += "<td><input class='form-control form-control-sm' type='number' value='1' autocomplete='off' required min='0' name='course_"+selected_course_num+"_2' id='course_"+selected_course_num+"_2' oninput='update()'></td>";
    tr += "<td><input class='form-control form-control-sm' type='number' readonly value='"+course_unit_price+"' name='course_"+selected_course_num+"_3' id='course_"+selected_course_num+"_3'></td>";
    tr += "<td><input class='form-control form-control-sm' type='number' readonly value='100' autocomplete='off' required max='100' min='0' step='1' name='course_"+selected_course_num+"_4' id='course_"+selected_course_num+"_4' oninput='update()'></td>";
    tr += "<td><input class='form-control form-control-sm' type='number' value='0' autocomplete='off' required min='0.00' step='0.01' name='course_"+selected_course_num+"_5' id='course_"+selected_course_num+"_5' oninput='update()'></td>";
    tr += "<td><input class='form-control form-control-sm' type='number' value='0' autocomplete='off' required min='0' name='course_"+selected_course_num+"_6' id='course_"+selected_course_num+"_6' oninput='update()'></td>";
    tr += "<td><input class='form-control form-control-sm' type='number' value='1' readonly name='course_"+selected_course_num+"_7' id='course_"+selected_course_num+"_7'></td>";
    tr += "<td><input class='form-control form-control-sm' type='number' value='"+course_unit_price+"' readonly name='course_"+selected_course_num+"_8' id='course_"+selected_course_num+"_8'></td>";
    tr += "</tr>";
    $("#table-body").append(tr);
    update();
}


function deleteCourse(course_id, course_name, course_type, course_unit_price, course_type_icon_path){
    // 获取已选课程数量
    var selected_course_num = parseInt($("#selected_course_num").val());
    // 增加已选课程数量
    selected_course_num = selected_course_num - 1;
    // 更新已选课程数量
    $("#selected_course_num").val(selected_course_num);
    // 删除课程表格<tr>
    $("#tr_"+course_id).remove();
    // 增加课程选项<li>
    var li = "<li class='list-group-item px-0' id='li_"+course_id+"'>";
    li += "<div class='row align-items-center'>";
    li += "<div class='col-auto'>";
    li += "<img src='"+course_type_icon_path+"''/>";
    li += "</div>";
    li += "<div class='col-8 ml--2'>";
    li += "<h4 class='mb-0'>"+course_name+"</h4>";
    li += "<span class='text-success'>●</span>";
    li += "<small>"+course_unit_price+"元/课时</small>";
    li += "</div>";
    li += "<div class='col-2'>";
    li += "<button type='button' class='btn btn-sm btn-warning' onclick=\"addCourse('"+course_id+"','"+course_name+"','"+course_type+"','"+course_unit_price+"','"+course_type_icon_path+"');\">添加</button>";
    li += "</div>";
    li += "</div>";
    li += "</li>";
    $("#ul_courses").append(li);
    update();
}

function update(){
    $('#submit_button').attr("disabled", false);
    if(course_num==0){
        $('#submit_button').attr("disabled", true);
    }
    // 获取已选课程数量
    var course_num = parseInt($("#selected_course_num").val());
    var original_hour_sum = 0;
    var free_hour_sum = 0;
    var total_hour_sum = 0;
    var original_price_sum = 0;
    var total_price_sum = 0;
    for(var i=1; i<=course_num; i++){
        // 获取输入
        var unit_price = Math.round(parseFloat($("#course_"+i+"_"+1).val()) * 100) / 100;
        var original_hour = Math.round(parseFloat($("#course_"+i+"_"+2).val()) * 10) / 10;
        // var price = parseInt($("#course_"+i+"_"+3).val());
        var discount_rate = Math.round(parseFloat($("#course_"+i+"_"+4).val())) / 100;
        var discount_amount = Math.round(parseFloat($("#course_"+i+"_"+5).val()) * 100) / 100;
        var free_hour = Math.round(parseFloat($("#course_"+i+"_"+6).val()) * 10) / 10;
        // 判断输入是否合法
        if(isNaN(original_hour)||original_hour<0){
            $('#submit_button').attr("disabled", true);
        }
        if(isNaN(discount_amount)||discount_amount<0){
            $('#submit_button').attr("disabled", true);
        }
        if(isNaN(free_hour)||free_hour<0){
            $('#submit_button').attr("disabled", true);
        }
        // 计算原总金额
        var original_price = Math.round((unit_price*original_hour) * 100) / 100;
        $("#course_"+i+"_"+3).val(original_price);
        // 计算合计课时
        var total_hour = (original_hour+free_hour);
        $("#course_"+i+"_"+7).val(total_hour);
        if(isNaN(total_hour)||total_hour<=0){
            $('#submit_button').attr("disabled", true);
        }
        // 计算应付金额
        var total_price = Math.round((original_price * discount_rate - discount_amount) * 100) / 100;
        $("#course_"+i+"_"+8).val(total_price);
        // 赋值给hidden input
        for(var j=1; j<=8; j++){
            $("#course_"+i+"_"+j).val($("#course_"+i+"_"+j).val());
        }
        // 统计合计
        original_hour_sum += original_hour;
        free_hour_sum += free_hour;
        total_hour_sum += total_hour;
        original_price_sum += original_price;
        total_price_sum += total_price;

        // 判断计算结果是否合法
        if(total_price<0){
            $('#submit_button').attr("disabled", true);
        }
    }
    var extra_fee = Math.round(parseFloat($("#extra_fee").val()) * 100) / 100;
    // 判断输入是否合法
    if(isNaN(extra_fee)||extra_fee<0){
        $('#submit_button').attr("disabled", true);
    }
    // 保留两位小数
    var original_price_sum = Math.round(original_price_sum * 100) / 100;
    var discount_sum = Math.round( (original_price_sum - total_price_sum)*100 ) / 100;
    var total_price_sum = Math.round( (total_price_sum + extra_fee)*100 ) / 100;
    // 显示统计合计
    if(isNaN(original_hour_sum)||original_hour_sum<0||original_hour_sum>10000){
        $("#original_hour").text(0);
        $('#submit_button').attr("disabled", true);
    }else{
        $("#original_hour").text(original_hour_sum);
    }
    if(isNaN(free_hour_sum)||free_hour_sum<0||free_hour_sum>10000){
        $("#free_hour_sum").text(0);
        $('#submit_button').attr("disabled", true);
    }else{
        $("#free_hour_sum").text(free_hour_sum);
    }
    if(isNaN(total_hour_sum)||total_hour_sum<=0||total_hour_sum>10000){
        $("#total_hour_sum").text(0);
        $('#submit_button').attr("disabled", true);
    }else{
        $("#total_hour_sum").text(total_hour_sum);
    }
    if(isNaN(original_price_sum)||original_price_sum<0||original_price_sum>1000000){
        $("#original_price_sum").text(0);
        $('#submit_button').attr("disabled", true);
    }else{
        $("#original_price_sum").text(original_price_sum);
    }
    if(isNaN(discount_sum)||discount_sum<0||discount_sum>1000000){
        $("#discount_sum").text(0);
        $('#submit_button').attr("disabled", true);
    }else{
        $("#discount_sum").text(discount_sum);
    }
    if(isNaN(total_price_sum)||total_price_sum<0||total_price_sum>1000000){
        $("#total_price_sum").text(0);
        $('#submit_button').attr("disabled", true);
    }else{
        $("#total_price_sum").text(total_price_sum);
        $("#total_price_sum_input").val(total_price_sum);
        $("#contract_paid_price").val(total_price_sum);
    }
    if(isNaN(extra_fee)||extra_fee<0||extra_fee>1000000){
        $("#contract_extra_fee").text(0);
        $('#submit_button').attr("disabled", true);
    }else{
        $("#contract_extra_fee").text(extra_fee);
    }
}
update();
</script>
<script>
  linkActive('link-market');
  navbarActive('navbar-market');
  linkActive('marketMyStudent');
</script>
@endsection
