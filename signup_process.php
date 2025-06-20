<?php
session_start();
include('db_connection.php');  // Include your database connection code

// Get the form data
$username = $_POST['username'];
$password = $_POST['password'];
$ip_address = $_SERVER['REMOTE_ADDR']; // Get the user's IP address

// Check if the username already exists
$query = "SELECT * FROM users WHERE username = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
	// Username already exists
	echo "Username is already taken.";
} else {
	// Insert new user into the database with IP address
	$query = "INSERT INTO users (username, password, ip_address) VALUES (?, ?, ?)";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('sss', $username, $password, $ip_address);
	$stmt->execute();

	// Log the user in
	$_SESSION['username'] = $username;
	header("Location: index.php");  // Redirect to chat (BineChat)
}
?>
