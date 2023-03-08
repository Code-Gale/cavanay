<?php
  // Start the session
  session_start();
  
  // Check if user is logged in
  if(!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
  }
  
  // Include the database connection
  include('includes/db.php');

  // Get the user's orders
  $user_id = $_SESSION['user_id'];
  $orders_query = "SELECT * FROM orders WHERE user_id='$user_id'";
  $orders_result = mysqli_query($conn, $orders_query);

  // Check if any orders were found
  if(mysqli_num_rows($orders_result) == 0) {
    $message = "You haven't placed any orders yet.";
  }

  // Get the order details
  while($order = mysqli_fetch_assoc($orders_result)) {
    $order_id = $order['id'];
    $order_date = $order['order_date'];
    $order_status = $order['order_status'];
    $order_total = $order['order_total'];

    // Get the products in the order
    $products_query = "SELECT p.product_name, op.product_price, op.product_quantity 
      FROM order_products op 
      JOIN products p ON p.id = op.product_id 
      WHERE op.order_id = '$order_id'";
    $products_result = mysqli_query($conn, $products_query);

    // Output the order details and products
    echo "<h2>Order #$order_id</h2>";
    echo "<p>Date: $order_date</p>";
    echo "<p>Status: $order_status</p>";
    echo "<p>Total: $$order_total</p>";
    echo "<table>";
    echo "<tr><th>Product Name</th><th>Price</th><th>Quantity</th></tr>";
    while($product = mysqli_fetch_assoc($products_result)) {
      $product_name = $product['product_name'];
      $product_price = $product['product_price'];
      $product_quantity = $product['product_quantity'];
      echo "<tr><td>$product_name</td><td>$$product_price</td><td>$product_quantity</td></tr>";
    }
    echo "</table>";
  }
?>
