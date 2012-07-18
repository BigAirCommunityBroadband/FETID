<?php

class sectionsform extends tpl_form {
  var $table = "sections";
  var $key = "section";
  var $key_field = "section"; # if different to $key
  var $classname = "sectionsform";
  var $database_class = "DB_rtrmin";

  function setup_fields () {
    $this->form_data->add_element(array("type"=>"text","name"=>"section","maxlength"=>"60","size"=>"20"
		,"minlength"=>1,"length_e"=>"Section must not be blank.  Required field.",
		"field"=>"section","extrahtml"=>""
		));
    $this->form_data->add_element(array("type"=>"submit","name"=>"submit","value"=>"Submit"));
  }

}
class sectionsTable extends Table {
  var $classname = "sectionsTable";
  var $sql_table = "sections";
  var $primary_key = "section";
  var $primary_field = "section";
  var $all_fields = array(
			"section");

  /* comment out or delete some of these default entries so that the table isn't too wide for the screen */
  var $default = array(
			"section");

  // These fields will be searchable and displayed in results.
  // Format is "RealFieldName"=>"Field Name Formatted For Display",
  var $map_cols = array(
			"section"=>"Section");
}
class sections_Sql_Query extends Sql_Query {
  var $classname = "sections_Sql_Query";
  var $primary_key = "section";
  var $primary_field = "section";
  var $table = "sections";
}

