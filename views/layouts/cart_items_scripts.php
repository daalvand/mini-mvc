<script>
    function updateItemCartCounters() {
        let counters = document.querySelectorAll(".item-cart-counter");
        let itemList = JSON.parse(getCookie("cartItemList") ?? "{}");
        counters.forEach(counter => {
            let item_id = counter.getAttribute("item_id");
            counter.innerText = itemList[item_id] ? itemList[item_id] : 0;
        })
    }

    function updateItemCartCounter(id, value) {
            let counter = document.querySelector(`.item-cart-counter[item_id="${id}"]`);
            counter.innerText = value;
    }


    window.onload = function () {
        updateCartCounter();
        updateItemCartCounters()
    }


    function addItem(item) {
        let itemList = JSON.parse(getCookie("cartItemList") ?? "{}");
        itemList[item] = itemList[item] ? itemList[item] + 1 : 1;
        setCookie("cartItemList", JSON.stringify(itemList));
        updateCartCounter();
        updateItemCartCounter(item, itemList[item] ?? 0)
    }

    function removeItem(item) {
        let itemList = JSON.parse(getCookie("cartItemList") ?? "{}");
        if (itemList[item] && itemList[item] > 1) {
            itemList[item] = itemList[item] - 1;
        } else {
            delete itemList[item];
        }

        setCookie("cartItemList", JSON.stringify(itemList));
        updateCartCounter();
        updateItemCartCounter(item, itemList[item] ?? 0)
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
        console.log(sum)
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
