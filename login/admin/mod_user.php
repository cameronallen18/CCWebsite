<?php

//prevents caching
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();

session_start();

include ('../config.php');
include ('../functions.php');
	//make connection to dbase
	$connection = @mysql_connect($server, $dbusername, $dbpassword)
				or die(mysql_error());
				
	$db = @mysql_select_db($db_name,$connection)
				or die(mysql_error());
				

if ($_POST[del_user] != "")
{

	$sql = "SELECT * FROM $table_name WHERE username = '$_POST[del_user]'";

	$result = @mysql_query($sql, $connection) or die(mysql_error());

	//get the number of rows in the result set
	$num = mysql_num_rows($result);

	//set session variables if there is a match
	if ($num != 0)
	{
		while ($sql = mysql_fetch_object($result)) 
		{	
		$first		= $sql -> firstname;
		$last		= $sql -> lastname;
		$uname		= $sql -> username;
		$pass		= $sql -> password;
		$gr1		= $sql -> group1;
		$gr2		= $sql -> group2;
		$gr3		= $sql -> group3;
		$change		= $sql -> pchange;
		$e_mail		= $sql -> email;
		$re_direct	= $sql -> redirect;
		$ver_d		= $sql -> verified;
		$last_log	= $sql -> last_login;
		$del_dat	= last_login();
		}
		
		$trash_user = "INSERT INTO trash (firstname, lastname, username, password, group1, group2, group3, 
		pchange, email, redirect, verified, last_login, del_date)VALUES
		('$first', '$last', '$uname', '$pass', '$gr1', '$gr2', '$gr3',
		'$change', '$e_mail', '$re_direct', '$ver_d', '$last_log', '$del_dat')";

		$del = "DELETE FROM $table_name WHERE username = '$_POST[del_user]'";
		
		$result = @mysql_query($del,$connection) or die(mysql_error());
		$result1 = @mysql_query($trash_user,$connection) or die(mysql_error());
		
		$msg .= "User $_POST[del_user] has been trashed from the database.<br>";
	}else{
		$msg .= "User $_POST[del_user] could not be located in the database.<br>";
	}
	
	$del_banned = "DELETE FROM banned WHERE no_access = '$_POST[del_user]'";
		$result = @mysql_query($del_banned,$connection) or die(mysql_error());


}

if (($_POST[username] != "") && ($_POST[mod_pass] == "Same as Old"))
{
$sql = "SELECT * FROM $table_name WHERE username = '$_POST[username]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());
while ($sql = mysql_fetch_object($result))
	{
		$pass = $sql -> password;
		$last = $sql -> last_login;
	}
			$sql = "DELETE FROM $table_name WHERE username = '$_POST[username]'";
			$result = @mysql_query($sql,$connection) or die(mysql_error());
			$sql = "INSERT INTO $table_name (firstname, lastname, username, password, group1, group2, group3, 
			pchange, email, redirect, verified, last_login) VALUES ('$_POST[mod_first]', '$_POST[mod_last]', 
			'$_POST[username]', '$pass', '$_POST[mod_group1]', '$_POST[mod_group2]', 
			'$_POST[mod_group3]', '$_POST[mod_chng]', '$_POST[mod_email]', '$_POST[mod_redirect]',
			'1', '$last')";
			$result = @mysql_query($sql,$connection) or die(mysql_error());
			$msg .= "The information for $_POST[username] has been changed updated.<br>";
}

if (($_POST[username] != "") && ($_POST[mod_pass] != "Same as Old"))
{
$sql = "SELECT * FROM $table_name WHERE username = '$_POST[username]'";
$result = @mysql_query($sql,$connection) or die(mysql_error());
while ($sql = mysql_fetch_object($result))
	{
		$pass = $sql -> password;
		$last = $sql -> last_login;
	}
			$sql = "DELETE FROM $table_name WHERE username = '$_POST[username]'";
			$result = @mysql_query($sql,$connection) or die(mysql_error());
			$sql = "INSERT INTO $table_name (firstname, lastname, username, password, group1, group2, group3, 
			pchange, email, redirect, verified, last_login) VALUES ('$_POST[mod_first]', '$_POST[mod_last]', 
			'$_POST[username]', password('$_POST[mod_pass]'), '$_POST[mod_group1]', '$_POST[mod_group2]', 
			'$_POST[mod_group3]', '$_POST[mod_chng]', '$_POST[mod_email]', '$_POST[mod_redirect]',
			'1', '$last')";
			$result = @mysql_query($sql,$connection) or die(mysql_error());
			$msg .= "The information for $_POST[username] has been changed updated.<br>";
}	
 
if ($_POST[ban_user] != "")
{

		$ban = "INSERT INTO banned (no_access, type) VALUES ('$_POST[ban_user]', 'user')";
		$result = @mysql_query($ban,$connection) or die(mysql_error());
		$msg .= "User $_POST[ban_user] has been banned.<br>";

}

$ip_addr = "$_POST[oct1].$_POST[oct2].$_POST[oct3].$_POST[oct4]";

if ($ip_addr != "...")
{
		$ban_ip = "INSERT INTO banned (no_access, type) VALUES ('$ip_addr', 'ip')";
		$result = @mysql_query($ban_ip,$connection) or die(mysql_error());
		$msg .= "IP Address $ip_addr has been banned.<br>";
}

