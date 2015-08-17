<?php
//start session
session_start();
//var_dump($_POST);
//unset/destroy/empty session post
if(isset($_POST['unset']) && $_POST['unset'] == "unset")
{ 
	session_destroy();
	$_SESSION = array();
	header('location: index.php');
}

//verify with post, which form we are recieving posts from
if(isset($_POST['see_quotes']) && $_POST['see_quotes'] == "see_quotes")
{ 
	header('location:main.php');
}

//verify with post, which form we are recieving posts from
if(isset($_POST['add_quote']) && $_POST['add_quote'] == "add_quote")
{ 
	$error_flags = 0;
	//name errors
	if(!empty($_POST['name']))
	{
		$name = trim($_POST['name']);
		$_SESSION['name'] = $name;
		//*updated first name regex match to validate if name is only characters and white space, instead of it just has numbers JM 4.17
		if( preg_match( "/^[a-zA-Z ]*$/", $name)) 
		{
			$_SESSION['name_flag'] = 'good';
		} else
		{
			$_SESSION['errors'] = '<li>Name :'. $name . ' is invalid. Name cannot contain any numerical values or special characters, please enter a valid name using only alphanumeric characters.</li>';
			$_SESSION['error_log'] .= $_SESSION['errors'];
			$_SESSION['name_flag'] = 'bad';
			$error_flags++;
		}
	} else 
	{
		$_SESSION['errors'] = "<li>Name not entered, you must enter a name</li>";
		$_SESSION['error_log'] .= $_SESSION['errors'];
		$_SESSION['name_flag'] = 'bad';
		$error_flags++;
	}
	//quote errors
	if(!empty($_POST['quote']))
	{
		$quote = trim(addslashes($_POST['quote']));
		$_SESSION['quote'] = $quote;
		$_SESSION['quote_flag'] = 'good';
	} else
	{
		$_SESSION['errors'] = "<li>Quote not entered, you must enter a quote before you submit.</li>";
		$_SESSION['error_log'] .= $_SESSION['errors'];
		$_SESSION['quote_flag'] = 'bad';
		$error_flags++;
	}
	//check if there are any errors written, if so redirect
	if ($error_flags>0)
	{
		$error_flags = 0;
		header('location:index.php');
	} else 
	{
		//all fields are okay, in this case we will just redirect back to homepage
		$_SESSION['OK'] = '<li>All fields are okay!</li>';
		$_SESSION['OK_log'] .= $_SESSION['OK'];


		// include connection page
		require_once('connection.php');
		//echo "we are here!";
		$name = $_SESSION['name'];
		$quote = $_SESSION['quote'];
		$query = 'INSERT INTO  quotes (name, quote, created_at, updated_at)
		          VALUES("' . $name . '","' . $quote . '", NOW(), NOW())';
		if(run_mysql_query($query))
		{
		    $_SESSION['message'] = "<li>New quote has been added correctly!</li>";
		    unset($_SESSION['name']);
			unset($_SESSION['quote']);
			unset($_SESSION['name_flag']);
			unset($_SESSION['quote_flag']);
			unset($_SESSION['errors']);
			unset($_SESSION['error_log']);
			unset($_SESSION['OK']);
			unset($_SESSION['OK_log']);
		}
		else
		{
		    $_SESSION['message'] = "<li>Failed to add new quote.</li>"; 
		}
		header('Location: main.php');
	}
}

?>