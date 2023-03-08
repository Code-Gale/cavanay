<?php
session_start();

// check if user is logged in
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// get artist id from the URL
if(!isset($_GET['artist_id'])) {
    header("Location: artists.php");
    exit();
} else {
    $artist_id = $_GET['artist_id'];
}

// connect to the database
$conn = mysqli_connect("localhost", "username", "password", "database");

// check if connection was successful
if(!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// get artist details from the database
$query = "SELECT * FROM artists WHERE id = '$artist_id'";
$result = mysqli_query($conn, $query);

if(mysqli_num_rows($result) == 0) {
    header("Location: artists.php");
    exit();
}

$artist = mysqli_fetch_assoc($result);

// handle form submission
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $art_type = $_POST['art_type'];
    $size = $_POST['size'];
    $description = $_POST['description'];
    $status = 'pending';
    
    // insert order into database
    $query = "INSERT INTO orders (user_id, artist_id, art_type, size, description, status) 
              VALUES ('$user_id', '$artist_id', '$art_type', '$size', '$description', '$status')";
              
    if(mysqli_query($conn, $query)) {
        header("Location: dashboard.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Order <?php echo $artist['name']; ?>'s Artwork</title>
    <link rel="stylesheet" type="text/css" href="order.css">
</head>
<body>
    <h1>Order <?php echo $artist['name']; ?>'s Artwork</h1>
    
    <div class="artwork">
        <img src="<?php echo $artist['image']; ?>" alt="<?php echo $artist['name']; ?>">
        <div class="info">
            <h2><?php echo $artist['name']; ?></h2>
            <p><?php echo $artist['description']; ?></p>
        </div>
    </div>
    
    <form method="POST">
        <label for="art_type">Art Type</label>
        <select id="art_type" name="art_type">
            <option value="portrait">Portrait</option>
            <option value="landscape">Landscape</option>
            <option value="still life">Still Life</option>
        </select>
        
        <label for="size">Size</label>
        <select id="size" name="size">
            <option value="small">Small</option>
            <option value="medium">Medium</option>
            <option value="large">Large</option>
        </select>
        
        <label for="description">Description</label>
        <textarea id="description" name="description"></textarea>
        
        <button type="submit">Submit Order</button>
    </form>
</body>
</html>
