<?php
session_start();
require_once("pdo.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    die("Access Denied");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize input data
    $user_id = $_SESSION['user_id'];
    $profile_id = $_POST['id'];
    $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
    $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $headline = filter_input(INPUT_POST, 'headline', FILTER_SANITIZE_STRING);
    $summary = filter_input(INPUT_POST, 'summary', FILTER_SANITIZE_STRING);

    // Update profile in the database
    try {
        $sql = "UPDATE profile SET first_name = :first_name, last_name = :last_name, email = :email, headline = :headline, summary = :summary WHERE user_id = :user_id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':first_name' => $first_name,
            ':last_name' => $last_name,
            ':email' => $email,
            ':headline' => $headline,
            ':summary' => $summary,
            ':user_id' => $user_id,
        ]);

        $_SESSION["success"] = "Profile updated successfully";
        header("Location: index.php");
        exit;
    } catch (PDOException $e) {
        $_SESSION["error"] = "Error updating profile: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
}

// Fetch current profile data for editing
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $user_id = $_SESSION['user_id'];
    $profile_id = $_GET['id'];

    try {
        $stmt = $pdo->prepare("SELECT * FROM profile WHERE user_id = :user_id");
        $stmt->execute([':user_id' => $user_id]);
        $profile = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($profile === false) {
            $_SESSION["error"] = "Profile not found";
            header("Location: index.php");
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION["error"] = "Error fetching profile: " . $e->getMessage();
        header("Location: index.php");
        exit;
    }
} else {
    $_SESSION["error"] = "Invalid profile ID";
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html>
<head>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <title>Edit Profile</title>
</head>
<body>
    <h2>Edit Profile</h2>
    <form method="post">
        <input type="hidden" name="id" value="<?= $profile['id'] ?>">
        <p>First Name:
            <input type="text" name="first_name" size="60" value="<?= htmlspecialchars($profile['first_name']) ?>" />
        </p>
        <p>Last Name:
            <input type="text" name="last_name" size="60" value="<?= htmlspecialchars($profile['last_name']) ?>" />
        </p>
        <p>Email:
            <input type="text" name="email" size="60" value="<?= htmlspecialchars($profile['email']) ?>" />
        </p>
        <p>Headline:
            <input type="text" name="headline" size="60" value="<?= htmlspecialchars($profile['headline']) ?>" />
        </p>
        <p>Summary:
            <textarea name="summary" rows="5" cols="60"><?= htmlspecialchars($profile['summary']) ?></textarea>
        </p>

        <input type="submit" name="save" value="Save">
        <input type="submit" name="cancel" value="Cancel">
    </form>

    <script>
        $(document).ready(function() {
            var positionCount = 0;

            $("#add-position").click(function() {
                positionCount++;

                var positionEntry = '<div class="position-entry">';
                positionEntry += '<p>Year: <input type="text" name="year' + positionCount + '"></p>';
                positionEntry += '<p>Description: <textarea name="desc' + positionCount + '" rows="8" cols="80"></textarea></p>';
                positionEntry += '<button class="remove-position">Remove</button>';
                positionEntry += '</div>';

                $("#positions-container").append(positionEntry);
            });

            // Remove Position Entry
            $("#positions-container").on("click", ".remove-position", function() {
                $(this).closest(".position-entry").remove();
                positionCount--;
            });
        });
    </script>

    <?php
    if (isset($_SESSION["error"])) {
        echo '<p style="color: red">' . $_SESSION["error"] . '</p>';
        unset($_SESSION["error"]);
    }

    if (isset($_SESSION["success"])) {
        echo '<p style="color: green">' . $_SESSION["success"] . '</p>';
        unset($_SESSION["success"]);
    }
    ?>
</body>
</html>