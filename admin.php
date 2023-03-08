<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['admin_id'])) {
	// Redirect to login page
	header('Location: login.php');
	exit();
}

// Include the database connection
require_once 'db.php';

// Get the total number of users
$total_users = $conn->query('SELECT COUNT(*) FROM users')->fetchColumn();

// Get the total number of orders
$total_orders = $conn->query('SELECT COUNT(*) FROM orders')->fetchColumn();

// Get the total revenue
$total_revenue = $conn->query('SELECT SUM(price) FROM orders')->fetchColumn();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	// Get the form data
	$username = $_POST['username'];
	$email = $_POST['email'];
	$password = $_POST['password'];

	// Hash the password
	$password_hash = password_hash($password, PASSWORD_DEFAULT);

	// Insert the new user into the database
	$stmt = $conn->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
	$stmt->execute([$username, $email, $password_hash]);

	// Redirect to the users page
	header('Location: users.php');
	exit();
}

// Include the header file
require_once 'header.php';
?>

<main>
	<div class="container">
		<h1>Admin Dashboard</h1>
		<div class="card-deck">
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Total Users</h5>
					<p class="card-text"><?php echo $total_users; ?></p>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Total Orders</h5>
					<p class="card-text"><?php echo $total_orders; ?></p>
				</div>
			</div>
			<div class="card">
				<div class="card-body">
					<h5 class="card-title">Total Revenue</h5>
					<p class="card-text">$<?php echo number_format($total_revenue, 2); ?></p>
				</div>
			</div>
		</div>
		<h2>Add User</h2>
		<form action="admin.php" method="POST">
			<div class="form-group">
				<label for="username">Username:</label>
				<input type="text" class="form-control" name="username" required>
			</div>
			<div class="form-group">
				<label for="email">Email:</label>
				<input type="email" class="form-control" name="email" required>
			</div>
			<div class="form-group">
				<label for="password">Password:</label>
				<input type="password" class="form-control" name="password" required>
			</div>
			<button type="submit" class="btn btn-primary">Add User</button>
		</form>
	</div>
</main>

<?php
// Include the footer file
require_once 'footer.php';
?>
