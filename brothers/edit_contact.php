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
$display = $_POST['display'];
$gchat = $_POST['gchat'];
$aim = $_POST['aim'];
$msn = $_POST['msn'];
$yahoo = $_POST['yahoo'];
$emailpers = $_POST['emailpers'];
$emailbusi = $_POST['emailbusi'];
$cell1 = $_POST['cell1'];
$cell2 = $_POST['cell2'];
$cell3 = $_POST['cell3'];
$homestreet = $_POST['homestreet'];
$homecity = $_POST['homecity'];
$homestate = $_POST['homestate'];
$homezip = $_POST['homezip'];
$work = $_POST['work'];
$workstreet = $_POST['workstreet'];
$workcity = $_POST['workcity'];
$workstate = $_POST['workstate'];
$workzip = $_POST['workzip'];
	
function verifyFormInput(){
		global $usr, $display, $gchat, $aim, $msn, $yahoo, $emailpers, $emailbusi, $cell, $homestreet, $homecity, $homestate, $homezip, $work, $workstreet, $workcity, $workstate, $workzip, $newline;
		$error_flag = FALSE;
		
		//Check Display Checkbox
		if ( $display == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: You did not let us know if you want to share your contact information.</font>" . $newline;
		}
		
		// Check Personal Email
		if ( $emailpers == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please input a value for the \"Personal E-Mail\".</font>" . $newline;
		}
		
		// Check Cell Phone
		if ( $cell1 == '' ){
			$error_flag = TRUE;			
			echo "<font color='red'>ERROR: Please input a value for the \"Cell Phone Area Code\".</font>" . $newline;
		}
		
		// Check Cell Phone
		if ( $cell2 == '' ){
			$error_flag = TRUE;			
			echo "<font color='red'>ERROR: Please input a value for the \"Cell Phone Number\".</font>" . $newline;
		}
		
		// Check Cell Phone
		if ( $cell3 == '' ){
			$error_flag = TRUE;			
			echo "<font color='red'>ERROR: Please input a value for the \"Cell Phone Number\".</font>" . $newline;
		}
		
		// Check Homes Street
		if ( $homestreet == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please input a value for the \"Home Street\".</font>" . $newline;
		}
		
		// Check Home City
		if ( $homecity == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please input a value for the \"Home City\".</font>" . $newline;
		}
		
		// Check Home State
		if ( $homestate == -1 ){
			$error_flag = TRUE;
			echo "<font color='orange'>ERROR: Please select your \"Home State\".</font>" . $newline;
		}
		
		// Check Home Zip
		if ( $homezip == '' ){
			$error_flag = TRUE;
			echo "<font color='orange'>ERROR: Please input a value for the \"Home Zipcode\".</font>" . $newline;
		}
		
		if ($error_flag){
			echo "There were errors that must be corrected before your info can be added/updated. You'll be redirected." . $newline;
			header("refresh:3;");
			return FALSE;
		}
		else{
			echo "Form data validated." . $newline;
			return TRUE;
		}
	}
	
	function updateDatabase(){
	
		global $usr, $display, $gchat, $aim, $msn, $yahoo, $emailpers, $emailbusi, $cell, $homestreet, $homecity, $homestate, $homezip, $work, $workstreet, $workcity, $workstate, $workzip, $newline;
	
		$con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
		if (!$con)
		{
			die("Could not connect: " . mysql_error());
		}
		
		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT username FROM contact WHERE username = '$usr'");
		$result = mysql_fetch_array($result);
		$query = FALSE;
		if( $result['username'] == "" ){
			echo "Adding new record..." . $newline;
			$query = mysql_query("INSERT INTO contact (username, display, gchat, aim, msn, yahoo, emailpers, emailbusi, cell, homestreet, homecity, homestate, homezip, work, workstreet, workcity, workstate, workzip) VALUES ('$usr', '$display', '$gchat', '$aim', '$msn', '$yahoo', '$emailpers', '$emailbusi', '$cell', '$homestreet', '$homecity', '$homestate', '$homezip', '$work', '$workstreet', '$workcity', '$workstate', '$workzip')");
			}
		else{
			echo "Updating existing record..." . $newline;
			$query = mysql_query("UPDATE contact SET display = '$display', gchat = '$gchat', aim = '$aim', msn = '$msn', yahoo = '$yahoo', emailpers = '$emailpers', emailbusi = '$emailbusi', cell = '$cell', homestreet = '$homestreet', homecity = '$homecity', homestate = '$homestate', homezip = '$homezip', work = '$work', workstreet = '$workstreet', workcity = '$workcity', workstate = '$workstate', workzip = '$workzip' WHERE username = '$usr'");
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
    <h5 class="nomargin">Edit Contact Information</h5><br />
	</td>
</tr>

<tr>
	<td colspan="2"><p class="noindent nomargin"><span class="small">Welcome <strong><?php echo $user ?></strong>! Please complete the following information.</span></p></td>
</tr>

<tr>
	<td colspan="2"><p class="noindent nomargin italic"><strong>Account Information</strong></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>User Name:</strong></p></td>
	<td width="75%"><p class="noindent"><textarea name="usr" rows="1" readonly><?php echo $user ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="noindent"><strong>Do you want your contact information displayed?</strong></p></td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$disp = $row['display'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%">&nbsp;</td>
    <td width="75%">
   	<p class="noindent">
    <input name="display" type="radio" value="1" <?php if ($disp == "1") echo "checked=\"checked\" "; ?>>Yes
    &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
   	<input name="display" type="radio" value="0" <?php if ($disp == "0") echo "checked=\"checked\" "; ?>>No
   	<br /></p></td>
</tr>

<tr>
	<td colspan="2"><br /><p class="noindent nomargin italic"><strong>Communications Information</strong></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Cell Phone:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="cll1" class="cell1" rows="1" name="cell1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
		echo $row['cell1'];
  		}
  
		mysql_close($con); ?></textarea>&nbsp;<textarea id="cll2" class="cell2" rows="1" name="cell2"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
		echo $row['cell2'];
  		}
  
		mysql_close($con); ?></textarea>&nbsp;<textarea id="cll3" class="cell3" rows="1" name="cell3"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
		echo $row['cell3'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Google Chat:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="ta2" rows="1" name="gchat"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['gchat'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>AOL Messenger:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="ta3" rows="1" name="aim"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['aim'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>MSN Messenger:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="ta4" rows="1" name="msn"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['msn'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Yahoo! Messenger:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="ta5" rows="1" name="yahoo"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['yahoo'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><br /><p class="noindent nomargin italic"><strong>Home Contact Information</strong></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Personal E-Mail:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea rows="1" name="emailpers"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['emailpers'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Personal Street:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea rows="1" name="homestreet"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['homestreet'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Personal City:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea rows="1" name="homecity"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['homecity'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$hmst = $row['homestate'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Personal State:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="homestate">
	          <option value='-1'>Select</option>
	          <option value="AL" <?php if ($hmst == "AL") echo "selected=\"selected\" "; ?>>Alabama</option>
              <option value="AK" <?php if ($hmst == "AK") echo "selected=\"selected\" "; ?>>Alaska</option>
              <option value="AZ" <?php if ($hmst == "AZ") echo "selected=\"selected\" "; ?>>Arizona</option>
              <option value="AR" <?php if ($hmst == "AR") echo "selected=\"selected\" "; ?>>Arkansas</option>
              <option value="CA" <?php if ($hmst == "CA") echo "selected=\"selected\" "; ?>>California</option>
              <option value="CO" <?php if ($hmst == "CO") echo "selected=\"selected\" "; ?>>Colorado</option>
              <option value="CT" <?php if ($hmst == "CT") echo "selected=\"selected\" "; ?>>Connecticut</option>
              <option value="DC" <?php if ($hmst == "DC") echo "selected=\"selected\" "; ?>>District of Columbia</option>
              <option value="DE" <?php if ($hmst == "DE") echo "selected=\"selected\" "; ?>>Delaware</option>
              <option value="FL" <?php if ($hmst == "FL") echo "selected=\"selected\" "; ?>>Florida</option>
              <option value="GA" <?php if ($hmst == "GA") echo "selected=\"selected\" "; ?>>Georgia</option>
              <option value="HI" <?php if ($hmst == "HI") echo "selected=\"selected\" "; ?>>Hawaii</option>
              <option value="ID" <?php if ($hmst == "ID") echo "selected=\"selected\" "; ?>>Idaho</option>
              <option value="IL" <?php if ($hmst == "IL") echo "selected=\"selected\" "; ?>>Illinois</option>
              <option value="IN" <?php if ($hmst == "IN") echo "selected=\"selected\" "; ?>>Indiana</option>
              <option value="IA" <?php if ($hmst == "IA") echo "selected=\"selected\" "; ?>>Iowa</option>
              <option value="KS" <?php if ($hmst == "KS") echo "selected=\"selected\" "; ?>>Kansas</option>
              <option value="KY" <?php if ($hmst == "KY") echo "selected=\"selected\" "; ?>>Kentucky</option>
              <option value="LA" <?php if ($hmst == "LA") echo "selected=\"selected\" "; ?>>Louisiana</option>
              <option value="ME" <?php if ($hmst == "ME") echo "selected=\"selected\" "; ?>>Maine</option>
              <option value="MD" <?php if ($hmst == "MD") echo "selected=\"selected\" "; ?>>Maryland</option>
              <option value="MA" <?php if ($hmst == "MA") echo "selected=\"selected\" "; ?>>Massachusetts</option>
              <option value="MI" <?php if ($hmst == "MI") echo "selected=\"selected\" "; ?>>Michigan</option>
              <option value="MN" <?php if ($hmst == "MN") echo "selected=\"selected\" "; ?>>Minnesota</option>
              <option value="MS" <?php if ($hmst == "MS") echo "selected=\"selected\" "; ?>>Mississippi</option>
              <option value="MO" <?php if ($hmst == "MO") echo "selected=\"selected\" "; ?>>Missouri</option>
              <option value="MT" <?php if ($hmst == "MT") echo "selected=\"selected\" "; ?>>Montana</option>
              <option value="NE" <?php if ($hmst == "NE") echo "selected=\"selected\" "; ?>>Nebraska</option>
              <option value="NV" <?php if ($hmst == "NV") echo "selected=\"selected\" "; ?>>Nevada</option>
              <option value="NH" <?php if ($hmst == "NH") echo "selected=\"selected\" "; ?>>New Hampshire</option>
              <option value="NJ" <?php if ($hmst == "NJ") echo "selected=\"selected\" "; ?>>New Jersey</option>
              <option value="NM" <?php if ($hmst == "NM") echo "selected=\"selected\" "; ?>>New Mexico</option>
              <option value="NY" <?php if ($hmst == "NY") echo "selected=\"selected\" "; ?>>New York</option>
              <option value="NC" <?php if ($hmst == "NC") echo "selected=\"selected\" "; ?>>North Carolina</option>
              <option value="ND" <?php if ($hmst == "ND") echo "selected=\"selected\" "; ?>>North Dakota</option>
              <option value="OH" <?php if ($hmst == "OH") echo "selected=\"selected\" "; ?>>Ohio</option>
              <option value="OK" <?php if ($hmst == "OK") echo "selected=\"selected\" "; ?>>Oklahoma</option>
              <option value="OR" <?php if ($hmst == "OR") echo "selected=\"selected\" "; ?>>Oregon</option>
              <option value="PA" <?php if ($hmst == "PA") echo "selected=\"selected\" "; ?>>Pennsylvania</option>
              <option value="RI" <?php if ($hmst == "RI") echo "selected=\"selected\" "; ?>>Rhode Island</option>
              <option value="SC" <?php if ($hmst == "SC") echo "selected=\"selected\" "; ?>>South Carolina</option>
              <option value="SD" <?php if ($hmst == "SD") echo "selected=\"selected\" "; ?>>South Dakota</option>
              <option value="TN" <?php if ($hmst == "TN") echo "selected=\"selected\" "; ?>>Tennessee</option>
              <option value="TX" <?php if ($hmst == "TX") echo "selected=\"selected\" "; ?>>Texas</option>
              <option value="UT" <?php if ($hmst == "UT") echo "selected=\"selected\" "; ?>>Utah</option>
              <option value="VT" <?php if ($hmst == "VT") echo "selected=\"selected\" "; ?>>Vermont</option>
              <option value="VA" <?php if ($hmst == "VA") echo "selected=\"selected\" "; ?>>Virginia</option>
              <option value="WA" <?php if ($hmst == "WA") echo "selected=\"selected\" "; ?>>Washington</option>
              <option value="WV" <?php if ($hmst == "WV") echo "selected=\"selected\" "; ?>>West Virginia</option>
              <option value="WI" <?php if ($hmst == "WI") echo "selected=\"selected\" "; ?>>Wisconsin</option>
              <option value="WY" <?php if ($hmst == "WY") echo "selected=\"selected\" "; ?>>Wyoming</option>
        </select>
        <br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Personal Zip:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="zpcd1" rows="1" name="homezip"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['homezip'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><br /><p class="noindent nomargin italic"><strong>Work Contact Information</strong></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Work E-Mail:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea rows="1" name="emailbusi"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['emailbusi'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Work Street:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea rows="1" name="workstreet"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['workstreet'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Work City:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea rows="1" name="workcity"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['workcity'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$wkst = $row['workstate'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Personal State:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="workstate">
	          <option value='-1'>Select</option>
	          <option value="AL" <?php if ($wkst == "AL") echo "selected=\"selected\" "; ?>>Alabama</option>
              <option value="AK" <?php if ($wkst == "AK") echo "selected=\"selected\" "; ?>>Alaska</option>
              <option value="AZ" <?php if ($wkst == "AZ") echo "selected=\"selected\" "; ?>>Arizona</option>
              <option value="AR" <?php if ($wkst == "AR") echo "selected=\"selected\" "; ?>>Arkansas</option>
              <option value="CA" <?php if ($wkst == "CA") echo "selected=\"selected\" "; ?>>California</option>
              <option value="CO" <?php if ($wkst == "CO") echo "selected=\"selected\" "; ?>>Colorado</option>
              <option value="CT" <?php if ($wkst == "CT") echo "selected=\"selected\" "; ?>>Connecticut</option>
              <option value="DC" <?php if ($wkst == "DC") echo "selected=\"selected\" "; ?>>District of Columbia</option>
              <option value="DE" <?php if ($wkst == "DE") echo "selected=\"selected\" "; ?>>Delaware</option>
              <option value="FL" <?php if ($wkst == "FL") echo "selected=\"selected\" "; ?>>Florida</option>
              <option value="GA" <?php if ($wkst == "GA") echo "selected=\"selected\" "; ?>>Georgia</option>
              <option value="HI" <?php if ($wkst == "HI") echo "selected=\"selected\" "; ?>>Hawaii</option>
              <option value="ID" <?php if ($wkst == "ID") echo "selected=\"selected\" "; ?>>Idaho</option>
              <option value="IL" <?php if ($wkst == "IL") echo "selected=\"selected\" "; ?>>Illinois</option>
              <option value="IN" <?php if ($wkst == "IN") echo "selected=\"selected\" "; ?>>Indiana</option>
              <option value="IA" <?php if ($wkst == "IA") echo "selected=\"selected\" "; ?>>Iowa</option>
              <option value="KS" <?php if ($wkst == "KS") echo "selected=\"selected\" "; ?>>Kansas</option>
              <option value="KY" <?php if ($wkst == "KY") echo "selected=\"selected\" "; ?>>Kentucky</option>
              <option value="LA" <?php if ($wkst == "LA") echo "selected=\"selected\" "; ?>>Louisiana</option>
              <option value="ME" <?php if ($wkst == "ME") echo "selected=\"selected\" "; ?>>Maine</option>
              <option value="MD" <?php if ($wkst == "MD") echo "selected=\"selected\" "; ?>>Maryland</option>
              <option value="MA" <?php if ($wkst == "MA") echo "selected=\"selected\" "; ?>>Massachusetts</option>
              <option value="MI" <?php if ($wkst == "MI") echo "selected=\"selected\" "; ?>>Michigan</option>
              <option value="MN" <?php if ($wkst == "MN") echo "selected=\"selected\" "; ?>>Minnesota</option>
              <option value="MS" <?php if ($wkst == "MS") echo "selected=\"selected\" "; ?>>Mississippi</option>
              <option value="MO" <?php if ($wkst == "MO") echo "selected=\"selected\" "; ?>>Missouri</option>
              <option value="MT" <?php if ($wkst == "MT") echo "selected=\"selected\" "; ?>>Montana</option>
              <option value="NE" <?php if ($wkst == "NE") echo "selected=\"selected\" "; ?>>Nebraska</option>
              <option value="NV" <?php if ($wkst == "NV") echo "selected=\"selected\" "; ?>>Nevada</option>
              <option value="NH" <?php if ($wkst == "NH") echo "selected=\"selected\" "; ?>>New Hampshire</option>
              <option value="NJ" <?php if ($wkst == "NJ") echo "selected=\"selected\" "; ?>>New Jersey</option>
              <option value="NM" <?php if ($wkst == "NM") echo "selected=\"selected\" "; ?>>New Mexico</option>
              <option value="NY" <?php if ($wkst == "NY") echo "selected=\"selected\" "; ?>>New York</option>
              <option value="NC" <?php if ($wkst == "NC") echo "selected=\"selected\" "; ?>>North Carolina</option>
              <option value="ND" <?php if ($wkst == "ND") echo "selected=\"selected\" "; ?>>North Dakota</option>
              <option value="OH" <?php if ($wkst == "OH") echo "selected=\"selected\" "; ?>>Ohio</option>
              <option value="OK" <?php if ($wkst == "OK") echo "selected=\"selected\" "; ?>>Oklahoma</option>
              <option value="OR" <?php if ($wkst == "OR") echo "selected=\"selected\" "; ?>>Oregon</option>
              <option value="PA" <?php if ($wkst == "PA") echo "selected=\"selected\" "; ?>>Pennsylvania</option>
              <option value="RI" <?php if ($wkst == "RI") echo "selected=\"selected\" "; ?>>Rhode Island</option>
              <option value="SC" <?php if ($wkst == "SC") echo "selected=\"selected\" "; ?>>South Carolina</option>
              <option value="SD" <?php if ($wkst == "SD") echo "selected=\"selected\" "; ?>>South Dakota</option>
              <option value="TN" <?php if ($wkst == "TN") echo "selected=\"selected\" "; ?>>Tennessee</option>
              <option value="TX" <?php if ($wkst == "TX") echo "selected=\"selected\" "; ?>>Texas</option>
              <option value="UT" <?php if ($wkst == "UT") echo "selected=\"selected\" "; ?>>Utah</option>
              <option value="VT" <?php if ($wkst == "VT") echo "selected=\"selected\" "; ?>>Vermont</option>
              <option value="VA" <?php if ($wkst == "VA") echo "selected=\"selected\" "; ?>>Virginia</option>
              <option value="WA" <?php if ($wkst == "WA") echo "selected=\"selected\" "; ?>>Washington</option>
              <option value="WV" <?php if ($wkst == "WV") echo "selected=\"selected\" "; ?>>West Virginia</option>
              <option value="WI" <?php if ($wkst == "WI") echo "selected=\"selected\" "; ?>>Wisconsin</option>
              <option value="WY" <?php if ($wkst == "WY") echo "selected=\"selected\" "; ?>>Wyoming</option>
        </select>
        <br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Work Zip:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="zpcd2" rows="1" name="workzip"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM contact WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['workzip'];
  		}
  
		mysql_close($con);?></textarea><br /></p></td>
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
