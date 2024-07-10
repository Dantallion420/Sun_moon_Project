<?php
session_start();
require_once "pdo.php";

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $name = $_SESSION['user_id'];

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $first_name = filter_input(INPUT_POST, 'first_name', FILTER_SANITIZE_STRING);
        $last_name = filter_input(INPUT_POST, 'last_name', FILTER_SANITIZE_STRING);
        $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
        $headline = filter_input(INPUT_POST, 'headline', FILTER_SANITIZE_STRING);
        $summary = filter_input(INPUT_POST, 'summary', FILTER_SANITIZE_STRING);
        $moon_phase = filter_input(INPUT_POST, 'moon_phase', FILTER_SANITIZE_STRING);
        $moon_distance = filter_input(INPUT_POST, 'moon_distance', FILTER_SANITIZE_STRING);
        $sun_intensity = filter_input(INPUT_POST, 'sun_intensity', FILTER_SANITIZE_STRING);

        // Check for empty fields
        if (empty($first_name) || empty($last_name) || empty($email) || empty($headline) || empty($summary) || empty($moon_phase) || empty($moon_distance) || empty($sun_intensity)) {
            $_SESSION["error"] = "All fields are required";
            header("Location: add.php");
            return;
        }

        try {
            $sql = "INSERT INTO profile (user_id, first_name, last_name, email, headline, summary, moon_phase, moon_distance, sun_intensity) 
                    VALUES (:user_id, :first_name, :last_name, :email, :headline, :summary, :moon_phase, :moon_distance, :sun_intensity)";
            $stmt = $pdo->prepare($sql);

            $stmt->execute(array(
                ':user_id' => $user_id,
                ':first_name' => $first_name,
                ':last_name' => $last_name,
                ':email' => $email,
                ':headline' => $headline,
                ':summary' => $summary,
                ':moon_phase' => $moon_phase,
                ':moon_distance' => $moon_distance,
                ':sun_intensity' => $sun_intensity
            ));

            $profile_id = $pdo->lastInsertId();

            $rank = 1;

            while (isset($_POST['year' . $rank]) && isset($_POST['desc' . $rank])) {
                $year = $_POST['year' . $rank];
                $desc = $_POST['desc' . $rank];

                // Validate 'year' input
                if (!is_numeric($year) || !preg_match("/^\d{4}$/", $year)) {
                    $_SESSION["error"] = "Invalid year format";
                    header("Location: add.php");
                    return;
                }

                $sql = "INSERT INTO position (profile_id, rank, year, description) 
                        VALUES (:profile_id, :rank, :year, :description)";
                $stmt = $pdo->prepare($sql);

                $stmt->execute(array(
                    ':profile_id' => $profile_id,
                    ':rank' => $rank,
                    ':year' => $year,
                    ':description' => $desc
                ));

                $rank++;
            }

            $rank = 1;

            while (isset($_POST['edu_year' . $rank]) && isset($_POST['edu_school' . $rank])) {
                $edu_year = $_POST['edu_year' . $rank];
                $edu_school = $_POST['edu_school' . $rank];

                // Validate 'edu_year' input
                if (!is_numeric($edu_year) || !preg_match("/^\d{4}$/", $edu_year)) {
                    $_SESSION["error"] = "Invalid education year format";
                    header("Location: add.php");
                    return;
                }

                // Implement a function to get institution_id based on the school name
                $institution_id = getInstitutionId($edu_school); 

                $sql = "INSERT INTO education (profile_id, institution_id, rank, year) 
                        VALUES (:profile_id, :institution_id, :rank, :year)";
                $stmt = $pdo->prepare($sql);

                $stmt->execute(array(
                    ':profile_id' => $profile_id,
                    ':institution_id' => $institution_id,
                    ':rank' => $rank,
                    ':year' => $edu_year
                ));

                $rank++;
            }

            $_SESSION["success"] = "Profile, positions, and education added";
            header("Location: index.php");
            return;
        } catch (PDOException $e) {
            error_log("Database Error: " . $e->getMessage());
            $_SESSION["error"] = "Database Error: " . $e->getMessage();
            header("Location: add.php");
            return;
        }
    }
} else {
    $_SESSION["error"] = "Please log in to add a profile";
    header("Location: add.php");
    return;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Profile</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="script.js"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2 class="mt-5">Adding Profile for <?php echo htmlspecialchars($name); ?></h2>
    <?php
    if (isset($_SESSION["error"])) {
        echo '<div class="alert alert-danger">' . $_SESSION["error"] . '</div>';
        unset($_SESSION["error"]);
    }

    if (isset($_SESSION["success"])) {
        echo '<div class="alert alert-success">' . $_SESSION["success"] . '</div>';
        unset($_SESSION["success"]);
    }
    ?>
    <form method="post" action="add.php" class="mt-3">
        <div class="form-group">
            <label for="first_name">First Name</label>
            <input type="text" class="form-control" name="first_name" id="first_name" />
        </div>
        <div class="form-group">
            <label for="last_name">Last Name</label>
            <input type="text" class="form-control" name="last_name" id="last_name" />
        </div>
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" class="form-control" name="email" id="email" />
        </div>
        <div class="form-group">
            <label for="headline">Headline</label>
            <input type="text" class="form-control" name="headline" id="headline" />
        </div>
        <div class="form-group">
            <label for="summary">Summary</label>
            <textarea class="form-control" name="summary" id="summary" rows="5"></textarea>
        </div>
        <div class="form-group">
            <label for="moon_phase">Moon Phase</label>
            <input type="text" class="form-control" name="moon_phase" id="moon_phase" />
        </div>
        <div class="form-group">
            <label for="moon_distance">Moon Distance</label>
            <input type="text" class="form-control" name="moon_distance" id="moon_distance" />
        </div>
        <div class="form-group">
            <label for="sun_intensity">Sun Intensity</label>
            <input type="text" class="form-control" name="sun_intensity" id="sun_intensity" />
        </div>
        <div id="positions-container"></div>
        <button type="button" class="btn btn-secondary" id="add-position">Add Position</button>
        <div id="educations-container" class="mt-3"></div>
        <button type="button" class="btn btn-secondary" id="add-education">Add Education</button>
        <div class="form-group mt-3">
            <input type="submit" class="btn btn-primary" name="add" value="Add">
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<script>
    $(document).ready(function() {
        var positionCount = 0;

        $("#add-position").click(function() {
            positionCount++;
            var positionEntry = '<div class="form-group position-entry">';
            positionEntry += '<label for="year' + positionCount + '">Year</label>';
            positionEntry += '<input type="text" class="form-control" name="year' + positionCount + '" id="year' + positionCount + '">';
            positionEntry += '<label for="desc' + positionCount + '">Description</label>';
            positionEntry += '<textarea class="form-control" name="desc' + positionCount + '" id="desc' + positionCount + '" rows="3"></textarea>';
            positionEntry += '<button type="button" class="btn btn-danger remove-position mt-2">Remove</button>';
            positionEntry += '</div>';
            $("#positions-container").append(positionEntry);
        });

        $("#positions-container").on("click", ".remove-position", function() {
            $(this).closest(".position-entry").remove();
        });

        var educationCount = 0;

        $("#add-education").click(function() {
            educationCount++;
            var educationEntry = '<div class="form-group education-entry">';
            educationEntry += '<label for="edu_year' + educationCount + '">Year</label>';
            educationEntry += '<input type="text" class="form-control" name="edu_year' + educationCount + '" id="edu_year' + educationCount + '">';
            educationEntry += '<label for="edu_school' + educationCount + '">School</label>';
            educationEntry += '<input type="text" class="form-control" name="edu_school' + educationCount + '" id="edu_school' + educationCount + '">';
            educationEntry += '<button type="button" class="btn btn-danger remove-education mt-2">Remove</button>';
            educationEntry += '</div>';
            $("#educations-container").append(educationEntry);
        });

        $("#educations-container").on("click", ".remove-education", function() {
            $(this).closest(".education-entry").remove();
        });

        // Validate the 'year' input for positions and education
        $("#positions-container, #educations-container").on("input", "input[name^='year'], input[name^='edu_year']", function() {
            var yearInput = $(this).val();
            var yearContainer = $(this).siblings(".year-validation-message");
            if (!/^\d{4}$/.test(yearInput)) {
                yearContainer.text("Invalid year format (e.g., 2023)");
            } else {
                yearContainer.text("");
            }
        });
    });
</script>
</body>
</html>