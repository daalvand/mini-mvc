@extends(layouts/main.php)
@block(title)Register@endblock
@block(content)

<form action='/register' method='post'>
    <input type="hidden" name="csrf_token" value="{{ session()->csrfToken() }}">
    <ul>
        <?php foreach ($errors ?? [] as $field => $error) : ?>
               <li class="invalid-feedback d-block">{{$field}}: {{ $error[0] }}</li>
        <?php endforeach; ?>
    </ul>

    <div class="row">
        <div class="col">
            <div class="form-group mt-1">
                <label for="first_name">First name</label>
                <input id="first_name" type="text" class="form-control " name="first_name" value="{{$old_inputs['first_name'] ?? ''}}">
            </div>
        </div>
        <div class="col">
            <div class="form-group mt-1">
                <label for="last_name">Last name</label>
                <input id="last_name" type="text" class="form-control " name="last_name" value="{{$old_inputs['last_name'] ?? ''}}">
            </div>
        </div>
    </div>
    <div class="form-group mt-1">
        <label for="email">Email</label>
        <input id="email" type="email" class="form-control " name="email" value="{{$old_inputs['email'] ?? ''}}">
    </div>
    <div class="form-group mt-1">
        <label for="password">Password</label>
        <input id="password" type="password" class="form-control" name="password" value="{{$old_inputs['password'] ?? ''}}">
    </div>
    <div class="form-group mt-1">
        <label for="passwordConfirm">Confirm password</label>
        <input id="passwordConfirm" type="password" class="form-control " name="password_confirmation" value="{{$old_inputs['password_confirmation'] ?? ''}}">
    </div>
    <button class="btn btn-success">Submit</button>
</form>
@endblock
