// Main card animation
$(document).ready(function(){
    $(".main_card").fadeIn("slow");
    //$("#main_card").fadeIn("3000");

    $(".freeze-table-1").freezeTable({
        'columnNum' : 1,
        'shadow': true,
        'freezeHead': false,
    });

    $(".freeze-table-2").freezeTable({
        'columnNum' : 2,
        'shadow': true,
        'freezeHead': false,
    });

    $(".freeze-table-3").freezeTable({
        'columnNum' : 3,
        'shadow': true,
        'freezeHead': false,
    });

    $(".freeze-table-4").freezeTable({
        'columnNum' : 4,
        'shadow': true,
        'freezeHead': false,
    });

    $(".freeze-table-5").freezeTable({
        'columnNum' : 5,
        'shadow': true,
        'freezeHead': false,
    });

    $(".freeze-table-6").freezeTable({
        'columnNum' : 6,
        'shadow': true,
        'freezeHead': false,
    });

});


//Notify function
function notify(title, message, type){
    $.notify({
        // options
        title: '<strong>'+title+'</strong><br>',
        message: message,
    },{
        // settings
        element: 'body',
        type: type,
        allow_dismiss: true,
        placement: {
            from: "top",
            align: "center"
        },
        delay: 5000,
        mouse_over:"pause",
        animate: {
            enter: 'animated fadeInDown',
            exit: 'animated fadeOutUp'
        }
    });
}

//Active Status
function linkActive(id){
    document.getElementById(id).setAttribute("class", "nav-link active");
    document.getElementById(id).setAttribute("aria-expanded", "true");
}
function navbarActive(id){
    document.getElementById(id).setAttribute("class", "collapse show");
}


function barChart(id,label,labels,data) {
    var barChart = new Chart($("#" + id), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: ['rgba(153, 102, 255, 0.6)']
            }]
        },
        options: {
            hover: {
                animationDuration: 0  // 防止鼠标移上去，数字闪烁
            },
            animation: {           // 这部分是数值显示的功能实现
                onComplete: function () {
                    var chartInstance = this.chart,

                        ctx = chartInstance.ctx;
                    // 以下属于canvas的属性（font、fillStyle、textAlign...）
                    ctx.font = Chart.helpers.fontString(Chart.defaults.global.defaultFontSize, Chart.defaults.global.defaultFontStyle, Chart.defaults.global.defaultFontFamily);
                    ctx.fillStyle = "black";
                    ctx.textAlign = 'center';
                    ctx.textBaseline = 'bottom';

                    this.data.datasets.forEach(function (dataset, i) {
                        var meta = chartInstance.controller.getDatasetMeta(i);
                        meta.data.forEach(function (bar, index) {
                            var data = dataset.data[index];
                            if(data!=0){
                                ctx.fillText(data, bar._model.x, bar._model.y - 5);
                            }
                        });
                    });
                }
            }
        }
    });
}

function deleteConfirm(button_id, url, msg) {
    // Disable button first
    $('#'+button_id).attr("disabled", true);
    // Confirmation
    var result = confirm(msg);
    if (result == true) {
        window.location.href=url;
    } else {
        $('#'+button_id).attr("disabled", false);
    }
}

function batchDeleteConfirm(url, msg) {
    // Disable button first
    $('.delete-button').attr("disabled", true);
    // Confirmation
    var result = confirm(msg);
    if (result == true) {
        obj = document.getElementsByName("id");
        check_val = [];
        for(k in obj){
            if(obj[k].checked)
                check_val.push(obj[k].value);
        }
        if(check_val.length<=0){
            alert("请至少勾选一行数据！");
            $('.delete-button').attr("disabled", false);
        }else{
            url += "?";
            for(var i=0;i<check_val.length;i++){
                url+="id[]="+check_val[i]+"&";
            }
            window.location.href=url;
        }
    } else {
        $('.delete-button').attr("disabled", false);
    }
}

function submitButtonDisable(button_id) {
    // Disable button first
    $('#'+button_id).attr("disabled", true);
}

function calendar_weekly(startDate, calendars, schedules){

    var COMMON_CUSTOM_THEME = {

    };

    var calendar = new tui.Calendar(document.getElementById('calendar'), {
        defaultView: 'week',
        taskView: false,
        scheduleView: ['time'],
        disableClick: true,
        disableDblClick: true,
        useDetailPopup: true,
        usageStatistics: false,
        theme: COMMON_CUSTOM_THEME, // set theme
        week: {
            daynames: ['周日', '周一', '周二', '周三', '周四', '周五', '周六'],
            startDayOfWeek: 1,
            narrowWeekend: false,
            hourStart: 7,
            hourEnd: 23,
        },
        template: {
            timegridDisplayPrimaryTime: function(time) {
                return time.hour + ':00';
            },
            time: function(schedule) {
                return schedule.title + "<br>"
                       + moment(schedule.start.getTime()).format('HH:mm') + "~" + moment(schedule.end.getTime()).format('HH:mm') + "<br>"
                       + "<span class='fa fa-map-marker-alt'></span> " + schedule.location + "<br>"
                       + "<span class='fa fa-user-tie'></span> " + schedule.raw.teacher + "<br>"
                       + "<span class='fa fa-user-friends'></span> " + (schedule.attendees || []).join(', ') + "<br>";
            },
            popupDetailState: function(schedule) {
                return '<span class="tui-full-calendar-icon tui-full-calendar-ic-user-b"></span>';
            },
        },
        calendars: calendars,
    });

    calendar.createSchedules(schedules);

    calendar.setDate(startDate);
}
