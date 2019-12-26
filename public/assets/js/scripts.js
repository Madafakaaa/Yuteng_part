// Main card animation
$(document).ready(function(){
    $(".main_card").show("800");
    //$("#main_card").fadeIn("3000");
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

//Sidebar Status
function linkActive(id){
    document.getElementById(id).setAttribute("class", "nav-link active");
    document.getElementById(id).setAttribute("aria-expanded", "true");
}
function navbarActive(id){
    document.getElementById(id).setAttribute("class", "collapse show");
}

// Form auto submit
function form_submit(id){
    document.getElementById(id).submit();
}