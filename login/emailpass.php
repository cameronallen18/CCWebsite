<?

//prevents caching
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();

session_start();

//require the config file
require ("config.php");

//make the connection to the database
$connection = @mysql_connect($server, $dbusername, $dbpassword) or die(mysql_error());
$db = @mysql_select_db($db_name,$connection)or die(mysql_error());

//build and issue the query
$sql ="SELECT * FROM $table_name WHERE email = '$_POST[email]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());

//get the number of rows in the result set
$num = mysql_num_rows($result);

//If match was found, get username and email from database
if ($num != 0) 
{
	while ($sql = mysql_fetch_object($result)) 
	{
	$email		= $sql -> email;
	$uname		= $sql -> username;
	}

//Update database with new password
	$newpass = rand(10000000,99999999);
	$chng = "UPDATE $table_name SET
	password = password('$newpass'), pchange = '1'
	WHERE email = '$email'";
	
	$result2 = @mysql_query($chng,$connection) or die(mysql_error());

//create message to user
	$msg = "<p>Your username & temporary password has been emailed to you.</p>";
	$msg .= "<p>You must change this password immediately after your next login.</p>";
	$msg .= "<p></p>";	
	$msg .= "<p><a href=\"login.html\">Login</a></p>";
	
//create mail message
	$mailheaders = "From: www$domain\n";
	$mailheaders .= "Your username is $uname.\n";
	$mailheaders .= "Your password is $newpass.\n";
	$mailheaders .= "$base_dir/login.html";
}
else
{

//If no email was found in the database send a notification to the admin
	$email = $adminemail;
	$msg = "<p>Your email address could not be located</p>";
	$msg .="<p>The Website Administrator has been emailed, you should contacted by them shortly.</p>";

	$mailheaders = "From: $domain\n";
	$mailheaders .= "A user with the email address of $_POST[email] has requested a username and password reminder.\n";
	$mailheaders .= "$_POST[email] could not be located in the database.\n";
}

//Email the request		
$to = "$email";
$subject = "Your Username & Password for www$domain";

mail($to, $subject, $mailheaders, "From: No Reply <$adminemail>\n");

?>

<html>
<head>
<title>Username and Password Request</title>

<!--link to the css files-->
<link rel="stylesheet" type="text/css" href="styles/styles.css" />
<link rel="stylesheet" type="text/css" href="styles/shadowbox/shadowbox.css" />

</head>
<body>

<div id="header">

<img src="../images/main-banner.jpg" />

</div><div id="links">

<center>

<ul id="nav">
	<li><a href="../index.php">Home</a></li>

	<li><a href="../about.php">About Us</a></li>
    
	<li><a href="../events.php">Events</a></li>
	
	<li><a href="../brothers.php">Brothers</a></li>

	<li><a href="../gallery.php">Photos</a></li>

	<li><a href="../contribute.php">Contribute</a></li>
	
	<li><a href="../contact.php">Contact Us</a>
</ul>

</center>

</div><div id="content">

<div id="sublinks">

<div id="main">
<div id="current">
You are currently viewing the <a href="login.html">Control Panel</a>. 
</div>

<div id="today">
Click here to go <a href="ccity.mit.edu">Home</a>.
</div>
</div>

</div>

<div id="main">

<? echo "$msg"; ?>

</div>

<div id="footer">

<p>Chocolate City &copy; 2009</p>

</div>
</body>
</html>
