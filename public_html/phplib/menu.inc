<?php

class menuform extends tpl_form {
  var $table = "menu";
  var $key = "id";
  var $key_field = "id"; # if different to $key
  var $classname = "menuform";
  var $database_class = "DB_rtrmin";

  function setup_fields () {
    $this->form_data->add_element(array("type"=>"hidden","name"=>"id","field"=>"id","size"=>"12"));
    $this->form_data->add_element(array("type"=>"text","name"=>"parent","size"=>"12",
		"valid_regex"=>"^[0-9|\-]+$","field"=>"parent",
		"valid_e"=>"Parent must be a whole number containing digits 0-9 only. May also start with negative symbol -",
		"extrahtml"=>"","value"=>"0"));
    $this->form_data->add_element(array("type"=>"text","name"=>"position","size"=>"12",
		"valid_regex"=>"^[0-9|\-]+$","field"=>"position",
		"valid_e"=>"Position must be a whole number containing digits 0-9 only. May also start with negative symbol -",
		"extrahtml"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"title","maxlength"=>"40","size"=>"13"
		,"minlength"=>1,"length_e"=>"Title must not be blank.  Required field.",
		"field"=>"title","extrahtml"=>""
		,"value"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"target","maxlength"=>"255","size"=>"85"
		,"minlength"=>1,"length_e"=>"Target must not be blank.  Required field.",
		"field"=>"target","extrahtml"=>""
		,"value"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"header","maxlength"=>"80","size"=>"26",
		"field"=>"header","extrahtml"=>""
		,"value"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"subnavhdr","maxlength"=>"20","size"=>"6",
		"field"=>"subnavhdr","extrahtml"=>""
		,"value"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"HtmlTitle","maxlength"=>"255","size"=>"85",
		"field"=>"HtmlTitle","extrahtml"=>""
		,"value"=>""));
    $this->form_data->add_element(array("type"=>"textarea","name"=>"MetaData","rows"=>"5","cols"=>"50",
		"field"=>"MetaData","extrahtml"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"view_requires","maxlength"=>"255","size"=>"85",
		"field"=>"view_requires","extrahtml"=>""
		,"value"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"edit_requires","maxlength"=>"255","size"=>"85",
		"field"=>"edit_requires","extrahtml"=>""
		,"value"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"LongDescription","maxlength"=>"80","size"=>"26",
		"field"=>"LongDescription","extrahtml"=>""
		,"value"=>""));
    $this->form_data->add_element(array("type"=>"htmlarea","name"=>"HelpText","height"=>"700px","width"=>"750px",
                "field"=>"HelpText","extrahtml"=>""));
    $this->form_data->add_element(array("type"=>"text","name"=>"width","size"=>"12",
		"valid_regex"=>"^[0-9|\-]+$|^$","field"=>"width",
		"valid_e"=>"Width must be a whole number containing digits 0-9 only. May also start with negative symbol -",
		"extrahtml"=>""));
    $this->form_data->add_element(array("type"=>"submit","name"=>"submit","value"=>"Submit"));
  }

}
class menuTable extends Table {
  var $classname = "menuTable";
  var $sql_table = "menu";
  var $primary_key = "id";
  var $primary_field = "id";
  var $all_fields = array(
			"parent",
			"position",
			"title",
			"target",
			"header",
			"subnavhdr",
			"HtmlTitle",
			"MetaData",
			"view_requires",
			"edit_requires",
			"LongDescription",
			"width");

  /* comment out or delete some of these default entries so that the table isn't too wide for the screen */
  var $default = array(
			"parent",
			"position",
			"title",
			"target",
			"header",
			"subnavhdr",
			"HtmlTitle",
			"MetaData",
			"view_requires",
			"edit_requires",
			"LongDescription",
			"width");

  // These fields will be searchable and displayed in results.
  // Format is "RealFieldName"=>"Field Name Formatted For Display",
  var $map_cols = array(
			"parent"=>"Parent",
			"position"=>"Position",
			"title"=>"Title",
			"target"=>"Target",
			"header"=>"Header",
			"subnavhdr"=>"Subnavhdr",
			"HtmlTitle"=>"Html Title",
			"MetaData"=>"Meta Data",
			"view_requires"=>"View Requires",
			"edit_requires"=>"Edit Requires",
			"LongDescription"=>"Long Description",
			"width"=>"Width");
}
class menu_Sql_Query extends Sql_Query {
  var $classname = "menu_Sql_Query";
  var $primary_key = "id";
  var $primary_field = "id";
  var $table = "menu";
}

