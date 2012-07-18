<html> 
<head>

<b>RANCID Config Backup Web Administration:</b><br>
<br>


>> <a href="cgi-bin/cvsweb.cgi">Config Browser</a><br>
>> <a href="register.php">Register Devices</a><br>
>> <a href="delete.php">Delete Devices</a><br>
>> <a href="search.php">Search Devices</a><br>

<br>


<title>RANCID NETWORK DEVICE REGISTRATION</title>
<link rel=stylesheet href=style.css type=text/css> 
</head>

<br>
<br>

<form action=<?php echo $_SERVER[PHP_SELF] ?> method=post>
<big><b>Device Registration:</big></b><br>

<table>
<br><br>

<tr>
<td><center>Device IP</center></td>
<td><center>Network</center></td>
<td><center>Type</center></td>
<td><center>Login</center></td>
<td><center>Username</center></td>
<td><center>Exec Pass</center></td>
<td><center>Enable Pass</center></td>
</tr>



<tr>
<td><input type=text name=ip size=15></td>

<td><SELECT name=section>
        <OPTION>agriculture</OPTION>
        <OPTION>athletics</OPTION>
        <OPTION>au_net</OPTION>
        <OPTION>auwan1</OPTION>
        <OPTION>auwan2</OPTION>
        <OPTION>border</OPTION>
        <OPTION>core</OPTION>
        <OPTION>engineering</OPTION>
        <OPTION>firewalls</OPTION>
        <OPTION>haley</OPTION>
        <OPTION>library</OPTION>
        <OPTION>oit</OPTION>
        <OPTION>resnet</OPTION>
        <OPTION>vetmed</OPTION>
        <OPTION>wireless</OPTION>
        </Select></td>

<td><SELECT name=type>
        <OPTION>cisco</OPTION>
        <OPTION>cat5</OPTION>
        </SELECT></td>

<td><SELECT name=login_type>
        <OPTION>telnet</OPTION>
        <OPTION>ssh</OPTION>
        </SELECT></td>

<td><SELECT name=username>
        <OPTION>none</OPTION>
        <OPTION>rancid</OPTION>
        <OPTION>joeblow</OPTION>
        </SELECT></td>

<td><input type=text name=passwd size=10></td>
<td><input type=text name=en_passwd size=10></td>

</TR>
</TABLE>

<BR>

<input type=submit name=submit value=Add>

</form>



<?php


// *** MySQL user and password - edit these ***//
$dbhost="localhost";
$dbuser="rancid";
$dbpass="rancid";



if (isset($_REQUEST[submit]))
{


//******** Make sure the IP field is not empty ********//
    if ($_REQUEST[ip] == "") {
	echo "<br>The IP Address field must not be empty!!<br><br>";
    } else {


//********* Code to verify IP input does not contain letters g thru z.  **********//
    if (preg_match ("/[g-z]/i", "$_REQUEST[ip]")) {
	echo "<br>The IP address you entered was invalid.&nbsp&nbsp  Valid IP addresses can <b>ONLY</b> contain the <b>numbers 0 through 9</b>.&nbsp&nbsp  Please try again.<br><br>";
    } else {


//********* Code to verify IP input does not contain special characters.  **********//
if (preg_match ("/[ !@#$%^&*()~`+=?<>;|,_{}'\/\[\] ]/", "$_REQUEST[ip]")) {
        echo "<br>The IP address you entered contained special characters and is invalid.&nbsp&nbsp  Valid IP addresses <b>cannot</b> contain characters such as <b>@#$%^&*()+={}[]<></b>.&nbsp&nbsp  Please try again.<br><br>";
    } else {



// Connect to MySQL
mysql_connect($dbhost, $dbuser, $dbpass) or die ("Unable to connect to database.");

 
// Select and query DB for IP.  
mysql_select_db('rancid') or die ("Could not open database");
$db_query = mysql_query("SELECT ip from devices WHERE ip='$_REQUEST[ip]'");


//******** If IP is not already in db, add to db *********//
    if (mysql_num_rows($db_query) == 0) {


// Insert register information into device table.
$db_insert = mysql_query("insert into devices (ip, section, type, login_type, username, passwd, en_passwd) values (\"$_REQUEST[ip]\", \"$_REQUEST[section]\", \"$_REQUEST[type]\", \"$_REQUEST[login_type]\", \"$_REQUEST[username]\", \"$_REQUEST[passwd]\", \"$_REQUEST[en_passwd]\")"); 


//Output to screen
echo "<br><br>Device <b>$_REQUEST[ip]</b> has been added to the RANCID database and will be scheduled to have it's configuration backed up.<br>";

    } else {


//Output to screen
echo "<br><br>Device <b>$_REQUEST[ip]</b> already exists in the RANCID database.\n";


         }
      }
    }
  }
}


//include("footer.html"); 

?>
