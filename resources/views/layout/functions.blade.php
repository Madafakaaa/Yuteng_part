<script>
@if (session('notify'))
    notify('{{session("title")}}','{{session("message")}}','{{session("type")}}');
@endif
</script>
