<?php
/*
    This program is free software; you can redistribute it and/or modify
    it under the terms of the Revised BSD License.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    Revised BSD License for more details.

    Copyright 2004-2009 JakeBBS - http://google.com/search?q=JakeBBS+Inc.&btnI=I%27m+Feeling+Lucky
    Copyright 2004-2009 JakeBBS Inc. - http://google.com/search?q=JakeBBS+Inc.&btnI=I%27m+Feeling+Lucky

    $FileInfo: preinstall.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
@error_reporting(E_ALL ^ E_NOTICE);
/* Some ini setting changes uncomment if you need them. */
//@ini_set('session.use_trans_sid', false);
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "preinstall.php" || $File3Name == "/preinstall.php") {
    @header('Location: index.php');
    exit();
}

@header("Cache-Control: private, must-revalidate"); // IE 6 Fix
@header("Pragma: private, must-revalidate");
@header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
@output_reset_rewrite_vars();
if (!isset($SettDir['inc'])) {
    $SettDir['inc'] = "inc/";
}
if (!isset($SettDir['misc'])) {
    $SettDir['misc'] = "inc/misc/";
}
if (!isset($SettDir['admin'])) {
    $SettDir['admin'] = "inc/admin/";
}
if (!isset($SettDir['mod'])) {
    $SettDir['mod'] = "inc/mod/";
}
if (!isset($SettDir['themes'])) {
    $SettDir['themes'] = "themes/";
}
if (!isset($_POST['License'])) {
    $_POST['License'] = null;
}
if (file_exists($SettDir['themes']."JakeBBS/settings.php")) {
    require($SettDir['themes']."JakeBBS/settings.php");
}
if (!file_exists($SettDir['themes']."JakeBBS/settings.php")) {
    require($SettDir['themes']."Gray/settings.php");
}
