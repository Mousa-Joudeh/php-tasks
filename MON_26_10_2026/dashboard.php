<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>

    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f2f2f2;
        margin: 0;
        padding: 30px;
    }

    .container {
        max-width: 900px;
        margin: auto;
    }

    header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 15px;
    }

    form {
        background: white;
        padding: 20px;
        border-radius: 10px;
        margin-bottom: 25px;
    }

    label {
        display: block;
        margin-top: 15px;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-top: 5px;
        box-sizing: border-box;
    }

    button {
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        background: #0d1c42;
        color: white;
    }

    button:disabled {
        opacity: 0.6;
        cursor: wait;
    }

    form button {
        margin-top: 20px;
    }

    .delete-btn {
        background: #c62828;
    }

    .table-container {
        overflow-x: auto;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        background: white;
    }

    th,
    td {
        padding: 12px;
        border: 1px solid #ddd;
        text-align: left;
    }

    th {
        background: #0d1c42;
        color: white;
    }

    .error {
        color: #c62828;
    }

    .success {
        color: #176b32;
    }
    </style>
</head>

<body>
    <div class="container">
        <header>
            <h1>Users Dashboard</h1>
            <a href="logout.php">Logout</a>
        </header>

        <h2>Add New User</h2>

        <form id="createUserForm">
            <div>
                <label for="name">Name</label>
                <input type="text" id="name" name="name" autocomplete="name" required>
            </div>

            <div>
                <label for="email">Email</label>
                <input type="email" id="email" name="email" autocomplete="email" required>
            </div>

            <div>
                <label for="password">Password</label>
                <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required>
            </div>

            <button type="submit" id="createUserButton">
                Create User
            </button>
        </form>

        <p id="formMessage" role="status" aria-live="polite"></p>

        <h2>Users</h2>

        <p id="usersMessage" role="status" aria-live="polite"></p>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Delete</th>
                        <th>Update</th>
                    </tr>
                </thead>

                <tbody id="usersBody"></tbody>
            </table>
        </div>
    </div>

    <script src="assets/dashboard.js"></script>
</body>

</html>