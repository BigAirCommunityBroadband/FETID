<?php include('phplib/prepend.php');
page_open(array("sess" => "fetid_Session")); ?>
<html><head><title>smeg rtrmin Database</title></head><body>
<font class=bigTextBold align="CENTER">rtrmin Database</font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("EventLog.php") ?>">Event Log</a></font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("device_types.php") ?>">Device Types</a></font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("devices.php") ?>">Devices</a></font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("login_types.php") ?>">Login Types</a></font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("menu.php") ?>">Menu</a></font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("sections.php") ?>">Sections</a></font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("login.php") ?>">Login</a></font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("register.php") ?>">Register</a></font>
<font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("logout.php") ?>">Logout</a></font>
<?php page_close(); ?> </body></html>
