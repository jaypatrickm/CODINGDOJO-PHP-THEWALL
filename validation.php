<?php
//start session
session_start();
date_default_timezone_set('America/Los_Angeles');
//unset/destroy/empty session post
if(isset($_POST['unset']) && $_POST['unset'] == "unset")
{ 
	session_destroy();
	$_SESSION = array();
	header('location: index.php');
}

//verify with post, which form we are recieving posts from
//then decide where we should go, register, login, or exit.
//inside we will pass the entire post through to the function
if(isset($_POST['register']) && $_POST['register'] == "register")
{ 
	register_validation($_POST);
} 
else if (isset($_POST['login']) && $_POST['login'] == "login")
{ 
	login_validation($_POST);
}
else if (isset($_POST['post_message']) && $_POST['post_message'] == "post_message")
{
	message_validation($_POST);
}
else if (isset($_POST['post_comment']) && $_POST['post_comment'] == "post_comment")
{
	comment_validation($_POST);
}
else if (isset($_POST['delete']) && $_POST['delete'] == "delete")
{ 
	delete_message($_POST);
}
else
{
	session_destroy();
	header('location:index.php');
}

//register validaiton
function register_validation($post)
{
	//set error flags to 0
	$error_flags = 0;

	//first_name errors
	if(!empty($_POST['first_name']))
	{
		$first_name = trim($_POST['first_name']);
		$_SESSION['first_name'] = $first_name;
		if(preg_match( "/^[a-zA-Z ]*$/", $first_name)) 
		{
			$_SESSION['first_name_flag'] = 'good';
		} 
		else
		{
			$_SESSION['errors'][] = 'First Name :'. $first_name . ' is invalid. First name cannot contain any numerical values or special characters, please enter a valid name using only alphanumeric characters.';
			$_SESSION['first_name_flag'] = 'bad';
			$error_flags++;
		}
	} 
	else
	{
		$_SESSION['errors'][] = 'First name field is empty. Please enter your first name.';
		$_SESSION['first_name_flag'] = 'bad';
		$error_flags++;
	}
	
	//last_name errors
	if(!empty($_POST['last_name']))
	{
		$last_name = trim($_POST['last_name']);
		$_SESSION['last_name'] = $last_name;
		if( preg_match( "/^[a-zA-Z ]*$/", $last_name)) 
		{
			$_SESSION['last_name_flag'] = 'good';
		} 
		else
		{
			$_SESSION['errors'][] = 'Last Name :'. $last_name . ' is invalid. Last name cannot contain any numerical values or special characters, please enter a valid name using only alphanumeric characters.';
			$_SESSION['last_name_flag'] = 'bad';
			$error_flags++;
		}
	} 
	else
	{
		$_SESSION['errors'][] = 'Last name field is empty. Please enter your last name.';
		$_SESSION['last_name_flag'] = 'bad';
		$error_flags++;		
	}

	//email errors
	if(!empty($_POST['email']))
	{
		$email = trim($_POST['email']);
		
		//check if email is in use
		//include connection page
		require_once('connect-coding-dojo.php');
		$esc_email= mysqli_real_escape_string($connection, $email);
		$email_query = "SELECT users.email FROM users WHERE users.email = '{$esc_email}'";
		$user = fetch($email_query);
		if (!empty($user))
		{
			$_SESSION['errors'][] = 'Email is in use. Please try a different email.';
			$_SESSION['email_flag'] = 'bad';
			$error_flags++;
		}
		else 
		{
			$_SESSION['email'] = $email;
			if(!filter_var($email, FILTER_VALIDATE_EMAIL))
			{
				$_SESSION['errors'][] = 'Email :'. $email . ' is invalid. Please provide a valid email, like speros@codindojo.com or chris@gmail.com.';
				$_SESSION['email_flag'] = 'bad';
				$error_flags++;
			} 
			else 
			{
				$_SESSION['email_flag'] = 'good';
			}
		}
	} 
	else 
	{
		$_SESSION['errors'][] = 'Email field is empty. Please enter a valid email, like speros@codindojo.com or chris@yahoo.com.';
		$_SESSION['email_flag'] = 'bad';
		$error_flags++;
	}

	//password errors
	if (!empty($_POST['password']))
	{
		$password = trim($_POST['password']);
		if (strlen($password) < 6) 
		{
			$_SESSION['errors'][] = 'Password must be at least 6 characters.';
			$_SESSION['password_flag'] = 'bad';
			$error_flags++;
		} 
		else 
		{
			$_SESSION['password_flag'] = 'good';
		}
	} else 
	{
		$_SESSION['errors'][] = 'Please enter a password';
		$_SESSION['password_flag'] = 'bad';
		$error_flags++;
	}

	//confirm password errors
	if (!empty($_POST['confirm_password']))
	{
		$confirm_password = trim($_POST['confirm_password']);
		if (strlen($confirm_password) < 6) 
		{
			$_SESSION['errors'][] = 'Confirm password must be at least 6 characters. Please re-enter both passwords.';
			$_SESSION['password_flag'] = 'bad';
			$_SESSION['confirm_password_flag'] = 'bad';
			$error_flags++;
		} 
		else 
		{
			if($confirm_password == $password)
			{
				$_SESSION['confirm_password_flag'] = 'good';
			} 
			else 
			{
				$_SESSION['errors'][] = 'Passwords did not match, please re-enter passwords.';
				$_SESSION['password_flag'] = 'bad';
				$_SESSION['confirm_password_flag'] = 'bad';
				$error_flags++;
			}
		}
	} 
	else 
	{
		$_SESSION['errors'][] = 'Confirm password not entered. Please enter your passwords again to confirm.';
		$_SESSION['password_flag'] = 'bad';
		$_SESSION['confirm_password_flag'] = 'bad';
		$error_flags++;
	}

	//date_of_birth errors
	if (!empty($_POST['date_of_birth'])) 
	{
		$date_of_birth = trim($_POST['date_of_birth']);
		$_SESSION['date_of_birth'] = $date_of_birth;
		if (strlen($date_of_birth) != 10)
		{
			$_SESSION['errors'][] = '*Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.';
			$_SESSION['date_of_birth_flag'] = 'bad';
			$error_flags++;
		} 
		else 
		{
			$dob = explode('/', $date_of_birth);
			if (count($dob)!=3) 
			{
				$_SESSION['errors'][] = '!Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.';
				$_SESSION['date_of_birth_flag'] = 'bad';
				$error_flags++;
			} 
			else 
			{
				if ((strlen($dob[0])) == 2 && (strlen($dob[1]) == 2) && (strlen($dob[2])== 4) )
				{
					$dob_count = 0;
					foreach ($dob as $numbers) 
					{
						if (is_numeric($numbers)) 
						{
							$dob_count++;
						}
					}
					if ($dob_count!= 3)
					{
						$_SESSION['errors'][] = 'Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.';
						$_SESSION['date_of_birth_flag'] = 'bad';
						$error_flags++;
					}
					else 
					{
						$_SESSION['date_of_birth_flag'] = 'good';	
					}
				} 
				else 
				{
					$_SESSION['errors'][] = '@Date was entered as: ' . $date_of_birth . '. Date of birth must be entered like so : MM/DD/YYYY , 11/17/1988.';
					$_SESSION['date_of_birth_flag'] = 'bad';
					$error_flags++;
				}
			}
		}
	}

	if(!empty($_FILES['profile_picture']['tmp_name']))
	{	
		$profile_photo = $_FILES['profile_picture']['name'];
		$uploads_dir = 'uploads/';
		$profile_picture = $uploads_dir . basename($_FILES['profile_picture']['name']);
		$imageFileType = pathinfo($profile_picture, PATHINFO_EXTENSION);
		$check = getimagesize($_FILES['profile_picture']['tmp_name']);
		$getfilesize = $_FILES['profile_picture']['tmp_name'];
		$filesize = filesize($getfilesize);
		//check if image is an image
		if($check !== false) 
		{
	        $_SESSION['OK'][] = "File is an image - " . $check["mime"] . ".";
	        $_SESSION['profile_picture_flag'] = 'green';
	    } 
	    else 
	    {
	        $_SESSION['errors'][] = "File must be an image file, (JPG, JPEG, PNG, or GIF.";
	        $_SESSION['profile_picture_flag'] = 'bad';
	        $error_flags++;
	    }
	    // Check file size
		if ($filesize > 5000000) 
		{
		    $_SESSION['errors'][] = "Sorry, your photo must be under 5MB.";
		    $_SESSION['profile_picture_flag'] = 'bad';
		    $error_flags++;
		}
		// Allow certain file formats
		if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
		&& $imageFileType != "gif" ) 
		{
		    $_SESSION['errors'][] =  "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
			$_SESSION['profile_picture_flag'] = 'bad';
			$error_flags++;
		}
	}

	//if there are any errors, we must redirect them back to the form for registration
	if($error_flags > 0)
	{
		if(isset($profile_photo))
		{
			$_SESSION['password_flag'] = 'bad';
			$_SESSION['confirm_password_flag'] = 'bad';
			$_SESSION['profile_picture_flag'] = 'bad';
			$_SESSION['errors'][] =  "Please re-enter your passwords and profile photo.";
			header('location: index.php');	
		} 
		else 
		{
			$_SESSION['password_flag'] = 'bad';
			$_SESSION['confirm_password_flag'] = 'bad';
			$_SESSION['errors'][] =  "Please re-enter your passwords.";
			header('location: index.php');	
		}
	}
	else 
	{
		//if a photo exists we will try and upload
		if(!empty($_FILES['profile_picture']['tmp_name']))
		{
			// create another if/else to check if file is successfully moved, in case it is a directory error or something we cannot catch before move
			// and preventing upload to database before everything is OK
		    if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $profile_picture)) 
		    {
		    	//all validations have passed so we will connect and upload to database
				
				//include connection page
				require_once('connect-coding-dojo.php');

				//since dob is optional, we will create empty entry
				if(!isset($date_of_birth))
				{
					$date_of_birth = '';
				}

				//prevent mysql injection
				global $connection;
     			$esc_email= mysqli_real_escape_string($connection, $email);
     			$esc_password = mysqli_real_escape_string($connection, $password);
     			$esc_profile_picture= mysqli_real_escape_string($connection, $profile_photo);

				//password handling
				// we will create salt
				$salt = bin2hex(openssl_random_pseudo_bytes(22));
				$encrypted_password = md5($esc_password . '' . $salt);

				// if validations check out we insert the records into the database
				$query = "INSERT INTO  users (first_name, last_name, email, salt, password, date_of_birth, profile_picture, created_at, updated_at)
				          VALUES('{$first_name}','{$last_name}','{$email}', '{$salt}', '{$encrypted_password}', '{$date_of_birth}','{$esc_profile_picture}', NOW(), NOW())";
				if(run_mysql_query($query))
				{
				    //on success let us retrieve the id of the latest entry
				    $last_id = $connection->insert_id;
				    $_SESSION['message'][] = "New Interest has been added with the id = " . $last_id;
				    $_SESSION['active_user_id'] = $last_id;
				    $_SESSION['message'][] = $query;
				    if(isset($_SESSION['first_name_flag'])) { unset($_SESSION['first_name_flag']); };
					/*unset($_SESSION['first_name'])
					unset($_SESSION['last_name_flag'])
					unset($_SESSION['last_name'])
					unset($_SESSION['email_flag'])
					unset($_SESSION['email'])
					unset($_SESSION['password_flag'])
					unset($_SESSION['confirm_password_flag'])
					unset($_SESSION['date_of_birth_flag'])
					unset($_SESSION['date_of_birth'])
					unset($_SESSION['profile_picture_flag'])
					unset($_SESSION['profile_picture'])*/
				    header('Location: wall.php');
				}
				else
				{
				    $_SESSION['message'][] = "<li>Failed to add new Interest</li>"; 
				    $_SESSION['message'][] = $query;
				    header('Location: index.php');
				}
		    } 
		    else 
		    {
		        $_SESSION['errors'][] = "Sorry, there was an error uploading your file, please try again or select a different image.";
		        $_SESSION['profile_picture_flag'] = 'bad';
		        $error_flags++;
		        header('location: index.php');
		    }
		}
		else 
		{
			//all validations have passed so we will connect and upload to database
				
			//include connection page
			require_once('connect-coding-dojo.php');

			//since dob is optional, we will create empty entry
			if(!isset($date_of_birth))
			{
				$date_of_birth = '';
			}

			//since profile_picture is optional, we will create empty entry
			if(!isset($profile_picture))
			{
				$profile_picture = '';
			}

			//prevent mysql injection
			global $connection;
 			$esc_email= mysqli_real_escape_string($connection, $email);
 			$esc_password = mysqli_real_escape_string($connection, $password);
 		
			//password handling
			// we will create salt
			$salt = bin2hex(openssl_random_pseudo_bytes(22));
			$encrypted_password = md5($esc_password . '' . $salt);

			// if validations check out we insert the records into the database
			$query = "INSERT INTO  users (first_name, last_name, email, salt, password, date_of_birth, profile_picture, created_at, updated_at)
			          VALUES('{$first_name}','{$last_name}','{$email}', '{$salt}', '{$encrypted_password}', '{$date_of_birth}', '{$profile_picture}', NOW(), NOW())";
			if(run_mysql_query($query))
			{
			    //on success let us retrieve the id of the latest entry
			    $last_id = $connection->insert_id;
			    $_SESSION['message'][] = "New Interest has been added with the id = " . $last_id;
			    $_SESSION['active_user_id'] = $last_id;
			    $_SESSION['message'][] = $query;
			    /*unset($_SESSION['first_name_flag'])
				unset($_SESSION['first_name'])
				unset($_SESSION['last_name_flag'])
				unset($_SESSION['last_name'])
				unset($_SESSION['email_flag'])
				unset($_SESSION['email'])
				unset($_SESSION['password_flag'])
				unset($_SESSION['confirm_password_flag'])
				unset($_SESSION['date_of_birth_flag'])
				unset($_SESSION['date_of_birth'])
				unset($_SESSION['profile_picture_flag'])
				unset($_SESSION['profile_picture'])*/
			    header('Location: wall.php');
			}
			else
			{
			    $_SESSION['message'][] = "<li>Failed to add new Interest</li>"; 
			    $_SESSION['message'][] = $query;
			    header('Location: index.php');
			}
		}
	}

}

