<?php
session_start();
//var_dump($_SESSION);
date_default_timezone_set('America/Los_Angeles');
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
	<?php
	}	
	echo "<ul>";
	if(isset($_SESSION['errors']))
	{ 
		foreach ($_SESSION['errors'] as $errors) 
		{
			echo "<li>". $errors . "</li>";
		}
		unset($_SESSION['errors']);
	} 
	else if(isset($_SESSION['OK']))
	{
		foreach ($_SESSION['OK'] as $OKs) 
		{
			echo "<li>". $OKs . "</li>";
		}
		unset($_SESSION['OK']);
	}
	if(isset($_SESSION['message']))
	{
		foreach ($_SESSION['message'] as $messages)
		{
			echo "<li>" . $messages . "</li>";
		}
	}
	echo "</ul>";
	//session_unset();
	//check $_SESSION
	var_dump($_SESSION);
	unset($_SESSION['errors']);
	unset($_SESSION['message']);
	unset($_SESSION['OK']);
	?>
	<form action="validation.php" method="post">
		<input type="hidden" name="post_message" value="post_message">
		<input type="hidden" name="active_user_id" value="<?= $_SESSION['active_user_id'] ?>">
		<div <?php if(isset($_SESSION['message_flag'] )) { echo "class='" . $_SESSION['message_flag'] . "'"; }?>>
			<label for="message">Post a Message </label>
			<textarea name="message" id="message" cols="65" rows="6" maxlength="255" placeholder="I make lame jokes."></textarea>
		</div>
		<div id="button-message" class="button">
			<button type="submit" name="message_button">Post my message</button>
		</div>
	</form>
	<?php
		// index.php
		// include connection page
		require_once('connect-coding-dojo.php');
		// get a single record from the table interests joining musics
		$query = "SELECT messages.id, messages.message, messages.created_at, messages.updated_at, users.first_name, users.last_name, users.id AS user_id
		          FROM messages
		          LEFT JOIN users
		          ON messages.user_id = users.id
		          ORDER BY messages.created_at DESC";
/*
		$query2 = "SELECT * 
		           FROM comments
		           LEFT JOIN messages
		           ON comments.message_id = messages.id
		           LEFT JOIN users
		           ON messages.user_id = users.id
		           ORDER BY comments.created_at DESC";

		// since we've included the connection page, we can use the $connection variable
		$results2 = fetch_all($query2);
*/
		// since we've included the connection page, we can use the $connection variable
		$results = fetch_all($query);
		echo "<div id='messages'>";
		echo "<ul>";
		foreach($results as $row)
		{
			
			echo "<li>";
			$time = strtotime($row['created_at']);
			$created_at= date("F j Y", $time);
			$thirtymin = date("h:i F j Y", strtotime("+30 minutes", $time));
			echo "<h3>" . $row['first_name'] . " " . $row['last_name'] . " - " . $created_at . "</h3>"; 
			//echo "$created_at" . strotime($thirtymin);
			//echo strtotime(date("H:i F j Y"));
			//echo strtotime($thirtymin);
			
			if ($active_user_id == $row['user_id'] && date("H:i F j Y") < strtotime($thirtymin))
			{
				echo	"<form id='delete' action='validation.php' method='post'>";
				echo		"<input type='hidden' name='delete' value='delete'>";
				echo 		"<input type='hidden' name='thirtymin' value='".$thirtymin."'>";
				echo		"<input type='hidden' name='record' value='". $row['id'] ."'>";
				echo 		"<input type='submit' value='Delete Record'/>";
				echo	"</form>";
			}
			
			echo "<p>\"" . stripslashes($row['message']) . "\"</p>";
				$message_id = $row['id'];
				$query2 = "SELECT users.first_name, users.last_name, comments.comment, comments.created_at, comments.message_id
				          FROM comments
				          LEFT JOIN users
				          ON comments.user_id = users.id
				          WHERE comments.message_id = " . $message_id ."
				          ORDER BY comments.created_at ASC";
				// since we've included the connection page, we can use the $connection variable
				$results2 = fetch_all($query2);
				echo "<div class='comments'>";
				echo "<ul>";

				foreach($results2 as $row2)
				{
					echo "<li>";
					$time2 = strtotime($row2['created_at']);
					$created_at2= date("F j Y", $time2);
					echo "<h4>" . $row2['first_name'] . " " . $row2['last_name'] . " - " . $created_at2 . "</h4>"; 
					echo "<p>\"" . stripslashes($row2['comment']) . "\"</p>";
					echo "</li>";
				}
				echo "</ul>";
				echo "</div>";

			echo "<form action='validation.php' method='post'>";
			echo 	"<input type='hidden' name='post_comment' value='post_comment'>";
			echo 	"<input type='hidden' name='message_id' value='" . $row['id'] ."'/>";
			echo 	"<input type='hidden' name='active_user_id' value='". $_SESSION['active_user_id'] . "'/>";
			echo 	"<div>";
			echo		"<label for='comment'>Post a Comment</label>";
			echo		"<textarea name='comment' id='comment' cols='65' rows='3' maxlength='255' placeholder='You do make lame jokes.'></textarea>";
			echo	"</div>";
			echo 	"<div class='button button-comment'>";
			echo		"<button type='submit' name='comment_button'>Post my comment</button>";
			echo 	"</div>";
			echo "</form>";
			echo "</li>";
    	}
    	echo "</div>";
    	echo "</ul>";
    ?>
      	</ul>
    </div>
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
?>
</body>
</html>