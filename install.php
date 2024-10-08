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
    JakeBBS Installer made by JakeBBS Inc. - http://google.com/search?q=JakeBBS+Inc.&btnI=I%27m+Feeling+Luckysupport/category.php?act=view&id=2

    $FileInfo: install.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
if (@ini_get("register_globals")) {
    require_once('inc/misc/killglobals.php');
}
@error_reporting(E_ALL ^ E_NOTICE);
if (@ini_get("register_globals")) {
    if (!isset($SettDir['misc'])) {
        $SettDir['misc'] = "inc/misc/";
    }
    require_once($SettDir['misc'].'killglobals.php');
}
if (!isset($preact['jakebbs'])) {
    $preact['jakebbs'] = null;
}
if (!isset($_GET['act'])) {
    $_GET['act'] = null;
}
if (!isset($_POST['act'])) {
    $_POST['act'] = null;
}
if ($_GET['act'] == null || $_GET['act'] == "view") {
    $_GET['act'] = "Part1";
}
if ($_POST['act'] == null || $_POST['act'] == "view") {
    $_POST['act'] = "Part1";
}
$_TEG = array(null);
$_TEG['part'] = preg_replace("/Part(1|2|3|4)/", "\\1", $_GET['act']);
$_GET['act'] = strtolower($_GET['act']);
if (isset($_TEG['part'])) {
    if ($_TEG['part'] <= 4 && $_TEG['part'] >= 1) {
        $_GET['act'] = "Part".$_TEG['part'];
    }
}
if ($_GET['act'] != "Part4" && $_POST['act'] != "Part4") {
    $preact['jakebbs'] = "installing";
}
$SetupDir['setup'] = "setup/";
$ConvertDir['setup'] = $SetupDir['setup'];
$SetupDir['convert'] = "setup/convert/";
$ConvertDir['convert'] = $SetupDir['convert'];
$Settings['output_type'] = "html";
$Settings['html_type'] = "xhtml10";
$Settings['board_name'] = "Installing JakeBBS";
if (!isset($Settings['charset'])) {
    $Settings['charset'] = "ISO-8859-15";
}
if (isset($Settings['charset'])) {
    if ($Settings['charset'] != "ISO-8859-15" && $Settings['charset'] != "ISO-8859-1" &&
        $Settings['charset'] != "UTF-8" && $Settings['charset'] != "CP866" &&
        $Settings['charset'] != "Windows-1251" && $Settings['charset'] != "Windows-1252" &&
        $Settings['charset'] != "KOI8-R" && $Settings['charset'] != "BIG5" &&
        $Settings['charset'] != "GB2312" && $Settings['charset'] != "BIG5-HKSCS" &&
        $Settings['charset'] != "Shift_JIS" && $Settings['charset'] != "EUC-JP") {
        $Settings['charset'] = "ISO-8859-15";
    }
}
$SQLCharset = "latin1";
if (isset($_POST['charset'])) {
    if ($_POST['charset'] == "ISO-8859-1") {
        $SQLCharset = "latin1";
    }
    if ($_POST['charset'] == "ISO-8859-15") {
        $SQLCharset = "latin1";
    }
    if ($_POST['charset'] == "UTF-8") {
        $SQLCharset = "utf8";
    }
    $Settings['charset'] = $_POST['charset'];
}
if (!isset($_SERVER['HTTPS'])) {
    $_SERVER['HTTPS'] == "off";
}
if ($_SERVER['HTTPS'] == "on") {
    $prehost = "https://";
}
if ($_SERVER['HTTPS'] != "on") {
    $prehost = "http://";
}
$this_dir = null;
if (dirname($_SERVER['SCRIPT_NAME']) != "." ||
    dirname($_SERVER['SCRIPT_NAME']) != null) {
    $this_dir = dirname($_SERVER['SCRIPT_NAME'])."/";
}
if ($this_dir == null || $this_dir == ".") {
    if (dirname($_SERVER['SCRIPT_NAME']) == "." ||
        dirname($_SERVER['SCRIPT_NAME']) == null) {
        $this_dir = dirname($_SERVER['PHP_SELF'])."/";
    }
}
if ($this_dir == "\/") {
    $this_dir = "/";
}
$this_dir = str_replace("//", "/", $this_dir);
$jakebbsdir = addslashes(str_replace("\\", "/", dirname(__FILE__)."/"));
if (!isset($_POST['BoardURL'])) {
    $Settings['jakebbsurl'] = $prehost.$_SERVER["HTTP_HOST"].$this_dir;
}
if (isset($_POST['BoardURL'])) {
    $Settings['jakebbsurl'] = $_POST['BoardURL'];
}
$Settings['qstr'] = "&";
$Settings['qsep'] = "=";
require($SetupDir['setup'].'preinstall.php');
require_once($SettDir['misc'].'utf8.php');
require_once($SettDir['inc'].'filename.php');
require_once($SettDir['inc'].'function.php');
require($SetupDir['convert'].'info.php');
require($SettDir['inc'].'xhtml10.php');
$Error = null;
$_GET['time'] = false;
?>

