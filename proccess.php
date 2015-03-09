<?php 
	session_start();
	require('new-connection.php');

	if(isset($_POST['action']) && $_POST['action'] == 'register') {
		//call to function
		register_user($_POST); 
	}

	elseif(isset($_POST['action']) && $_POST['action'] == 'login') {
		//call to function
		login_user($_POST);
	}

	if(isset($_POST['action']) && $_POST['action'] == 'post_message') {
		post_message($_POST, $_SESSION);
	}

	if(isset($_POST['action']) && $_POST['action'] == 'post_comment') {
		post_comment($_POST, $_SESSION);
	}
 
	function register_user($post) {
		
		$_SESSION['errors'] = array();

		if(empty($post['first_name']))
		{
			$_SESSION['errors'][] = "First name can't be blank.";
		}
		if(empty($post['last_name']))
		{
			$_SESSION['errors'][] = "Last name can't be blank.";
		}
		if(empty($post['password']))
		{
			$_SESSION['errors'][] = "Password field is required!";
		}
		if(empty($post['confirm_password']))
		{
			$_SESSION['errors'][] = "You must confirm your password!";
		}
		if(!empty($post['password']) && !empty($post['confirm_password']) && $post['password'] !== $post['confirm_password']) {
			$_SESSION['errors'][] = "Your passwords don't match!";
		}
		if(!empty($post['password']) && !empty($post['confirm_password']) && $post['password'] == $post['confirm_password'] && strlen($post['password']) < 6) {
			$_SESSION['errors'][] = "Your password must be longer than 6 characters!";
		}

		if(!filter_var($post['email'], FILTER_VALIDATE_EMAIL)) {
			$_SESSION['errors'][] = "Your email address is invalid.";
		}
		
		// Validation Checks COMPLETE
		
		if(count($_SESSION['errors']) > 0) {
			header('location: index.php');
			die();
		}
		else { //Data is validated so we can insert user info into database
			$firstName = escape_this_string($post['first_name']);
			$lastName = escape_this_string($post['last_name']);
			$email = escape_this_string($post['email']);
			$password = escape_this_string($post['password']);
			$query = "INSERT INTO users (first_name, last_name, email, password, created_at) VALUES ('{$firstName}', '{$lastName}', '{$email}', '{$password}', NOW())";
			$_SESSION['success_message'] = "Account successfully created";
			run_mysql_query($query);
			header('location: index.php');
			die();
		}
	}

	function login_user($post) {
		$query = "SELECT * FROM users WHERE users.password = '{$post['password']}' AND users.email = '{$post['email']}'";
		$user = fetch_all($query); //Grabs user with the above credentials
		if(count($user) > 0) {
			$_SESSION['user_id'] = $user[0]['id'];
			$_SESSION['first_name'] = $user[0]['first_name'];
			$_SESSION['last_name'] = $user[0]['last_name'];
			$_SESSION['user_name'] = $_SESSION['first_name'] . ' ' . $_SESSION['last_name'];
			$_SESSION['member_since'] = $user[0]['created_at'];
			$_SESSION['email'] = $user[0]['email'];
			$_SESSION['logged_in'] = TRUE;
			header('location: wall.php');
			die();
		}
		else {
			$_SESSION['errors'][] = "Oops! Something went wrong! Please check your email and password and try again.";
			header('location: index.php');
			die();
		}
	}

	function post_message($post, $session) {
		if(!empty($post['message']) && isset($session['user_id'])) {
			$message = escape_this_string($post['message']);
			$query = "INSERT INTO messages (messages.user_id, messages.message, messages.created_at) VALUES ('{$session['user_id']}', '{$message}', NOW())";
			run_mysql_query($query);
			$_SESSION['success_message'] = "Your message was added successfully!";
			header('location: wall.php');
			die();
		}
		else {
			$_SESSION['errors'] = "Oops! Something went wrong. Your message wasn't posted.";
			header('location: wall.php');
			die();
		}
	}

	function post_comment($post, $session) {
		if(!empty($post['comment']) && isset($session['user_id'])) {
			$comment = escape_this_string($post['comment']);
			$query = "INSERT INTO comments (user_id, comment, message_id, created_at) VALUES ('{$session['user_id']}', '{$comment}', '{$post['message_id']}', NOW())";
			run_mysql_query($query);			
			$_SESSION['success_message'] = "Yay! Your comment was added successfully!";
			header('location: wall.php');
			die();
		}
		else {
			$_SESSION['errors'] = "Oops! Something went wrong. Your comment wasn't posted.";
			header('location: wall.php');
			die();
		}
	}

?>