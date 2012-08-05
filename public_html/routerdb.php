<?php
include ("db_pdo.inc");   # PHP Database Objects, PHPLIB style 
class DB_rancid extends DB_Sql {
  var $Host     = "localhost";
  var $Database = "rancid";
  var $User     = "rancid";
  var $Password = "cFa478w79VvQ5ved";
}
$db = new DB_rancid;

# where to put router.db files (%s=section)
$path = "/var/lib/rancid/%s/router.db";

$db->query("SELECT section, hostname, state, type, comments FROM devices ORDER BY section, hostname");
$last = "";
$fp = false;
while ($db->next_record()) {
	extract($db->Record);
	if (!strpos($hostname,":",1)) {   #don't output if hostname has colons which are not permitted
		if ($section<>$last) {
			if ($fp) fclose($fp);
			$file = sprintf($path,$section);
			$fp = fopen($file,"w");
		}
		if ($comments) $cmt = ":$comments"; else $cmt="";
		if ($fp) fwrite($fp,"$hostname:$type:$state$cmt\n");
		$last = $section;
	}
}
fclose($fp);
?>
