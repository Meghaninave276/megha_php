function applyFilters(event) {
  event.preventDefault(); // stop form reload

  const searchText = document.getElementById("searchBox").value.toLowerCase();
  const priceRange = document.getElementById("priceFilter").value;

  // Merge both product lists
  let allProducts = [...watchlist, ...Perfumelist];

  // === 1. Filter by search text ===
  let filtered = allProducts.filter(product => {
    return (
      product.title.toLowerCase().includes(searchText) ||
      product.description.toLowerCase().includes(searchText)
    );
  });

  // === 2. Filter by price range ===
  filtered = filtered.filter(product => {
    if (priceRange === "all") return true;
    let price = product.price;

    if (priceRange === "0-50") return price <= 50;
    if (priceRange === "51-100") return price >= 51 && price <= 100;
    if (priceRange === "101-500") return price >= 101 && price <= 500;
    if (priceRange === "500plus") return price > 500;

    return true;
  });

  // === 3. Clear old products ===
  document.querySelector("#watch .product-box").innerHTML = "";
  document.querySelector("#perfume .product-box").innerHTML = "";

  // === 4. Redisplay filtered products ===
  filtered.forEach(product => {
    if (watchlist.includes(product)) {
      productdisplay([product], "#watch");
    } else {
      productdisplay([product], "#perfume");
    }
  });

  // === 5. If exactly one match → scroll to it ===
  if (filtered.length === 1) {
    setTimeout(() => {
      const target = document.querySelector(
        `img[alt="${filtered[0].title}"]`
      );
      if (target) {
        target.scrollIntoView({ behavior: "smooth", block: "center" });
      }
    }, 300);
  }
}
