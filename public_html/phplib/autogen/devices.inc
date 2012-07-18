<?php

class devicesform extends tpl_form {
  var $table = "devices";
  var $key = "id";
  var $key_field = "id"; # if different to $key
  var $classname = "devicesform";
  var $database_class = "DB_rtrmin";

  function setup_fields () {
    $this->form_data->add_element(array("type"=>"hidden","name"=>"id","field"=>"id","size"=>"12"));
    $this->form_data->add_element(array("type"=>"text","name"=>"ip","maxlength"=>"40","size"=>"13",
		"field"=>"ip","extrahtml"=>""
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
		"field"=>"username","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"text","name"=>"passwd","maxlength"=>"60","size"=>"20",
		"field"=>"passwd","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"text","name"=>"en_passwd","maxlength"=>"60","size"=>"20",
		"field"=>"en_passwd","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"submit","name"=>"submit","value"=>"Submit"));
  }

}
class devicesTable extends Table {
  var $classname = "devicesTable";
  var $sql_table = "devices";
  var $primary_key = "id";
  var $primary_field = "id";
  var $all_fields = array(
			"ip",
			"section",
			"type",
			"login_type",
			"username",
			"passwd",
			"en_passwd");

  /* comment out or delete some of these default entries so that the table isn't too wide for the screen */
  var $default = array(
			"ip",
			"section",
			"type",
			"login_type",
			"username",
			"passwd",
			"en_passwd");

  // These fields will be searchable and displayed in results.
  // Format is "RealFieldName"=>"Field Name Formatted For Display",
  var $map_cols = array(
			"ip"=>"Ip",
			"section"=>"Section",
			"type"=>"Type",
			"login_type"=>"Login Type",
			"username"=>"Username",
			"passwd"=>"Passwd",
			"en_passwd"=>"En Passwd");
}
class devices_Sql_Query extends Sql_Query {
  var $classname = "devices_Sql_Query";
  var $primary_key = "id";
  var $primary_field = "id";
  var $table = "devices";
}

