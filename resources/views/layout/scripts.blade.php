<!-- Argon Scripts -->
<!-- Core -->
<script src="{{ asset(_ASSETS_.'/vendor/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/js-cookie/js.cookie.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/jquery.scrollbar/jquery.scrollbar.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/jquery-scroll-lock/dist/jquery-scrollLock.min.js') }}"></script>
<!-- Optional JS -->
<script src="{{ asset(_ASSETS_.'/vendor/chart.js/dist/Chart.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/chart.js/dist/Chart.extension.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/bootstrap-notify/bootstrap-notify.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/datatables.net-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/datatables.net-buttons/js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/datatables.net-buttons-bs4/js/buttons.bootstrap4.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/datatables.net-buttons/js/buttons.html5.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/datatables.net-buttons/js/buttons.flash.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/datatables.net-buttons/js/buttons.print.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/datatables.net-select/js/dataTables.select.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/select2/dist/js/select2.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/sweetalert2/dist/sweetalert2.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/freeze-table/freeze-table.min.js') }}"></script>
<script src="{{ asset(_ASSETS_.'/vendor/jquery-table2excel-master/dist/jquery.table2excel.min.js') }}"></script>

<script src="https://uicdn.toast.com/tui.code-snippet/latest/tui-code-snippet.js"></script>
<script src="https://uicdn.toast.com/tui.dom/v3.0.0/tui-dom.js"></script>
<script src="https://uicdn.toast.com/tui.time-picker/latest/tui-time-picker.min.js"></script>
<script src="https://uicdn.toast.com/tui.date-picker/latest/tui-date-picker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js"></script>
<script src="{{ asset(_ASSETS_.'/vendor/toastuicalendar/tui-calendar.js') }}"></script>

<!-- Argon JS -->
<script src="{{ asset(_ASSETS_.'/js/argon.js?v=1.1.0') }}"></script>
<!-- My scripts -->
<script src="{{ asset(_ASSETS_.'/js/scripts.js') }}"></script>
<script>
@if (session('notify'))
    notify('{{session("title")}}','{{session("message")}}','{{session("type")}}');
@endif
</script>

