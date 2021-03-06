<?php
/*
 * Session Management for PHP3
 *
 * Copyright (c) 1998,1999 SH Online Dienst GmbH
 *                    Boris Erdmann, Kristian Koehntopp
 *
 * $Id: local.inc,v 1.23 1999/08/25 11:40:48 kk Exp $
 *
 */ 

include($_ENV['local'].'/.htauth.local');

class fetid_CT_Sql extends CT_Sql {
  var $database_class = "DB_fetid";          ## Which database to connect...
  var $database_table = "active_sessions"; ## and find our session data in this table.
}

#class fetid_CT_Split_Sql extends CT_Split_Sql {
#  var $database_class = "DB_fetid";         ## Which database to connect...
#  var $database_table = "active_sessions_split"; ## and find our session data in this table.
#  var $split_length = 4096                    ## Split rows every 4096 bytes
#}

#class fetid_CT_Shm extends CT_Shm {
#  var $max_sessions   = 500;               ## number of maximum sessions
#  var $shm_key        = 0x123754;          ## unique shm identifier
#  var $shm_size       = 64000;             ## size of segment
#}

#class fetid_CT_Ldap extends CT_Ldap {
#   var $ldap_host = "localhost";
#   var $ldap_port = 389;
#   var $basedn    = "dc=your-domain, dc=com";
#   var $rootdn    = "cn=root, dc=your-domain, dc=com";
#   var $rootpw    = "secret";
#   var $objclass  = "phplibdata";
#}

#class fetid_CT_Dbm extends CT_DBM {
#   var $dbm_file  = "must_exist.dbm";
#}

class fetid_Session extends Session {
  var $classname = "fetid_Session";

  var $cookiename     = "";                ## defaults to classname
  var $magic          = "Hocuspocus";      ## ID seed
  var $mode           = "cookie";          ## We propagate session IDs with cookies
  var $fallback_mode  = "get";
  var $lifetime       = 0;                 ## 0 = do session cookies, else minutes
  var $that_class     = "fetid_CT_Sql"; ## name of data storage container
  var $gc_probability = 5;  
  var $auto_init      = "setup.inc";		## comment out to disable session logs.
}

class fetid_User extends User {
  var $classname = "fetid_User";

  var $magic          = "Abracadabra";     ## ID seed
  var $that_class     = "fetid_CT_Sql"; ## data storage container
}

class fetid_Auth extends Auth {
  var $classname      = "fetid_Auth";

  var $lifetime       =  300;

  var $database_class = "DB_fetid";
  var $database_table = "auth_user";
  
  function auth_loginform() {
    global $sess;

    include($_ENV["local"] . "loginform.ihtml");
  }
  
  function auth_validatelogin() {
    global $sess;

    $uid = false;

    if(isset($_POST['phplib_username'])) {
      $user = trim($_POST['phplib_username']);
      $pass = hash_auth($user,$_POST['phplib_password']);
      $this->auth["uname"]=$user;        ## This provides access for "loginform.ihtml"
    
      $this->db->query(sprintf("select user_id, perms ".
                             "   from %s ".
                             "   where username = %s ".
                             "   and password = %s ",
                          $this->database_table,
                          $this->db->quote($user),
                          $this->db->quote($pass)));
      while($this->db->next_record()) {
	$uid = $this->db->f("user_id");
	$this->auth["perm"] = $this->db->f("perms");
        global $loggedIn;
        $loggedIn = "TimeOutDetect";
        $sess->register("loggedIn");
      }
    }
    return $uid;
  }

  function auth_refreshlogin() {
    global $sess;

    include($_ENV["local"] . "refreshloginform.ihtml");
  }

  function auth_registerform() {
    global $sess;

    include($_ENV["local"] . "registrationform.ihtml");

  }

  function auth_doregister() {
    global $sess, $phplib_username, $emailaddress, $ErrorMsg;
    
    if(isset($username)) {
      $this->auth["uname"]=$phplib_username;        ## This provides access for "loginform.ihtml"
    } 
    

    $uid = false;
    
    $this->db->query(sprintf("select user_id, perms, password from %s where username = '%s' ",
                          $this->database_table, addslashes($phplib_username)));
    if ($this->db->next_record()) { 
      if ($phplib_username) $ErrorMsg="Username found in database (Can't register same name again)";
    } else {
      $this->db->query(sprintf("select user_id, perms, password from %s where emailaddress = '%s' ",
                          $this->database_table, addslashes($emailaddress)));
      if ($this->db->next_record()) {
        $ErrorMsg="Email Address found in database (Can't Re-Register same email address)";
      } else {
        // Username not already in database, so we can register with this username.
        // Create a uid and insert the user...
        $hash_secret = "Silmarillion...";
        $u_id=md5(uniqid($hash_secret));
        $phplib_password = substr(md5(uniqid($hash_secret)),3,8);
        $permlist = "guest";
        $query = "insert into auth_user values('$u_id','$phplib_username','$phplib_password','$permlist','".addslashes($emailaddress)."')";
        $this->db->query($query);
        if ($this->db->affected_rows() == 0) {
            echo "<b>Failed:</b> $query";
        } else {
          echo "User \"$phplib_username\" created.<BR>";
          mail ($emailaddress,"Welcome to fetid.local","
Welcome!, someone (hopefully you!) has registered this email address for guest access
to the website at http://fetid.local.

Your new password is ".$phplib_password."

You may now visit the site guest access pages or sign up for full membership.

Thank-you for visiting,
info@fetid.local


PS: If you did not register the email address ".$emailaddress." (ie some else did),
you can remove it from our database by clicking below:-
http://fetid.local/unsubscribe.php?uid=".$u_id."

","From:info@fetid.local");
	 echo "The Password has been emailed to ".$emailaddress;
         $this->mode="log";
    	 include($_ENV["local"] . "loginform.ihtml");
	 exit;
        }
      }
    }
    return $uid;
  }

        function auth_preauth() {
                global $PHP_SELF, $Login, $u, $p;
                if (($PHP_SELF!='/logout.php') && (!$Login)) {
                        $this->db->query("select user_id, perms from auth_user where username='$u' and password ='$p'");
                        if ($this->db->next_record()) {
				$this->auth["uname"] = $u;
                                $this->auth["perm"] = $this->db->f(1);
                                $this->auth["uid"] = $this->db->f(0);
				return $this->db->f(0);
			}
                }
                return false;
        }

}

class fetid_Auth_Reg extends fetid_Auth {
  var $classname = "fetid_Auth_Reg";
  var $mode      = "reg";
}


class fetid_Default_Auth extends fetid_Auth {
  var $classname = "fetid_Default_Auth";
  
