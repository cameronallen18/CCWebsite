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
	
// Data from from
$usr = $_POST['usr'];
$q1 = $_POST['q1'];
$a1 = $_POST['a1'];
$q2 = $_POST['q2'];
$a2 = $_POST['a2'];
$q3 = $_POST['q3'];
$a3 = $_POST['a3'];
$bio = $_POST['bio'];
	
function verifyFormInput(){
		global $usr, $q1, $a1, $q2, $a2, $q3, $a3, $bio, $newline;
		$error_flag = FALSE;
		
		// Check Questions
		if ( $q1 == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please select \"Question 1\".</font>" . $newline;
		}
		
		// Check Questions
		if ( $q2 == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please select \"Question 2\".</font>" . $newline;
		}
		
		// Check Questions
		if ( $q3 == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please select \"Question 3\".</font>" . $newline;
		}
		
		// Check Answers
		if ( $a1 == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please answer \"Question 1\".</font>" . $newline;
		}
		
		// Check Answers
		if ( $a2 == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please answer \"Question 2\".</font>" . $newline;
		}
		
		// Check Answers
		if ( $a3 == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please answer \"Question 3\".</font>" . $newline;
		}
		
		// Check Bio
		if ( $bio == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please enter your \"Biography\".</font>" . $newline;
		}
		
		
		if ($error_flag){
			echo "There were errors that must be corrected before your info can be added/updated. You will be redirected" . $newline;
			header("refresh:3;");
			return FALSE;
		}
		else{
			echo "Form data validated." . $newline;
			return TRUE;
		}
	}
	
	function updateDatabase(){
	
		global $user, $usr, $q1, $a1, $q2, $a2, $q3, $a3, $bio, $newline;
	
		$con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
		if (!$con)
		{
			die("Could not connect: " . mysql_error());
		}
		
		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT username FROM bio WHERE username = '$usr'");
		$result = mysql_fetch_array($result);
		$query = FALSE;
		if( $result['username'] == "" ){
			echo "Adding new record..." . $newline;
			$query = mysql_query("INSERT INTO bio (username, q1, a1, q2, a2, q3, a3, bio) VALUES ('$usr', '$q1', '$a1', '$q2', '$a2', '$q3', '$a3', '$bio')");
			}
		else{
			echo "Updating existing record..." . $newline;
			$query = mysql_query("UPDATE bio SET q1 = '$q1', a1 = '$a1',  q2 = '$q2', a2 = '$a2', q3 = '$q3', a3 = '$a3', bio = '$bio' WHERE username = '$usr'");
			}
		
		if(!$query)
			die("mySQL error: " . mysql_error());
		mysql_close($con);
		echo "<b>Database successfully updated.</b>" . $newline;
		header("refresh:1; url='index.php?username=" . $usr . "'");
		return TRUE;
	}


