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

    $FileInfo: category.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
if (@ini_get("register_globals")) {
    require_once('inc/misc/killglobals.php');
}
$checklowview = true;
require('preindex.php');
$usefileext = $Settings['file_ext'];
if ($ext == "noext" || $ext == "no ext" || $ext == "no+ext") {
    $usefileext = "";
}
$filewpath = $exfile['category'].$usefileext.$_SERVER['PATH_INFO'];
if (!is_numeric($_GET['id'])) {
    $_GET['id'] = "1";
}
?>

<title> <?php echo $Settings['board_name'].$jakebbspowertitle; ?> </title>
</head>
<body>
<?php if ($_GET['act'] != "lowview") {
    require($SettDir['inc'].'navbar.php');
}
$CatCheck = null;
if ($_GET['act'] == null) {
    $_GET['act'] = "view";
}
if (!is_numeric($_GET['id'])) {
    $_GET['id'] = "1";
}
if ($_GET['act'] == "view") {
    require($SettDir['inc'].'categories.php');
}
if ($_GET['act'] == "lowview") {
    require($SettDir['inc'].'lowcategories.php');
}
if ($_GET['act'] == "view" || $_GET['act'] == "stats") {
    require($SettDir['inc'].'stats.php');
}
require($SettDir['inc'].'endpage.php');
if (!isset($CategoryName)) {
    $CategoryName = null;
}
?>

</body>
</html>
<?php
change_title($Settings['board_name']." ".$ThemeSet['TitleDivider']." ".$CategoryName, $Settings['use_gzip'], $GZipEncode['Type']);
?>
