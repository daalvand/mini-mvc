<script>
    window.onload = function (){
        updateCartCounter()
    }


    function addItem(item) {
        let itemList = JSON.parse(getCookie("itemList")) || {};
        itemList[item] = itemList[item] ? itemList[item] + 1 : 1;
        setCookie("itemList", JSON.stringify(itemList));
        updateCartCounter();
    }

    function removeItem(item) {
        let itemList = JSON.parse(getCookie("itemList")) || {};
        if(itemList[item]){
            itemList[item] = itemList[item] - 1;
        }else {
            itemList[item] = 0;
        }

        setCookie("itemList", JSON.stringify(itemList));
        updateCartCounter();
    }


    function updateCartCounter() {
        let counter = document.getElementById("nav-cart-counter");
        let itemList = JSON.parse(getCookie("itemList")) || {};
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
