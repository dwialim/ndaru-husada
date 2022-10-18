<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
</script>    

<script src="{{ asset('all.js') }}"></script>
<script src="{{ asset('assets/extra-libs/sweetalert2/sweetalert2.all.min.js') }}"></script>

<script src="{{ asset('assets/extra-libs/rowspan/rowspan.js') }}"></script>
<!-- Stack array for including inline js or scripts -->
@stack('script')
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="{{ asset('dist/js/theme.js') }}"></script>
<script src="{{ asset('js/chat.js') }}"></script>
<script src="{{ asset('vendor/animate/animate.js')}}"></script>