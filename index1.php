<?php
require_once "pdo.php";
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>MOONS AND SUN Resume Registry</title>
    <style>
        .container {
            width: 50%;
            margin: auto;
            text-align: center;
        }
        h2 {
            color: #333;
        }
        a {
            text-decoration: none;
            color: #0066cc;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Register the Moon Phase and Sun Intensity</h2>
    <h2>Welcome to the MOONS AND SUN Resume Registry Database</h2>
    <p><a href="login.php">Please log in</a></p>
    <p>Attempt to <a href="add.php">add data</a> without logging in</p>
</div>

</body>
</html>