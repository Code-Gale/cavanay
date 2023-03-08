<?php
// Include the database connection file
require_once 'db.php';

// Initialize variables for pagination
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$limit = 10;
$offset = ($page - 1) * $limit;

// Fetch the artists' information from the database
$artists = $conn->query("SELECT * FROM artists LIMIT $limit OFFSET $offset")->fetch_all(MYSQLI_ASSOC);

// Get the total number of artists in the database
$total_artists = $conn->query("SELECT COUNT(*) as total FROM artists")->fetch_assoc()['total'];

// Calculate the total number of pages for pagination
$total_pages = ceil($total_artists / $limit);

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Artist Services - Artists</title>
	<link rel="stylesheet" href="styles.css">
	<script src="main.js"></script>
</head>
<body>
	<header>
		<nav>
			<ul>
				<li><a href="index.php">Home</a></li>
				<li><a href="artists.php">Artists</a></li>
				<li><a href="login.php">Log In</a></li>
				<li><a href="register.php">Sign Up</a></li>
			</ul>
		</nav>
	</header>
	<main>
		<section class="hero">
			<h1>Our Artists</h1>
			<p>Check out our talented artists and their works</p>
		</section>
		<section class="artists">
			<?php foreach ($artists as $artist): ?>
				<div class="card">
					<img src="<?php echo $artist['profile_image']; ?>" alt="<?php echo $artist['name']; ?>">
					<h3><?php echo $artist['name']; ?></h3>
					<p><?php echo $artist['description']; ?></p>
					<a href="artist.php?id=<?php echo $artist['id']; ?>">View Artist</a>
				</div>
			<?php endforeach; ?>
		</section>
		<section class="pagination">
			<?php if ($page > 1): ?>
				<a href="?page=<?php echo $page - 1; ?>">&laquo; Previous</a>
			<?php endif; ?>

			<?php for ($i = 1; $i <= $total_pages; $i++): ?>
				<?php if ($i == $page): ?>
					<span class="current"><?php echo $i; ?></span>
				<?php else: ?>
					<a href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
				<?php endif; ?>
			<?php endfor; ?>

			<?php if ($page < $total_pages): ?>
				<a href="?page=<?php echo $page + 1; ?>">Next &raquo;</a>
			<?php endif; ?>
		</section>
	</main>
	<footer>
		<p>&copy; 2023 Artist Services</p>
	</footer>
</body>
</html>
