<?php
include('phplib/prepend.php');

if ($export_results) {
        page_open(array("sess"=>"fetid_Session","auth"=>"fetid_Auth","perm"=>"fetid_Perm","silent"=>"silent"));
} else {
	page_open(array("sess"=>"fetid_Session","auth"=>"fetid_Auth","perm"=>"fetid_Perm"));
	#if ($Field) include("pophead.ihtml"); else include("head.ihtml");
	echo "<span class='big'>Login Types</span>";
	#if (empty($Field)) include("menu.html");
}
check_view_perms();

get_request_values('login_type');


$f = new login_typesform;


if ($WithSelected) {
        check_edit_perms();
        switch ($WithSelected) {
                case "Delete":
			if (array_search('login_types',$_ENV['no_edit'])) {
				echo "No Delete Allowed";
			} else {
                        	$sql = "DELETE FROM login_types WHERE login_type IN (";
                        	$sql .= implode(",",$login_type);
                        	$sql .= ")";
                        	if ($dev) echo "<h1>$sql</h1>";
                        	$db->query($sql);
                        	echo $db->affected_rows()." deleted.";
		    	}
                        if (!$dev) echo "<META HTTP-EQUIV=REFRESH CONTENT=\"10; URL=".$sess->self_url()."\">";
                        break;
                case "Print";
                        foreach ($login_type as $row) {
				echo "<div class='float_left'>\n";
                                $f = new login_typesform;
                                $f->find_values($row);
                                $f->freeze();
                                $f->display();
				echo "\n</div>\n";
                        }
			echo "\n<br style='clear: both;'>\n";
                        break;
        }
        echo "&nbsp<a href=\"".$sess->self_url();
        echo "\">Back to login_types.</a><br>\n";
        page_close();
        exit;
}

if ($submit) {
  switch ($submit) {
   case "Copy": $id="";
   case "Save":
    if ($id) $submit = "Edit";
    else $submit = "Add";
   case "Add":
   case "Edit":
    if (isset($auth)) {
     check_edit_perms();
     if (!$f->validate()) {
        $cmd = $submit;
        echo "<font class='bigTextBold'>$cmd Login Types</font>\n<hr />\n";
        $f->reload_values();
        $f->display();
        page_close();
        exit;
     }
     else
     {
        echo "Saving....";
        $id = $f->save_values();
        if ($Field) {
                $text = $_POST["AddressLine1"].", ".$_POST["AddressLine2"].", ".$_POST["AddressLine3"].", ".$_POST["City"];
?><script>
if (window.opener) {
        window.opener.addOption("<?php echo $Field; ?>","<?php echo $text; ?>","<?php echo $id; ?>");
        window.close();
}
</script><?php
        }
        echo "<b>Done!</b><br />\n";
        if (!$dev) echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=".$sess->self_url()."\">";
        echo "&nbsp;<a href=\"".$sess->self_url()."\">Back to login_types.</a><br />\n";
        page_close();
        exit;
     }
    } else {
        echo "You are not logged in....";
        echo "<b>Aborted!</b><br />\n";
    }
   case "View":
   case "Back":
        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0; URL=".$sess->self_url()."\">";
        echo "&nbsp;<a href=\"".$sess->self_url()."\">Back to login_types.</a><br />\n";
        page_close();
        exit;
   case "Delete":
    if (isset($auth)) {
        check_edit_perms();
        echo "Deleting....";
        $f->save_values();
        echo "<b>Done!</b><br />\n";
    } else {
        echo "You are not logged in....";
        echo "<b>Aborted!</b><br />\n";
    }
        if (!$dev) echo "<META HTTP-EQUIV=REFRESH CONTENT=\"2; URL=".$sess->self_url()."\">";
        echo "&nbsp;<a href=\"".$sess->self_url()."\">Back to login_types.</a><br />\n";
        page_close();
        exit;
   default:
	include("search.php");
  }
} else {
    if ($login_type) {
	$f->find_values($login_type);
    } else {
	include("search.php");
    }
}


if ($export_results) $f->setup();
else $f->javascript();


