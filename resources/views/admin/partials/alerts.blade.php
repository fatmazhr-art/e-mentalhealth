@if (session('success'))
    <div class="login-alert login-alert--success" role="alert">
        <span class="material-icons-round">check_circle</span>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if (session('error'))
    <div class="login-alert login-alert--error" role="alert">
        <span class="material-icons-round">error</span>
        <span>{{ session('error') }}</span>
    </div>
@endif

@if ($errors->has('login'))
    <div class="login-alert login-alert--error" role="alert">
        <span class="material-icons-round">error</span>
        <span>{{ $errors->first('login') }}</span>
    </div>
@endif
