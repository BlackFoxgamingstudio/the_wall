<?php 
	session_start();
	require('new-connection.php');

	if(isset($_POST['action']) && $_POST['action'] == 'register') 
	{
		//call to function
		register_user($_POST); 
	}

	elseif(isset($_POST['action']) && $_POST['action'] == 'login') 
	{
		//call to function
		login_user($_POST);
	}

	if(isset($_POST['action']) && $_POST['action'] == 'post_message') 
	{
		post_message($_POST, $_SESSION);
	}

	function post_message($post, $session) {
		if(!empty($post['message']) && isset($session['user_id'])) {
			$query = "INSERT INTO messages (messages.user_id, messages.message, created_at, updated_at) VALUES ('{$session['user_id']}', '{$post['message']}', NOW(), NOW())";
			var_dump($query);
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
		else { //Data is validated so can inert into database
			$query = "INSERT INTO users (first_name, last_name, email, password, created_at, updated_at) VALUES ('{$post['first_name']}', '{$post['last_name']}', '{$post['email']}', '{$post['password']}', NOW(), NOW())";
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

 ?>