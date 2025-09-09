document.getElementById("filterForm").addEventListener("submit", function(e) {
    e.preventDefault();

    const search = document.getElementById("searchBox").value;
    const category = document.getElementById("categoryFilter").value;
    const price = document.getElementById("priceFilter").value;

    const xhr = new XMLHttpRequest();
    xhr.open("GET", "fetch_products.php?search=" + search + "&category=" + category + "&price=" + price, true);

    xhr.onload = function() {
        if (this.status === 200) {
            document.getElementById("productResults").innerHTML = this.responseText;
        }
    };

    xhr.send();
});

// Load all products on first page load
window.onload = function() {
    document.getElementById("filterForm").dispatchEvent(new Event("submit"));
};