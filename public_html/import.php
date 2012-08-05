<?php 

// set the groupname and path to import
$GroupToImport = "BACB";

$path = "/var/lib/rancid/%s/router.db";
$password_file = "/var/lib/rancid/.cloginrc";  #put full path if not in current dir
$mode = "REPLACE";  # allow overwite;
#$mode = "INSERT";  # do NOT overwrite;

include ("/usr/share/phplib/db_pdo.inc");   # PHP Database Objects, PHPLIB style 

class DB_rancid extends DB_Sql {
  var $Host     = "localhost";
  var $Database = "rancid";
  var $User     = "rancid";
  var $Password = "cFa478w79VvQ5ved";
}

class Router {
  # some sensible default values, 
  # for things that might not be found in the config files.
  var $comment = "";
  var $passwd = "";
  var $en_passwd = "";
  var $user = "none";
  var $method = "telnet";
  var $ssh_cmd = "";
  var $autoenable = 0;
  var $timeout = 0;
  function get_auto_enable() {
    return $this->autoenable ? "Yes" : "No";
  }
}

function encrypt($str) {
	if (!$str) return "";
        include("../keys.php");
	return openssl_public_encrypt($str,$encrypted,$PublicKey,OPENSSL_PKCS1_OAEP_PADDING) ? base64_encode($encrypted) : "";
}

# should not have to configure anything below here

$routers = array();	# routers to import
$includes = array();	# files to read.

$file = sprintf($path,$GroupToImport);
if (!$fp = fopen($file,"r")) die("Can't open $file\n");
while (!feof($fp)) {
	$line = trim(fgets($fp,1000));
	if (strlen($line>3) and substr($line,0,1)<>"#") {
		$param = explode(":",$line);
		$c = count($param);
		if ($c<3 or $c>4) {
			echo "can't parse $line with $c parameters\n";
		} else {
			$hostname = $param[0];
			$routers[$hostname] = new Router;
			$routers[$hostname]->device_type = $param[1];
			$routers[$hostname]->state = $param[2];
			$routers[$hostname]->comment = $c>3 ? $param[3] : "";
		}
	}
}
fclose($fp);

if (!$fp=fopen($password_file,"r")) die("Can't open password file");

function read_file($fp) {
    global $routers, $includes;
    if (!$fp) return;
    $comment = "";
    while (!feof($fp)) {
	$line = trim(fgets($fp,1000));
	if (substr($line,0,1)=="#") {
	    # keep last comment line
	    $comment = trim(substr($line,1));
	} else if (strlen($line)>3) {
	    # convert line to words and add a few empty ones on the end so we'll have enough words.
	    $line = str_replace("\t"," ",$line);
	    $line = preg_replace('/\s\s+/', ' ', $line);
	    $words = explode(" ",$line) + Array(null,null,null,null,null);
	    foreach($words as $that=>$word) {
		if (substr($word,0,1)=="{") { #starts with {
		    if (substr($word,-1)=="}") {  #ends with }
			$words[$that] = substr($word,1,-1); #take the middle bit
		    } else {
			die("ugly, write more code to handle escaped {}");
		    }
		}
	    }
	    # give our words some better names, so we don't get lost.
	    list($directive,$keyword,$hostname,$param1,$param2) = $words;
	    switch ($directive) {
		default:
		    echo "don't understand directive '$line'\n";
		    break;
		case "include":
		    $includes[] = $keyword;
		    break;
		case "add":
		    if (array_key_exists($hostname,$routers)) {  # switch on keyword only if known router
		        switch ($keyword) {
			    case "autoenable":
			    case "noenable":
				$routers[$hostname]->autoenable = $param1;
				break;
			    case "method":
				$routers[$hostname]->method = $param1;
				break;
			    case "user":
				$routers[$hostname]->user = $param1;
				break;
			    case "password":
				$routers[$hostname]->passwd = $param1;
				$routers[$hostname]->en_passwd = $param2;
				break;
			    case "sshcmd":
				$routers[$hostname]->ssh_cmd = $param1;
				break;
			    case "timeout":
				$routers[$hostname]->timeout = $param1;
				break;
			    default: 
				echo "don't understand keyword '$keyword' in directive '$line'\n";	
		        }
			if ($comment and empty($routers[$hostname]->comment)) $routers[$hostname]->comment = $comment;
		    } else {
			# Not importing $hostname at this time.
		    }
	    }
	}
    }
    fclose($fp);
    return count($includes);  # Any left?
}

while (read_file($fp)) {
	$next_file = array_pop($includes);
	if (!$fp=fopen($next_file,"r")) echo "failed to open $next_file\n";
}

#now we've got all the data, let's put it in the database
$db = new DB_rancid;

$db->query("$mode INTO sections (section) VALUES ('$GroupToImport')"); #make sure section exists.

if ($st = $db->prepare("$mode INTO devices 
		(hostname,section,type,login_type,username,passwd,en_passwd,no_enable,ssh_cmd,timeout,state,comments) 
			VALUES (?,?,?,?,?,?,?,?,?,?,?,?)")) {

	foreach ($routers as $hostname => $r) {
		$data = array( $hostname,$GroupToImport,$r->device_type,$r->method,$r->user,encrypt($r->passwd),encrypt($r->en_passwd),
				$r->get_auto_enable(),$r->ssh_cmd,$r->timeout,$r->state,$r->comment);
		if (!$st->execute($data)) {
			$err = $st->errorInfo();
			echo $err[2]."\n";
		}
	}
}
else die("DB prepare failed\n");

?>
