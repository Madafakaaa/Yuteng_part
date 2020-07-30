// Main card animation
$(document).ready(function(){
    $(".main_card").fadeIn("slow");
    //$("#main_card").fadeIn("3000");

    $(".freeze-table-1").freezeTable({
        'columnNum' : 1,
        'shadow': true,
        'freezeHead': true,
    });

    $(".freeze-table-2").freezeTable({
        'columnNum' : 2,
        'shadow': true,
        'freezeHead': true,
    });

    $(".freeze-table-3").freezeTable({
        'columnNum' : 3,
        'shadow': true,
        'freezeHead': true,
    });

    $(".freeze-table-4").freezeTable({
        'columnNum' : 4,
        'shadow': true,
        'freezeHead': true,
    });

    $(".freeze-table-5").freezeTable({
        'columnNum' : 5,
        'shadow': true,
        'freezeHead': true,
    });

    $(".freeze-table-6").freezeTable({
        'columnNum' : 6,
        'shadow': true,
        'freezeHead': true,
    });

    $(".freeze-table-7").freezeTable({
        'columnNum' : 7,
        'shadow': true,
        'freezeHead': true,
    });

    $(".freeze-table-8").freezeTable({
        'columnNum' : 8,
        'shadow': true,
        'freezeHead': true,
    });

    $('.counter-value').each(function(){
        $(this).prop('Counter',0).animate({
            Counter: $(this).text()
        },{
            duration: 1000,
            easing: 'swing',
            step: function (now){
                $(this).text(Math.ceil(now));
            }
        });
    });

    // swal({
    //     title:"Warning",
    //     text:"<a href='/operation/schedule'>个人风格发动机号</a>",
    //     type:"warning",
    //     buttonsStyling:!1,
    //     content: "<a href='/operation/schedule'>个人风格发动机号</a>",
    //     confirmButtonClass:"btn btn-warning"
    // });

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

function calendar_weekly(startDate, calendars, schedules, dailyUrl){

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
                    + "<span class='fa fa-user-tie'></span> 教师： " + schedule.state + "<br>"
                    + "<span class='fa fa-user-friends'></span> 学生： " + (schedule.attendees || []).join(', ') + "<br>";
            },
            popupDetailState: function(schedule) {
                return '教师：' + schedule.state;
            },
            popupDetailUser: function(schedule) {
                return '学生：' + (schedule.attendees || []).join(', ');
            },
            weekDayname: function(model) {
                return "<a href='"+dailyUrl+"filter_date="+model.renderDate+"' style='text-decoration: none;color:inherit;'><span class='tui-full-calendar-dayname-date'>" + model.date + "</span>&nbsp;&nbsp;<span class='tui-full-calendar-dayname-name'>" + model.dayName + "</span></a>";
            },
        },
        calendars: calendars,
    });

    calendar.createSchedules(schedules);

    calendar.setDate(startDate);
}

function calendar_daily(startDate, calendars, schedules){

    var COMMON_CUSTOM_THEME = {

    };

    var calendar = new tui.Calendar(document.getElementById('calendar'), {
        defaultView: 'day',
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
                    + "<span class='fa fa-user-tie'></span> 教师： " + schedule.state + "<br>"
                    + "<span class='fa fa-user-friends'></span> 学生： " + (schedule.attendees || []).join(', ') + "<br>";
            },
            popupDetailState: function(schedule) {
                return '教师：' + schedule.state;
            },
            popupDetailUser: function(schedule) {
                return '学生：' + (schedule.attendees || []).join(', ');
            },
        },
        calendars: calendars,
    });

    calendar.createSchedules(schedules);

    calendar.setDate(startDate);
}

function scheduleConflictAlert(title, table, backUrl){
    Swal.fire({
        title: title,
        html: table,
        showCloseButton: true,
        showCancelButton: true,
        focusConfirm: false,
        confirmButtonText: "<i class='fa fa-caret-right'></i> 继续排课",
        cancelButtonText: "<i class='fa fa-undo'></i> 返回",
        allowOutsideClick: false
    }).then((result) => {
        if (!result.value) {
            window.location.href = backUrl;
        }
    });
}

function form_submit(id){
    document.getElementById(id).submit();
}

function pieChart(id, label, data, backgroundColor, borderColor){
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
        type: "pie",
        data: {
            labels: label,
            datasets: [{
                data: data,
                backgroundColor: backgroundColor,
                borderColor: borderColor,
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            legend: {
                display: true,
            },
            animation: {
                animateScale: true,
                animateRotate: true
            }
        }
    });
}

function lineChart(id, label, labels, data){
    var ctx = document.getElementById(id).getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: label,
                data: data,
                backgroundColor: [
                    'rgba(17, 205, 239, 0.2)',
                ],
                borderColor: [
                    'rgba(17, 205, 239, 1)',
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                yAxes: [{
                    ticks: {
                        beginAtZero: true
                    }
                }]
            }
        }
    });
}