if ( !isset($_POST['submit']) ) { // if page is not submitted to itself echo the form
	if ( !isset($username) ){
		echo "You must have a username to access this site. You will be redirected to the login page in 3 seconds." . $newline;
		header("refresh:3; url='../login/login.html'");
		endPage();
	}
?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd"><html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>General Information</title>
<link rel="stylesheet" type="text/css" href="../styles/styles.css" />
<link rel="stylesheet" type="text/css" href="../styles/brothers.css" />
<script type="text/javascript" src="../styles/javascript/jquery.js"></script>
<script type="text/javascript" src="../styles/javascript/brothers.js"></script>

<body>

<table width="600px" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td>



<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF;?>" enctype='multipart/form-data' name='input' id='input'>

<table width="600px" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
	<td colspan="2">
    <h5 class="nomargin">Edit Biography Information</h5><br />
	</td>
</tr>

<tr>
	<td colspan="2"><p class="noindent nomargin"><span class="small">Welcome <strong><?php echo $user ?></strong>! Please complete the following information.</span></p></td>
</tr>

<tr>
	<td colspan="2"><p class="noindent nomargin italic"><strong>Questions & Biographies</strong></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>User Name:</strong></p></td>
	<td width="75%"><p class="noindent"><textarea name="usr" rows="1" readonly><?php echo $user ?></textarea><br /></p></td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM bio WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$biography = $row['q1'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Question 1:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="q1">
		<option value='-1'>Select</option>
	    <option value="1" <?php if ($biography == "1") echo "selected=\"selected\" "; ?>>What made you want to live in Chocolate City?</option>
        <option value="2" <?php if ($biography == "2") echo "selected=\"selected\" "; ?>>Where did you first here about Chocolate City?</option>
        <option value="3" <?php if ($biography == "3") echo "selected=\"selected\" "; ?>>What do you like most about Chocolate City?</option>
        <option value="4" <?php if ($biography == "4") echo "selected=\"selected\" "; ?>>How has your CC experience impacted your life at MIT?</option>
        <option value="5" <?php if ($biography == "5") echo "selected=\"selected\" "; ?>>What do you (or did you) hope to gain from Chocolate City?</option>
        <option value="6" <?php if ($biography == "6") echo "selected=\"selected\" "; ?>>What do you (or did you) hope to gain from attending MIT?</option>
        <option value="7" <?php if ($biography == "7") echo "selected=\"selected\" "; ?>>What is the most valuable thing you have gotten from Chocolate City?</option>
        <option value="8" <?php if ($biography == "8") echo "selected=\"selected\" "; ?>>What is the best advice you would give to a perspective student?</option>
        <option value="9" <?php if ($biography == "9") echo "selected=\"selected\" "; ?>>What are your future career interests?</option>
        <option value="10" <?php if ($biography == "10") echo "selected=\"selected\" "; ?>>Where do you see yourself in ten years?</option>
    </select>
    <br /></p></td>
</tr>

<tr>
	<td width="25%" valign="top"><p class="noindent"><strong>Answer 1:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="a1" rows="10" name="a1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM bio WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['a1'];
  		}
  
		mysql_close($con); ?></textarea><br /></p>
        
        <p class="noindent"><span class="small"><strong>Note:</strong> The input will only allow a maximum of "500" characters. <span id="a1l">500</span> characters left.</span></p>
        </td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM bio WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$biography = $row['q2'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Question 1:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="q2">
		<option value='-1'>Select</option>
	    <option value="1" <?php if ($biography == "1") echo "selected=\"selected\" "; ?>>What made you want to live in Chocolate City?</option>
        <option value="2" <?php if ($biography == "2") echo "selected=\"selected\" "; ?>>Where did you first here about Chocolate City?</option>
        <option value="3" <?php if ($biography == "3") echo "selected=\"selected\" "; ?>>What do you like most about Chocolate City?</option>
        <option value="4" <?php if ($biography == "4") echo "selected=\"selected\" "; ?>>How has your CC experience impacted your life at MIT?</option>
        <option value="5" <?php if ($biography == "5") echo "selected=\"selected\" "; ?>>What do you (or did you) hope to gain from Chocolate City?</option>
        <option value="6" <?php if ($biography == "6") echo "selected=\"selected\" "; ?>>What do you (or did you) hope to gain from attending MIT?</option>
        <option value="7" <?php if ($biography == "7") echo "selected=\"selected\" "; ?>>What is the most valuable thing you have gotten from Chocolate City?</option>
        <option value="8" <?php if ($biography == "8") echo "selected=\"selected\" "; ?>>What is the best advice you would give to a perspective student?</option>
        <option value="9" <?php if ($biography == "9") echo "selected=\"selected\" "; ?>>What are your future career interests?</option>
        <option value="10" <?php if ($biography == "10") echo "selected=\"selected\" "; ?>>Where do you see yourself in ten years?</option>
    </select>
    <br /></p></td>
</tr>

<tr>
	<td width="25%" valign="top"><p class="noindent"><strong>Answer 2:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="a2" rows="10" name="a2"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM bio WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['a2'];
  		}
  
		mysql_close($con); ?></textarea><br /></p>
        
        <p class="noindent"><span class="small"><strong>Note:</strong> The input will only allow a maximum of "500" characters. <span id="a2l">500</span> characters left.</span></p>
        </td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM bio WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$biography = $row['q3'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Question 1:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="q3">
		<option value='-1'>Select</option>
	    <option value="1" <?php if ($biography == "1") echo "selected=\"selected\" "; ?>>What made you want to live in Chocolate City?</option>
        <option value="2" <?php if ($biography == "2") echo "selected=\"selected\" "; ?>>Where did you first here about Chocolate City?</option>
        <option value="3" <?php if ($biography == "3") echo "selected=\"selected\" "; ?>>What do you like most about Chocolate City?</option>
        <option value="4" <?php if ($biography == "4") echo "selected=\"selected\" "; ?>>How has your CC experience impacted your life at MIT?</option>
        <option value="5" <?php if ($biography == "5") echo "selected=\"selected\" "; ?>>What do you (or did you) hope to gain from Chocolate City?</option>
        <option value="6" <?php if ($biography == "6") echo "selected=\"selected\" "; ?>>What do you (or did you) hope to gain from attending MIT?</option>
        <option value="7" <?php if ($biography == "7") echo "selected=\"selected\" "; ?>>What is the most valuable thing you have gotten from Chocolate City?</option>
        <option value="8" <?php if ($biography == "8") echo "selected=\"selected\" "; ?>>What is the best advice you would give to a perspective student?</option>
        <option value="9" <?php if ($biography == "9") echo "selected=\"selected\" "; ?>>What are your future career interests?</option>
        <option value="10" <?php if ($biography == "10") echo "selected=\"selected\" "; ?>>Where do you see yourself in ten years?</option>
    </select>
    <br /></p></td>
</tr>

<tr>
	<td width="25%" valign="top"><p class="noindent"><strong>Answer 3:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="a3" rows="10" name="a3"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM bio WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['a3'];
  		}
  
		mysql_close($con); ?></textarea><br /></p>
        
        <p class="noindent"><span class="small"><strong>Note:</strong> The input will only allow a maximum of "500" characters. <span id="a3l">500</span> characters left.</span></p>
    </td>
</tr>

<tr>
	<td colspan="2" valign="top"><p class="noindent"><strong>Biography:</strong></p></td>
</tr>

<tr>
    <td colspan="2"><p class="noindent nomargin" style="margin-left:.5cm; margin-top:.5cm; margin-bottom:.5cm;"><textarea id="bio" rows="25" name="bio"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM bio WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['bio'];
  		}
  
		mysql_close($con); ?></textarea><br /></p>
        
        <p class="noindent"><span class="small"><strong>Note:</strong> The input will only allow a maximum of "1500" characters. <span id="biol">1500</span> characters left.</span></p>
	</td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>&nbsp;</strong></p></td>
    <td width="75%">
<table id="profile">
<tr>
	<td width="25%">
    <p class="noindent">
    <INPUT TYPE="submit" NAME="submit" VALUE="Submit" style="font-family: Tahoma">
    <br /></p>
    </td>
    
<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+profile", $con);

		$result = mysql_query("SELECT * FROM authorize WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		$redirect = $row['redirect'];
  		}
  
		mysql_close($con); 
?>
    
    <td width="75%">
    <p class="noindent">
    <INPUT TYPE="button" value="Return to the Control Panel" onClick="location.href='<?php echo $redirect;?>'">
    <br /></p>
    </td>
</tr>
</table>
    
</td></tr></table>
</FORM>

<p>&nbsp;</p>
</td></tr></table>

<?
	endPage();
} else {

	if (!verifyFormInput())
		endPage();
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
