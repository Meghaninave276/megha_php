<?php
// Login DB
$conn_login = mysqli_connect("localhost", "root", "", "login_db");
if (!$conn_login) {
    die("Login DB connection failed: " . mysqli_connect_error());
}

// Product DB
$conn_product = mysqli_connect("localhost", "root", "", "product_db");
if (!$conn_product) {
    die("Product DB connection failed: " . mysqli_connect_error());
}

// Cart/Orders DB
$conn_cart = mysqli_connect("localhost", "root", "", "cart_system");
if (!$conn_cart) {
    die("Cart DB connection failed: " . mysqli_connect_error());
}

// Contact DB
$conn_contact = mysqli_connect("localhost", "root", "", "contact_db");
if (!$conn_contact) {
    die("Contact DB connection failed: " . mysqli_connect_error());
}
?>
