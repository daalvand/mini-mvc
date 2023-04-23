@extends(layouts/main.php)
@block(title)Login@endblock
@block(content)
<h1>Login</h1>
<div class="invalid-feedback {{$error ? 'd-block' : 'd-none'}}">{{$error ?? '' }}</div>
<form action='/login' method='post'>
    <input type="hidden" name="csrf_token" value="{{ session()->csrfToken() }}">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" class="form-control" id="email" name="email" value="{{$old_inputs['email'] ?? ''}}">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" class="form-control" id="password" name="password" value="{{$old_inputs['password'] ?? ''}}">
        <div class="invalid-feedback"></div>
    </div>
    <button class="btn btn-success">Submit</button>
</form>
@endblock