if ($_POST[lift_user_ban] != "")
{

		$lift_user = "DELETE FROM banned (no_access, type) WHERE no_access = '$_POST[lift_user_ban]'";
		$result = @mysql_query($lift_user,$connection) or die(mysql_error());
		$msg .= "The Ban for user $_POST[lift_user_ban] has been lifted.<br>";

}	

if ($_POST[lift_ip_ban] != "")
{

		$lift_ip = "DELETE FROM banned (no_access, type) WHERE no_access = '$_POST[lift_ip_ban]'";
		$result = @mysql_query($lift_ip,$connection) or die(mysql_error());
		$msg .= "The Ban for IP Address $_POST[lift_ip_ban] has been lifted.<br>";

}

if ($_POST[restore] != "")
{
	$ruser = "SELECT * FROM trash WHERE username = '$_POST[restore]'";

	$result0 = @mysql_query($ruser, $connection) or die(mysql_error());

	//get the number of rows in the result set
	$num = mysql_num_rows($result0);

	//set session variables if there is a match
	if ($num != 0)
	{
		while ($ruser = mysql_fetch_object($result0)) 
		{	
		$rfirst		= $ruser -> firstname;
		$rlast		= $ruser -> lastname;
		$runame		= $ruser -> username;
		$rpass		= $ruser -> password;
		$rgr1		= $ruser -> group1;
		$rgr2		= $ruser -> group2;
		$rgr3		= $ruser -> group3;
		$rchange	= $ruser -> pchange;
		$re_mail	= $ruser -> email;
		$rre_direct	= $ruser -> redirect;
		$rver_d		= $ruser -> verified;
		$rlast_log	= $ruser -> last_login;
		}
		
		$r_user = "INSERT INTO $table_name (firstname, lastname, username, password, group1, group2, group3, 
		pchange, email, redirect, verified, last_login) VALUES
		('$rfirst', '$rlast', '$runame', '$rpass', '$rgr1', '$rgr2', '$rgr3',
		'$rchange', '$re_mail', '$rre_direct', '$rver_d', '$rlast_log')";

		$del = "DELETE FROM trash WHERE username = '$_POST[restore]'";

		$result = @mysql_query($del,$connection) or die(mysql_error());
		$result1 = @mysql_query($r_user,$connection) or die(mysql_error());
	
		$msg .= "User $_POST[restore] has been restored.<br>";
	}else{
		$msg .= "User $_POST[restore] could not be located in the database.<br>";
	}
}

if ($_POST[empt_trash] == "yes")
{

	$empty = "DELETE FROM trash";
	$gone = @mysql_query($empty, $connection) or die(mysql_error());
	
	$msg .= "The trash has been emptied.<br>";
}

if ($_POST[amt_time] != "" &&  $_POST[incr_time] != "")
{
	$msg .= "The following accounts were inactive for $amt_time $incr_time or more and have been moved to the trash.<br><br>";
	$killtime = "NOW() - INTERVAL $_POST[amt_time] $_POST[incr_time]";
	$xfer = "SELECT * FROM $table_name WHERE last_login < $killtime";
	$resultp1 = @mysql_query($xfer, $connection) or die(mysql_error());
	while ($xfer = mysql_fetch_object($resultp1))
	{
		$pfirst		= $xfer -> firstname;
		$plast		= $xfer -> lastname;
		$puname		= $xfer -> username;
		$ppass		= $xfer -> password;
		$pgr1		= $xfer -> group1;
		$pgr2		= $xfer -> group2;
		$pgr3		= $xfer -> group3;
		$ppchange	= $xfer -> pchange;
		$pe_mail	= $xfer -> email;
		$pre_direct	= $xfer -> redirect;
		$pver_d		= $xfer -> verified;
		$plast_log	= $xfer -> last_login;
		$pdel_date	= last_login();		
		
		$msg .= "$puname<br>";
		$xfer2 = "INSERT INTO trash (firstname, lastname, username, password, group1, group2, group3, 
		pchange, email, redirect, verified, last_login, del_date) VALUES ('$pfirst', ' $plast', '$puname', 
		'$ppass', '$pgr1', '$pgr2', '$pgr3', '$ppchange', '$pe_mail', '$pre_direct', '$pver_d', '$plast_log', '$pdel_date')";
		$resultp2 = @mysql_query($xfer2, $connection) or die(mysql_error());
	}
	$purge = "DELETE FROM $table_name WHERE last_login < $killtime";
	$resultp3 = @mysql_query($purge, $connection) or die(mysql_error());

}


echo "<link rel=\"stylesheet\" type=\"text/css\" href=\"adminpage.css\">";

echo $msg;

if ($_POST[username] == $_SESSION[user_name])
{
session_destroy();
echo "<html>";
echo "<head>";
echo "<meta http-equiv=\"refresh\" content=\"3; url=../login.html\">";
echo "<title>New Page 2</title>";
echo "</head>";
exit;
}
?>

<html>

<head>
<meta http-equiv="refresh" content="3; url=adminpage.php">
<title>Modify User</title>
</head>

<body>

</body>

</html>