//login validation
function login_validation($post)
{

	//email errors
	if(!empty($_POST['email']))
	{	
		//include connection page
		require_once('connect-coding-dojo.php');

		$email = trim($_POST['email']);
		$password = trim($_POST['password']);
		$esc_email= mysqli_real_escape_string($connection, $email);
		$esc_password = mysqli_real_escape_string($connection, $password);
		$user_query = "SELECT * FROM users WHERE users.email = '{$esc_email}'";
		$user = fetch($user_query);
		if (!empty($user))
		{
			$encrypted_password = md5($esc_password . '' . $user['salt']);
			if($user['password'] == $encrypted_password)
			{
				$_SESSION['active_user_id'] = $user['id'];
				$_SESSION['message'][] = "successfully logged in!";
				header('location:wall.php');
			}
			else 
			{
				$_SESSION['email_login'] = $email;
				$_SESSION['message'][] = "invalid password!";
				$_SESSION['password_login_flag'] = 'bad';
				header('location:index.php');
			}
		}
		else 
		{
			$_SESSION['message'][] = "email not found.";
			$_SESSION['email_login_flag'] = 'bad';
			header('location:index.php');
		}
	} 
	else 
	{
		$_SESSION['errors'][] = 'Login Email field is empty. Please enter your login email.';
		$_SESSION['email_login_flag'] = 'bad';
		header('location:index.php');
	}
}

