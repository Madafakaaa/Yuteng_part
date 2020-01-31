@extends('main')

@include('layout.php_functions')

@section('nav')
    <li class="breadcrumb-item"><a href="/home"><i class="fas fa-home"></i></a></li>
    <li class="breadcrumb-item active">财务中心</li>
    <li class="breadcrumb-item"><a href="/contract">学生购课</a></li>
    <li class="breadcrumb-item active">添加购课</li>
@endsection

@section('content')
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
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <div class="col-4">
              <div class="form-group row mb-0">
                <label class="col-md-4 col-form-label form-control-label">学生</label>
                <div class="col-md-8 px-2 mb-2">
                  <input class="form-control" type="text" value="{{ $student->student_name }}" readonly>
                  <input type="hidden" name="input1" value="{{ $student->student_id }}" readonly>
                </div>
              </div>
            </div>
            <div class="col-4">
              <div class="form-group row mb-0">
                <label class="col-md-4 col-form-label form-control-label">校区</label>
                <div class="col-md-8 px-2 mb-2">
                  <input class="form-control" type="text" value="{{ $student->department_name }}" readonly>
                </div>
              </div>
            </div>
            <div class="col-4">
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
    <div class="col-6">
      <div class="card">
        <div class="card-body">
          <form action="/contract/create/step2" method="post" id="form1" name="form1">
            @csrf
            <input type="hidden" name="input1" value="{{ $student->student_id }}">
            <input type="hidden" name="selected_course_num" value="{{ $selected_course_num }}">
            @for($i=0; $i<$selected_course_num; $i++)
              <input type="hidden" name="course{{ $i }}" value="{{ $selected_course_ids[$i] }}">
            @endfor
            <div class="row">
              <div class="col-12">
                <div class="form-group row mb-0">
                  <label class="col-3 col-form-label form-control-label">添加课程<span style="color:red">*</span></label>
                  <div class="col-9">
                    <select class="form-control" name="input2" id="input2" data-toggle="select" onChange="form_submit('form1')" required>
                      <option value=''>请选择课程...</option>
                      @foreach ($courses as $course)
                        <option value="{{ $course->course_id }}">
                          {{ $course->course_type }}: {{ $course->course_name }}，{{ $course->grade_name }}，{{ $course->course_unit_price }}元/课时
                        </option>
                      @endforeach
                    </select>
                  </div>
                  <!-- <div class="col-3"><input type="submit" class="btn btn-warning btn-block" value="添加"></div> -->
                </div>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-12">
      <div class="card main_card mb-4" style="display:none">
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
                <th style='width:110px;'>折扣优惠</th>
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
                  <input class="form-control form-control-sm" type="number" value="1" autocomplete='off' required min="1" name="input_{{ $loop->iteration }}_2" id="input_{{ $loop->iteration }}_2" oninput="update({{ $selected_course_num }})">
                </td>
                <td>
                  <input class="form-control form-control-sm" type="number" readonly value="{{ $selected_course->course_unit_price }}" name="input_{{ $loop->iteration }}_3" id="input_{{ $loop->iteration }}_3">
                </td>
                <td>
                  <select class="form-control form-control-sm" data-toggle="select" required name="input_{{ $loop->iteration }}_4" id="input_{{ $loop->iteration }}_4" onchange="update({{ $selected_course_num }})">
                    <option value='1' selected>无折扣</option>
                    <option value='0.95'>95折</option>
                    <option value='0.9'>9折</option>
                    <option value='0.85'>85折</option>
                    <option value='0.8'>8折</option>
                    <option value='0.75'>75折</option>
                    <option value='0.7'>7折</option>
                    <option value='0.65'>65折</option>
                    <option value='0.6'>6折</option>
                    <option value='0.55'>55折</option>
                    <option value='0.5'>5折</option>
                  </select>
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
                  <form action="/contract/create/step2" method="post" id="course_{{ $loop->iteration }}" name="course_{{ $loop->iteration }}">
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
  <form action="/contract" method="post" id="form2" name="form2">
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
          </div>
          <div class="row">
            <div class="col-12">
              <div class="form-group">
                <textarea class="form-control" name="remark" rows="6" resize="none" placeholder="合同备注..." maxlength="140"></textarea>
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
        var unit_price = Math.floor( parseFloat($("#input_"+i+"_"+1).val()) * 100) / 100 ;
        var original_hour = parseInt($("#input_"+i+"_"+2).val());
        // var price = parseInt($("#input_"+i+"_"+3).val());
        var discount_rate = Math.floor( parseFloat($("#input_"+i+"_"+4).val()) * 100) / 100 ;
        var discount_amount = Math.floor( parseFloat($("#input_"+i+"_"+5).val()) * 100) / 100 ;
        var free_hour = parseInt($("#input_"+i+"_"+6).val());
        // 判断输入是否合法
        if(isNaN(original_hour)||original_hour<=0){
            $('#submit_button').attr("disabled", true);
        }
        if(isNaN(discount_amount)||discount_amount<0){
            $('#submit_button').attr("disabled", true);
        }
        if(isNaN(free_hour)||free_hour<0){
            $('#submit_button').attr("disabled", true);
        }
        // 计算原金额
        var original_price = floatObj.multiply(unit_price, original_hour);
        original_price = Math.floor(original_price * 10) / 10;
        $("#input_"+i+"_"+3).val(original_price);
        // 计算合计课时
        var total_hour = floatObj.add(original_hour, free_hour);
        $("#input_"+i+"_"+7).val(total_hour);
        // 计算应付金额
        var total_price = Math.floor( floatObj.multiply(original_price, discount_rate) * 10 ) / 10 ;
        total_price = Math.floor( floatObj.subtract(total_price, discount_amount) * 10 ) / 10 ;
        total_price = Math.floor(total_price * 10) / 10;
        $("#input_"+i+"_"+8).val(total_price);
        // 赋值给hidden input
        for(var j=1; j<=8; j++){
            $("#course_"+i+"_"+j).val($("#input_"+i+"_"+j).val());
        }
        // 统计合计
        original_hour_sum = floatObj.add(original_hour_sum, original_hour);
        free_hour_sum = floatObj.add(free_hour_sum, free_hour);
        total_hour_sum = floatObj.add(total_hour_sum, total_hour);
        original_price_sum = floatObj.add(original_price_sum, original_price);
        total_price_sum = floatObj.add(total_price_sum, total_price);
        console.log(total_price_sum);

        // 判断计算结果是否合法
        if(total_price<=0){
            $('#submit_button').attr("disabled", true);
        }
    }
    // 保留两位小数
    var original_price_sum = Math.floor(original_price_sum * 10) / 10;
    var total_price_sum = Math.ceil(total_price_sum * 10) / 10;
    var discount_sum = Math.floor( (original_price_sum * 10 - total_price_sum * 10) ) / 10;
    // 显示统计合计
    if(isNaN(original_hour_sum)||original_hour_sum<=0||original_hour_sum>10000){
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
    if(isNaN(total_hour_sum)||total_hour_sum<0||total_hour_sum>10000){
        $("#total_hour_sum").text(0);
        $('#submit_button').attr("disabled", true);
    }else{
        $("#total_hour_sum").text(total_hour_sum);
    }
    if(isNaN(original_price_sum)||original_price_sum<=0||original_price_sum>1000000){
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
    }
}
update({{ $selected_course_num }});
</script>
<script>
  linkActive('link-2');
  navbarActive('navbar-2');
  linkActive('contract');
</script>
@endsection