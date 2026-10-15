<?php

$search = "";

if (isset($_GET["q"])) {
    $search = trim($_GET["q"]);
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <form action="search.php" method="get">

        <label for="search">Search</label>

        <input
            type="text"
            id="search"
            name="q"
            value="<?php echo htmlspecialchars($search); ?>"
        >

        <button type="submit">Search</button>

        <?php
        if ($search != "") {
            echo "<p>Search result for: " . htmlspecialchars($search) . "</p>";
        }
        ?>

    </form>

</body>
</html>