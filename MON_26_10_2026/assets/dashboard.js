const apiUrl = "api/users.php";

const createForm = document.getElementById("createUserForm");
const createButton = document.getElementById("createUserButton");
const formMessage = document.getElementById("formMessage");
const usersMessage = document.getElementById("usersMessage");
const usersBody = document.getElementById("usersBody");

// Display a success or error message.
function showMessage(element, message, isError = false) {
    element.textContent = message;
    element.className = isError ? "error" : "success";
}

// Send a request to the API.
async function apiRequest(url, options = {}) {
    const response = await fetch(url, {
        credentials: "same-origin",
        ...options,
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

// Create a table cell safely.
function createCell(value) {
    const cell = document.createElement("td");
    cell.textContent = value;
    return cell;
}

// Display users in the table.
function renderUsers(users) {
    usersBody.replaceChildren();

    if (users.length === 0) {
        const row = document.createElement("tr");
        const cell = createCell("No users found.");

        cell.colSpan = 5;
        row.appendChild(cell);
        usersBody.appendChild(row);
        return;
    }

    users.forEach((user) => {
        const row = document.createElement("tr");

        row.appendChild(createCell(user.id));
        row.appendChild(createCell(user.name));
        row.appendChild(createCell(user.email));

        const deleteCell = document.createElement("td");
        const deleteButton = document.createElement("button");

        deleteButton.type = "button";
        deleteButton.textContent = "Delete";
        deleteButton.className = "delete-btn";

        deleteButton.addEventListener("click", () => {
            deleteUser(user.id, deleteButton);
        });

        deleteCell.appendChild(deleteButton);
        row.appendChild(deleteCell);

        const updateCell = document.createElement("td");
        const updateLink = document.createElement("a");

        updateLink.textContent = "Update";
        updateLink.href = `update.php?id=${encodeURIComponent(user.id)}`;

        updateCell.appendChild(updateLink);
        row.appendChild(updateCell);

        usersBody.appendChild(row);
    });
}

// Read users from the API.
async function loadUsers() {
    const users = await apiRequest(apiUrl);

    if (!Array.isArray(users)) {
        throw new Error("Unexpected users response.");
    }

    renderUsers(users);
}

// Create a new user.
createForm.addEventListener("submit", async (event) => {
    event.preventDefault();

    const formData = new FormData(createForm);

    const user = {
        name: formData.get("name").trim(),
        email: formData.get("email").trim(),
        password: formData.get("password"),
    };

    createButton.disabled = true;
    showMessage(formMessage, "Creating user...");

    try {
        const result = await apiRequest(apiUrl, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify(user),
        });

        createForm.reset();
        showMessage(formMessage, result.message);

        try {
            await loadUsers();
            showMessage(usersMessage, "");
        } catch (error) {
            showMessage(
                usersMessage,
                `User created, but the list could not refresh: ${error.message}`,
                true,
            );
        }
    } catch (error) {
        showMessage(formMessage, error.message, true);
    } finally {
        createButton.disabled = false;
    }
});

// Delete a user.
async function deleteUser(id, button) {
    if (!window.confirm("Are you sure you want to delete this user?")) {
        return;
    }

    button.disabled = true;
    showMessage(usersMessage, "Deleting user...");

    try {
        const result = await apiRequest(`${apiUrl}?id=${encodeURIComponent(id)}`, {
            method: "DELETE",
        });

        showMessage(usersMessage, result.message);

        try {
            await loadUsers();
        } catch (error) {
            showMessage(
                usersMessage,
                `User deleted, but the list could not refresh: ${error.message}`,
                true,
            );
        }
    } catch (error) {
        showMessage(usersMessage, error.message, true);
    } finally {
        button.disabled = false;
    }
}

// Load users when the page opens.
async function initialize() {
    showMessage(usersMessage, "Loading users...");

    try {
        await loadUsers();
        showMessage(usersMessage, "");
    } catch (error) {
        showMessage(usersMessage, error.message, true);
    }
}

initialize();
