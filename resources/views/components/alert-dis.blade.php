<div class="alert alert-{{ $type }} alert-dismissible fade show" role="alert">
    @php
        echo $message; 
    @endphp 
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <i class="ik ik-x"></i>
    </button>
</div>