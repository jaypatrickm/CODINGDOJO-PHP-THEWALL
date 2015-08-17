<?php
session_start();
//var_dump($_SESSION);
if (!isset($_SESSION['active_user_id'])){
	header('location: index.php');
}
else 
{
	$active_user_id = $_SESSION['active_user_id'];
	require_once('connect-coding-dojo.php');
	$query = "SELECT *
			  FROM users
			  WHERE users.id = {$active_user_id}";
	$results = fetch_all($query);
	foreach ($results as $row) 
	{
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login / Registration - Logged In as <?php $row['first_name'] . ' ' . $row['last_name']; ?></title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<h1>Welcome <?= $row['first_name'] ?>,</h1>
	<p>You are looking great today.</p>
	<form action="validation.php" method="post">
		<input type="hidden" name="post_message" value="post_message">
		<div <?php if(isset($_SESSION['message_flag'] )) { echo "class='" . $_SESSION['message_flag'] . "'"; }?>>
			<label for="message">Post a Message </label>
			<textarea name="message" id="message" cols="65" rows="6" maxlength="255" placeholder="I make lame jokes."></textarea>
		</div>
		<div id="button-message" class="button">
			<button type="submit" name="message_button">Post my message</button>
		</div>
	</form>
	<form action="validation.php" method="post">
		<div class="button">
			<button type="submit">Log Out</button>
		</div>
	</form>
	<form id="start-over" action="message_validation.php" method="post">
		<input type="hidden" name="unset" value="unset">
		<button type="submit" value="start_over">Start Over</button>
	</form>
<?php
	}
}
?>
</body>
</html>