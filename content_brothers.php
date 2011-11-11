<div id="content">
    <div id="sublinks">
        <div id="main">
            <div id="current">
            You are currently viewing the <a href="brothers.php">Brothers</a> page.
            </div>
        
            <div id="today">
            Today is <?php echo date("l") ?>, <?php echo date("F dS") ?>, <?php echo date("Y") ?>.
            </div>
        </div>
    </div>
    <div id="main">                    
    
        <h5>Current Brothers</h5>
        
        <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed ultrices luctus porta. Phasellus pellentesque, elit quis aliquam laoreet, odio leo lobortis libero, ac fermentum urna turpis ac erat. Morbi ullamcorper mauris in nisl congue varius. Curabitur pulvinar porttitor quam, id aliquam lorem sagittis et. Sed ligula erat, venenatis nec sagittis non, sagittis ut erat. In ut diam eu orci suscipit viverra. Donec sed nunc nec sem placerat ultrices ut at dui. Vivamus at nulla nibh. Quisque elementum magna nec mi posuere in molestie libero tristique.</p>
        
        <p>&nbsp;</p>
        
        <?php
        // database constants
        $db_bros ="chocolate-city+brothers";
        $db_prof ="chocolate-city+profile";
        $table_name ="general";
        $server = "sql.mit.edu";
        $dbusername = "chocolate-city";
        $dbpassword = "qweruiop";

        //make the connection to the database
        $connection = @mysql_connect($server, $dbusername, $dbpassword) or die(mysql_error());
        $db = @mysql_select_db($db_prof,$connection)or die(mysql_error());
        // get senior class
        $sql = "SELECT * FROM period WHERE active = 1";
        $result = @mysql_query($sql,$connection) or die(mysql_error());
        $row = @mysql_fetch_assoc($result) or die(mysql_error());
        $senclass = $row['senior'];
        // get brothers database for remaining info
        $db = @mysql_select_db($db_bros,$connection)or die(mysql_error());

        $classes = array("Senior","Junior","Sophomore","Freshmen");

        for( $currclass=$senclass; $currclass<$senclass+4; $currclass++ ) {
            echo "<div id=\"title\">";
            $currclassindex = $currclass - $senclass;
            echo "<p class=\"nomargin\"><span class=\"italic\">$classes[$currclassindex]</span> Class of $currclass</p>";
            echo "</div>";
            echo "<div id=\"class\">";
            //build and issue the query for all bros in current class
            $sql = "SELECT * FROM $table_name WHERE year = $currclass ORDER BY lastname";
            $result = @mysql_query($sql,$connection) or die(mysql_error());
            //count number of ppl in class
            $currclasslen = mysql_num_rows($result);
            while( $currbro = mysql_fetch_assoc($result) ) {
				echo "<div id=\"bropic\">";
                echo "<a href=\"brothers/display.php?username=",$currbro['username'],"\" rel=\"shadowbox;height=450;width=800\" title=\"",$currbro['firstname']," ",$currbro['lastname'],"\">";
                echo "<img id=\"brother\" src=\"",$currbro['image'],"\" width=\"100px\" height=\"150px\" /></a>";
				echo "<br />";
				echo "<div id=\"broname\">";
				echo "<p>".$currbro['firstname']."<br />".$currbro['lastname']."</p>";
				echo "</div>";
				echo "</div>";
            }
            echo "</div>";
			echo "<br clear=\"all\">";
			echo "<br />";
        }
        ?>
        
        <br />
      
    </div>
    
</div>