<?

//prevents caching
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();
session_start();

//require config and functions files
require('login/config.php');
require('login/functions.php');

//send an email
$to = "cc-webmaster@mit.edu";
$subject = $_POST[e_subject];
$email = $_POST[e_from];
$message = $_POST[e_message]; 
$headers = "From: $email"; 
$sent = mail($to, $subject, $message, $headers);	

//redirects the user	
header("Location:http://ccity.mit.edu/");

?>