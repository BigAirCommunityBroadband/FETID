<?php

class device_typesform extends tpl_form {
  var $table = "device_types";
  var $key = "device_type";
  var $key_field = "device_type"; # if different to $key
  var $classname = "device_typesform";
  var $database_class = "DB_rtrmin";

  function setup_fields () {
    $this->form_data->add_element(array("type"=>"text","name"=>"device_type","maxlength"=>"60","size"=>"20"
		,"minlength"=>1,"length_e"=>"Device Type must not be blank.  Required field.",
		"field"=>"device_type","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"submit","name"=>"submit","value"=>"Submit"));
  }

}
class device_typesTable extends Table {
  var $classname = "device_typesTable";
  var $sql_table = "device_types";
  var $primary_key = "device_type";
  var $primary_field = "device_type";
  var $all_fields = array(
			"device_type");

  /* comment out or delete some of these default entries so that the table isn't too wide for the screen */
  var $default = array(
			"device_type");

  // These fields will be searchable and displayed in results.
  // Format is "RealFieldName"=>"Field Name Formatted For Display",
  var $map_cols = array(
			"device_type"=>"Device Type");
}
class device_types_Sql_Query extends Sql_Query {
  var $classname = "device_types_Sql_Query";
  var $primary_key = "device_type";
  var $primary_field = "device_type";
  var $table = "device_types";
}

