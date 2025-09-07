<?php
include 'connection.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $payment_method = $_POST['payment_method'];

    // Fetch cart items
    $cart_result = $conn_cart->query("SELECT * FROM cart");
    if ($cart_result->num_rows === 0) {
        echo "<script>alert('Your cart is empty!'); window.location='nav.php';</script>";
        exit();
    }

    $grand_total = 0;
    $cart_items_array = [];
    while ($item = $cart_result->fetch_assoc()) {
        $grand_total += $item['price'] * $item['quantity'];
        $cart_items_array[] = $item;
    }

    // Insert order into orders table
    $stmt = $conn_cart->prepare("INSERT INTO orders (name, email, phone, address, payment_method, total_amount) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssd", $name, $email, $phone, $address, $payment_method, $grand_total);
    $stmt->execute();
    $order_id = $stmt->insert_id; // Get the last inserted order ID
    $stmt->close();

    // Insert order items into order_items table
    $stmt_item = $conn_cart->prepare("INSERT INTO order_items (o_id, p_id, quantity, price) VALUES (?, ?, ?, ?)");
    foreach ($cart_items_array as $item) {
        $stmt_item->bind_param("iiid", $order_id, $item['p_id'], $item['quantity'], $item['price']);
        $stmt_item->execute();
    }
    $stmt_item->close();

    // Clear cart
    $conn_cart->query("TRUNCATE TABLE cart");

    // Redirect to success page
    header("Location: success.php?order_id=$order_id");
    exit();
}

// Fetch cart items for summary
$cart_items = $conn_cart->query("SELECT * FROM cart");
$grand_total = 0;
$cart_array = [];
while ($item = $cart_items->fetch_assoc()) {
    $grand_total += $item['price'] * $item['quantity'];
    $cart_array[] = $item;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Checkout</title>
    <style>
       body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: linear-gradient(135deg, #07121e, #122b40);
            margin: 0;
            padding: 0;
            color: #fff;
        }

        .container {
            max-width: 1100px;
            margin: 40px auto;
            padding: 20px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }

        .form-section, .summary-section {
            background: rgba(255, 255, 255, 0.08);
            border-radius: 15px;
            padding: 25px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
            backdrop-filter: blur(8px);
        }

        h1, h2 {
            text-align: center;
            margin-bottom: 20px;
            color: #00e6e6;
        }

        form label {
            display: block;
            margin-top: 15px;
            font-weight: 500;
        }

        form input, form textarea, form select {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            border: none;
            border-radius: 8px;
            background: rgba(255, 255, 255, 0.15);
            color: #fff;
            font-size: 15px;
        }

        form input:focus, form textarea:focus, form select:focus {
            outline: 2px solid #00e6e6;
        }

        form textarea {
            resize: none;
            height: 70px;
        }

        button {
            margin-top: 20px;
            width: 100%;
            padding: 12px;
            font-size: 18px;
            background: #00e6e6;
            border: none;
            border-radius: 8px;
            color: #07121e;
            font-weight: bold;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #00cccc;
        }

        /* Order Summary Table */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            color: #fff;
        }

        thead th {
            background: rgba(0, 230, 230, 0.2);
            padding: 10px;
            text-align: left;
        }

        tbody td {
            padding: 10px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }

        .total {
            margin-top: 20px;
            text-align: right;
            font-size: 20px;
            font-weight: bold;
            color: #00e6e6;
        }

        /* Responsive */
        @media screen and (max-width: 900px) {
            .container {
                grid-template-columns: 1fr;
            }
        }
       
    </style>
</head>
<body>
<div class="container">
    <div class="form-section">
        <h1>Checkout</h1>
        <form method="POST" action="">
            <label>Name:</label><input type="text" name="name" required>
            <label>Email:</label><input type="email" name="email" required>
            <label>Phone:</label><input type="text" name="phone" required>
            <label>Address:</label><textarea name="address" required></textarea>
            <label>Payment Method:</label>
            <select name="payment_method" required>
                <option value="Cash on Delivery">Cash on Delivery</option>
                <option value="Credit Card">Credit Card</option>
                <option value="PayPal">PayPal</option>
            </select>
            <button type="submit">Place Order</button>
        </form>
    </div>

    <div class="summary-section">
        <h2>Your Order</h2>
        <table>
            <thead>
                <tr><th>Product</th><th>Quantity</th><th>Price (₹)</th><th>Total (₹)</th></tr>
            </thead>
            <tbody>
            <?php foreach ($cart_array as $item): 
                $total_price = $item['price'] * $item['quantity']; ?>
                <tr>
                    <td style='text-align:left;'><?php echo htmlspecialchars($item['name']); ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo $item['price']; ?></td>
                    <td><?php echo $total_price; ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total">Total: ₹<?php echo $grand_total; ?></div>
    </div>
</div>
</body>
</html>
