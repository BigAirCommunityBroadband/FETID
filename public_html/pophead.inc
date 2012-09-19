<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?=$GLOBALS["HTML_title"]?></title>
<?=$GLOBALS["MetaData"]?>
<meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" href="css/layout.css" type="text/css"/>
<link rel="stylesheet" href="css/style.css" type="text/css"/>
<link rel="stylesheet" href="css/menu-<?=$_ENV["MenuMode"]?>.css" type="text/css"/>
</head>
<body>
<div id="Layout">
  <font class=bigTextBold align="CENTER">fetid Database</font>
  <font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("sections.php") ?>">Sections</a></font>
  <font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("devices.php") ?>">Devices</a></font>
  <font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("login_types.php") ?>">Login Types</a></font>
  <font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("device_types.php") ?>">Device Types</a></font>
  <font class='bigTextBold' align='CENTER'><a href="<?php $sess->purl("logout.php") ?>">Logout</a></font>
<div id="Navigation">
  <ul id="nav">
<?php include('phplib/menus.php'); ?>
  </ul>
</div>
