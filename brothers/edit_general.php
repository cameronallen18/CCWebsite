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
$firstname = $_POST['firstname'];
$middleinitial = $_POST['middleinitial'];
$lastname = $_POST['lastname'];
$suffix = $_POST['suffix'];
$year = $_POST['year'];
$activities = $_POST['activities'];
$major1 = $_POST['major1'];
$major2 = $_POST['major2'];
$minor1 = $_POST['minor1'];
$minor2 = $_POST['minor2'];
$occupation = $_POST['occupation'];
$homecity = $_POST['homecity'];
$homestate = $_POST['homestate'];
$website = $_POST['website'];
$image = $_POST['image'];

// File paths
$target_path_photo = $_FILES['photo_file']['tmp_name'];
	
function verifyFormInput(){
		global $usr, $firstname, $middleinitial, $lastname, $suffix, $year, $activities, $major1, $major2, $minor1, $minor2, $occupation, $homecity, $homestate, $website, $image, $target_path_photo, $newline;
		$error_flag = FALSE;
		
		// Check First Name
		if ( $firstname == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please input a value for the \"First Name\".</font>" . $newline;
		}
		
		// Check Last Name
		if ( $lastname == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please input a value for the \"Last Name\".</font>" . $newline;
		}
		
		// Check Year
		if ( $year == '' ){
			$error_flag = TRUE;			
			echo "<font color='red'>ERROR: Please input a value for the \"Year\".</font>" . $newline;
		}
		
		// Check Activities
		if ( $activities == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please list any activities you participated in while at MIT.</font>" . $newline;
		}
		
		// Check Primary Major
		if ( $major1 == -1 ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please select your primary major.</font>" . $newline;
		}
		
		// Check Second Major
		if ( $major2 == -1 ){
			$major2 = '0';
			echo "<font color='orange'>Warning: You did not list a second major.</font>" . $newline;
		}
		
		// Check Primary Minor
		if ( $minor1 == -1 ){
			$minor1 = '0';
			echo "<font color='orange'>Warning: You did not list a primary minor.</font>" . $newline;
		}
		
		// Check Second Minor
		if ( $minor2 == -1 ){
			$minor2 = '0';
			echo "<font color='orange'>Warning: You did not list a second minor.</font>" . $newline;
		}
		
		// Check Occupation
		if ( $occupation == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please list your \"Occupation\". If you're a student, indicate that.</font>" . $newline;
		}
		
		// Check Home City
		if ( $homecity == '' ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please list your \"Home City\".</font>" . $newline;
		}
		
		// Check Home State
		if ( $homestate == -1 ){
			$error_flag = TRUE;
			echo "<font color='red'>ERROR: Please select your \"Home State\".</font>" . $newline;
		}
		
		// Check image path
		if ( $image == 0 ) {
			if ( $target_path_photo == '' ){
				echo "<font color='orange'>WARNING: You chose not to upload a picture, so the CC logo will be used.</font>" . $newline;
			}	
		}
		
		if ($error_flag){
			echo "There were errors that must be corrected before your info can be added/updated. You'll be redirected" . $newline;
			header("refresh:3;");
			return FALSE;
		}
		else{
			echo "Form data validated." . $newline;
			return TRUE;
		}
	}
	
	function checkFileSize(){
		global $newline;
		// Check file size
		$MAX_FILE_SIZE_PHOTO = (int)$_POST['MAX_FILE_SIZE_PHOTO'];
		
		if( filesize($_FILES['photo_file']['tmp_name']) > $MAX_FILE_SIZE_PHOTO ){
			echo "The file size of your photo is too large. The file size must be less than <B>"
				. $MAX_FILE_SIZE_PHOTO . " bytes</B>." . $newline;
			header("refresh:3;");
			return FALSE;
		}
		
		echo "File size validated." . $newline;
		return TRUE;
	}
	
	
	function checkFileExtension() {
		global $newline;
		
		// Check file extension
		$path_parts_photo = pathinfo($_FILES['photo_file']['name']);
		
		if( $_FILES['photo_file']['tmp_name'] != ''){
			if( strtolower($path_parts_photo['extension']) != 'jpg' ){
				echo strtolower($_FILES['photo_file']['name']) . $newline;
				echo "Your photo must be in .JPG format." . $newline;
				header("refresh:3;");
				return FALSE;
			}
		}
		
		echo "File extensions validated." . $newline;
		return TRUE;
	}
	
	function moveFiles(){
		// Global variables
		global $usr, $year, $target_path_photo, $tpp_used, $newline;
		
		$target_path_photo = "brothers/" . $year . "/" . basename( $usr . ".jpg");
		$tpp_used = $year . "/" . basename( $usr . ".jpg");
		
		// Try moving the files
		if( $_FILES['photo_file']['tmp_name'] != ''){
			move_uploaded_file($_FILES['photo_file']['tmp_name'], $tpp_used);
			echo "Your files were successfully uploaded." . $newline;
			return TRUE;
		}
		else{
			$target_path_photo = NULL;
			header("refresh:3;");
		}
	}
	
	function updateDatabase(){
	
		global $usr, $firstname, $middleinitial, $lastname, $suffix, $year, $activities, $major1, $major2, $minor1, $minor2, $occupation, $homecity, $homestate, $website, $image, $target_path_photo, $newline;
	
		$con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
		if (!$con)
		{
			die("Could not connect: " . mysql_error());
		}
		
		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT username FROM general WHERE username = '$usr'");
		$result = mysql_fetch_array($result);
		$query = FALSE;
		if( $result['username'] == "" ){
			echo "Adding new record..." . $newline;
			$query = mysql_query("INSERT INTO general (username, firstname, middleinitial, lastname, suffix, year, activities, major1, major2, minor1, minor2, occupation, homecity, homestate, website, image) VALUES ('$usr', '$firstname', '$middleinitial', '$lastname', '$suffix', '$year', '$activities', '$major1', '$major2', '$minor1', '$minor2', '$occupation', '$homecity', '$homestate', '$website', '$target_path_photo')");
			}
		else{
			echo "Updating existing record..." . $newline;
			$query = mysql_query("UPDATE general SET firstname = '$firstname', middleinitial = '$middleinitial',  lastname = '$lastname', suffix = '$suffix', year = '$year', activities = '$activities', major1 = '$major1', major2 = '$major2', minor1 = '$minor1', minor2 = '$minor2', occupation = '$occupation', homecity = '$homecity', homestate = '$homestate', website = '$website', image = '$target_path_photo' WHERE username = '$usr'");
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

</head>


<body>

<table width="600px" border="0" cellspacing="0" cellpadding="0" align="center">
<tr><td>



<FORM METHOD="POST" ACTION="<?php echo $PHP_SELF;?>" enctype='multipart/form-data' name='input' id='input'>

<table width="600px" border="0" cellpadding="0" cellspacing="0" align="center">
<tr>
	<td colspan="2">
    <h5 class="nomargin">Edit General Information</h5><br />
	</td>
</tr>

<tr>
	<td colspan="2"><p class="noindent nomargin"><span class="small">Welcome <strong><?php echo $user ?></strong>! Please complete the following information.</span></p></td>
</tr>

<tr>
	<td colspan="2"><p class="noindent nomargin italic"><strong>Name</strong></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>User Name:</strong></p></td>
	<td width="75%"><p class="noindent"><textarea name="usr" rows="1" readonly><?php echo $user ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>First Name:</strong></p></td>
	<td width="75%"><p class="noindent"><textarea id="ta1" name="firstname" rows="1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['firstname'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small">&nbsp;</span></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Middle Initial:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea name="middleinitial" rows="1" id="mid"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['middleinitial'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small"><strong>Note:</strong> The input will only allow a maximum of "2" characters. <span id="midl">2</span> characters left.</span></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Last Name:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="ta2" id="ta" name="lastname" rows="1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['lastname'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small">&nbsp;</span></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Suffix:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="suf1" name="suffix" rows="1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['suffix'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small"><strong>Note:</strong> The input will only allow a maximum of "4" characters. <span id="suf1l">4</span> characters left.</span></p></td>
</tr>

<tr>
	<td colspan="2"><br /><p class="noindent nomargin italic"><strong>MIT Related Information</strong></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Class Year:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="suf2" name="year" rows="1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['year'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small"><strong>Note:</strong> The input will only allow a maximum of "4" characters. <span id="suf2l">4</span> characters left.</span></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small">Please input the class year you most identify with.</span></p></td>
</tr>

<tr>
	<td width="25%" valign="top"><p class="noindent"><strong>Activities:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="avt" rows="5" name="activities"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['activities'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small"><strong>Note:</strong> The input will only allow a maximum of "250" characters. <span id="avtl">250</span> characters left.</span></p></td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$mjr1 = $row['major1'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Primary Major:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="major1">
       <option value='-1'>Select</option>
       <option value="1" <?php if ($mjr1 == "1") echo "selected=\"selected\" "; ?>>Aeronautics and Astronautics</option>
       <option value="2" <?php if ($mjr1 == "2") echo "selected=\"selected\" "; ?>>Anthropology</option>
       <option value="3" <?php if ($mjr1 == "3") echo "selected=\"selected\" "; ?>>Architecture</option>
       <option value="4" <?php if ($mjr1 == "4") echo "selected=\"selected\" "; ?>>Biological Engineering</option>
       <option value="5" <?php if ($mjr1 == "5") echo "selected=\"selected\" "; ?>>Biology</option>
       <option value="6" <?php if ($mjr1 == "6") echo "selected=\"selected\" "; ?>>Brain and Cognitive Sciences</option>
       <option value="7" <?php if ($mjr1 == "7") echo "selected=\"selected\" "; ?>>Business (Sloan School of Management)</option>
       <option value="8" <?php if ($mjr1 == "8") echo "selected=\"selected\" "; ?>>Chemical Engineering</option>
       <option value="9" <?php if ($mjr1 == "9") echo "selected=\"selected\" "; ?>>Chemistry</option>
       <option value="10" <?php if ($mjr1 == "10") echo "selected=\"selected\" "; ?>>Civil and Environmental Engineering</option>
       <option value="11" <?php if ($mjr1 == "11") echo "selected=\"selected\" "; ?>>Comparative Media Studies</option>
       <option value="12" <?php if ($mjr1 == "12") echo "selected=\"selected\" ";?>>Earth, Atmospheric, and Planetary Sciences</option>
       <option value="13" <?php if ($mjr1 == "13") echo "selected=\"selected\" "; ?>>Economics</option>
       <option value="14" <?php if ($mjr1 == "14") echo "selected=\"selected\" ";?>>Electrical Engineering/Computer Science</option>
       <option value="15" <?php if ($mjr1 == "15") echo "selected=\"selected\" "; ?>>Engineering Systems Division</option>
       <option value="16" <?php if ($mjr1 == "16") echo "selected=\"selected\" "; ?>>Foreign Languages and Literatures</option>
       <option value="17" <?php if ($mjr1 == "17") echo "selected=\"selected\" "; ?>>Health Sciences and Technology</option>
       <option value="18" <?php if ($mjr1 == "18") echo "selected=\"selected\" "; ?>>History</option>
       <option value="19" <?php if ($mjr1 == "19") echo "selected=\"selected\" "; ?>>Linguistics and Philosophy</option>
       <option value="20" <?php if ($mjr1 == "20") echo "selected=\"selected\" "; ?>>Literature</option>
       <option value="21" <?php if ($mjr1 == "21") echo "selected=\"selected\" "; ?>>Materials Science and Engineering</option>
       <option value="22" <?php if ($mjr1 == "22") echo "selected=\"selected\" "; ?>>Mathematics</option>
       <option value="23" <?php if ($mjr1 == "23") echo "selected=\"selected\" "; ?>>Mechanical Engineering</option>
       <option value="24" <?php if ($mjr1 == "24") echo "selected=\"selected\" "; ?>>Media Arts and Sciences (Media Lab)</option>
       <option value="25" <?php if ($mjr1 == "25") echo "selected=\"selected\" "; ?>>Music and Theater Arts</option>
       <option value="26" <?php if ($mjr1 == "26") echo "selected=\"selected\" "; ?>>Nuclear Science and Engineering</option>
       <option value="27" <?php if ($mjr1 == "27") echo "selected=\"selected\" "; ?>>Physics</option>
       <option value="28" <?php if ($mjr1 == "28") echo "selected=\"selected\" "; ?>>Political Science</option>
       <option value="29" <?php if ($mjr1 == "29") echo "selected=\"selected\" "; ?>>Science, Technology, and Society</option>
       <option value="30" <?php if ($mjr1 == "30") echo "selected=\"selected\" "; ?>>Urban Studies and Planning</option>
       <option value="31" <?php if ($mjr1 == "31") echo "selected=\"selected\" "; ?>>Writing and Humanistic Studies</option>
	</select>
        <br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small">&nbsp;</span></p></td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$mjr2 = $row['major2'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Second Major:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="major2">
       <option value='-1'>Select</option>
       <option value="1" <?php if ($mjr2 == "1") echo "selected=\"selected\" "; ?>>Aeronautics and Astronautics</option>
       <option value="2" <?php if ($mjr2 == "2") echo "selected=\"selected\" "; ?>>Anthropology</option>
       <option value="3" <?php if ($mjr2 == "3") echo "selected=\"selected\" "; ?>>Architecture</option>
       <option value="4" <?php if ($mjr2 == "4") echo "selected=\"selected\" "; ?>>Biological Engineering</option>
       <option value="5" <?php if ($mjr2 == "5") echo "selected=\"selected\" "; ?>>Biology</option>
       <option value="6" <?php if ($mjr2 == "6") echo "selected=\"selected\" "; ?>>Brain and Cognitive Sciences</option>
       <option value="7" <?php if ($mjr2 == "7") echo "selected=\"selected\" "; ?>>Business (Sloan School of Management)</option>
       <option value="8" <?php if ($mjr2 == "8") echo "selected=\"selected\" "; ?>>Chemical Engineering</option>
       <option value="9" <?php if ($mjr2 == "9") echo "selected=\"selected\" "; ?>>Chemistry</option>
       <option value="10" <?php if ($mjr2 == "10") echo "selected=\"selected\" "; ?>>Civil and Environmental Engineering</option>
       <option value="11" <?php if ($mjr2 == "11") echo "selected=\"selected\" "; ?>>Comparative Media Studies</option>
       <option value="12" <?php if ($mjr2 == "12") echo "selected=\"selected\" ";?>>Earth, Atmospheric, and Planetary Sciences</option>
       <option value="13" <?php if ($mjr2 == "13") echo "selected=\"selected\" "; ?>>Economics</option>
       <option value="14" <?php if ($mjr2 == "14") echo "selected=\"selected\" ";?>>Electrical Engineering/Computer Science</option>
       <option value="15" <?php if ($mjr2 == "15") echo "selected=\"selected\" "; ?>>Engineering Systems Division</option>
       <option value="16" <?php if ($mjr2 == "16") echo "selected=\"selected\" "; ?>>Foreign Languages and Literatures</option>
       <option value="17" <?php if ($mjr2 == "17") echo "selected=\"selected\" "; ?>>Health Sciences and Technology</option>
       <option value="18" <?php if ($mjr2 == "18") echo "selected=\"selected\" "; ?>>History</option>
       <option value="19" <?php if ($mjr2 == "19") echo "selected=\"selected\" "; ?>>Linguistics and Philosophy</option>
       <option value="20" <?php if ($mjr2 == "20") echo "selected=\"selected\" "; ?>>Literature</option>
       <option value="21" <?php if ($mjr2 == "21") echo "selected=\"selected\" "; ?>>Materials Science and Engineering</option>
       <option value="22" <?php if ($mjr2 == "22") echo "selected=\"selected\" "; ?>>Mathematics</option>
       <option value="23" <?php if ($mjr2 == "23") echo "selected=\"selected\" "; ?>>Mechanical Engineering</option>
       <option value="24" <?php if ($mjr2 == "24") echo "selected=\"selected\" "; ?>>Media Arts and Sciences (Media Lab)</option>
       <option value="25" <?php if ($mjr2 == "25") echo "selected=\"selected\" "; ?>>Music and Theater Arts</option>
       <option value="26" <?php if ($mjr2 == "26") echo "selected=\"selected\" "; ?>>Nuclear Science and Engineering</option>
       <option value="27" <?php if ($mjr2 == "27") echo "selected=\"selected\" "; ?>>Physics</option>
       <option value="28" <?php if ($mjr2 == "28") echo "selected=\"selected\" "; ?>>Political Science</option>
       <option value="29" <?php if ($mjr2 == "29") echo "selected=\"selected\" "; ?>>Science, Technology, and Society</option>
       <option value="30" <?php if ($mjr2 == "30") echo "selected=\"selected\" "; ?>>Urban Studies and Planning</option>
       <option value="31" <?php if ($mjr2 == "31") echo "selected=\"selected\" "; ?>>Writing and Humanistic Studies</option>
	</select>
    <br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small">**If applicable.</span></p></td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$mnr1 = $row['minor1'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Primary Minor:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="minor1">
       <option value='-1'>Select</option>
       <option value="1" <?php if ($mnr1 == "1") echo "selected=\"selected\" "; ?>>Aeronautics and Astronautics</option>
       <option value="2" <?php if ($mnr1 == "2") echo "selected=\"selected\" "; ?>>Anthropology</option>
       <option value="3" <?php if ($mnr1 == "3") echo "selected=\"selected\" "; ?>>Architecture</option>
       <option value="4" <?php if ($mnr1 == "4") echo "selected=\"selected\" "; ?>>Biological Engineering</option>
       <option value="5" <?php if ($mnr1 == "5") echo "selected=\"selected\" "; ?>>Biology</option>
       <option value="6" <?php if ($mnr1 == "6") echo "selected=\"selected\" "; ?>>Brain and Cognitive Sciences</option>
       <option value="7" <?php if ($mnr1 == "7") echo "selected=\"selected\" "; ?>>Business (Sloan School of Management)</option>
       <option value="8" <?php if ($mnr1 == "8") echo "selected=\"selected\" "; ?>>Chemical Engineering</option>
       <option value="9" <?php if ($mnr1 == "9") echo "selected=\"selected\" "; ?>>Chemistry</option>
       <option value="10" <?php if ($mnr1 == "10") echo "selected=\"selected\" "; ?>>Civil and Environmental Engineering</option>
       <option value="11" <?php if ($mnr1 == "11") echo "selected=\"selected\" "; ?>>Comparative Media Studies</option>
       <option value="12" <?php if ($mnr1 == "12") echo "selected=\"selected\" ";?>>Earth, Atmospheric, and Planetary Sciences</option>
       <option value="13" <?php if ($mnr1 == "13") echo "selected=\"selected\" "; ?>>Economics</option>
       <option value="14" <?php if ($mnr1 == "14") echo "selected=\"selected\" ";?>>Electrical Engineering/Computer Science</option>
       <option value="15" <?php if ($mnr1 == "15") echo "selected=\"selected\" "; ?>>Engineering Systems Division</option>
       <option value="16" <?php if ($mnr1 == "16") echo "selected=\"selected\" "; ?>>Foreign Languages and Literatures</option>
       <option value="17" <?php if ($mnr1 == "17") echo "selected=\"selected\" "; ?>>Health Sciences and Technology</option>
       <option value="18" <?php if ($mnr1 == "18") echo "selected=\"selected\" "; ?>>History</option>
       <option value="19" <?php if ($mnr1 == "19") echo "selected=\"selected\" "; ?>>Linguistics and Philosophy</option>
       <option value="20" <?php if ($mnr1 == "20") echo "selected=\"selected\" "; ?>>Literature</option>
       <option value="21" <?php if ($mnr1 == "21") echo "selected=\"selected\" "; ?>>Materials Science and Engineering</option>
       <option value="22" <?php if ($mnr1 == "22") echo "selected=\"selected\" "; ?>>Mathematics</option>
       <option value="23" <?php if ($mnr1 == "23") echo "selected=\"selected\" "; ?>>Mechanical Engineering</option>
       <option value="24" <?php if ($mnr1 == "24") echo "selected=\"selected\" "; ?>>Media Arts and Sciences (Media Lab)</option>
       <option value="25" <?php if ($mnr1 == "25") echo "selected=\"selected\" "; ?>>Music and Theater Arts</option>
       <option value="26" <?php if ($mnr1 == "26") echo "selected=\"selected\" "; ?>>Nuclear Science and Engineering</option>
       <option value="27" <?php if ($mnr1 == "27") echo "selected=\"selected\" "; ?>>Physics</option>
       <option value="28" <?php if ($mnr1 == "28") echo "selected=\"selected\" "; ?>>Political Science</option>
       <option value="29" <?php if ($mnr1 == "29") echo "selected=\"selected\" "; ?>>Science, Technology, and Society</option>
       <option value="30" <?php if ($mnr1 == "30") echo "selected=\"selected\" "; ?>>Urban Studies and Planning</option>
       <option value="31" <?php if ($mnr1 == "31") echo "selected=\"selected\" "; ?>>Writing and Humanistic Studies</option>
	</select>
    <br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small">**If applicable.</span></p></td>
</tr>

<?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$mnr2 = $row['minor2'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Second Minor:</strong></p></td>
    <td width="75%"><p class="noindent">
    <select name="minor2">
       <option value='-1'>Select</option>
       <option value="1" <?php if ($mnr2 == "1") echo "selected=\"selected\" "; ?>>Aeronautics and Astronautics</option>
       <option value="2" <?php if ($mnr2 == "2") echo "selected=\"selected\" "; ?>>Anthropology</option>
       <option value="3" <?php if ($mnr2 == "3") echo "selected=\"selected\" "; ?>>Architecture</option>
       <option value="4" <?php if ($mnr2 == "4") echo "selected=\"selected\" "; ?>>Biological Engineering</option>
       <option value="5" <?php if ($mnr2 == "5") echo "selected=\"selected\" "; ?>>Biology</option>
       <option value="6" <?php if ($mnr2 == "6") echo "selected=\"selected\" "; ?>>Brain and Cognitive Sciences</option>
       <option value="7" <?php if ($mnr2 == "7") echo "selected=\"selected\" "; ?>>Business (Sloan School of Management)</option>
       <option value="8" <?php if ($mnr2 == "8") echo "selected=\"selected\" "; ?>>Chemical Engineering</option>
       <option value="9" <?php if ($mnr2 == "9") echo "selected=\"selected\" "; ?>>Chemistry</option>
       <option value="10" <?php if ($mnr2 == "10") echo "selected=\"selected\" "; ?>>Civil and Environmental Engineering</option>
       <option value="11" <?php if ($mnr2 == "11") echo "selected=\"selected\" "; ?>>Comparative Media Studies</option>
       <option value="12" <?php if ($mnr2 == "12") echo "selected=\"selected\" ";?>>Earth, Atmospheric, and Planetary Sciences</option>
       <option value="13" <?php if ($mnr2 == "13") echo "selected=\"selected\" "; ?>>Economics</option>
       <option value="14" <?php if ($mnr2 == "14") echo "selected=\"selected\" ";?>>Electrical Engineering/Computer Science</option>
       <option value="15" <?php if ($mnr2 == "15") echo "selected=\"selected\" "; ?>>Engineering Systems Division</option>
       <option value="16" <?php if ($mnr2 == "16") echo "selected=\"selected\" "; ?>>Foreign Languages and Literatures</option>
       <option value="17" <?php if ($mnr2 == "17") echo "selected=\"selected\" "; ?>>Health Sciences and Technology</option>
       <option value="18" <?php if ($mnr2 == "18") echo "selected=\"selected\" "; ?>>History</option>
       <option value="19" <?php if ($mnr2 == "19") echo "selected=\"selected\" "; ?>>Linguistics and Philosophy</option>
       <option value="20" <?php if ($mnr2 == "20") echo "selected=\"selected\" "; ?>>Literature</option>
       <option value="21" <?php if ($mnr2 == "21") echo "selected=\"selected\" "; ?>>Materials Science and Engineering</option>
       <option value="22" <?php if ($mnr2 == "22") echo "selected=\"selected\" "; ?>>Mathematics</option>
       <option value="23" <?php if ($mnr2 == "23") echo "selected=\"selected\" "; ?>>Mechanical Engineering</option>
       <option value="24" <?php if ($mnr2 == "24") echo "selected=\"selected\" "; ?>>Media Arts and Sciences (Media Lab)</option>
       <option value="25" <?php if ($mnr2 == "25") echo "selected=\"selected\" "; ?>>Music and Theater Arts</option>
       <option value="26" <?php if ($mnr2 == "26") echo "selected=\"selected\" "; ?>>Nuclear Science and Engineering</option>
       <option value="27" <?php if ($mnr2 == "27") echo "selected=\"selected\" "; ?>>Physics</option>
       <option value="28" <?php if ($mnr2 == "28") echo "selected=\"selected\" "; ?>>Political Science</option>
       <option value="29" <?php if ($mnr2 == "29") echo "selected=\"selected\" "; ?>>Science, Technology, and Society</option>
       <option value="30" <?php if ($mnr2 == "30") echo "selected=\"selected\" "; ?>>Urban Studies and Planning</option>
       <option value="31" <?php if ($mnr2 == "31") echo "selected=\"selected\" "; ?>>Writing and Humanistic Studies</option>
	</select>
    <br /></p></td>
</tr>

<tr>
	<td colspan="2"><p class="nomargin"><span class="small">**If applicable.</span></p></td>
</tr>

<tr>
	<td colspan="2"><br /><p class="noindent nomargin italic"><strong>Other General Information</strong></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Occupation:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="ta3" name="occupation" rows="1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['occupation'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Hometown-City:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="ta4" name="homecity" rows="1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
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

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  			$hmst = $row['homestate'];
  		}
  
		mysql_close($con); 
?>

<tr>
	<td width="25%"><p class="noindent"><strong>Hometown-State:</strong></p></td>
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
	<td width="25%"><p class="noindent"><strong>Personal Website:</strong></p></td>
    <td width="75%"><p class="noindent"><textarea id="wbst" name="website" rows="1"><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		echo $row['website'];
  		}
  
		mysql_close($con); ?></textarea><br /></p></td>
</tr>

<tr>
	<td width="25%"><p class="noindent"><strong>Image File:</strong></p></td>
    <td width="75%"><p class="noindent"><input type='hidden' name='MAX_FILE_SIZE_PHOTO' value='300000'><?php $con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
	if (!$con)
  		{
  		die('Could not connect: ' . mysql_error());
  		}

		mysql_select_db("chocolate-city+brothers", $con);

		$result = mysql_query("SELECT * FROM general WHERE username='$username'");
		while($row = mysql_fetch_array($result))
  		{
  		$img = $row['image'];
  		}
  
		mysql_close($con); 
		
		if ($img == NULL) {
			echo "<input name=\"photo_file\" type=\"file\">";
			echo "<input name=\"image\" type=\"hidden\" value=\"0\">";
			
		}else{
			echo $img;
			echo "<input name=\"image\" type=\"hidden\" value=\"1\">";
		}
		
		?><br /></p></td>
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
	if (!checkFileSize())
		endPage();
	if (!checkFileExtension())
		endPage();
	if (!moveFiles())
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
