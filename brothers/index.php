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
$brothers = "http://ccity.mit.edu/brothers";
$base_dir = "http://ccity.mit.edu/login";

//access information from authorize table
$con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("chocolate-city+profile", $con);

$result = mysql_query("SELECT * FROM authorize WHERE username='$username'");

while($row = mysql_fetch_array($result))
  {
  $firstname	= $row['firstname'];
  $lastname 	= $row['lastname'];
  $email		= $row['email'];
  $group1		= $row['group1'];
  $group2		= $row['group2'];
  $group3		= $row['group3'];
  $redirect		= $row['redirect'];
  $last_login	= $row['last_login'];
  $_SESSION[username]	= $row['username'];
  }
  
mysql_close($con);

//check is username exists
if (!isset($username)) header("refresh:0; url='../login/login.html'");

//check is user a Brother of Chocolate City
if (($group1 || $group2 || $group3) != "Brothers") 
	{
	header("refresh:3; url='http://ccity.mit.edu'");
	echo "You must be a \"Brother\" to view this page.<br />";
	echo "You will be redirected to the \"Home Page\" in 3 seconds.";
	exit;
	}

//access database to gather general profile information
$con = mysql_connect("sql.mit.edu","chocolate-city","qweruiop");
if (!$con)
  {
  die('Could not connect: ' . mysql_error());
  }

mysql_select_db("chocolate-city+brothers", $con);

$result = mysql_query("SELECT * FROM general WHERE username='$username'");

while($row = mysql_fetch_array($result))
  {
  $fname		= $row['firstname'];
  $midname	 	= $row['middleinitial'];
  $lname		= $row['lastname'];
  $suffix		= $row['suffix'];
  $year			= $row['year'];
  $activities	= $row['activities'];
  $major1		= $row['major1'];
  $major2		= $row['major2'];
  $minor1		= $row['minor1'];
  $minor2		= $row['minor2'];
  $occupation	= $row['occupation'];
  $hmcity		= $row['homecity'];
  $hmstate		= $row['homestate'];
  $website		= $row['website'];
  $image		= $row['image'];
  }
  
mysql_close($con);

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"

"http://www.w3.org/TR/html4/loose.dtd"><html>

<head>

<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Control Panel</title>
<link rel="stylesheet" type="text/css" href="../styles/styles.css" />
<link rel="stylesheet" type="text/css" href="../styles/brothers.css" />

</head>
<body style="margin:10px;">

<div id="content">

<div id="main">
<div id="current" style="background-color:#000;">
Welcome <?php echo $firstname; ?> <?php echo $lastname; ?>!<br />
You're logged in as <?php echo $username; ?>. <a href="<?php echo $base_dir; ?>">Not you?</a>
</div>

<div id="today" style="background-color:#000;">
Today is <?php echo date("l") ?>, <?php echo date("F dS") ?>, <?php echo date("Y") ?><br/>
Your last log-in was <?php echo $last_login; ?>
</div>
</div>

<p>&nbsp;</p>

<div id="main">

<center>

<div id="profdisp">
<h6 class="lefT nomargin">Control Panel</h6>
<p class="nomargin">What would you like to do today?</p>

  <div>
	<div id="proflink">
    <form>
    <INPUT TYPE="button" value="Answer Questions" onClick="location.href='<?php echo "edit_bio.php?username=" . $username;?>'">
    </form>
    </div>
    
    <div id="proflink">
    <form>
    <INPUT TYPE="button" value="Contact Information" onClick="location.href='<?php echo "edit_contact.php?username=" . $username;?>'">
    </form>
    </div>
    
    <div id="proflink">
    <form>
    <INPUT TYPE="button" value="General Information" onClick="location.href='<?php echo "edit_general.php?username=" . $username;?>'">
    </form>
    </div>
    
    <div id="proflink">
    <form method="post" action="<?php echo "delete_image.php?username=" . $username;?>" onSubmit="return confirm('Are you sure you want to delete your profile picture?');">
	<INPUT TYPE="submit" NAME="submit" VALUE="Purge Pofile Image">
    </form>
    </div>
    
    <br clear="right">
    
    <div id="proflink">
    <form>
    <INPUT TYPE="button" value="Preview My Profile" onClick="location.href='<?php echo "display.php?username=" . $username;?>'">
    </form>
    </div>
    
    <div id="proflink">
    <form>
    <INPUT TYPE="button" value="Return to the Index" onClick="location.href='http://ccity.mit.edu'">
    </form>
    </div>

	<div id="proflink">
    <form>
    <INPUT TYPE="button" value="Change Password" onClick="location.href='<?php echo "$base_dir/pass_change.html"; ?>'">
    </form>
    </div>
    
    <div id="proflink">
    <form>
    <INPUT TYPE="button" value="Logout My Account" onClick="location.href='../login/logout.php'">
    </form>
    </div>
  </div>