//message validation
function message_validation($post) 
{
	//quote errors
	if(!empty($_POST['message']))
	{
		$message = trim(addslashes($_POST['message']));
		$active_user_id = trim($_POST['active_user_id']);
		//include connection page
		require_once('connect-coding-dojo.php');
		//prevent mysql injection
		global $connection;
     	$esc_message= mysqli_real_escape_string($connection, $message);
     	// if validations check out we insert the records into the database
		$query = "INSERT INTO  messages (user_id, message, created_at, updated_at)
		          VALUES('{$active_user_id}','{$message}', NOW(), NOW())";
		if(run_mysql_query($query))
		{
		    //on success let us retrieve the id of the latest entry
		    $last_id = $connection->insert_id;
		    $_SESSION['message'][] = "New Post has been added with the id = " . $last_id;
		    $_SESSION['latest_post_id'] = $last_id;
		    $_SESSION['message'][] = $query;
		    header('Location: wall.php');
		}
		else
		{
		    $_SESSION['message'][] = "<li>Failed to add new post. Please try again</li>"; 
		    $_SESSION['message'][] = $query;
		    header('Location: wall.php');
		}
	} 
	else
	{
		$_SESSION['errors'][] = "Message not entered. Please enter a message before submitting.";
		$_SESSION['message_flag'] = 'bad';
		header('location:wall.php');
	}
}

