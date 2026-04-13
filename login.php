<?php
session_start();
include 'config.php'; // Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
	$username = trim($_POST['username']);
	$password = $_POST['password'];

	// Prepare and execute select statement
	$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
	$stmt->bind_param("s", $username);
	$stmt->execute();
	$stmt->store_result();

	if ($stmt->num_rows == 1) {
		$stmt->bind_result($id, $hashed_password);
		$stmt->fetch();

		if (password_verify($password, $hashed_password)) {
			// Password is correct, start session
			$_SESSION['user_id'] = $id;
			$_SESSION['username'] = $username;
			header("Location: dashboard.php");
			exit;
		} else {
			echo "Invalid password.";
		}
	} else {
		echo "No user found.";
	}
	$stmt->close();
}
?>
<!-- Login Form -->
<form method="post">
	<input type="text" name="username" required placeholder="Username"><br>
	<input type="password" name="password" required placeholder="Password"><br>
	<button type="submit">Login</button>
</form>
<?php
require_once 'config.php';

/*
// Role 3: Authentication, Verification & Session Management
*/

require_once 'header.php';
?>