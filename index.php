<?php
// Start the session
session_start();

// Include the database connection file
require_once 'db.php';

// Check if the user is logged in
if (isset($_SESSION['user_id'])) {
	// Redirect the user to the dashboard page
	header('Location: dashboard.php');
	exit;
}

// Fetch featured artists from the database
$featured_artists = $conn->query("SELECT * FROM artists WHERE is_featured = 1 ORDER BY RAND() LIMIT 3");

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Artist Services - Home</title>
	<link rel="stylesheet" href="styles.css">
	<script src="main.js"></script>
</head>
<body>
	<header>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="artists.php">Artists</a></li>
				<li><a href="my-orders.php">My Orders</a></li>
				<?php if (isset($_SESSION['user_id'])): ?>
					<li><a href="dashboard.php">Dashboard</a></li>
					<li><a href="logout.php">Log Out</a></li>
				<?php else: ?>
					<li><a href="login.php">Log In</a></li>
					<li><a href="register.php">Register</a></li>
				<?php endif; ?>
			</ul>
		</nav>
	</header>
	<main>
		<section class="hero">
			<h1>Find Your Perfect Artist</h1>
			<p>Get a high-quality portrait or drawing from our talented artists</p>
			<form action="search.php" method="get">
				<input type="text" name="query" placeholder="Search artists...">
				<button type="submit">Search</button>
			</form>
		</section>
		<section class="featured-artists">
			<h2>Featured Artists</h2>
			<ul>
				<?php while ($artist = $featured_artists->fetch_assoc()): ?>
					<li>
						<a href="artist-profile.php?id=<?php echo $artist['id']; ?>">
							<img src="<?php echo $artist['photo']; ?>" alt="<?php echo $artist['name']; ?>">
							<h3><?php echo $artist['name']; ?></h3>
							<p><?php echo $artist['description']; ?></p>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>
		</section>
		<section class="call-to-action">
			<h2>Ready to order?</h2>
			<p>Choose an artist and place your order now!</p>
			<a href="artists.php" class="button">Browse Artists</a>
		</section>
	</main>
	<footer>
		<p>&copy; 2023 Artist Services</p>
	</footer>
</body>
</html>
