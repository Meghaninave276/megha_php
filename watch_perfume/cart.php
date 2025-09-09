<?php
include 'connection.php';

// Add to Cart
if (isset($_POST['add_to_cart'])) {
    $p_id = (int)$_POST['id'];
    $name = mysqli_real_escape_string($conn_cart, $_POST['name']);
    $price = (float)$_POST['price'];
    $image = mysqli_real_escape_string($conn_cart, $_POST['image']);

    $check = mysqli_query($conn_cart, "SELECT * FROM cart WHERE p_id='$p_id'");
    if (mysqli_num_rows($check) > 0) {
        mysqli_query($conn_cart, "UPDATE cart SET quantity = quantity + 1 WHERE p_id='$p_id'");
    } else {
        mysqli_query($conn_cart, "INSERT INTO cart (p_id, name, price, image, quantity) 
                                  VALUES ('$p_id', '$name', '$price', '$image', 1)");
    }
    header("Location: cart.php");
    exit();
}

// Remove or update items
if (isset($_GET['remove'])) {
    $id = (int)$_GET['remove'];
    mysqli_query($conn_cart, "DELETE FROM cart WHERE id='$id'");
    header("Location: cart.php");
    exit();
}

if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    if ($_GET['action'] == 'increase') {
        mysqli_query($conn_cart, "UPDATE cart SET quantity = quantity + 1 WHERE id='$id'");
    } elseif ($_GET['action'] == 'decrease') {
        $q = mysqli_fetch_assoc(mysqli_query($conn_cart, "SELECT quantity FROM cart WHERE id='$id'"));
        if ($q['quantity'] > 1) {
            mysqli_query($conn_cart, "UPDATE cart SET quantity = quantity - 1 WHERE id='$id'");
        } else {
            mysqli_query($conn_cart, "DELETE FROM cart WHERE id='$id'");
        }
    }
    header("Location: cart.php");
    exit();
}

// Fetch cart items
$result = mysqli_query($conn_cart, "SELECT * FROM cart");
$grand_total = 0;
?>
<!-- HTML for cart display (same as your previous table code) -->

<!DOCTYPE html>
<html>
<head>
    <title>Your Cart</title>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #07121e, #122b40); 
            color: #fff;
            margin: 0;
            padding: 0;
        }
        h1 { text-align: center; margin: 30px 0; font-size: 2.5rem; }
        table {
            width: 80%; margin: 20px auto;
            border-collapse: collapse;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 15px;
            overflow: hidden;
        }
        th, td { padding: 15px; text-align: center; color: #fff; }
        th { background: rgba(255, 255, 255, 0.2); font-size: 1.2rem; }
        td img { width: 80px; height: 80px; border-radius: 10px; }
        tr:nth-child(even) { background: rgba(255, 255, 255, 0.05); }
        a {
            text-decoration: none;
            padding: 8px 15px;
            background: #ff6f61;
            color: white;
            border-radius: 8px;
            font-weight: bold;
            transition: 0.3s;
        }
        a:hover { background: #ff3b2e; }
        .btn-checkout {
            display: inline-block;
            padding: 12px 25px;
            background: #00c6ff;
            color: #fff;
            font-weight: bold;
            text-transform: uppercase;
            border-radius: 50px;
            transition: 0.3s;
        }
        .btn-checkout:hover { background: #0072ff; }
    </style>
</head>
<body>
<h1>Shopping Cart</h1>
<table>
    <tr>
        <th>Image</th>
        <th>Name</th>
        <th>Price</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Action</th>
    </tr>
    <?php
$result = mysqli_query($conn_cart, "SELECT * FROM cart");
$grand_total = 0;

if (mysqli_num_rows($result) > 0) {
    while ($item = mysqli_fetch_assoc($result)) {
        $total = $item['price'] * $item['quantity'];
        $grand_total += $total;

        echo "
        <tr>
            <td><img src='{$item['image']}' alt='Product'></td>
            <td>{$item['name']}</td>
            <td>\${$item['price']}</td>
            <td>
                <a href='cart.php?action=decrease&id={$item['id']}'>-</a>
                {$item['quantity']}
                <a href='cart.php?action=increase&id={$item['id']}'>+</a>
            </td>
            <td>\${$total}</td>
            <td>
                <a href='cart.php?remove={$item['id']}' style='background:#dc3545;'>Remove</a>
            </td>
        </tr>
        ";
    }

    echo "
    <tr>
        <td colspan='4' style='text-align:right; font-weight:bold;'>Grand Total:</td>
        <td colspan='2' style='font-weight:bold;'>\${$grand_total}</td>
    </tr>
    ";
} else {
    echo "<tr><td colspan='6' style='text-align:center;'>Your cart is empty.</td></tr>";
}
?>
</table>
<div style="text-align:center; margin-top:20px;">
    <a href="nav.php">Continue Shopping</a> | 
    <a href="checkout.php" class="btn-checkout">Checkout</a>
</div>
</body>
</html>