</div>

<p>&nbsp;</p>

<div id="profdisp">
<h6 class="lefT nomargin"><?php 
        
        if (($suffix == NULL) && ($midname == NULL)) {
            echo $fname . " " . $lname . "'s Chocolate City Profile";
        }elseif ($suffix == NULL) {
            echo $fname . " " . $midname . " " . $lname . "'s Chocolate City Profile";
        }else{
            echo $fname . " " . $lname . " " . $suffix . "'s Chocolate City Profile";	
        }
        
        ?></h6>

<div id="profpic-container">
    <div id="profpic">
      	<?php 
        if ($image == NULL) {
            echo "<img id=\"profpic\" src=\"../images/cclogo.gif\">";
        }else {
            echo "<img id=\"profpic\" src=\"../" . $image . "\">";
        }
        ?>
    </div>
</div>

<div id="proftab-container">
	<div id="proftab">
    <a href="something.php">
    <p class="noindent nomargin center">
    General Info
    </p>
    </a>
    </div>
    
    <div id="proftab">
    <a href="something.php">
    <p class="noindent nomargin center">
    Contact Info
    </p>
    <span>
    Testing
    </span>
    </a>
    </div>
</div>



<div id="proftext-container">    
    <div id="profpost">
    	<p class="noindent"><?php 
        
        if (($suffix == NULL) && ($midname == NULL)) {
            echo $fname . " " . $lname;
        }elseif ($suffix == NULL) {
            echo $fname . " " . $midname . " " . $lname;
        }else{
            echo $fname . " " . $lname . " " . $suffix;	
        }
        
        ?></p>
    </div>
    
	<div id="proftitle">
        <p class="noindent right"><strong>Name:</strong></p>
    </div>
    
    <br clear="right">
    
	<div id="profpost">
    	<p class="noindent"><?php echo $occupation;?></p>
    </div>
    
    <div id="proftitle">
    	<p class="noindent right"><strong>Occupation:</strong></p>
    </div>
    
    <br clear="right">
    
    <div id="profpost">
    	<p class="noindent"><?php echo $hmcity . ", " . $hmstate;?></p>
    </div>
    
    <div id="proftitle">
    	<p class="noindent right"><strong>Hometown:</strong></p>
    </div>
    
    <br clear="right">
    
    <div id="profpost">
    	<p class="noindent"><?php echo $year;?></p>
    </div>
    
    <div id="proftitle">
    	<p class="noindent right"><strong>Class Year:</strong></p>
    </div>
    
    <br clear="right">
    
    <div id="profpost">
        <p class="noindent"><?php 
        
        //gather Primary Major name
        if ($major1 == '1') {
            $mjr1 = 'Aeronautics and Astronautics';
        }elseif ($major1 == '2') {
            $mjr1 = 'Anthropology';
        }elseif ($major1 == '3') {
            $mjr1 = 'Architecture';
        }elseif ($major1 == '4') {
            $mjr1 = 'Biological Engineering';
        }elseif ($major1 == '5') {
            $mjr1 = 'Biology';
        }elseif ($major1 == '6') {
            $mjr1 = 'Brain and Cognitive Sciences';
        }elseif ($major1 == '7') {
            $mjr1 = 'Management Sciences (Sloan School of Management)';
        }elseif ($major1 == '8') {
            $mjr1 = 'Chemical Engineering';
        }elseif ($major1 == '9') {
            $mjr1 = 'Chemistry';
        }elseif ($major1 == '10') {
            $mjr1 = 'Civil and Environmental Engineering';
        }elseif ($major1 == '11') {
            $mjr1 = 'Comparative Media Studies';
        }elseif ($major1 == '12') {
            $mjr1 = 'Earth, Atmospheric, and Planetary Sciences';
        }elseif ($major1 == '13') {
            $mjr1 = 'Economics';
        }elseif ($major1 == '14') {
            $mjr1 = 'Electrical Engineering and Computer Science';
        }elseif ($major1 == '15') {
            $mjr1 = 'Engineering Systems Division';
        }elseif ($major1 == '16') {
            $mjr1 = 'Foreign Languages and Literatures';
        }elseif ($major1 == '17') {
            $mjr1 = 'Health Sciences and Technology';
        }elseif ($major1 == '18') {
            $mjr1 = 'History';
        }elseif ($major1 == '19') {
            $mjr1 = 'Linguistics and Philosophy';
        }elseif ($major1 == '20') {
            $mjr1 = 'Literature';
        }elseif ($major1 == '21') {
            $mjr1 = 'Materials Science and Engineering';
        }elseif ($major1 == '22') {
            $mjr1 = 'Mathematics';
        }elseif ($major1 == '23') {
            $mjr1 = 'Mechanical Engineering';
        }elseif ($major1 == '24') {
            $mjr1 = 'Media Arts and Sciences (Media Lab)';
        }elseif ($major1 == '25') {
            $mjr1 = 'Music and Theater Arts';
        }elseif ($major1 == '26') {
            $mj2 = 'Nuclear Science and Engineering';
        }elseif ($major1 == '27') {
            $mjr1 = 'Physics';
        }elseif ($major1 == '28') {
            $mjr1 = 'Political Science';
        }elseif ($major1 == '29') {
            $mjr1 = 'Science, Technology, and Society';
        }elseif ($major1 == '30') {
            $mjr1 = 'Urban Studies and Planning';
        }elseif ($major1 == '31') {
            $mjr1 = 'Writing and Humanistic Studies';
        }	
        
        //gather Second Major name
        if ($major2 == '1') {
            $mjr2 = 'Aeronautics and Astronautics';
        }elseif ($major2 == '2') {
            $mjr2 = 'Anthropology';
        }elseif ($major2 == '3') {
            $mjr2 = 'Architecture';
        }elseif ($major2 == '4') {
            $mjr2 = 'Biological Engineering';
        }elseif ($major2 == '5') {
            $mjr2 = 'Biology';
        }elseif ($major2 == '6') {
            $mjr2 = 'Brain and Cognitive Sciences';
        }elseif ($major2 == '7') {
            $mjr2 = 'Management Sciences (Sloan School of Management)';
        }elseif ($major2 == '8') {
            $mjr2 = 'Chemical Engineering';
        }elseif ($major2 == '9') {
            $mjr2 = 'Chemistry';
        }elseif ($major2 == '10') {
            $mjr2 = 'Civil and Environmental Engineering';
        }elseif ($major2 == '11') {
            $mjr2 = 'Comparative Media Studies';
        }elseif ($major2 == '12') {
            $mjr2 = 'Earth, Atmospheric, and Planetary Sciences';
        }elseif ($major2 == '13') {
            $mjr2 = 'Economics';
        }elseif ($major2 == '14') {
            $mjr2 = 'Electrical Engineering and Computer Science';
        }elseif ($major2 == '15') {
            $mjr2 = 'Engineering Systems Division';
        }elseif ($major2 == '16') {
            $mjr2 = 'Foreign Languages and Literatures';
        }elseif ($major2 == '17') {
            $mjr2 = 'Health Sciences and Technology';
        }elseif ($major2 == '18') {
            $mjr2 = 'History';
        }elseif ($major2 == '19') {
            $mjr2 = 'Linguistics and Philosophy';
        }elseif ($major2 == '20') {
            $mjr2 = 'Literature';
        }elseif ($major2 == '21') {
            $mjr2 = 'Materials Science and Engineering';
        }elseif ($major2 == '22') {
            $mjr2 = 'Mathematics';
        }elseif ($major2 == '23') {
            $mjr2 = 'Mechanical Engineering';
        }elseif ($major2 == '24') {
            $mjr2 = 'Media Arts and Sciences (Media Lab)';
        }elseif ($major2 == '25') {
            $mjr2 = 'Music and Theater Arts';
        }elseif ($major2 == '26') {
            $mj2 = 'Nuclear Science and Engineering';
        }elseif ($major2 == '27') {
            $mjr2 = 'Physics';
        }elseif ($major2 == '28') {
            $mjr2 = 'Political Science';
        }elseif ($major2 == '29') {
            $mjr2 = 'Science, Technology, and Society';
        }elseif ($major2 == '30') {
            $mjr2 = 'Urban Studies and Planning';
        }elseif ($major2 == '31') {
            $mjr2 = 'Writing and Humanistic Studies';
        }
        
        //gather First Minor name
        if ($minor1 == '1') {
            $mnr1 = 'Aeronautics and Astronautics';
        }elseif ($minor1 == '2') {
            $mnr1 = 'Anthropology';
        }elseif ($minor1 == '3') {
            $mnr1 = 'Architecture';
        }elseif ($minor1 == '4') {
            $mnr1 = 'Biological Engineering';
        }elseif ($minor1 == '5') {
            $mnr1 = 'Biology';
        }elseif ($minor1 == '6') {
            $mnr1 = 'Brain and Cognitive Sciences';
        }elseif ($minor1 == '7') {
            $mnr1 = 'Management Sciences (Sloan School of Management)';
        }elseif ($minor1 == '8') {
            $mnr1 = 'Chemical Engineering';
        }elseif ($minor1 == '9') {
            $mnr1 = 'Chemistry';
        }elseif ($minor1 == '10') {
            $mnr1 = 'Civil and Environmental Engineering';
        }elseif ($minor1 == '11') {
            $mnr1 = 'Comparative Media Studies';
        }elseif ($minor1 == '12') {
            $mnr1 = 'Earth, Atmospheric, and Planetary Sciences';
        }elseif ($minor1 == '13') {
            $mnr1 = 'Economics';
        }elseif ($minor1 == '14') {
            $mnr1 = 'Electrical Engineering and Computer Science';
        }elseif ($minor1 == '15') {
            $mnr1 = 'Engineering Systems Division';
        }elseif ($minor1 == '16') {
            $mnr1 = 'Foreign Languages and Literatures';
        }elseif ($minor1 == '17') {
            $mnr1 = 'Health Sciences and Technology';
        }elseif ($minor1 == '18') {
            $mnr1 = 'History';
        }elseif ($minor1 == '19') {
            $mnr1 = 'Linguistics and Philosophy';
        }elseif ($minor1 == '20') {
            $mnr1 = 'Literature';
        }elseif ($minor1 == '21') {
            $mnr1 = 'Materials Science and Engineering';
        }elseif ($minor1 == '22') {
            $mnr1 = 'Mathematics';
        }elseif ($minor1 == '23') {
            $mnr1 = 'Mechanical Engineering';
        }elseif ($minor1 == '24') {
            $mnr1 = 'Media Arts and Sciences (Media Lab)';
        }elseif ($minor1 == '25') {
            $mnr1 = 'Music and Theater Arts';
        }elseif ($minor1 == '26') {
            $mj2 = 'Nuclear Science and Engineering';
        }elseif ($minor1 == '27') {
            $mnr1 = 'Physics';
        }elseif ($minor1 == '28') {
            $mnr1 = 'Political Science';
        }elseif ($minor1 == '29') {
            $mnr1 = 'Science, Technology, and Society';
        }elseif ($minor1 == '30') {
            $mnr1 = 'Urban Studies and Planning';
        }elseif ($minor1 == '31') {
            $mnr1 = 'Writing and Humanistic Studies';
        }
        
        //gather Second Minor name
        if ($minor2 == '1') {
            $mnr2 = 'Aeronautics and Astronautics';
        }elseif ($minor2 == '2') {
            $mnr2 = 'Anthropology';
        }elseif ($minor2 == '3') {
            $mnr2 = 'Architecture';
        }elseif ($minor2 == '4') {
            $mnr2 = 'Biological Engineering';
        }elseif ($minor2 == '5') {
            $mnr2 = 'Biology';
        }elseif ($minor2 == '6') {
            $mnr2 = 'Brain and Cognitive Sciences';
        }elseif ($minor2 == '7') {
            $mnr2 = 'Management Sciences (Sloan School of Management)';
        }elseif ($minor2 == '8') {
            $mnr2 = 'Chemical Engineering';
        }elseif ($minor2 == '9') {
            $mnr2 = 'Chemistry';
        }elseif ($minor2 == '10') {
            $mnr2 = 'Civil and Environmental Engineering';
        }elseif ($minor2 == '11') {
            $mnr2 = 'Comparative Media Studies';
        }elseif ($minor2 == '12') {
            $mnr2 = 'Earth, Atmospheric, and Planetary Sciences';
        }elseif ($minor2 == '13') {
            $mnr2 = 'Economics';
        }elseif ($minor2 == '14') {
            $mnr2 = 'Electrical Engineering and Computer Science';
        }elseif ($minor2 == '15') {
            $mnr2 = 'Engineering Systems Division';
        }elseif ($minor2 == '16') {
            $mnr2 = 'Foreign Languages and Literatures';
        }elseif ($minor2 == '17') {
            $mnr2 = 'Health Sciences and Technology';
        }elseif ($minor2 == '18') {
            $mnr2 = 'History';
        }elseif ($minor2 == '19') {
            $mnr2 = 'Linguistics and Philosophy';
        }elseif ($minor2 == '20') {
            $mnr2 = 'Literature';
        }elseif ($minor2 == '21') {
            $mnr2 = 'Materials Science and Engineering';
        }elseif ($minor2 == '22') {
            $mnr2 = 'Mathematics';
        }elseif ($minor2 == '23') {
            $mnr2 = 'Mechanical Engineering';
        }elseif ($minor2 == '24') {
            $mnr2 = 'Media Arts and Sciences (Media Lab)';
        }elseif ($minor2 == '25') {
            $mnr2 = 'Music and Theater Arts';
        }elseif ($minor2 == '26') {
            $mj2 = 'Nuclear Science and Engineering';
        }elseif ($minor2 == '27') {
            $mnr2 = 'Physics';
        }elseif ($minor2 == '28') {
            $mnr2 = 'Political Science';
        }elseif ($minor2 == '29') {
            $mnr2 = 'Science, Technology, and Society';
        }elseif ($minor2 == '30') {
            $mnr2 = 'Urban Studies and Planning';
        }elseif ($minor2 == '31') {
            $mnr2 = 'Writing and Humanistic Studies';
        }
        
        //computer Major line iten
        if ($mjr2 == 0) {
            echo $mjr1 . "<br />";
        }else{
            echo $mjr1 . " (Primary Major)<br /> " . $mjr2 . " (Second Major)";
        }
        //computer Minor line item
        if (($mnr1 == NULL) && ($mnr2 == NULL)) {
            echo " ";
        }elseif ($mnr2 == NULL) {
            echo "<br />Minor: " . $mnr1;
        }else{
            echo "<br />Minor (1st): " . $mnr1 . "<br />Minor (2nd): " . $mnr2 . "<br />";	
        }
        
        
        ?></p>
    </div>
    
    <div id="proftitle">
        <p class="noindent right"><strong>Degree<?php
        $current_date = date("Y");
    
        if ($year > $current_date) {
            echo " (Sought):";
        }else{
            echo ":";
        }
        
        ?></strong></p>
    </div>
    
    <br clear="right">
    
    <div id="profpost">
    	<p class="noindent"><?php echo $activities;?></p>
    </div>
    
    <div id="proftitle">
    	<p class="noindent right"><strong>Activities:</strong></p>
    </div>
    
    <br clear="right">
    
    <div id="profpost">
    	<p class="noindent"><?php echo "<a href=\"" . $website . "\" target=\"_blank\">" . $website . "</a>";?></p>
    </div>
    
    <div id="proftitle">
    	<p class="noindent right"><strong>Website:</strong></p>
    </div>
    
    <br clear="right">
    
    <p>&nbsp;</p>
</div>

</div>
</center>

</div>
</body>
</html>
