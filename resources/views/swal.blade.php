{{-- <script src="{{asset('assets/vendor_components/sweetalert/sweetalert.min.js')}}"></script> --}}
@if (session('success'))
<script>
    Swal.fire({
        icon: 'success',
        title: 'Berhasil !!',
        text: '{{session('success')}}',
    });
</script>
@endif
@if (session('error'))
<script>
   Swal.fire({
    icon: 'error',
        title: 'Gagal !!',
        text: '{{session('error')}}',
    })
</script>
@endif
@if ($errors->any())
@php
    $message='';
    foreach ($errors->all() as $error){
        $message .= $error;
    }
@endphp
<script>
    Swal.fire({
        icon: 'error',
        title: 'Gagal !!',
        text: '{{$message}}',
    })
</script>
@endif
