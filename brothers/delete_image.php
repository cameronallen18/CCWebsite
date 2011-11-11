<?php

//prevents caching
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();
session_start();

// newline character
$newline = '<br />';
$username = $_GET['username'];

//Access database to get groups defined for username
$con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("chocolate-city+profile", $con);

$result = mysql_query("SELECT * FROM authorize WHERE username='$username'");

while($row = mysql_fetch_array($result))
  {
  $user			= $row['username'];
  $group1		= $row['group1'];
  $group2		= $row['group2'];
  $group3		= $row['group3'];
  $last_login	= $row['last_login'];
  $_SESSION[username]	= $row['username'];
  }
  
mysql_close($con);	

//check is username is blank
if (!isset($username)) header("refresh:0; url='../login/login.html'");

//check is username authorized
if ($username != $user)
	{
	header("refresh:3; url='http://ccity.mit.edu'");
	echo "You must be an \"Authorized User\" to view this page.<br />";
	echo "You will be redirected to the \"Home Page\" in 3 seconds.";
	exit;
	}

//check is user a Brother of Chocolate City
if (($group1 || $group2 || $group3) != "Brothers") 
	{
	header("refresh:3; url='http://ccity.mit.edu'");
	echo "You must be a \"Brother\" to view this page.<br />";
	echo "You will be redirected to the \"Home Page\" in 3 seconds.";
	exit;
	}

//Update the database
	function updateDatabase(){
	
		global $username, $newline;
	
		$con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
		if (!$con)
		{
			die("Could not connect: " . mysql_error());
		}
		
		mysql_select_db("chocolate-city+brothers", $con);

		echo "Updating existing record..." . $newline;
		$query = mysql_query("UPDATE general SET image = '' WHERE username = '$username'");

		
		if(!$query)
			die("mySQL error: " . mysql_error());
		mysql_close($con);
		echo "<b>Database successfully updated.</b>" . $newline;
		header("refresh:0; url='index.php?username=" . $username . "'");
		return TRUE;
	}


if ( !isset($_POST['submit']) ) { // if page is not submitted to itself echo the form
	if ( !isset($username) ){
		echo "You must have a username to access this site. You will be redirected to the login page in 3 seconds." . $newline;
		header("refresh:3; url='../login/login.html'");
		endPage();
	}
?>


<head><title>Delete Profile Picture</title></head>

<?
	endPage();
} else {

	if (!updateDatabase())
		endPage();
	endPage();
}
?>  

<?

function endPage(){
echo "</body>
</html>";

exit();
}
?>