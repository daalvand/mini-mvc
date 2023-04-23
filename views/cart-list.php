@extends(layouts/main.php)
@block(title)Cart List@endblock
@block(content)
<div class="container">
    <h1>Cart List</h1>
    <div class="row">
        <?php
        /** @var App\Models\Item[] $items */
        /** @var array $pagination */
        ?>
        <?php foreach ($items as $item): ?>
            @include(layouts/cart_item.php)
        <?php endforeach; ?>
    </div>
</div>
@include(layouts/cart_items_scripts.php)

@endblock
