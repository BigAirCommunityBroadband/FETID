<?php

$path = "/var/lib/rancid/.cloginrc-%s"; 	# %s = Section

include ("/usr/share/phplib/db_pdo.inc");	# PHP Database Objects, PHPLIB style 

class DB_rancid extends DB_Sql {
  var $Host     = "localhost";
  var $Database = "rancid";
  var $User     = "rancid";
  var $Password = "cFa478w79VvQ5ved";
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
$db->query("SELECT * FROM devices ORDER BY section, hostname, username");
$last = "";
$fp = false;
while ($db->next_record()) {
	extract($db->Record);
	$ucname = strtoupper($section);
	$esc_passwd = escape(decrypt($passwd));
	$esc_en_passwd = escape(decrypt($en_passwd));
	$esc_username = escape($username);
	if ($ucname<>$last) {
		if ($fp) fclose($fp);
		$file = sprintf($path,$ucname);
		if (!$fp = fopen($file,"w")) die("Can't write file $file\n");
		fwrite($fp,"\n#### $ucname section ####\n");
	}
	fwrite($fp,"# $comments\n");
	if ($login_type=="ssh") {
		fwrite($fp,"add method   $hostname ssh\n");
	}
	if (($username) and ($username<>"none")) {
		fwrite($fp,"add user     $hostname $esc_username\n");
	}
	if ($en_passwd) {
		fwrite($fp,"add password $hostname $esc_passwd $esc_en_passwd\n");
	} else {
		if ($passwd) {
			fwrite($fp,"add password $hostname $esc_passwd\n");
		}
	}
	if ($timeout>0) fwrite($fp,"add timeout  $hostname {$timeout}\n");
	if ($no_enable=='Yes') fwrite($fp,"add noenable $hostname {1}\n");
	fwrite($fp,"#---\n");
	$last = $ucname;
}
fclose($fp);
?>
