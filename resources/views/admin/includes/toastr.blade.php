<script type="text/javascript">
    @foreach ($errors->all() as $error)
    toastr.error('{{$error}}');
    @endforeach
</script>