//comment validation
function comment_validation($post) 
{
	//quote errors
	if(!empty($_POST['comment']))
	{
		$comment = trim(addslashes($_POST['comment']));
		$active_user_id = trim($_POST['active_user_id']);
		$message_id = trim($_POST['message_id']);
		//include connection page
		require_once('connect-coding-dojo.php');
		//prevent mysql injection
		global $connection;
     	$esc_message= mysqli_real_escape_string($connection, $comment);
     	// if validations check out we insert the records into the database
		$query = "INSERT INTO  comments (message_id, user_id, comments.comment, created_at, updated_at)
		          VALUES('{$message_id}','{$active_user_id}', '{$comment}', NOW(), NOW())";
		if(run_mysql_query($query))
		{
		    //on success let us retrieve the id of the latest entry
		    $last_id = $connection->insert_id;
		    $_SESSION['message'][] = "New Comment has been added with the id = " . $last_id;
		    $_SESSION['latest_comment_id'] = $last_id;
		    $_SESSION['message'][] = $query;
		    header('Location: wall.php');
		}
		else
		{
		    $_SESSION['message'][] = "<li>Failed to add new comment. Please try again</li>"; 
		    $_SESSION['message'][] = $query;
		    header('Location: wall.php');
		}
	} 
	else
	{
		$_SESSION['errors'][] = "Message not entered. Please enter a message before submitting.";
		$_SESSION['message_flag'] = 'bad';
		header('location:wall.php');
	}
}

//delete message
function delete_message($post)
{
	require_once('connect-coding-dojo.php');
	//echo "we are here!";

	$id = $_POST['record'];
	$thirtymin = $_POST['thirtymin'];
	if(strtotime(date("H:i F j Y")) < strtotime($thirtymin))
	{
		$query = "DELETE FROM messages
				  WHERE messages.id = $id";
		if(run_mysql_query($query))
		{
		    $_SESSION['message'][] = "<li>Record has been deleted!</li>";
		    header('location: wall.php');
		}
		else
		{
		    $_SESSION['message'][] = "<li>Failed to delete record.</li>"; 
		    header('location: wall.php');
		}	
	}
	else 
	{
		 $_SESSION['message'][] = "<li>30 minutes has passed. We can no longer delete the record.</li>"; 
		 header('location: wall.php');
	}
}




?>