switch ($cmd) {
    case "View":
    case "Delete":
	$f->freeze();
    case "Add":

    case "Copy":
	if ($cmd=="Copy") $id="";
    case "Edit":
	echo "<font class='bigTextBold'>$cmd Login Types</font>\n<hr />\n";
	$f->display();
	if ($orig_cmd=="View") $f->showChildRecords();
	break;
    default:
	$cmd="Query";
	$t = new login_typesTable;
	$t->heading = 'on';
	$t->sortable = 'on';
	$t->trust_the_data = false;   /* if true, send raw data without htmlspecialchars */
	$t->limit = 100; 	 /* max length of field data before trucation and add ... */
	$t->add_extra = 'on';   /* or set to base url of php file to link to, defaults to PHP_SELF */
    #   $t->add_extra = "SomeFile.php";                           # use defaults, but point to a different target file.
    #   $t->add_extra = array("View","Edit","Copy","Delete");     # just specify the command names.
    #   $t->add_extra = array(                                    # or specify parameters as well.
    #                      "View" => array("target"=>"PayPal.php","key"=>"id","perm"=>"admin","display"=>"view","class"=>"ae_view"),
    #                      );
	$t->add_total = 'on';   /* add a grand total row to the bottom of the table on the numberic columns */
	$t->add_insert = $f->classname;  /* Add a blank row ontop of table allowing insert or search */
	$t->add_insert_buttons = 'Search';   /* Control which buttons appear on the add_insert row eg: Add,Search */
	/* See below - EditMode can also be turned on/off by user if section below uncommented */
	#$t->edit = $f->classname;   /* Allow rows to be editable with a save button that appears onchange */
	#$t->ipe_table = 'login_types';   /* Make in place editing changes immediate without a save button */
	#$t->checkbox_menu = Array('Print');
	#$t->check = 'login_type';  /* Display a column of checkboxes with value of key field*/

	$db = new DB_fetid;


        if (array_key_exists("login_types_fields",$_REQUEST)) $login_types_fields = $_REQUEST["login_types_fields"];
        if (empty($login_types_fields)) {
                $login_types_fields = array_first_chunk($t->default,7,11);
                $sess->register("login_types_fields");
        }
	if (in_array(@$LocField,$login_types_fields)) displayLocSelect($f->classname,$LocField);
        
        $t->fields = $login_types_fields;
		
	#$t->extra_html = array('fieldname'=>'extrahtml');
	#$t->align      = array('fieldname'=>'right', 'otherfield'=>'center'); 	

        if (!$export_results) {
          echo "Output to:";
          echo "&nbsp;<input name='ExportTo' type='radio' checked='checked' value='' onclick=\"javascript:export_results('');\"> Here";
          echo "&nbsp;<input name='ExportTo' type='radio' onclick=\"javascript:export_results('Excel2007');\"> Excel 2007&nbsp;\n";
  

          echo "\n<a class='btn' href='#ColumnChooser' data-toggle='modal'>Column Chooser</a>\n";
          echo "<div id='ColumnChooser' class='modal hide'>\n";
          echo "  <div class='modal-header'>\n   <button type='button' class='close' data-dismiss='modal'>×</button>\n";
          echo "   <h3>Column Chooser</h3>\n  </div>\n  <div class='modal-body'>";
          echo " <form id=ColumnSelector method='post'>\n";

          foreach ($t->all_fields as $field) {
                if (in_array($field,$login_types_fields,TRUE)) $chk = "checked='checked'"; else $chk="";
                echo "\n   <input type='checkbox' $chk name='login_types_fields[]' value='$field' />$field <br />";
          }
	  $foot = "";
          if ($sess->have_edit_perm()) {
            if ($EditMode=='on') {
                $on='checked="checked"'; $off='';
		$t->edit = 'login_typesform';   
		# $t->ipe_table = 'login_types';   #uncomment this for immediate table update (no save button)
            } else {
                $off='checked="checked"'; $on='';
            }
            $foot = " &nbsp; Edit Mode <input type='radio' name='EditMode' value='on' $on> On <input type='radio' name='EditMode' value='off' $off /> Off &nbsp; ";
          } else {
            $EditMode='';
          }

          echo "\n  </div>\n  <div class='modal-footer'>\n   <a href='#' class='btn' data-dismiss='modal'>Close</a>\n";
          echo "  $foot<input type=submit class='btn btn-primary' value='Set'>\n  </div>\n </form>";
	  echo "\n</div>";
          echo "<script>$('#ColumnChooser').modal();</script>\n";

	}

  // When we hit this page the first time,
  // there is no $q.
  if (!isset($q_login_types)) {
    $q_login_types = new login_types_Sql_Query;     // We make one
    $q_login_types->conditions = 1;     // ... with a single condition (at first)
    $q_login_types->translate  = "on";  // ... column names are to be translated
    $q_login_types->container  = "on";  // ... with a nice container table
    $q_login_types->variable   = "on";  // ... # of conditions is variable
    $q_login_types->lang       = "en";  // ... in English, please
    $q_login_types->extra_cond = "";  
    $q_login_types->default_query = "1";  
    $q_login_types->default_sortorder = "login_type desc";  

    $sess->register("q_login_types");   // and don't forget this!
  }

  if ($rowcount) {
        $q_login_types->start_row = $startingwith;
        $q_login_types->row_count = $rowcount;
  } else {
        $startingwith = $q_login_types->start_row;
        $rowcount = $q_login_types->row_count;
  }

  if ($submit=='Search') $query = $f->search();   // create sql query from form posted values.

  // When we hit that page a second time, the array named
  // by $base will be set and we must generate the $query.
  // Ah, and don\'t set $base to "q" when $q is your Sql_Query
  // object... :-)
  if (array_key_exists("x",$_POST)) {
    get_request_values("x");
    $query = $q_login_types->where("x", 1);
    $hideQuery = "";
  } else {
    $hideQuery = "style='display:none'";
  }

  if ($Format = $export_results) {
        $custom_query = array_key_exists("custom_query",$_POST) ? $_POST["custom_query"] : "";

        require_once "/usr/share/PHPExcel.php";

        $cacheMethod = PHPExcel_CachedObjectStorageFactory::cache_in_memory;
        PHPExcel_Settings::setCacheStorageMethod($cacheMethod);

        $locale = 'en_us';
        $validLocale = PHPExcel_Settings::setLocale($locale);

        $workbook = new PHPExcel();
        $workbook->setActiveSheetIndex(0);
        $worksheet1 = $workbook->getActiveSheet();
        $worksheet1->setTitle('login_types');

        $cols = count($t->fields);
        $range = "A1:" . chr(64+$cols) . "1";
        $worksheet1->getStyle($range)->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setARGB(PHPExcel_Style_Color::COLOR_DARKGREEN);
        $worksheet1->getStyle($range)->getAlignment()->setHorizontal('center');
        $worksheet1->getStyle($range)->getFont()->getColor()->setARGB(PHPExcel_Style_Color::COLOR_WHITE);

        $r = 1;
        $col = "A";
        foreach ($t->fields as $field) {
                if (!isset($f->form_data->elements[$field]["ob"])) {var_dump($f->form_data->elements[$field]); exit; }
                $el = $f->form_data->elements[$field]["ob"];
                if (!$size=@$el->size) {
                        $size = 5;
                        if (!isset($el->options)) {
                                $size = strlen($el->value);
                        } else
                        foreach($el->options as $option) {
				if (is_array($option)) $len=strlen($option["label"]);
                                else $len = strlen($option);
                                if ($len>$size) $size = $len;
                        }
                }
                $worksheet1->getColumnDimension($col)->setWidth($size);
                $worksheet1->getCell("$col$r")->setValue($t->map_cols[$field]);
                $col++;
        }

        $sql = "SELECT * FROM login_types $custom_query WHERE $query";
        $db->query($sql);
        while ($db->next_record()) {
                $r++;
                $col = "A";
                foreach ($t->fields as $field) {
                        $worksheet1->getCell("$col$r")->setValue($db->f($field));
                        $col++;
                }
        }

        $ContentType="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet";

        header("Content-Type: $ContentType");
        header("Content-Disposition: attachment;filename=\"login_types.xlsx\"");
        header("Cache-Control: max-age=0");

        $objWriter = PHPExcel_IOFactory::createWriter($workbook, $Format);
        $objWriter->save('php://output');
        exit;
  }


  if (empty($sortorder)) $sortorder = empty($q_login_types->last_sortorder) ? $q_login_types->default_sortorder : $q_login_types->last_sortorder ;
  if (empty($query))   $query     = empty($q_login_types->last_query)     ? $q_login_types->default_query     : $q_login_types->last_query ;

  $q_login_types->last_query = $query;
  $q_login_types->last_sortorder = $sortorder;
/*
  $db->query("SELECT COUNT(*) as total from ".$db->qi("login_types")." where ".$query);
  $db->next_record();
  if ($db->f("total") < ($q_login_types->start_row - $q_login_types->row_count))
      { $q_login_types->start_row = $db->f("total") - $q_login_types->row_count; }
*/ 
  if ($q_login_types->start_row < 0) { $q_login_types->start_row = 0; }

#  $f->sort_function_maps = array(  /* use a function to sort values for specified fields */
#      "ip_addr"=>"inet_aton",  
#      );

  if (strpos(strtolower($query),"order by")===false) {
	if ($so=$f->order_by($sortorder)) $query .= " order by ".$so;
  }

  $query .= " LIMIT ".$q_login_types->start_row.",".$q_login_types->row_count;

  // In any case we must display that form now. Note that the
  // "x" here and in the call to $q->where must match.
  // Tag everything as a CSS "query" class.
  $mode = "'hide'";

  echo "\n<a class='btn' href='#customQuery' data-toggle='modal'>Custom Query</a>\n";
  echo "<div id='customQuery' class='modal hide'>\n";

  echo " <div class='modal-header'>\n  <button type='button' class='close' data-dismiss='modal'>×</button>\n";
  echo "  <h3>Query Stats</h3>\n </div>\n <div class='modal-body'>\n";
  printf($q_login_types->form("x", $t->map_cols, "query"));
  if (array_key_exists("more_0",$x)) {$query=""; $mode="'show'";}
  if (array_key_exists("less_0",$x)) {$query=""; $mode="'show'";}
  if (!array_key_exists("x",$_POST)) $mode="'hide'";

  echo "\n </div>\n <div class='modal-footer'>\n  <a href='#' class='btn' data-dismiss='modal'>Close</a>\n";
  echo "  <a href='#' class='btn btn-primary'>Save changes</a>\n </div>\n</div>";

  echo "<script>$('#customQuery').modal($mode);</script>\n";


  // Do we have a valid query string?
  if ($query) {

    // Do that query
    $sql = $t->select($f).$query;
    $db->query($sql);
    #$db->query("select * from ".$db->qi("login_types")." where ". $query);

    // Show that condition

    echo "\n<a class='btn' href='#QueryStats' data-toggle='modal'>Query Stats</a>\n";
    echo "<div id='QueryStats' class='modal hide'>\n";

    echo " <div class='modal-header'>\n  <button type='button' class='close' data-dismiss='modal'>×</button>\n";
    echo "  <h3>Query Stats</h3>\n </div>\n <div class='modal-body'>\n";
    printf("  Query Condition = %s<br />\n", $query);
    printf("  Query Results = %s<br />\n", $db->num_rows());
    echo " </div>\n <div class='modal-footer'>\n";

    echo "  <a href='#' class='btn' data-dismiss='modal'>Close</a>\n";
    echo " </div>\n</div><script>$('#QueryStats').modal();</script>\n";

    echo "\n<a class='btn' href=\"".$sess->self_url().$sess->add_query(array("cmd"=>"Add"))."\">Add New Login Types</a>\n";
    echo "<hr />\n\n";

    // Dump the results (tagged as CSS class default)
    $t->show_result($db, "default");
  }
} // switch $cmd
page_close();
?>
