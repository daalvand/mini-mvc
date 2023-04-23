<script>
    function updateItemCartCounters() {
        let itemList = JSON.parse(getCookie("cartItemList") ?? "{}");
        Object.keys(itemList).forEach(id => {
            updateItemCartCounter(id, itemList[id]);
        });


    }

    function updateItemCartCounter(id, value) {
        let counter = document.querySelector(`#item-cart-num-${id}`);
        if (!counter) {
            return;
        }
        counter.innerText = value;
        if (value) {
            document.querySelector(`#plus-minus-cart-item-${id}`).classList.remove("d-none");
            document.querySelector(`#add-to-cart-item-btn-${id}`).classList.add("d-none");
        } else {
            document.querySelector(`#plus-minus-cart-item-${id}`).classList.add("d-none");
            document.querySelector(`#add-to-cart-item-btn-${id}`).classList.remove("d-none");
        }
    }

    window.onload = function () {
        updateCartCounter();
        updateItemCartCounters()
    }


    function addCartItem(id) {
        let itemList = JSON.parse(getCookie("cartItemList") ?? "{}");
        itemList[id] = itemList[id] ? itemList[id] + 1 : 1;
        setCookie("cartItemList", JSON.stringify(itemList));
        updateCartCounter();
        updateItemCartCounter(id, itemList[id] ?? 0)
    }

    function removeCartItem(id, removeEntire = false) {
        let itemList = JSON.parse(getCookie("cartItemList") ?? "{}");
        if (itemList[id] && itemList[id] > 1) {
            itemList[id] = itemList[id] - 1;
        } else {
            delete itemList[id];
        }

        setCookie("cartItemList", JSON.stringify(itemList));
        updateCartCounter();

        let value = itemList[id] ?? 0;
        updateItemCartCounter(id, value)
        if (removeEntire && value === 0) {
            document.querySelector(`#cart-item-container-${id}`).remove();
        }
    }


    function updateCartCounter() {
        let counter = document.getElementById("nav-cart-counter");
        let itemList = JSON.parse(getCookie("cartItemList") ?? "{}");
        itemList.values
        let sum = 0;
        for (let key in itemList) {
            sum += itemList[key];
        }
        counter.innerText = sum.toString();
    }

    function getCookie(name) {
        let value = "; " + document.cookie;
        let parts = value.split("; " + name + "=");
        if (parts.length === 2) return parts.pop().split(";").shift();
    }

    function setCookie(name, value) {
        let expires = new Date();
        expires.setTime(expires.getTime() + (365 * 24 * 60 * 60 * 1000));
        document.cookie = `${name}=${value};expires=${expires.toUTCString()};path=/`;
    }
</script>
