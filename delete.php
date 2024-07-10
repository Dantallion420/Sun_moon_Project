<?php
session_start();
require_once "pdo.php";

if (isset($_POST['delete'])) {
    
    $profile_id = $_POST['profile_id']; 

    try {
        $stmt = $pdo->prepare("DELETE FROM profile WHERE profile_id = :profile_id");
        $stmt->execute(array(':profile_id' => $profile_id));

        $count = $stmt->rowCount();
        if ($count > 0) {
            $_SESSION['success'] = "Profile deleted";
        }
    } catch (PDOException $e) {
        $_SESSION['error'] = "Error deleting the profile: " . $e->getMessage();
    }

    header("Location: index.php");
    return;
}

if (isset($_GET['profile_id'])) {
    $profile_id = $_GET['profile_id']; 
} else {
    $_SESSION['error'] = "Invalid request. Profile not found.";
    header("Location: index.php");
    return;
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Confirmation Page</title>
</head>
<body>
    <div class="container">
        <p>Confirm: Deleting Profile ID <?php echo $profile_id; ?></p>
        <form method="post">
            <input type="hidden" name="profile_id" value="<?php echo $profile_id; ?>">
            <input type="submit" value="Delete" name="delete">
        </form>
        <a href="index.php">Cancel</a>
    </div>
</body>
</html>