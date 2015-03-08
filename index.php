<?php 
	session_start();
	include('new-connection.php');
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login/Registration</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php 
		if(isset($_SESSION['errors'])) {
			foreach ($_SESSION['errors'] as $error) {
				echo "<div class='error'><p>{$error}</p></div>";
			}
			unset($_SESSION['errors']);
		}
		if(isset($_SESSION['success_message'])) {
			echo "<div class='success'>{$_SESSION['success_message']}</div>";
			unset($_SESSION['success_message']);
		}
	?>
	<div class="container">
		<div class="welcome">
			<h1>Welcome to The Wall!</h1>
			<p>Please login or register to continue.</p>
		</div>
		<div class="col-50 left">
			<h2>Login</h2>
			<form action="proccess.php" method="post">
				<input type="hidden" name="action" value="login">
				<input type="text" name="email" placeholder="Email">
				<input type="password" name="password" placeholder="Password">
				<button type="submit">Sign in</button>
			</form>
		</div>
		<div class="col-50 right">
			<h2>Register</h2>
			<form action="proccess.php" method="post">
				<input type="hidden" name="action" value="register">
				<input type="text" name="first_name" placeholder="First Name">
				<input type="text" name="last_name" placeholder="Last Name">
				<input type="text" name="email" placeholder="Email">
				<input type="password" name="password" placeholder="Password">
				<input type="password" name="confirm_password" placeholder="Confirm Password">
				<button type="submit">Register</button>
			</form>
		</div>
	</div>
</body>
</html>