<title> <?php echo "Installing ".$VerInfo['JakeBBS_Ver_Show']." on ".$OSType2; ?> </title>
</head>
<body>
<?php require($SettDir['inc'].'navbar.php'); ?>
<div class="Table1Border">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableRow1">
<span style="font-weight: bold; text-align: left;"><?php echo $ThemeSet['TitleIcon']; ?><a href="Install.php">Install <?php echo $VerInfo['JakeBBS_Ver_Show']." on ".$OSType2; ?> </a></span>
</div>
<?php } ?>
<table class="Table1">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableRow1">
<td class="TableColumn1" colspan="2"><span style="font-weight: bold; text-align: left;"><?php echo $ThemeSet['TitleIcon']; ?><a href="Install.php">Install <?php echo $VerInfo['JakeBBS_Ver_Show']." on ".$OSType2; ?> </a></span>
</td>
</tr><?php } ?>
<tr class="TableRow2">
<th class="TableColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Inert your install info: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<?php
if ($_SERVER['HTTPS'] == "on") {
    $prehost = "https://";
}
if ($_SERVER['HTTPS'] != "on") {
    $prehost = "http://";
}
$this_dir = null;
if (dirname($_SERVER['SCRIPT_NAME']) != "." ||
    dirname($_SERVER['SCRIPT_NAME']) != null) {
    $this_dir = dirname($_SERVER['SCRIPT_NAME'])."/";
}
if ($this_dir == null || $this_dir == ".") {
    if (dirname($_SERVER['SCRIPT_NAME']) == "." ||
        dirname($_SERVER['SCRIPT_NAME']) == null) {
        $this_dir = dirname($_SERVER['PHP_SELF'])."/";
    }
}
if ($this_dir == "\/") {
    $this_dir = "/";
}
$this_dir = str_replace("//", "/", $this_dir);
$jakebbsdir = addslashes(str_replace("\\", "/", dirname(__FILE__)."/"));
function sql_list_dbs()
{
    $result = exec_query("SHOW DATABASES;");
    while ($data = mysql_fetch_row($result)) {
        $array[] = $data[0];
    } return $array;
}
if ($_GET['act'] != "Part2" && $_POST['act'] != "Part2") {
    if ($_GET['act'] != "Part3" && $_POST['act'] != "Part3") {
        if ($_GET['act'] != "Part4" && $_POST['act'] != "Part4") {
            require($SetupDir['setup'].'license.php');
        }
    }
}
if ($_GET['act'] == "Part2" && $_POST['act'] == "Part2") {
    if ($_GET['act'] != "Part3" && $_POST['act'] != "Part3") {
        if ($_GET['act'] != "Part4" && $_POST['act'] != "Part4") {
            require($SetupDir['setup'].'presetup.php');
        }
    }
}
if ($_POST['SetupType'] == "convert") {
    require($ConvertInfo['ConvertFile']);
}
if ($_POST['SetupType'] == "install") {
    if ($_GET['act'] != "Part2" && $_POST['act'] != "Part2") {
        if ($_GET['act'] == "Part3" && $_POST['act'] == "Part3") {
            if ($_GET['act'] != "Part4" && $_POST['act'] != "Part4") {
                require($SetupDir['setup'].'setup.php');
            }
        }
    }
}
if ($_POST['SetupType'] == "install") {
    if ($_GET['act'] != "Part2" && $_POST['act'] != "Part2") {
        if ($_GET['act'] != "Part3" && $_POST['act'] != "Part3") {
            if ($_GET['act'] == "Part4" && $_POST['act'] == "Part4") {
                require($SetupDir['setup'].'mkconfig.php');
            }
        }
    }
}
if ($Error == "Yes") { ?>
<br />Install Failed with errors. <a href="install.php?act=view">Click here</a> to restart install. &lt;_&lt;
<br /><br />
</td>
</tr>
<?php } ?>
<tr class="TableRow4">
<td class="TableColumn4">&nbsp;<a href="index.php?act=ReadMe">Readme.txt</a>&nbsp;</td>
</tr>
</table></div>
<div>&nbsp;</div>
<?php require($SettDir['inc'].'endpage.php'); ?>
</body>
</html>
<?php fix_amp(null); ?>
