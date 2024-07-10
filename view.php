<?php
session_start();
require_once("pdo.php");


$profile = null;
$positions = array();


if (isset($_GET['profile_id']) && is_numeric($_GET['profile_id'])) {
    $profileId = $_GET['profile_id'];

   
    try {
        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$db", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        die("Database connection failed: " . $e->getMessage());
    }


    $stmt = $pdo->prepare("SELECT * FROM profile WHERE profile_id = :profile_id");
    $stmt->bindParam(':profile_id', $profileId, PDO::PARAM_INT);
    $stmt->execute();
    $profile = $stmt->fetch(PDO::FETCH_ASSOC);

   
    $stmt = $pdo->prepare("SELECT year, description FROM position WHERE profile_id = :profile_id");
    $stmt->bindParam(':profile_id', $profileId, PDO::PARAM_INT);
    $stmt->execute();
    $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Leopoldo Facci</title>
</head>
<body>
    <h1>Profile Details</h1>

    <?php
    if ($profile) {
        echo '<p><b>Name:</b> ' . htmlentities($profile['first_name']) . '</p>';
        echo '<p><b>Headline:</b> ' . htmlentities($profile['headline']) . '</p>';
        echo '<p><b>Summary:</b> ' . htmlentities($profile['summary']) . '</p>';

        echo '<h2>Positions</h2>';
        if (!empty($positions)) {
            echo '<ul>';
            foreach ($positions as $position) {
                echo '<li>' . htmlentities($position['year']) . ': ' . htmlentities($position['description']) . '</li>';
            }
            echo '</ul>';
        } else {
            echo '<p>No positions available for this profile.</p>';
        }

        echo '<a href="index.php">Back to Profile List</a>';
    } else {
        echo '<p>Profile not found</p>';
        echo '<a href="index.php">Back to Profile List</a>';
    }
    ?>
</body>
</html>