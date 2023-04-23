@extends(layouts/main.php)
@block(title)Register@endblock
@block(content)

<form action='' method='post'>
    <input type="hidden" name="csrf_token" value="{{ session()->csrfToken() }}">
    <div class="row">
        <div class="col">
            <div class="form-group">
                <label for="first_name">First name</label>
                <input id="first_name" type="text" class="form-control " name="first_name" value="">
                <div class="invalid-feedback"></div>
            </div>
        </div>
        <div class="col">
            <div class="form-group">
                <label for="last_name">Last name</label>
                <input id="last_name" type="text" class="form-control " name="last_name" value="">
                <div class="invalid-feedback"></div>
            </div>
        </div>
    </div>
    <div class="form-group">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control " name="email" value="">
        <div class="invalid-feedback"></div>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control " name="password" value="">
        <div class="invalid-feedback"></div>
    </div>
    <div class="form-group">
        <label for="passwordConfirm">Confirm password</label>
        <input id="passwordConfirm" type="password" class="form-control " name="passwordConfirm" value="">
        <div class="invalid-feedback"></div>
    </div>
    <button class="btn btn-success">Submit</button>
</form>
@endblock
