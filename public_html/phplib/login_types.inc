<?php

class login_typesform extends tpl_form {
  var $table = "login_types";
  var $key = "login_type";
  var $key_field = "login_type"; # if different to $key
  var $classname = "login_typesform";
  var $database_class = "DB_fetid";

  function setup_fields () {
    $this->form_data->add_element(array("type"=>"text","name"=>"login_type","maxlength"=>"60","size"=>"20"
		,"minlength"=>1,"length_e"=>"Login Type must not be blank.  Required field.",
		"field"=>"login_type","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"submit","name"=>"submit","value"=>"Submit"));
  }

}
class login_typesTable extends Table {
  var $classname = "login_typesTable";
  var $sql_table = "login_types";
  var $primary_key = "login_type";
  var $primary_field = "login_type";
  var $all_fields = array(
			"login_type");

  /* comment out or delete some of these default entries so that the table isn't too wide for the screen */
  var $default = array(
			"login_type");

  // These fields will be searchable and displayed in results.
  // Format is "RealFieldName"=>"Field Name Formatted For Display",
  var $map_cols = array(
			"login_type"=>"Login Type");
}
class login_types_Sql_Query extends Sql_Query {
  var $classname = "login_types_Sql_Query";
  var $primary_key = "login_type";
  var $primary_field = "login_type";
  var $table = "login_types";
}

