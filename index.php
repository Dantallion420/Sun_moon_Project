<?php
session_start();
require_once("pdo.php");

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    return;
}

// Fetch all entries from "profile" table
$stmtProfiles = $pdo->query("SELECT * FROM profile");
if ($stmtProfiles) {
    $profiles = $stmtProfiles->fetchAll(PDO::FETCH_ASSOC);
} else {
    $profiles = []; // Empty array if no profiles found
}

// Fetch all situations from "situations" table
$stmtSituations = $pdo->query("SELECT * FROM situations");
if ($stmtSituations) {
    $situations = $stmtSituations->fetchAll(PDO::FETCH_ASSOC);
} else {
    $situations = []; // Empty array if no situations found
}

$apiKey = "";
?>

<!DOCTYPE html>
<html lang="en">
<>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sun and Moon Project</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script> <!-- Ensure jQuery is included first -->
    <script src="script.js"></script> <!-- Your script that depends on jQuery -->

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <a class="navbar-brand" href="#">Sun and Moon Project</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item"><a class="nav-link" href="add.php">Add New Profile</a></li>
                <li class="nav-item"><a class="nav-link" href="add_situation.php">Add New Situation</a></li>
                <li class="nav-item"><a class="nav-link" href="logout.php">Log Out</a></li>
                <li class="nav-item"><a class="nav-link btn btn-warning text-dark" href="https://example.com">Sun & Moon Info</a></li>
            </ul>
        </div>
    </nav>

    <div class="container">
        <div class="sun-moon-container">
            <div class="sun"></div>
            <div class="moon"></div>
        </div>
        <h1>☪☾✩☽🌙🌚🌕☪☾✩☽🌙🌚🌕☪☾✩☽🌙🌚🌕☪☾✩☽🌙🌚🌕☪☾✩☽🌙🌚🌕☪☾✩☽🌙🌚🌕</h1>
        <h1>Astronomy Information</h1>

        <!-- Form for city-based data fetch -->
        <form id="city-form">
            <label for="city">Enter City Name:</label>
            <input type="text" id="city" name="city" required>
            <button type="submit">Get Astronomy Data</button>
        </form>
    <div id="city-display"></div>
        <!-- Sun and Moon Information Boxes -->
        <div class="info-box" id="sun-info">
            <h2>Sun Information</h2>
            <p id="sunrise">Sunrise: </p>
            <p id="sunset">Sunset: </p>
            <p id="solar_noon">Solar Noon: </p>
            <p id="day_length">Day Length: </p>
            <p id="sun_altitude">Sun Altitude: </p>
            <p id="sun_distance">Sun Distance: </p>
            <p id="sun_azimuth">Sun Azimuth: </p>
        </div>

        <div class="info-box" id="moon-info">
            <h2>Moon Information</h2>
            <p id="moonrise">Moonrise: </p>
            <p id="moonset">Moonset: </p>
            <p id="moon_altitude">Moon Altitude: </p>
            <p id="moon_distance">Moon Distance: </p>
            <p id="moon_azimuth">Moon Azimuth: </p>
            <p id="moon_parallactic_angle">Moon Parallactic Angle: </p>
        </div>

        <!-- Display Forum Anomalies and Situations -->
        <h2>Forum Anomalies and Situations</h2>

        <?php if (!empty($situations)): ?>
    <?php foreach ($situations as $situation): ?>
        <div class="card">
            <div class="card-body">
                <h5 class="card-title"><?= htmlentities($situation['title'] ?? '') ?></h5>
                <p class="card-text"><?= htmlentities($situation['description'] ?? '') ?></p>
                <p class="card-text"><small class="text-muted">Posted by: <?= htmlentities($situation['user'] ?? '') ?></small></p>
                <?php if (!empty($post['image_data'])): ?>
                    <img src="data:image/jpeg;base64,<?= base64_encode($post['image_data']) ?>" class="img-fluid" alt="Image">
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>
<?php else: ?>
    <p>No situations found.</p>
<?php endif; 
?>



        <!-- Display All Profiles -->
        <h2>All Profiles</h2>
        <?php if (!empty($profiles)): ?>
            <?php foreach ($profiles as $profile): ?>
                <div class="card mb-3">
                    <div class="card-body">
                        <h5 class="card-title"><?= htmlentities($profile['first_name']) ?> <?= htmlentities($profile['last_name']) ?></h5>
                        <p class="card-text">Email: <?= htmlentities($profile['email']) ?></p>
                        <p class="card-text">Headline: <?= htmlentities($profile['headline']) ?></p>
                        <p class="card-text">Summary: <?= htmlentities($profile['summary']) ?></p>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No profiles found.</p>
        <?php endif; ?>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script src="script.js"></script>
</body>
</html>
