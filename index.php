<?php
$hostname = "info.tm.edu.ro:3366";
$username = "SRata";
$password = "t4Fu3u";
$database = "SRata";

// Connection
$conn = mysqli_connect(
    $hostname,
    $username,
    $password,
    $database
);

try {
    $options = [
        PDO::ATTR_EMULATE_PREPARES => false, // Disable emulation mode for "real" prepared statements
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Disable errors in the form of exceptions
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, // Make the default fetch be an associative array
    ];
    $pdo = new PDO("mysql:host=$hostname;dbname=$database;charset=utf8mb4", $username, $password, $options);
} catch (Exception $e) {
    error_log($e->getMessage());
    exit('Something bad happened');
}


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./style.css">
    <link rel="stylesheet" href="./normalize.css">
    <title>Document</title>
</head>

<body>
    <section class="landing-page">
        <div class="text-half">
            <h1 class="title">PAC-MAN</h1>
            <p>
                The game
                <span class="emphasis gold">
                    🎮 PAC-MAN
                </span>
                has generated
                <span class="emphasis green">
                    💲 more than 14 billion dollars in revenue</span>
                selling
                <span class="emphasis blue">
                    🔝 more than 43 million units world wide </span>
                and is considered
                <span class="emphasis purple">
                    🌟 the greatest video game of all time!
                </span>
            </p>
            <button class="vote" type="submit"
                onClick="document.getElementById('vote-results-and-submissions').scrollIntoView();">
                💡 BUT IS IT STILL? 🤔
            </button>
        </div>
        <div class="image-half">
            <img src="./pacman.png" alt="pacman">
        </div>
    </section>

    <section class="vote-results-and-submissions" id="vote-results-and-submissions">
        <div class="vote-results">
            <?php
            $pac_man_review_rows = $conn->query("SELECT * FROM pacman_reviews");
            while ($pac_man_review = $pac_man_review_rows->fetch_assoc()) {
                ?>
                <div class="review-card">
                    <div class='reviewer-details'>
                        <span class="reviewer">
                            <?= $pac_man_review["reviewer"] ?>
                        </span>
                        -
                        <span class="reviewer_email">
                            <?= $pac_man_review["reviewer_email"] ?>
                        </span>
                    </div>
                    <div class="review">
                        <?= $pac_man_review["review"] ?>
                    </div>
                    <div class="stars">
                        Stars:
                        <?= $pac_man_review["stars"] ?>⭐
                    </div>
                </div>

            <?php } ?>
        </div>

        <div class="vote-submissions">
            <form action="" method="post">
                <!-- Prevent implicit submission of the form -->
                <button type="submit" disabled style="display: none" aria-hidden="true"></button>
                <input type="text" name="review">
                <input type="text" name="reviewer">
                <input type="text" name="reviewer_email">
                <input type="number" name="stars">
                <button type="submit" value="submit" name="submit">Submit</button>
            </form>

            <?php

            if (isset($_POST["submit"])) {
                $review = $_POST["review"];
                $reviewer = $_POST["reviewer"];
                $reviewer_email = $_POST["reviewer_email"];
                $stars = $_POST["stars"];

                $insert_into_pac_man = $pdo->prepare("INSERT INTO pacman_reviews(review, reviewer, reviewer_email, stars) VALUES (:review, :reviewer, :reviewer_email, :stars)");
                $insert_into_pac_man->bindParam(":review", $review);
                $insert_into_pac_man->bindParam(":reviewer", $reviewer);
                $insert_into_pac_man->bindParam(":reviewer_email", $reviewer_email);
                $insert_into_pac_man->bindParam(":stars", $stars);

                if ($insert_into_pac_man->execute()) {
                    echo "New record created successfully";
                } else {
                    echo "Unable to create record";
                }
            }
            ?>
        </div>

    </section>
</body>
<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>

</html>