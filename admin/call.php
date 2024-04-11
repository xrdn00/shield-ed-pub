<?php
$host = "localhost";
$dbname = "login_db";
$username = "root";
$password = "";

$mysqli = new mysqli($host, $username, $password, $dbname);

if ($mysqli->connect_errno) {
    die("Connection error: " . $mysqli->connect_error);
}

// Example query to fetch data from the "users" table
$sql = "SELECT id, firstname, lastname,fcm FROM users WHERE id = ?"; // Modify this query as needed
$stmt = $mysqli->prepare($sql);
$id = $_GET['id']; // Assuming you're passing the ID via the URL parameter

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    echo json_encode($user); // Return the user data as JSON
} else {
    echo json_encode(["error" => "User not found"]); // Handle the case when no user is found
}
?>
