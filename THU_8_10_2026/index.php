<?php
session_start();

$name = "";
$email = "";
$mobile = "";
$governorate = "";
$track = "";
$skills = [];
$message = "";
$agree = false;

$errors = [];
$success = false;

$governorates = ["Amman", "Irbid", "Aqaba", "Zarqa"];
$tracks = ["Full Stack", "Frontend", "Backend"];
$availableSkills = ["HTML", "CSS", "JavaScript"];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mobile = trim($_POST["mobile"] ?? "");
    $governorate = trim($_POST["governorate"] ?? "");
    $track = trim($_POST["track"] ?? "");
    $message = trim($_POST["message"] ?? "");

    $skills = $_POST["skills"] ?? [];
    $agree = isset($_POST["agree"]);

    if ($name == "") {
        $errors["name"] = "Full name is required";
    }

    if ($email == "") {
        $errors["email"] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors["email"] = "Invalid email format";
    }

    if ($mobile == "") {
        $errors["mobile"] = "Mobile number is required";
    } elseif (!preg_match("/^07[789][0-9]{7}$/", $mobile)) {
        $errors["mobile"] = "Enter a valid Jordanian mobile number";
    }

    if (!in_array($governorate, $governorates)) {
        $errors["governorate"] = "Please select a governorate";
    }

    if (!in_array($track, $tracks)) {
        $errors["track"] = "Please select a track";
    }

    if (!is_array($skills)) {
        $skills = [];
    }

    $skills = array_map("trim", $skills);

    if (count($skills) == 0) {
        $errors["skills"] = "Please select at least one skill";
    } elseif (count(array_diff($skills, $availableSkills)) > 0) {
        $errors["skills"] = "Invalid skill selected";
    }

    if (!$agree) {
        $errors["agree"] = "You must accept the terms";
    }

    if (empty($errors)) {
        $success = true;

        $registration = new stdClass();

        $registration->name = $name;
        $registration->email = $email;
        $registration->mobile = $mobile;
        $registration->governorate = $governorate;
        $registration->track = $track;
        $registration->skills = $skills;
        $registration->message = $message;

        $_SESSION["registration"] = $registration;

        setcookie("username", $name, time() + (86400 * 30), "/");

        header("Location: profile.php");
        exit;
    }
}

function clean($value) {
    return htmlspecialchars($value, ENT_QUOTES, "UTF-8");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <?php if (isset($_COOKIE["username"])): ?>

        <p class="welcome">
            Welcome back,
            <?php echo clean($_COOKIE["username"]); ?>!
        </p>

    <?php endif; ?>

    <form action="" method="post" novalidate>

        <h2>Academy Registration</h2>

        <?php if ($success): ?>
            <p class="success">Registration successful!</p>
        <?php endif; ?>

        <label for="name">Full Name</label>
        <input
            type="text"
            id="name"
            name="name"
            value="<?php echo clean($name); ?>"
        >
        <span class="error">
            <?php echo $errors["name"] ?? ""; ?>
        </span>

        <label for="email">Email</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?php echo clean($email); ?>"
        >
        <span class="error">
            <?php echo $errors["email"] ?? ""; ?>
        </span>

        <label for="mobile">Mobile</label>
        <input
            type="tel"
            id="mobile"
            name="mobile"
            placeholder="0791234567"
            value="<?php echo clean($mobile); ?>"
        >
        <span class="error">
            <?php echo $errors["mobile"] ?? ""; ?>
        </span>

        <label for="governorate">Governorate</label>
        <select id="governorate" name="governorate">
            <option value="">Choose a governorate</option>

            <?php foreach ($governorates as $city): ?>
                <option
                    value="<?php echo clean($city); ?>"
                    <?php echo $governorate == $city ? "selected" : ""; ?>
                >
                    <?php echo clean($city); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <span class="error">
            <?php echo $errors["governorate"] ?? ""; ?>
        </span>

        <label>Track</label>

        <?php foreach ($tracks as $item): ?>
            <label class="inline">
                <input
                    type="radio"
                    name="track"
                    value="<?php echo clean($item); ?>"
                    <?php echo $track == $item ? "checked" : ""; ?>
                >
                <?php echo clean($item); ?>
            </label>
        <?php endforeach; ?>

        <span class="error">
            <?php echo $errors["track"] ?? ""; ?>
        </span>

        <label>Skills you already have</label>

        <?php foreach ($availableSkills as $skill): ?>
            <label class="inline">
                <input
                    type="checkbox"
                    name="skills[]"
                    value="<?php echo clean($skill); ?>"
                    <?php echo in_array($skill, $skills) ? "checked" : ""; ?>
                >
                <?php echo clean($skill); ?>
            </label>
        <?php endforeach; ?>

        <span class="error">
            <?php echo $errors["skills"] ?? ""; ?>
        </span>

        <label for="message">Why do you want to join? (optional)</label>
        <textarea id="message" name="message" rows="4"><?php echo clean($message); ?></textarea>

        <label class="inline terms">
            <input
                type="checkbox"
                name="agree"
                <?php echo $agree ? "checked" : ""; ?>
            >
            I agree to the academy terms
        </label>

        <span class="error">
            <?php echo $errors["agree"] ?? ""; ?>
        </span>

        <button type="submit">Register</button>

    </form>

</body>
</html>