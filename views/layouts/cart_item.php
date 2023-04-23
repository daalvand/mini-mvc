<div class="card col-3 m-3">
    <img class="card-img-top" src="{{ $item->image }}" alt="Card image cap">
    <div class="card-body">
        <h5 class="card-title">{{ $item->title }}</h5>
        <p class="card-text">{{ $item->description }}</p>
        <p class="card-text">{{ $item->price }} $</p>
        <p class="card-text"><small class="text-muted">{{ $item->created_at }}</small></p>
    </div>
    <button type="button" onclick="addItem({{$item->id}})">add</button>
    <span class="item-cart-counter" item_id="{{$item->id}}"></span>
    <button type="button" onclick="removeItem({{$item->id}})">remove</button>
</div>
