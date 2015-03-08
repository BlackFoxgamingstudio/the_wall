<?php 
	session_start();
	if(!isset($_SESSION['logged_in'])) {
		$_SESSION['errors'][] = "Oops! You have to be logged in to view this page.";
			header('location: index.php');
			die();
	}
	if(isset($_SESSION['errors'])) {
		echo "<div class='error'>{$_SESSION['errors']}</div>";
		unset($_SESSION['errors']);
	}
	if(isset($_SESSION['success_message'])) {
		echo "<div class='success'>{$_SESSION['success_message']}</div>";
		unset($_SESSION['success_message']);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>The Wall</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	
	<div class="header">
		<h3>Coding Dojo Wall</h3>
		<p> Welcome <?php echo $_SESSION['first_name']; ?></p>
		<a href='logout.php'><p>Logout</p></a>
	</div>
	<div class="body">
		<h4>Post a message</h4>
		<form action="proccess.php" method="post">
			<textarea type="text" class="message" name="message"></textarea>
			<input type="hidden" name="action" value="post_message">
			<button class="align-right" type="submit">Post a message</button>
		</form>
	</div>
</body>
</html>