<?

//prevents caching
header("Expires: Sat, 01 Jan 2000 00:00:00 GMT");
header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
header("Cache-Control: post-check=0, pre-check=0",false);
session_cache_limiter();

session_start();

//clear session variables
session_unset();

header("Location:https://alum.mit.edu/calendar/ViewCalendarEvent.dyn?usergroup=&itemId=46821&source=SMARTRANS");
exit;

?>