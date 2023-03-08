<?php
// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  
  // Get the input values from the form
  $customer_name = $_POST['customer_name'];
  $customer_email = $_POST['customer_email'];
  $artist_id = $_POST['artist_id'];
  $artwork_type = $_POST['artwork_type'];
  $artwork_description = $_POST['artwork_description'];
  
  // Validate input values
  $errors = array();
  
  if (empty($customer_name)) {
    $errors[] = 'Please enter your name';
  }
  
  if (empty($customer_email)) {
    $errors[] = 'Please enter your email';
  } else if (!filter_var($customer_email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = 'Please enter a valid email';
  }
  
  if (empty($artist_id)) {
    $errors[] = 'Please select an artist';
  }
  
  if (empty($artwork_type)) {
    $errors[] = 'Please select an artwork type';
  }
  
  if (empty($artwork_description)) {
    $errors[] = 'Please enter artwork description';
  }
  
  // If there are no errors, process the order
  if (empty($errors)) {
    
    // Connect to the database
    $servername = "localhost";
    $username = "username";
    $password = "password";
    $dbname = "myDB";
    
    $conn = new mysqli($servername, $username, $password, $dbname);
    
    // Check connection
    if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
    }
    
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO orders (customer_name, customer_email, artist_id, artwork_type, artwork_description) VALUES (?, ?, ?, ?, ?)");
    
    // Bind the parameters to the statement
    $stmt->bind_param("ssiss", $customer_name, $customer_email, $artist_id, $artwork_type, $artwork_description);
    
    // Execute the statement
    if ($stmt->execute() === TRUE) {
      // Redirect to the order confirmation page
      header("Location: order-confirmation.php");
      exit();
    } else {
      // Display an error message if there is a problem with the database
      echo "Error: " . $stmt->error;
    }
    
    // Close the statement and connection
    $stmt->close();
    $conn->close();
    
  } else {
    // Display the errors to the user
    foreach ($errors as $error) {
      echo "<p>$error</p>";
    }
  }
}
?>
