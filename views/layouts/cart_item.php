<div class="col-md-4 mt-2" id="cart-item-container-{{$item->id}}">
    <div class="card">
        <img src="{{ $item->image }}" class="card-img-top" alt="...">
        <div class="card-body">
            <h5 class="card-title">{{ $item->title }}</h5>
            <p class="card-text">{{ $item->description }}</p>
            <p class="card-text">{{ $item->price }} $</p>
            <p class="card-text"><small class="text-muted">{{ $item->created_at }}</small></p>
        </div>

        <div class="card-footer">
            <div class="input-group d-none" id="plus-minus-cart-item-{{$item->id}}">
                <button class="btn btn-outline-secondary minus-btn" type="button" onclick="removeCartItem('{{$item->id}}', {{$removeCartItem ?? false}})">-</button>
                <span class="quantity-span p-2" id="item-cart-num-{{$item->id}}"></span>
                <button class="btn btn-outline-secondary plus-btn" type="button" onclick="addCartItem('{{$item->id}}')">+</button>
            </div>
            <button type="button" class="btn btn-primary add-to-cart-item-btn" id="add-to-cart-item-btn-{{$item->id}}" onclick="addCartItem('{{$item->id}}')">Add to Cart</button>
        </div>
    </div>
</div>
