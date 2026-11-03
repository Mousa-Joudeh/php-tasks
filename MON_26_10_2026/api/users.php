<?php
session_start();

header("Content-Type: application/json; charset=utf-8");

require_once __DIR__ . "/../db.php";
require_once __DIR__ . "/../user.php";

// Send a JSON response and stop execution.
function respond($data, $status = 200)
{
    http_response_code($status);
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Only logged-in users can access this API.
if (!isset($_SESSION["user_id"])) {
    respond(["error" => "Please log in first."], 401);
}

$method = $_SERVER["REQUEST_METHOD"];

if (!in_array($method, ["GET", "POST", "PUT", "DELETE"], true)) {
    header("Allow: GET, POST, PUT, DELETE");
    respond(["error" => "Method not allowed."], 405);
}

// Validate the ID when supplied.
$id = null;

if (isset($_GET["id"])) {
    $id = filter_var(
        $_GET["id"],
        FILTER_VALIDATE_INT,
        ["options" => ["min_range" => 1]]
    );

    if ($id === false) {
        respond(["error" => "Invalid user ID."], 400);
    }
}

if (in_array($method, ["PUT", "DELETE"], true) && $id === null) {
    respond(["error" => "User ID is required."], 400);
}

try {
    $db = (new Database())->connect();
    $userModel = new User($db);

    // Read all users or one user.
    if ($method === "GET") {
        if ($id === null) {
            respond($userModel->getAllUsers());
        }

        $user = $userModel->getUserById($id);

        if (!$user) {
            respond(["error" => "User not found."], 404);
        }

        respond($user);
    }

    // Read the JSON body for create and update.
    if ($method === "POST" || $method === "PUT") {
        $data = json_decode(file_get_contents("php://input"));

        if (json_last_error() !== JSON_ERROR_NONE || !is_object($data)) {
            respond(["error" => "Send a valid JSON object."], 400);
        }

        if (
            !isset($data->name, $data->email) ||
            !is_string($data->name) ||
            !is_string($data->email) ||
            (isset($data->password) && !is_string($data->password))
        ) {
            respond(["error" => "Invalid name, email, or password."], 422);
        }

        $name = trim($data->name);
        $email = trim($data->email);
        $password = $data->password ?? "";

        if ($name === "") {
            respond(["error" => "Name is required."], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            respond(["error" => "Enter a valid email address."], 422);
        }

        // Required on create; optional on update.
        if (
            ($method === "POST" && strlen($password) < 8) ||
            ($method === "PUT" && $password !== "" && strlen($password) < 8)
        ) {
            respond([
                "error" => "Password must be at least 8 characters."
            ], 422);
        }

        if ($method === "PUT" && !$userModel->getUserById($id)) {
            respond(["error" => "User not found."], 404);
        }

        // Reject an email already used by another user.
        $stmt = $db->prepare(
            "SELECT id FROM users WHERE email = :email LIMIT 1"
        );
        $stmt->execute([":email" => $email]);
        $existingUser = $stmt->fetch();

        if (
            $existingUser &&
            ($method === "POST" || (int) $existingUser["id"] !== $id)
        ) {
            respond(["error" => "Email is already in use."], 409);
        }

        if ($method === "POST") {
            if (!$userModel->register($name, $email, $password)) {
                respond(["error" => "Could not create user."], 500);
            }

            $newId = (int) $db->lastInsertId();

            respond([
                "message" => "User created successfully.",
                "user" => $userModel->getUserById($newId)
            ], 201);
        }

        if (!$userModel->updateUser($id, $name, $email, $password)) {
            respond(["error" => "Could not update user."], 500);
        }

        respond([
            "message" => "User updated successfully.",
            "user" => $userModel->getUserById($id)
        ]);
    }

    // Delete a user.
    if ($method === "DELETE") {
        if (!$userModel->getUserById($id)) {
            respond(["error" => "User not found."], 404);
        }

        if (!$userModel->deleteUser($id)) {
            respond(["error" => "Could not delete user."], 500);
        }

        respond(["message" => "User deleted successfully."]);
    }
} catch (PDOException $e) {
    error_log($e->getMessage());

    // Handles duplicate values enforced by a UNIQUE constraint.
    if ($e->getCode() === "23000" && (int) ($e->errorInfo[1] ?? 0) === 1062) {
        respond(["error" => "A unique value is already in use."], 409);
    }

    respond(["error" => "A database error occurred."], 500);
} catch (Throwable $e) {
    error_log($e->getMessage());
    respond(["error" => "An unexpected error occurred."], 500);
}