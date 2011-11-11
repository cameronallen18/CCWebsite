<?php

$request_email = $_POST['request_email'];
$request_subject = $_POST['request_subject'];
$request_body = $_POST['request_body'];
$request_fname = $_POST['request_fname'];
$request_lname = $_POST['request_lname'];
$request_year = $_POST['request_year'];


if ($request_body == 'TRUE')
	{
	$body = "Hi, my name is " . $request_fname . " " . $request_lname . " and I would like to request the Brother Identification Code to create, edit and update my Chocolate City Profile Page. I am a graduate of Chocolate City, Class of " . $request_year . ". Please send me the Brother Identification Code so that I may edit my profile";
	$readonly = "readonly";
	}else{
	$body = "Please put the content of your E-Mail message here.";
	$readonly = " ";
	}

?>

<div id="content">

<div id="sublinks">

<div id="main">
<div id="current">
You are currently viewing the <a href="contact.php">Contact</a> page.
</div>

<div id="today">
Today is <?php echo date("l") ?>, <?php echo date("F dS") ?>, <?php echo date("Y") ?>.
</div>
</div>

</div>


<div id="sixhun">
<form method="POST" action="contact_form.php">

<p class="noindent"><strong>E-Mail Subject:</strong><br />
<input type="text" name="e_subject" size="20" value="<?php echo $request_subject;?>" <?php echo $readonly;?>><br />
<span class="nomargin noindent small">Please be specific when writing your Subject Line</span></p>
	
<p class="noindent"><strong>Return E-Mail:</strong><br />
<input type="text" name="e_from" size="20" value="<?php echo $request_email;?>" <?php echo $readonly;?>><br />
<span class="nomargin noindent small">e.g., chocolatecity@mit.edu, chocolatecity@gmail.com</span></p>

<p class="noindent"><strong>Message:</strong><br />
<textarea rows="15" name="e_message" cols="76" <?php echo $readonly;?>><?php echo $body;?></textarea><br />
</p>

<p class="noindent">
<input type="submit" value="Submit" name="B1"><input type="reset" value="Reset" name="B2"></p>
</p>

</form>
</div>

</div>