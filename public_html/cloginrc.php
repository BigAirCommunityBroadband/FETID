<?php
include ("/usr/share/phplib/db_pdo.inc");   # PHP Database Objects, PHPLIB style 
class DB_rancid extends DB_Sql {
  var $Host     = "localhost";
  var $Database = "rancid";
  var $User     = "rancid";
  var $Password = "rancid";
}
$db = new DB_rancid;

function decrypt($str) {
	include("../keys.php");
	return openssl_private_decrypt(base64_decode($str),$decrypted,$PrivateKey,OPENSSL_PKCS1_OAEP_PADDING) ? $decrypted : "";
}
function escape($str) {
	$str = str_replace(" ","\ ",$str);
	$str = str_replace("}","\}",$str);
	return "{".$str."}";
}
if (!$fp = fopen(".cloginrc","w")) {
	die("Can't write file");
} else {
	$db->query("SELECT * FROM devices ORDER BY section, hostname, username");
	$last = "";
	while ($db->next_record()) {
		extract($db->Record);
		$ucname = strtoupper($section);
		$passwd = escape(decrypt($passwd));
		$en_passwd = escape(decrypt($en_passwd));
		$username = escape($username);
		if ($ucname<>$last) {
			fwrite($fp,"\n#### $ucname section ####\n");
		}
		if ($login_type=="ssh") {
			fwrite($fp,"add user $hostname $username\n");
			fwrite($fp,"add method $hostname ssh\n");
			fwrite($fp,"add password $hostname $passwd $en_passwd\n");
		}
		if ($login_type=="telnet") {
			if (($username) and ($username<>"none")) {
				fwrite($fp,"add user $hostname $username\n");
				fwrite($fp,"add password $hostname $passwd $en_passwd\n");
			} else {
				fwrite($fp,"add password $hostname $passwd $en_passwd\n");
			}
		}
		if ($timeout>0) fwrite($fp,"add timeout $hostname {$timeout}\n");
		if ($no_enable=='Yes') fwrite($fp,"add autoenable $hostname {1}\n");
		fwrite($fp,"#---\n");
		$last = $ucname;
	}
	fclose($fp);
}
?>
