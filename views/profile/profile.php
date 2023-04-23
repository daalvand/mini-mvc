@extends(layouts/main.php)
@block(title)Login@endblock
@block(content)
<h1>Profile page</h1>
<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-sm-3">
                <p class="mb-0">Full Name</p>
            </div>
            <div class="col-sm-9">
                <p class="text-muted mb-0">{{ $user->fullName() }}</p>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-sm-3">
                <p class="mb-0">Email</p>
            </div>
            <div class="col-sm-9">
                <p class="text-muted mb-0">{{ $user->email }}</p>
            </div>
        </div>
    </div>
</div>
@endblock