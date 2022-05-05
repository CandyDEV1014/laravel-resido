@if (session()->has('status'))
    <div class="alert alert-success">
        <span>{{ session('status') }}</span>
    </div>
@endif

@if (session()->has('success_msg'))
    <div class="alert alert-success">
        <span>{{ session('success_msg') }}</span>
    </div>
@endif

@if (session()->has('error_msg'))
    <div class="alert alert-danger">
        <span>{{ session('error_msg') }}</span>
    </div>
@endif