  var $nobody    = true;
}

class fetid_Challenge_Auth extends Auth {
  var $classname      = "fetid_Challenge_Auth";

  var $lifetime       =  1;

  var $magic          = "Simsalabim";  ## Challenge seed
  var $database_class = "DB_fetid";
  var $database_table = "auth_user";

  function auth_loginform() {
    global $sess;
    global $challenge;
    
    $challenge = md5(uniqid($this->magic));
    $sess->register("challenge");
    
    include($_ENV["local"] . "crloginform.ihtml");
  }
  
  function auth_validatelogin() {
    global $username, $password, $challenge, $response, $sess;

    if(isset($username)) {
      $this->auth["uname"]=$username;        ## This provides access for "loginform.ihtml"
    }
    $this->db->query(sprintf("select user_id,perms,password ".
                "from %s where username = '%s'",
                          $this->database_table,
                          addslashes($username)));

    while($this->db->next_record()) {
      $uid   = $this->db->f("user_id");
      $perm  = $this->db->f("perms");
      $pass  = $this->db->f("password");
    }
    $exspected_response = md5("$username:$pass:$challenge");

    ## True when JS is disabled
    if ($response == "") {
      if ($password != $pass) {
        return false;
      } else {
        $this->auth["perm"] = $perm;
        global $loggedIn;
        $loggedIn = "TimeOutDetect";
        $sess->register("loggedIn");
        return $uid;
      }
    }
    
    ## Response is set, JS is enabled
    if ($exspected_response != $response) {
      return false;
    } else {
      $this->auth["perm"] = $perm;
      global $loggedIn;
      $loggedIn = "TimeOutDetect";
      $sess->register("loggedIn");
      return $uid;
    }
  }
}

##
## fetid_Challenge_Crypt_Auth: Keep passwords in md5 hashes rather 
##                           than cleartext in database
## Author: Jim Zajkowski <jim@jimz.com>

class fetid_Challenge_Crypt_Auth extends Auth {
  var $classname      = "fetid_Challenge_Crypt_Auth";

  var $lifetime       =  1;

  var $magic          = "Frobozzica";  ## Challenge seed
  var $database_class = "DB_fetid";
  var $database_table = "auth_user_md5";

  function auth_loginform() {
    global $sess;
    global $challenge;
    
    $challenge = md5(uniqid($this->magic));
    $sess->register("challenge");
    
    include("crcloginform.ihtml");
  }
  
  function auth_validatelogin() {
    global $username, $password, $challenge, $response;

    $this->auth["uname"]=$username;        ## This provides access for "loginform.ihtml"
    
    $this->db->query(sprintf("select user_id,perms,password ".
                "from %s where username = '%s'",
                          $this->database_table,
                          addslashes($username)));

    while($this->db->next_record()) {
      $uid   = $this->db->f("user_id");
      $perm  = $this->db->f("perms");
      $pass  = $this->db->f("password");   ## Password is stored as a md5 hash
    }
    $exspected_response = md5("$username:$pass:$challenge");

    ## True when JS is disabled
    if ($response == "") {
      if (md5($password) != $pass) {       ## md5 hash for non-JavaScript browsers
        return false;
      } else {
        $this->auth["perm"] = $perm;
        return $uid;
      }
    }
    
    ## Response is set, JS is enabled
    if ($exspected_response != $response) {
      return false;
    } else {
      $this->auth["perm"] = $perm;
      return $uid;
    }
  }
}

class fetid_Perm extends Perm {
  var $classname = "fetid_Perm";
  
  var $permissions = array(
				"guest"  	  =>   1,
				"user"  	  =>   2,
				"editor"  	  =>   4,
				"admin"  	  =>   8,
                          );

  function perm_invalid($does_have, $must_have) {
    global $perm, $auth, $sess;
    
    include($_ENV["local"] . "perminvalid.ihtml");
  }
}




