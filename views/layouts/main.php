<!DOCTYPE html>
<html lang="en">
@include(layouts/head.php)
<body>
@include(layouts/navbar.php)
<div class="container">
    @yield(content)
</div>
@include(layouts/scripts.php)
</body>
</html>
