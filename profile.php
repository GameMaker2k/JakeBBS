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

    $FileInfo: profile.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
if (@ini_get("register_globals")) {
    require_once('inc/misc/killglobals.php');
}
require('preindex.php');
$usefileext = $Settings['file_ext'];
if ($ext == "noext" || $ext == "no ext" || $ext == "no+ext") {
    $usefileext = "";
}
$filewpath = $exfile['profile'].$usefileext.$_SERVER['PATH_INFO'];
?>

<title> <?php echo $Settings['board_name'].$jakebbspowertitle; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php');
if ($_SESSION['UserGroup'] == $Settings['GuestGroup'] || $GroupInfo['CanEditProfile'] == "no") {
    redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
if ($_SESSION['UserGroup'] != $Settings['GuestGroup'] ||
    $GroupInfo['CanEditProfile'] == "yes") {
    if ($_GET['act'] == null || $_GET['act'] == "notepad") {
        $_GET['act'] = "view";
    }
    if ($_GET['act'] == "view" ||
    $_GET['act'] == "signature" ||
    $_GET['act'] == "avatar" ||
    $_GET['act'] == "settings" ||
    $_GET['act'] == "profile" ||
    $_GET['act'] == "userinfo") {
        require($SettDir['inc'].'profilemain.php');
    }
}
require($SettDir['inc'].'endpage.php');
if (!isset($profiletitle)) {
    $profiletitle = null;
}
?>

</body>
</html>
<?php
if ($profiletitle == null) {
    fix_amp($Settings['use_gzip'], $GZipEncode['Type']);
}
if ($profiletitle != null) {
    change_title($Settings['board_name'].$profiletitle, $Settings['use_gzip'], $GZipEncode['Type']);
}
?>
