<?php
session_start();
if (!empty($_SESSION)) {
echo 'VAR DUMP for troubleshooting: ';
var_dump($_SESSION);	
} else {
echo 'We have an empty session!';
var_dump($_SESSION);
}

//let us store $_SESSION variable for clarity, we will unset variables below after Errors/OKs are displayed
if(isset($_SESSION['first_name_flag'])) { $first_name_flag = $_SESSION['first_name_flag']; };
if(isset($_SESSION['first_name'])) { $first_name = $_SESSION['first_name']; };
if(isset($_SESSION['last_name_flag'])) { $last_name_flag = $_SESSION['last_name_flag']; };
if(isset($_SESSION['last_name'])) { $last_name = $_SESSION['last_name']; };
if(isset($_SESSION['email_flag'])) { $email_flag = $_SESSION['email_flag']; };
if(isset($_SESSION['email'])) { $email = $_SESSION['email']; };
if(isset($_SESSION['password_flag'])) { $password_flag = $_SESSION['password_flag']; };
if(isset($_SESSION['confirm_password_flag'])) { $confirm_password_flag = $_SESSION['confirm_password_flag']; };
if(isset($_SESSION['date_of_birth_flag'])) { $date_of_birth_flag = $_SESSION['date_of_birth_flag']; };
if(isset($_SESSION['date_of_birth'])) { $date_of_birth = $_SESSION['date_of_birth']; };
if(isset($_SESSION['profile_picture_flag'])) { $profile_picture_flag = $_SESSION['profile_picture_flag']; };
if(isset($_SESSION['profile_picture'])) { $profile_picture_flag = $_SESSION['profile_picture']; };
if(isset($_SESSION['email_login_flag'])) { $email_login_flag = $_SESSION['email_login_flag']; };
if(isset($_SESSION['email_login'])) { $email_login = $_SESSION['email_login']; };
if(isset($_SESSION['password_login_flag'])) { $password_login_flag = $_SESSION['password_login_flag']; };

?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Login / Registration</title>
	<link rel="stylesheet" href="style.css">
</head>
<body>
	<?php
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

		session_unset();
		//check $_SESSION
		var_dump($_SESSION);
	?>
	<div id="register">
		<h3>First time here? Registration is easy!</h3>
		<form enctype="multipart/form-data" action="validation.php" method="post">
			<input type="hidden" name="register" value="register">
			<div <?php if(isset($first_name_flag)) { echo "class='" . $first_name_flag . "'"; }?>>
				<label for="first_name">First Name*</label>
				<input type="text" id="first_name" name="first_name" placeholder="First Name" value="<?php if (isset($first_name)) { echo $first_name;}?>" autofocus>
			</div>
			<div <?php if(isset($last_name_flag)) { echo "class='" . $last_name_flag. "'"; }?>>
				<label for="last_name">Last Name*</label>
				<input type="text" id="last_name" name="last_name" placeholder="Last Name" value="<?php if (isset($last_name)) { echo $last_name;}?>" >
			</div>
			<div <?php if(isset($email_flag)) { echo "class='" . $email_flag . "'"; }?>>
				<label for="email">Email*</label>
				<input type="text" id="email" name="email" placeholder="you@email.com" value="<?php if (isset($email)) { echo $email;}?>">
			</div>
			<div <?php if(isset($password_flag)) { echo "class='" . $password_flag . "'"; }?>>
				<label for="password">Password*</label>
				<input type="password" id="password" name="password" placeholder="Password" >
			</div>
			<div <?php if(isset($confirm_password_flag)) { echo "class='" . $confirm_password_flag . "'"; }?>>
				<label for="confirm_password">Confirm Password*</label>
				<input type="password" id="confirm_password" name="confirm_password" placeholder="Confirm Password">
			</div>
			<div <?php if(isset($date_of_birth_flag)) { echo "class='" . $date_of_birth_flag . "'"; }?>>
				<label for="date_of_birth">Date of Birth</label>
				<input type="text" id="date_of_birth" name="date_of_birth" maxlength="10" placeholder="MM/DD/YYYY" value="<?php if (isset($date_of_birth)) { echo $date_of_birth;}?>" >
			</div>
			<div <?php if(isset($profile_picture_flag)) { echo "class='" . $profile_picture_flag . "'"; }?> >
		    	<label for="profile_picture">Upload Profile Picture</label> 
		    	<input name="profile_picture" type="file" value="<?php if (isset($profile_picture)) { echo $profile_picture;}?>"/>
		    </div>
			<div class="button">
				<button type="submit">Submit Registration</button>
			</div>
		</form>
	</div>
	<div id="login">
		<h3>Hey! Great to see you again, Login here.</h3>
		<form action="validation.php" method="post">
			<input type="hidden" name="login" value="login">
			<div <?php if(isset($email_login_flag)) { echo "class='" . $email_login_flag . "'"; }?>>
				<label for="email">Email</label>
				<input type="text" id="email" name="email" placeholder="you@email.com" value="<?php if (isset($email_login)) { echo $email_login;}?>" autofocus>
			</div>
			<div <?php if(isset($password_login_flag)) { echo "class='" . $password_login_flag . "'"; }?>>
				<label for="password">Password</label>
				<input type="password" id="password" name="password" placeholder="Password">
			</div>
			<div class="button">
				<button type="submit">Login</button>
			</div>
		</form>
	</div>
	<?php
	//delete this php code for final form, this resets $_SESSION
	echo "<form id='start-over' action='validation.php' method='post'>";
	echo "<input type='hidden' name='unset' value='unset'>";
	echo "<input type='submit' value='Start Over!'/>";
	echo "</form>";
	?>
</body>
</html>