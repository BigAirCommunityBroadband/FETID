<?php
include ("db_pdo.inc");   # PHP Database Objects, PHPLIB style 
class DB_rancid extends DB_Sql {
  var $Host     = "localhost";
  var $Database = "rancid";
  var $User     = "rancid";
  var $Password = "rancid";
}
$db = new DB_rancid;

# where to put router.db files (%s=section)
$path = "/home/rancid/var/%s/router.db";
$path = "%s.db";

$db->query("SELECT section, hostname, type FROM devices ORDER BY section, hostname");
$last = "";
$fp = false;
while ($db->next_record()) {
	extract($db->Record);
	if ($section<>$last) {
		if ($fp) fclose($fp);
		$file = sprintf($path,$section);
		$fp = fopen($file,"w");
	}
	if ($fp) fwrite($fp,"$hostname:$type:up\n");
	$last = $section;
}
fclose($fp);
?>
