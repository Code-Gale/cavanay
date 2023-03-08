<?php
// Include the database connection
include_once 'config.php';

// Get the artist ID from the URL parameter
if(isset($_GET['id'])) {
    $artist_id = $_GET['id'];
} else {
    // Redirect to homepage if no artist ID is specified
    header('Location: index.php');
}

// Get the artist data from the database
$sql = "SELECT * FROM artists WHERE id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $artist_id]);
$artist = $stmt->fetch();

// Get the artist's drawings from the database
$sql = "SELECT * FROM drawings WHERE artist_id = :id";
$stmt = $pdo->prepare($sql);
$stmt->execute(['id' => $artist_id]);
$drawings = $stmt->fetchAll();

// Check if the artist exists
if(!$artist) {
    // Redirect to homepage if artist does not exist
    header('Location: index.php');
}

?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo $artist['name']; ?> - Artist Profile</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <?php include 'header.php'; ?>

    <div class="container">
        <h1><?php echo $artist['name']; ?></h1>
        <img src="<?php echo $artist['image']; ?>" alt="<?php echo $artist['name']; ?>">

        <div class="artist-info">
            <h2>Artist Information</h2>
            <p><strong>Name:</strong> <?php echo $artist['name']; ?></p>
            <p><strong>Email:</strong> <?php echo $artist['email']; ?></p>
            <p><strong>Location:</strong> <?php echo $artist['location']; ?></p>
        </div>

        <div class="drawings">
            <h2>Drawings</h2>
            <?php foreach($drawings as $drawing): ?>
            <div class="drawing">
                <img src="<?php echo $drawing['image']; ?>" alt="<?php echo $drawing['title']; ?>">
                <div class="drawing-info">
                    <h3><?php echo $drawing['title']; ?></h3>
                    <p><?php echo $drawing['description']; ?></p>
                    <p><strong>Price:</strong> $<?php echo $drawing['price']; ?></p>
                    <a href="order.php?id=<?php echo $drawing['id']; ?>">Order Now</a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
</body>
</html>
