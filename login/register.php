<?

//prevents caching
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();

session_start();

//require the config file
require ("config.php");
require ("functions.php");

//checks password length
if (password_check($min_pass, $max_pass, $_POST[password]) == "no")
{
?>
<html>

<head>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
<meta http-equiv="refresh" content="0; url=javascript:history.go(-1)">
<title>Registration</title>
<script language="JavaScript">
<!--
function FP_popUpMsg(msg) {//v1.0
 alert(msg);
}
// -->
</script>
</head>

<body onLoad="FP_popUpMsg('Your password must be between <? echo $min_pass; ?> & <? echo $max_pass; ?> characters.')">

</body>

</html>
<?
exit;
}

//check for Brother Identification Code

$con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+profile", $con);

		$result = mysql_query("SELECT * FROM idcodes WHERE type='Brothers'");
		while($row = mysql_fetch_array($result))
  		{
  			$idcode_bro = $row['code'];
  		}
		
		$result = mysql_query("SELECT * FROM idcodes WHERE type='Administrators'");
		while($row = mysql_fetch_array($result))
  		{
  			$idcode_admin = $row['code'];
  		}
  
		mysql_close($con); 

$register_code = $_POST['register_code'];

if ($register_code == $idcode_bro) {
	$register_group = "Brothers";
} elseif ($register_code == $idcode_admin) {
	$register_group = "Administrators";
} else {
	$register_group = 'Users';
}

//make the connection to the database
$connection = @mysql_connect($server, $dbusername, $dbpassword) or die(mysql_error());
$db = @mysql_select_db($db_name,$connection)or die(mysql_error());

//make query to database
$sql ="SELECT * FROM $table_name WHERE username= '$_POST[username]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());

//get the number of rows in the result set
$num = mysql_num_rows($result);

//checks it see if that username already exists
if ($num != 0){

echo "<P>Sorry, that username already exists.</P>";
echo "<P><a href=\"#\" onClick=\"history.go(-1)\">Try Another Username.</a></p>";
exit;

}else{
$sql = "INSERT INTO $table_name VALUES
('$_POST[firstname]', '$_POST[lastname]', '$_POST[username]', password('$_POST[password]'), '$register_group', '', '', '0', 
'$_POST[email]', '$default_url/index.php?username=$_POST[username]', '$verify', '')";

$result = @mysql_query($sql,$connection) or die(mysql_error());
}

//checks to see if the user needs to verify their email address before accessing the site
if ($verify == "0")
{
	$mailheaders = "From: $domain\n";
	$mailheaders .= "Your account has been created.\n";
	$mailheaders .= "Please activate your account now by visiting this page:\n";
	$mailheaders .= "$base_dir/activate.html\n";


	$to = "$_POST[email]";
	$subject = "Please activate your account";

mail($to, $subject, $mailheaders, "From: Chocolate City Webmaster <$adminemail>\n");

}else{
	header('Location:login.html');
}
?>


<html>
<head>
<title>Add a User</title>

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

<h5 class="noindent center">Please Check Your E-Mail to Activate Your Account</h5>
<h6 class="noindent center">Based on your Identification Code, you have been registered as "<?php echo $register_group; ?>".</h5>

</div>

<div id="xfooter">

<p>Chocolate City &copy; 2009</p>

</div>
</body>
</html>