<?

//set up the names of the database and table
$db_name ="chocolate-city+profile";
$table_name ="authorize";

//connect to the server and select the database
$server = "sql.mit.edu";
$dbusername = "chocolate-city";
$dbpassword = "qweruiop";

//Change to "0" to turn off the login log
$log_login = "1";

//base_dir is the location of the files, ie http://www.yourdomain/login
$base_dir = "http://ccity.mit.edu/login";

//length of time the cookie is good for - 7 is the days and 24 is the hours
//if you would like the time to be short, say 1 hour, change to 60*60*1
$duration = time()+(60*60*24*30);

//the site administrator\'s email address
$adminemail = "cc-webmaster@mit.edu";

//the site domain
$domain = "http://ccity.mit.edu";

//sets the time to EST
$zone=3600*-5;

//do you want the verify the new user through email if the user registers themselves?
//yes = "0" :  no = "1"
$verify = "0";

//default redirect, this is the URL that all self-registered users will be redirected to
$default_url = "http://ccity.mit.edu/brothers";

//minimum and maximum password lengths
$min_pass = 6;
$max_pass = 16;


$num_groups = 2+2;
$group_array = array("Brothers","GRT","Users","Administrators");

?>