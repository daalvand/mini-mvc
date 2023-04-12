<script>
    function updateItemCartCounters() {
        let counters = document.querySelectorAll(".item-cart-counter");
        let itemList = JSON.parse(getCookie("itemList") ?? "{}");
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
        let itemList = JSON.parse(getCookie("itemList") ?? "{}");
        itemList[item] = itemList[item] ? itemList[item] + 1 : 1;
        setCookie("itemList", JSON.stringify(itemList));
        updateCartCounter();
        updateItemCartCounter(item, itemList[item])
    }

    function removeItem(item) {
        let itemList = JSON.parse(getCookie("itemList") ?? "{}");
        if (itemList[item]) {
            itemList[item] = itemList[item] - 1;
        } else {
            delete itemList[item];
        }

        setCookie("itemList", JSON.stringify(itemList));
        updateCartCounter();
        updateItemCartCounter(item, itemList[item])
    }


    function updateCartCounter() {
        let counter = document.getElementById("nav-cart-counter");
        let itemList = JSON.parse(getCookie("itemList") ?? "{}");
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
