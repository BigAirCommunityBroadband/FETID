<?php

class devicesform extends tpl_form {
  var $table = "devices";
  var $key = "id";
  var $key_field = "id"; # if different to $key
  var $classname = "devicesform";
  var $database_class = "DB_fetid";

  function setup_fields () {
    $this->form_data->add_element(array("type"=>"hidden","name"=>"id","field"=>"id","size"=>"12"));
    $this->form_data->add_element(array("type"=>"text","name"=>"hostname","maxlength"=>"255","size"=>"20",
		"minlength"=>1,"length_e"=>"Hostname/IP Address must not be blank.  Required field.",
		"field"=>"hostname","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"text","name"=>"section","maxlength"=>"60","size"=>"20",
		"field"=>"section","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"text","name"=>"type","maxlength"=>"60","size"=>"20",
		"field"=>"type","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"text","name"=>"login_type","maxlength"=>"60","size"=>"20",
		"field"=>"login_type","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"text","name"=>"username","maxlength"=>"60","size"=>"20",
		"minlength"=>1,"length_e"=>"User Name must not be blank.  Required field.",
		"field"=>"username","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"password","name"=>"passwd","maxlength"=>"60","size"=>"20",
		"field"=>"passwd","extrahtml"=>"","multiple"=>"1"
		));
    $this->form_data->add_element(array("type"=>"password","name"=>"en_passwd","maxlength"=>"60","size"=>"20",
		"field"=>"en_passwd","extrahtml"=>"","multiple"=>"1"
		));
    $this->form_data->add_element(array("type"=>"select","name"=>"no_enable","field"=>"no_enable",
                "extrahtml"=>"class='dropdownMenu'",
                "options"=>array('Yes','No'),"value"=>"Yes"));
    $this->form_data->add_element(array("type"=>"text","name"=>"ssh_cmd","maxlength"=>"255","size"=>"85",
                "field"=>"ssh_cmd","extrahtml"=>""
                ));
    $this->form_data->add_element(array("type"=>"text","name"=>"timeout","size"=>"12",
                "valid_regex"=>"^[0-9]+$","field"=>"timeout",
                "valid_e"=>"Timeout must be a whole number containing digits 0-9 only.",
                "extrahtml"=>"","value"=>"0"));
    $this->form_data->add_element(array("type"=>"select","name"=>"state","field"=>"state",
                "extrahtml"=>"class='dropdownMenu'",
                "options"=>array('Up','Down'),"value"=>"Up"));
    $this->form_data->add_element(array("type"=>"textarea","name"=>"comments","rows"=>"5","cols"=>"50",
                "field"=>"comments","extrahtml"=>""));
    $this->form_data->add_element(array("type"=>"submit","name"=>"submit","value"=>"Submit"));

    $this->form_data->after = <<< JAVASCRIPT

if (f.elements["passwd[]"][0].value != f.elements["passwd[]"][1].value) {
        alert("Passwords don't match");
        f.elements["passwd[]"][1].focus();
        return false;
}
if (f.elements["en_passwd[]"][0].value != f.elements["en_passwd[]"][1].value) {
        alert("Enable Passwords don't match");
        f.elements["en_passwd[]"][1].focus();
        return false;
}
if (f.elements["en_passwd"].value.length<1) {
    e = f.elements["no_enable"];
    if (e.options[e.selectedIndex].value=="No") {
        alert("Please enter an enable password or select Yes for no_enable.");
        f.elements["en_passwd"].focus();
        return false;
    }
}


JAVASCRIPT;

  }
  function validate_input() {
        $db = new $this->database_class;
        $val_ok = true;
        if ($_POST["passwd"][0]<>$_POST["passwd"][1]) {
                $this->add_error("Passwords don't match.");
                $val_ok = false;
        }
        if ($_POST["en_passwd"][0]<>$_POST["en_passwd"][1]) {
                $this->add_error("Enable Passwords don't match.");
                $val_ok = false;
        }
	include ("../keys.php");
	if (empty($_POST["passwd"][0])) $_POST["passwd"]=""; else
	if (openssl_public_encrypt($_POST["passwd"][0],$encrypted,$PublicKey,OPENSSL_PKCS1_OAEP_PADDING)) {
		$_POST["passwd"] = base64_encode($encrypted);
        }
	if (empty($_POST["en_passwd"][0])) $_POST["en_passwd"]=""; else
	if (openssl_public_encrypt($_POST["en_passwd"][0],$encrypted,$PublicKey,OPENSSL_PKCS1_OAEP_PADDING)) {
		$_POST["en_passwd"] = base64_encode($encrypted);
        }
	$GLOBALS["passwd"] = $_POST["passwd"];
        return $val_ok;
  }

}
class devicesTable extends Table {
  var $classname = "devicesTable";
  var $sql_table = "devices";
  var $primary_key = "id";
  var $primary_field = "id";
  var $all_fields = array(
			"hostname",
			"section",
			"type",
			"login_type",
			"username",
			"passwd",
			"en_passwd",
			"no_enable",
			"ssh_cmd",
 			"timeout",
			"state",
			"comments");

  /* comment out or delete some of these default entries so that the table isn't too wide for the screen */
  var $default = array(
			"hostname",
			"section",
			"type",
			"login_type",
			"username",
			);

  // These fields will be searchable and displayed in results.
  // Format is "RealFieldName"=>"Field Name Formatted For Display",
  var $map_cols = array(
			"hostname"=>"Host Name",
			"section"=>"Section",
			"type"=>"Device Type",
			"login_type"=>"Login Type",
			"username"=>"Username",
			"passwd"=>"Password",
			"en_passwd"=>"En Passwd",
                        "no_enable"=>"No Enable",
                        "ssh_cmd"=>"SSH Command",
			"timeout"=>"Timeout",
			"state"=>"State",
			"comments"=>"Comments");
}
class devices_Sql_Query extends Sql_Query {
  var $classname = "devices_Sql_Query";
  var $primary_key = "id";
  var $primary_field = "id";
  var $table = "devices";
}

