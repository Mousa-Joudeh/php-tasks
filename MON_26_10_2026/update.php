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
    <title>Update User</title>

    <style>
    body {
        font-family: Arial, sans-serif;
        background: #f2f2f2;
        padding: 30px;
    }

    .container {
        max-width: 500px;
        margin: auto;
        background: white;
        padding: 25px;
        border-radius: 10px;
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
        margin-top: 20px;
        padding: 10px 15px;
        background: #0d1c42;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
    }

    button:disabled {
        opacity: 0.6;
        cursor: wait;
    }

    .error {
        color: #c62828;
    }

    .success {
        color: #176b32;
    }

    small {
        display: block;
        margin-top: 5px;
        color: #555;
    }
    </style>
</head>

<body>
    <div class="container">
        <h1>Update User</h1>

        <p id="message" role="status" aria-live="polite"></p>

        <form id="updateUserForm" hidden>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" autocomplete="name" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" autocomplete="email" required>

            <label for="password">New Password</label>
            <input type="password" id="password" name="password" autocomplete="new-password" minlength="8"
                aria-describedby="passwordHelp">
            <small id="passwordHelp">
                Leave blank to keep the current password.
            </small>

            <button type="submit" id="saveButton">
                Save Changes
            </button>
        </form>

        <p><a href="dashboard.php">Back to Dashboard</a></p>
    </div>

    <script>
    const form = document.getElementById("updateUserForm");
    const message = document.getElementById("message");
    const saveButton = document.getElementById("saveButton");

    const nameInput = document.getElementById("name");
    const emailInput = document.getElementById("email");
    const passwordInput = document.getElementById("password");

    const params = new URLSearchParams(window.location.search);
    const userId = params.get("id");

    const apiUrl = `api/users.php?id=${encodeURIComponent(userId)}`;

    function showMessage(text, isError = false) {
        message.textContent = text;
        message.className = isError ? "error" : "success";
    }

    async function apiRequest(options = {}) {
        const response = await fetch(apiUrl, {
            credentials: "same-origin",
            ...options
        });

        if (response.status === 401) {
            window.location.href = "login.php";
            throw new Error("Please log in first.");
        }

        let data;

        try {
            data = await response.json();
        } catch {
            throw new Error("The server did not return valid JSON.");
        }

        if (!response.ok) {
            throw new Error(data.error || "Request failed.");
        }

        return data;
    }

    // Read the selected user's information.
    async function loadUser() {
        if (!userId || !/^[1-9]\d*$/.test(userId)) {
            showMessage("Invalid user ID.", true);
            return;
        }

        showMessage("Loading user...");

        try {
            const user = await apiRequest();

            nameInput.value = user.name;
            emailInput.value = user.email;

            form.hidden = false;
            showMessage("");
        } catch (error) {
            showMessage(error.message, true);
        }
    }

    // Save changes through the API.
    form.addEventListener("submit", async (event) => {
        event.preventDefault();

        const user = {
            name: nameInput.value.trim(),
            email: emailInput.value.trim(),
            password: passwordInput.value
        };

        saveButton.disabled = true;
        showMessage("Saving changes...");

        try {
            const result = await apiRequest({
                method: "PUT",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify(user)
            });

            passwordInput.value = "";
            showMessage(result.message);
        } catch (error) {
            showMessage(error.message, true);
        } finally {
            saveButton.disabled = false;
        }
    });

    loadUser();
    </script>
</body>

</html>