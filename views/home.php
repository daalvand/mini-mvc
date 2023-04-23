@extends(layouts/main.php)
@block(title)Home@endblock
@block(content)
<div class="container">
    <h1>Welcome to <strong>{{ $name }}</strong></h1>
    <div class="row">
        <?php
        /** @var App\Models\Item[] $items */
        /** @var array $pagination */
        ?>
        <?php foreach ($items as $item): ?>
            @include(layouts/cart_item.php)
        <?php endforeach; ?>
    </div>

    @include(layouts/pagination.php)
</div>
@include(layouts/cart_items_scripts.php)

@endblock
