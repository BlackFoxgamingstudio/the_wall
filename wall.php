<?php 
	session_start();
	require('new-connection.php');
	if(!isset($_SESSION['logged_in'])) {
		$_SESSION['errors'][] = "Oops! You have to be logged in to view this page.";
			header('location: index.php');
			die();
	}
	if(isset($_SESSION['errors'])) {
		echo "<div class='alert error'>{$_SESSION['errors']}</div>";
		unset($_SESSION['errors']);
	}
	if(isset($_SESSION['success_message'])) {
		echo "<div class='alert success'>{$_SESSION['success_message']}</div>";
		unset($_SESSION['success_message']);
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>The Wall</title>
	<link rel="stylesheet" href="style.css">
	<!-- jQuery 1.11.2 -->
	<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
</head>
<body>
	<script>
		$('div.alert').delay('3500').fadeOut();
	</script>
	<div class="header">
		<h3>Coding Dojo Wall</h3>
		<p> Welcome <a href="profile.php"><?php echo $_SESSION['first_name']; ?></a>!</p>
		<a href='logout.php'><p>Logout</p></a>
	</div>
	<div class="body">
		<h4>Post a message</h4>
		<form action="proccess.php" method="post">
			<textarea type="text" class="message" name="message"></textarea>
			<input type="hidden" name="action" value="post_message">
			<div class="align-right">
				<button class="align-right" type="submit">Post a message</button>
			</div>
		</form>
		<!-- Display existing messages -->
		<?php 
			$query = "SELECT CONCAT_WS(' ', users.first_name, users.last_name) AS user_name, messages.message AS message, messages.id AS id, DATE_FORMAT(messages.created_at,'%M %e %Y %h:%i:%s %p') AS created_at FROM users JOIN messages ON users.id = messages.user_id ORDER BY created_at DESC";
			$messages = fetch_all($query);
			foreach ($messages as $message) {
				echo "<div class='messages'><h4> Message from {$message['user_name']} ({$message['created_at']})</h4>";
				echo "<p class='message'> {$message['message']} </p>";
		?>
		<!-- Display existing comments -->
		<div class="comment">
			<?php 
				// Fetches comments from database
				$query = "SELECT CONCAT_WS(' ', users.first_name, users.last_name) AS user_name, comments.message_id, comments.comment, DATE_FORMAT(comments.created_at,'%M %e %Y %h:%i:%s %p') AS created_at FROM comments LEFT JOIN users ON users.id = comments.user_id WHERE comments.message_id = {$message['id']}";
				$comments = fetch_all($query);
				// Loops through comments
				foreach ($comments as $comment) {
					echo "<div class='comments'><h4> Comment from {$comment['user_name']} ({$comment['created_at']})</h4>";
				echo "<p class='comments'> {$comment['comment']} </p></div>";
				}
			?>
			<!-- Display comment box below each message -->
			<form action="proccess.php" method="post">
				<h5>Post a comment</h5>
				<textarea type="text" class="comment" name="comment"></textarea>
				<input type="hidden" name="action" value="post_comment">
				<input type="hidden" name="message_id" value="<?= $message['id'] ?>">
				<div class="align-right">
					<button class="align-right" type="submit">Post a comment</button>
				</div>
			</form>
		</div></div><?php
			}
		?>
	</div>
</body>
</html>