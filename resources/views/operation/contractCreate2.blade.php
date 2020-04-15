@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><h1 class="mb-0" style="color:white;">上海育藤教育</h1></li>
@endsection

@section('content')
<div class="header bg-primary">
  <div class="container-fluid">
    <div class="header-body">
      <div class="row align-items-center py-4">
        <div class="col-6">
          <h6 class="h2 text-white d-inline-block mb-0">签约合同</h6>
          <nav aria-label="breadcrumb" class="d-none d-md-inline-block ml-md-4">
            <ol class="breadcrumb breadcrumb-links breadcrumb-dark">
              <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
              <li class="breadcrumb-item active">运营中心</li>
              <li class="breadcrumb-item active">签约合同</li>
            </ol>
          </nav>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="container-fluid mt-4">
  <div class="row justify-content-center">
    <div class="col-lg-6 col-md-9 col-sm-12 card-wrapper ct-example mb-4">
      <div class="row justify-content-center">
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-success">选择报名学生</span>
        </div>
        <div class="col-1 pt-2"><hr class="pr-4" style="height:3px;border:none;border-top:4px dashed #b0eed3;" /></div>
        <div class="col-2 text-center">
          <span class="badge badge-pill badge-info">选择报名课程</span>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-4">
      <div class="card">
        <div class="card-header">
          <h2 class="mb-0">学生信息</h2>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-12">
              <div class="form-group row mb-0">
                <label class="col-md-4 col-form-label form-control-label">姓名</label>
                <div class="col-md-8 px-2 mb-2">
                  <input class="form-control" type="text" value="{{ $student->student_name }}" readonly>
                  <input type="hidden" name="input1" value="{{ $student->student_id }}" readonly>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group row mb-0">
                <label class="col-md-4 col-form-label form-control-label">校区</label>
                <div class="col-md-8 px-2 mb-2">
                  <input class="form-control" type="text" value="{{ $student->department_name }}" readonly>
                </div>
              </div>
            </div>
            <div class="col-12">
              <div class="form-group row mb-0">
                <label class="col-md-4 col-form-label form-control-label">年级</label>
                <div class="col-md-8 px-2 mb-2">
                  <input class="form-control" type="text" value="{{ $student->grade_name }}" readonly>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-8">
      <div class="card">
        <div class="card-header">
          <h2 class="mb-0">已购课时</h2>
        </div>
        <div class="card-body" style="height:201px; overflow:auto;">
          <ul class="list-group list-group-flush list my--3">
            @foreach ($hours as $hour)
            <li class="list-group-item px-0">
              <div class="row align-items-center">
                <div class="col">
                  <small>课程:</small>
                  <h5 class="mb-0">{{ $hour->course_name }}</h5>
                </div>
                <div class="col">
                  <small>合同</small>
                  <h5 class="mb-0">
                    <a href='/contract/{{$hour->hour_contract}}' target="_blank">{{ $hour->hour_contract }}</a>
                  </h5>
                </div>
                <div class="col">
                  <small>剩余</small>
                  <h5 class="mb-0">{{ $hour->hour_remain+$hour->hour_remain_free }} 课时</h5>
                </div>
                <div class="col">
                  <small>日期</small>
                  <h5 class="mb-0">{{ $hour->contract_date }}</h5>
                </div>
                <div class="col">
                  <a href='/contract/{{$hour->hour_contract}}' target="_blank"><button type="button" class="btn btn-primary btn-sm">查看合同</button></a>
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
              <h2 class="mb-0">购买课程</h2>
            </div>
            <div class="col-4 text-right">
              <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#inputBox">
                添加课程
              </button>
            </div>
          </div>
        </div>
        <div class="modal fade" id="inputBox" tabindex="-1" role="dialog" aria-labelledby="inputBoxLabel" aria-hidden="true">
          <form action="/operation/contract/create2" method="post" id="form1" name="form1">
            @csrf
            <input type="hidden" name="input1" value="{{ $student->student_id }}">
            <input type="hidden" name="selected_course_num" value="{{ $selected_course_num }}">
            @for($i=0; $i<$selected_course_num; $i++)
              <input type="hidden" name="course{{ $i }}" value="{{ $selected_course_ids[$i] }}">
            @endfor
            <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <h3 class="modal-title" id="inputBoxLabel">添加课程</h3>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                </div>
                <div class="modal-body" style="border-bottom:1px solid #DDD;border-top:1px solid #DDD;">
                  <div class="custom-control custom-checkbox">
                    <div class="row pl-3" style="max-height:160px; overflow:auto;">
                      @foreach ($courses as $course)
                        <div class="col-12 mb-2">
                          <input type="checkbox" class="custom-control-input" id="{{ $course->course_id }}" name="input2[]" value="{{ $course->course_id }}">
                          <label class="custom-control-label" for="{{ $course->course_id }}"><img src="{{ asset(_ASSETS_.$course->course_type_icon_path) }}" /> {{ $course->course_name }}，{{ $course->course_unit_price }}元/课时</label>
                        </div>
                      @endforeach
                    </div>
                  </div>
                </div>
                <div class="modal-footer p-3">
                  <button type="button" class="btn btn-outline-danger" data-dismiss="modal">关闭</button>
                  <button class="btn btn-warning">添加</button>
                </div>
              </div>
            </div>
          </form>
        </div>
        <div class="table-responsive">
          <table class="table align-items-center table-hover text-left table-bordered">
            <thead class="thead-light">
              <tr>
                <th style='width:55px;'>序号</th>
                <th style='width:180px;'>报读课程</th>
                <th style='width:103px;'>类型</th>
                <th style='width:120px;'>单价</th>
                <th style='width:90px;'>数量</th>
                <th style='width:130px;'>总金额</th>
                <th style='width:110px;'>折扣优惠%</th>
                <th style='width:110px;'>金额优惠</th>
                <th style='width:90px;'>赠送课时</th>
                <th style='width:90px;'>总课时</th>
                <th style='width:142px;'>应收金额</th>
                <th style='width:80px;' class="text-center">操作</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              @if($selected_course_num==0)
              <tr class="text-center"><td colspan="12">请选择要报读的课程</td></tr>
              @endif
              @foreach ($selected_courses as $selected_course)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td title="{{ $selected_course->course_name }}">{{ $selected_course->course_name }}</td>
                <td>
                  <img src="{{ asset(_ASSETS_.$selected_course->course_type_icon_path) }}" />
                  &nbsp;
                  {{ $selected_course->course_type }}
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" readonly value="{{ $selected_course->course_unit_price }}" name="input_{{ $loop->iteration }}_1" id="input_{{ $loop->iteration }}_1">
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" value="1" autocomplete='off' required min="0" name="input_{{ $loop->iteration }}_2" id="input_{{ $loop->iteration }}_2" oninput="update({{ $selected_course_num }})">
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" readonly value="{{ $selected_course->course_unit_price }}" name="input_{{ $loop->iteration }}_3" id="input_{{ $loop->iteration }}_3">
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" value="100" autocomplete='off' required max="100" min="0" step="1" name="input_{{ $loop->iteration }}_4" id="input_{{ $loop->iteration }}_4" oninput="update({{ $selected_course_num }})">
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" value="0" autocomplete='off' required min="0.00" step="0.01" name="input_{{ $loop->iteration }}_5" id="input_{{ $loop->iteration }}_5" oninput="update({{ $selected_course_num }})">
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" value="0" autocomplete='off' required min="0" name="input_{{ $loop->iteration }}_6" id="input_{{ $loop->iteration }}_6" oninput="update({{ $selected_course_num }})">
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" value="1" readonly name="input_{{ $loop->iteration }}_7" id="input_{{ $loop->iteration }}_7">
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" value="{{ $selected_course->course_unit_price }}" readonly name="input_{{ $loop->iteration }}_8" id="input_{{ $loop->iteration }}_8">
                </td>
                <td class="text-center">
                  <form action="/operation/contract/create2" method="post" id="course_{{ $loop->iteration }}" name="course_{{ $loop->iteration }}">
                    @csrf
                    <input type="hidden" name="input1" value="{{ $student->student_id }}">
                    <input type="hidden" name="selected_course_num" value="{{ $selected_course_num }}">
                    @for($i=0; $i<$selected_course_num; $i++)
                      <input type="hidden" name="course{{ $i }}" value="{{ $selected_course_ids[$i] }}">
                    @endfor
                    <input type="hidden" name="input3" value="{{ $selected_course->course_id }}">
                    <input type="submit" class="btn btn-outline-danger btn-icon-only" value="删除">
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <form action="/operation/contract/store" method="post" id="form2" name="form2">
  @csrf
  <input type="hidden" name="student_id" value="{{ $student->student_id }}">
  <input type="hidden" name="selected_course_num" id="selected_course_num" value="{{ $selected_course_num }}">
  @foreach ($selected_courses as $selected_course)
    <input type="hidden" name="course_{{ $loop->iteration }}_0" id="course_{{ $loop->iteration }}_0" value="{{ $selected_course->course_id }}">
    <input type="hidden" name="course_{{ $loop->iteration }}_1" id="course_{{ $loop->iteration }}_1">
    <input type="hidden" name="course_{{ $loop->iteration }}_2" id="course_{{ $loop->iteration }}_2">
    <input type="hidden" name="course_{{ $loop->iteration }}_3" id="course_{{ $loop->iteration }}_3">
    <input type="hidden" name="course_{{ $loop->iteration }}_4" id="course_{{ $loop->iteration }}_4">
    <input type="hidden" name="course_{{ $loop->iteration }}_5" id="course_{{ $loop->iteration }}_5">
    <input type="hidden" name="course_{{ $loop->iteration }}_6" id="course_{{ $loop->iteration }}_6">
    <input type="hidden" name="course_{{ $loop->iteration }}_7" id="course_{{ $loop->iteration }}_7">
    <input type="hidden" name="course_{{ $loop->iteration }}_8" id="course_{{ $loop->iteration }}_8">
  @endforeach
  <div class="row">
    <div class="col-8">
      <div class="card">
        <div class="card-header">
          <h2 class="mb-0">合同信息</h2>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="form-group row">
                <label class="col-md-4 col-form-label form-control-label">签约类型<span style="color:red">*</span></label>
                <div class="col-md-8">
                  @if($student->student_customer_status==0)
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
                  <input class="form-control" type="number" value="0" autocomplete='off' required min="0.00" step="0.01" name="extra_fee" id="extra_fee" oninput="update({{ $selected_course_num }})">
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
                <label class="col-md-4 col-form-label form-control-label">签约时间<span style="color:red">*</span></label>
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
                  <input class="form-control" type="number" value="0" autocomplete='off' required min="0.00" step="0.01" name="contract_paid_price" id="contract_paid_price">
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
          <h2 class="mb-0">结算</h2>
        </div>
        <div class="card-body">
          <div class="row">
            <div class="col-6 text-right"><h3>课程种类</h3></div>
            <div class="col-6 text-right"><h3>{{ $selected_course_num }} 种</h3></div>
          </div>
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
              <a href="javascript:history.go(-1)" ><button type="button" class="btn btn-outline-primary btn-block">上一步</button></a>
            </div>
            <div class="col-lg-2 col-md-2 col-sm-12 my-2"></div>
            <div class="col-lg-5 col-md-5 col-sm-12">
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
function update(course_num){
    $('#submit_button').attr("disabled", false);
    if(course_num==0){
        $('#submit_button').attr("disabled", true);
    }
    var original_hour_sum = 0;
    var free_hour_sum = 0;
    var total_hour_sum = 0;
    var original_price_sum = 0;
    var total_price_sum = 0;
    for(var i=1; i<=course_num; i++){
        // 获取输入
        var unit_price = Math.round(parseFloat($("#input_"+i+"_"+1).val()) * 100) / 100;
        var original_hour = Math.round(parseFloat($("#input_"+i+"_"+2).val()) * 10) / 10;
        // var price = parseInt($("#input_"+i+"_"+3).val());
        var discount_rate = Math.round(parseFloat($("#input_"+i+"_"+4).val())) / 100;
        var discount_amount = Math.round(parseFloat($("#input_"+i+"_"+5).val()) * 100) / 100;
        var free_hour = Math.round(parseFloat($("#input_"+i+"_"+6).val()) * 10) / 10;
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
        $("#input_"+i+"_"+3).val(original_price);
        // 计算合计课时
        var total_hour = (original_hour+free_hour);
        $("#input_"+i+"_"+7).val(total_hour);
        if(isNaN(total_hour)||total_hour<=0){
            $('#submit_button').attr("disabled", true);
        }
        // 计算应付金额
        var total_price = Math.round((original_price * discount_rate - discount_amount) * 100) / 100;
        $("#input_"+i+"_"+8).val(total_price);
        // 赋值给hidden input
        for(var j=1; j<=8; j++){
            $("#course_"+i+"_"+j).val($("#input_"+i+"_"+j).val());
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
update({{ $selected_course_num }});
</script>
<script>
  linkActive('link-operation');
  navbarActive('navbar-operation');
  linkActive('operationContractCreate');
</script>
@endsection
