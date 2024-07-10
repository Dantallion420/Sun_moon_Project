<?php
require_once "pdo.php"; // Include PDO configuration
session_start();

if (isset($_POST['email']) && isset($_POST['pass'])) {
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $salt = 'XyZzy12*_';

    if (empty($email) || empty($password)) {
        $_SESSION['error'] = "User name and password are required";
        header("Location: login.php");
        return;
    }

    $stored_hash = '1a52e17fa899cf40fb04cfc42e6352f1';
    $check = hash('md5', $salt . $password);

    // Check if the user exists in the database
    $stmt = $pdo->prepare("SELECT user_id, name FROM users WHERE email = :email_field");
    $stmt->execute(array(':email_field' => $email));
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row !== false) {
        // User exists, verify password
        if ($check == $stored_hash) {
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_id'] = $row['user_id'];
            header("Location: index.php");
            return;
        } else {
            $_SESSION['error'] = "Incorrect password";
            header("Location: login.php");
            return;
        }
    } else {
        // User does not exist, register them
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (email, password_hash, name) VALUES (:email, :password_hash, :name)");
            $stmt->execute(array(
                ':email' => $email,
                ':password_hash' => $hashed_password,
                ':name' => '' // You can set a default name or leave it empty
            ));

            $new_user_id = $pdo->lastInsertId();
            $_SESSION['name'] = ''; // Set default name if desired
            $_SESSION['user_id'] = $new_user_id;
            $_SESSION['success'] = "User registered and logged in successfully!";
            header("Location: index.php");
            return;
        } catch (PDOException $e) {
            $_SESSION['error'] = "Database error: " . $e->getMessage();
            header("Location: login.php");
            return;
        }
    }
}

?>


<!DOCTYPE html>
<html>
<head>
<link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="style.css">
</head>

<body>
</head>
	<body>
		<div class="container">
			<h1>Please Log In</h1>
<?php


if ( isset($_SESSION['error']) ) {
    echo('<p style="color: red;">'.htmlentities($_SESSION['error'])."</p>\n");
    unset($_SESSION['error']);
}
?>
			<form method="POST">
            User Name <input type="text" name="email"><br/>

				
				<br/>
				<label for="id_1723">Password</label>
				<input type="text" name="pass" id="id_1723"/>
				<br/>
				<input type="submit" value="Log In"/>
				<input type="submit" name="cancel" value="Cancel"/>
			</form>
			<p>
For a password hint, view source and find a password hint
in the HTML comments.

			</p>
</form>
</body>
</html>
