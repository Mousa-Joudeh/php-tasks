<?php

session_start();

if (!isset($_SESSION["registration"])) {
    header("Location: index.php");
    exit;
}

$user = $_SESSION["registration"];

function clean($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Details</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="profile">

    <h2>Registration Successful!</h2>

    <p class="success">
        Welcome <?php echo clean($user->name); ?>!
        Your registration has been completed successfully.
    </p>

    <p><strong>Full Name:</strong>
        <?php echo clean($user->name); ?>
    </p>

    <p><strong>Email:</strong>
        <?php echo clean($user->email); ?>
    </p>

    <p><strong>Mobile:</strong>
        <?php echo clean($user->mobile); ?>
    </p>

    <p><strong>Governorate:</strong>
        <?php echo clean($user->governorate); ?>
    </p>

    <p><strong>Track:</strong>
        <?php echo clean($user->track); ?>
    </p>

    <p><strong>Skills:</strong>
        <?php echo clean(implode(", ", $user->skills)); ?>
    </p>

    <p><strong>Message:</strong>
        <?php echo clean($user->message); ?>
    </p>

    <a href="index.php">Back to Registration</a>

</div>

</body>
</html>