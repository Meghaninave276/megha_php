<?php
include 'connection.php';

if (!isset($_GET['order_id']) || !is_numeric($_GET['order_id'])) {
    die("Invalid order ID.");
}

$o_id = (int)$_GET['order_id'];

// Fetch order details
$order_query = mysqli_query($conn_cart, "SELECT * FROM orders WHERE o_id = $o_id");
if (!$order_query || mysqli_num_rows($order_query) == 0) {
    die("Order not found.");
}
$order = mysqli_fetch_assoc($order_query);

// Fetch order items
$items = [];
$items_query = mysqli_query($conn_cart, "SELECT * FROM order_items WHERE o_id = $o_id");
while ($row = mysqli_fetch_assoc($items_query)) {
    $items[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Success</title>
    <style>
        body {
        font-family: Arial, sans-serif;
        background: linear-gradient(135deg, #07121e, #122b40);
        color: #fff;
        margin: 0;
        padding: 0;
    }

    .container {
        max-width: 900px;
        margin: 50px auto;
        background: rgba(255, 255, 255, 0.08);
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0, 0, 0, 0.6);
        backdrop-filter: blur(8px);
    }

    h1 {
        text-align: center;
        color: #f9f9f9;
        font-size: 32px;
        margin-bottom: 20px;
        text-shadow: 0 0 8px rgba(255, 255, 255, 0.2);
    }

    h2 {
        color: #00f7ff;
        font-size: 24px;
        border-bottom: 2px solid rgba(0, 247, 255, 0.3);
        padding-bottom: 5px;
        margin-bottom: 15px;
    }

    .customer-info, .order-summary {
        margin-bottom: 25px;
        padding: 20px;
        border-radius: 10px;
        background: rgba(255, 255, 255, 0.05);
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.4);
    }

    p {
        font-size: 18px;
        margin: 8px 0;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 18px;
    }

    table th, table td {
        border: 1px solid rgba(255, 255, 255, 0.2);
        padding: 10px;
        text-align: center;
    }

    table th {
        background: rgba(0, 247, 255, 0.2);
        color: #fff;
        font-size: 18px;
    }

    table td {
        color: #e6e6e6;
    }

    .total {
        text-align: right;
        font-size: 22px;
        font-weight: bold;
        color: #00f7ff;
        margin-top: 15px;
    }

    .btn-home {
        display: inline-block;
        padding: 14px 30px;
        background: #00f7ff;
        color: #07121e;
        font-size: 18px;
        font-weight: bold;
        border-radius: 8px;
        text-decoration: none;
        transition: 0.3s ease-in-out;
    }

    .btn-home:hover {
        background: #0099cc;
        color: #fff;
    }
       
    </style>
</head>
<body>
<div class="container">
    <h1>Thank You! Your Order Has Been Placed ✅</h1>

    <div class="customer-info">
        <h2>Customer Information</h2>
        <p><strong>Name:</strong> <?php echo htmlspecialchars($order['name']); ?></p>
        <p><strong>Email:</strong> <?php echo htmlspecialchars($order['email']); ?></p>
        <p><strong>Phone:</strong> <?php echo htmlspecialchars($order['phone']); ?></p>
        <p><strong>Address:</strong> <?php echo htmlspecialchars($order['address']); ?></p>
        <p><strong>Payment Method:</strong> <?php echo htmlspecialchars($order['payment_method']); ?></p>
    </div>

    <div class="order-summary">
        <h2>Order Summary</h2>
        <table>
            <thead>
                <tr>
                    <th>Product ID</th>
                    <th>Quantity</th>
                    <th>Price (₹)</th>
                    <th>Total (₹)</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $grand_total = 0;
                foreach ($items as $item):
                    $total_price = $item['price'] * $item['quantity'];
                    $grand_total += $total_price;
                ?>
                <tr>
                    <td><?php echo $item['p_id']; ?></td>
                    <td><?php echo $item['quantity']; ?></td>
                    <td><?php echo number_format($item['price'], 2); ?></td>
                    <td><?php echo number_format($total_price, 2); ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="total">Grand Total: ₹<?php echo number_format($grand_total, 2); ?></div>
    </div>

    <div style="text-align:center;">
        <a class="btn-home" href="nav.php">Continue Shopping</a>
    </div>
</div>
</body>
</html>
