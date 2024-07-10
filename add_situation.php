<?php
session_start();
require_once("pdo.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);
    $geo_location = filter_input(INPUT_POST, 'geo_location', FILTER_SANITIZE_STRING);
    $date = filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING);
    $moon_data = filter_input(INPUT_POST, 'moon_data', FILTER_SANITIZE_STRING);
    $sun_data = filter_input(INPUT_POST, 'sun_data', FILTER_SANITIZE_STRING);
    $observations = filter_input(INPUT_POST, 'observations', FILTER_SANITIZE_STRING);
    $resource_link = filter_input(INPUT_POST, 'resource_link', FILTER_SANITIZE_URL);
    $user = $_SESSION['user_id']; // Assuming user ID is stored in the session
    
   // Handle file upload
   if ($_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmp_name = $_FILES['image']['tmp_name'];
    $imagePath = 'uploads/' . basename($_FILES['image']['name']);
    
    // Move uploaded file to the uploads directory
    if (move_uploaded_file($tmp_name, $imagePath)) {
        // Read image data
        $imageData = file_get_contents($imagePath);
    } else {
        echo "Failed to move uploaded file.";
        exit;
    }
}
    
    // Insert data into the database
    $sql = "INSERT INTO situations (title, description, geo_location, date, moon_data, sun_data, observations, resource_link, user, image_data)
            VALUES (:title, :description, :geo_location, :date, :moon_data, :sun_data, :observations, :resource_link, :user, :image_data)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':title' => $title,
        ':description' => $description,
        ':geo_location' => $geo_location,
        ':date' => $date,
        ':moon_data' => $moon_data,
        ':sun_data' => $sun_data,
        ':observations' => $observations,
        ':resource_link' => $resource_link,
        ':user' => $_SESSION['user_id'],
        ':image_data' => $imagedata
    ]);

    $_SESSION["success"] = "Situation added successfully";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Situation</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="script.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container mt-5">
        <h1>Add New Situation</h1>
        <form method="post">
            <div class="form-group">
                <label for="title">Title:</label>
                <input type="text" class="form-control" id="title" name="title" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="geo_location">Geolocation:</label>
                <input type="text" class="form-control" id="geo_location" name="geo_location" required>
            </div>
            <div class="form-group">
                <label for="date">Date:</label>
                <input type="date" class="form-control" id="date" name="date" required>
            </div>
            <div class="form-group">
                <label for="moon_data">Moon Data:</label>
                <textarea class="form-control" id="moon_data" name="moon_data" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="sun_data">Sun Data:</label>
                <textarea class="form-control" id="sun_data" name="sun_data" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="observations">Observations:</label>
                <textarea class="form-control" id="observations" name="observations" rows="5" required></textarea>
            </div>
            <div class="form-group">
                <label for="resource_link">Resource Link:</label>
                <input type="url" class="form-control" id="resource_link" name="resource_link">
            </div>
            <div class="form-group">
            <label for="image">Upload Image:</label>
            <input type="file" class="form-control-file" id="image" name="image">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
        <?php
        if (isset($_SESSION["success"])) {
            echo '<p class="text-success">' . $_SESSION["success"] . '</p>';
            unset($_SESSION["success"]);
        }
        if (isset($_SESSION["error"])) {
            echo '<p class="text-danger">' . $_SESSION["error"] . '</p>';
            unset($_SESSION["error"]);
        }
        ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
