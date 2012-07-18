<?php 
global $q, $widthTotal, $menu;
$widthTotal = 0;

if (substr($_SERVER["REQUEST_URI"],0,strlen($_SERVER["SCRIPT_NAME"]))==$_SERVER["SCRIPT_NAME"]) 
     $ModReWritten=false;
else $ModReWritten = true;

$db = new $_ENV['DatabaseClass'];
$sql = "SELECT * FROM menu";
$db->query($sql);
while ($db->next_record()) {
        extract($db->Record);
	$ok = false;
        if ($view_requires) {
               if ($perm) {
                        foreach(explode(",",$view_requires) as $need) {
                                if ($perm->have_perm($need)) $ok = true;
                        }
                }
        } else $ok = true;
        if ($ok) {
		if ($target=="menu") $target = "menupage.php?MenuId=$id";
		if (substr($target,0,4)<>"http") $target = "/$target";
		$menu[$id] = new StdClass;
		$menu[$id]->target = $target;
		$menu[$id]->title = $title;
		$menu[$id]->width = $width;
		$menu[$parent]->children[$position] = $id;
	}
}

function menu($parent,$indent) {
	global $widthTotal, $menu;
	if (!isset($menu[$parent]->children)) return;
	foreach ($menu[$parent]->children as $sortorder => $id) {
		$items[] = $sortorder;
	}
	sort($items);
	if ($parent) echo "\n$indent <ul class='dropdown-menu'>";
	foreach($items as $item) {
		$id = $menu[$parent]->children[$item];
		$target = $menu[$id]->target;
		$title = $menu[$id]->title;
		if (isset($menu[$id]->children)) {
			$li_xtra = " class='dropdown'";
			$a_xtra = " class='dropdown-toggle' data-toggle='dropdown' data-target='#'";
			$title .= " <b class='caret'></b>";
		} else { $li_xtra = ""; $a_xtra=""; }
		echo "\n$indent  <li$li_xtra><a href='$target'$a_xtra";
		echo ">$title</a>";
		menu($id,$indent."  ");
		echo "</li>";
	}
	if ($parent) echo "\n$indent </ul>\n$indent";
}

menu(0,"");

?>
