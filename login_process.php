<?php
session_start();
include('db_connection.php');

$username = $_POST['username'];
$password = $_POST['password'];

// Query to check if the user exists and is not banned
$query = "SELECT * FROM users WHERE username = ? AND password = ? AND banned = 0";
$stmt = $conn->prepare($query);
$stmt->bind_param('ss', $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
	// User found and not banned: Log the user in
	$_SESSION['username'] = $username;
	header("Location: index.php");
	exit;
} else {
	// Failed login or banned user — redirect with an error message
	// Check if the user exists but is banned
	$query = "SELECT * FROM users WHERE username = ? AND password = ?";
	$stmt = $conn->prepare($query);
	$stmt->bind_param('ss', $username, $password);
	$stmt->execute();
	$result = $stmt->get_result();

	if ($result->num_rows > 0) {
		// User exists but is banned
		echo "Your account is banned. Please contact support.";
	} else {
		// Failed login — return to login.php with original values
		echo '
			<form id="redirectForm" action="login.php?error=1" method="post">
				<input type="hidden" name="username" value="' . htmlspecialchars($username) . '">
				<input type="hidden" name="password" value="' . htmlspecialchars($password) . '">
			</form>
			<script>
				document.getElementById("redirectForm").submit();
			</script>
		';
	}
	exit;
}
?>
