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

    $FileInfo: mysql.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
/* Some ini setting changes uncomment if you need them.
   Display PHP Errors */
//@ini_set("display_errors", true);
//@ini_set("display_startup_errors", true);
@error_reporting(E_ALL ^ E_NOTICE);
/* Get rid of session id in urls */
//@ini_set("session.use_trans_sid", false);
//@ini_set("session.use_cookies", true);
//@ini_set("session.use_only_cookies", true);
//@ini_set("url_rewriter.tags","");
@set_time_limit(30);
@ignore_user_abort(true);
/* Change session garbage collection settings */
//@ini_set("session.gc_probability", 1);
//@ini_set("session.gc_divisor", 100);
//@ini_set("session.gc_maxlifetime", 1440);
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mysql.php" || $File3Name == "/mysql.php") {
    @header('Location: index.php');
    exit();
}
require('settings.php');
$Settings['bid'] = base64_encode(urlencode($Settings['jakebbsurl']));
if (!isset($Settings['showverinfo'])) {
    $Settings['showverinfo'] = "on";
}
if ($Settings['fixpathinfo'] == "off") {
    $Settings['fixpathinfo'] = null;
}
if ($Settings['fixbasedir'] == "off") {
    $Settings['fixbasedir'] = null;
}
if ($Settings['fixcookiedir'] == "off") {
    $Settings['fixcookiedir'] = null;
}
if ($Settings['jakebbsurl'] == "localhost") {
    @header("Content-Type: text/plain; charset=UTF-8");
    echo "500 Error: URL is malformed. Try reinstalling JakeBBS.";
    die();
}
if ($Settings['fixbasedir'] == "on") {
    if ($Settings['jakebbsurl'] != null && $Settings['jakebbsurl'] != "localhost") {
        $PathsTest = parse_url($Settings['jakebbsurl']);
        $Settings['fixbasedir'] = $PathsTest['path']."/";
        $Settings['fixbasedir'] = str_replace("//", "/", $Settings['fixbasedir']);
    }
}
if ($Settings['fixcookiedir'] == "on") {
    if ($Settings['jakebbsurl'] != null && $Settings['jakebbsurl'] != "localhost") {
        $PathsTest = parse_url($Settings['jakebbsurl']);
        $Settings['fixcookiedir'] = $PathsTest['path']."/";
        $Settings['fixcookiedir'] = str_replace("//", "/", $Settings['fixcookiedir']);
    }
}
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
$chkcharset = $Settings['charset'];
@ini_set('default_charset', $Settings['charset']);
//@session_save_path($SettDir['inc']."temp/");
if (!isset($Settings['sqldb'])) {
    if (file_exists("install.php")) {
        @header('Location: install.php');
        die();
    }
    if (!file_exists("install.php")) {
        @header("Content-Type: text/plain; charset=UTF-8");
        echo "403 Error: Sorry could not find install.php\nTry uploading files again and if that dose not work try download JakeBBS again.";
        die();
    }
}
if (!isset($Settings['sqlhost'])) {
    $Settings['sqlhost'] = "localhost";
}
@ini_set("error_prepend_string", "<span style='color: ff0000;'>");
@ini_set("error_append_string", "</span>");
if ($Settings['fixpathinfo'] == "on") {
    $_SERVER['PATH_INFO'] = $_SERVER['ORIG_PATH_INFO'];
    @putenv("PATH_INFO=".$_SERVER['ORIG_PATH_INFO']);
}
// Check to see if variables are set
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
if (!isset($Settings['use_iniset'])) {
    $Settings['use_iniset'] = null;
}
if (!isset($Settings['clean_ob'])) {
    $Settings['clean_ob'] = "off";
}
if (!isset($_SERVER['PATH_INFO'])) {
    $_SERVER['PATH_INFO'] = null;
}
if (!isset($_SERVER['HTTP_ACCEPT_ENCODING'])) {
    $_SERVER['HTTP_ACCEPT_ENCODING'] = null;
}
if (!isset($_SERVER["HTTP_ACCEPT"])) {
    $_SERVER["HTTP_ACCEPT"] = null;
}
if (!isset($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = null;
}
if (!isset($_GET['page'])) {
    $_GET['page'] = null;
}
if (!isset($_GET['act'])) {
    $_GET['act'] = null;
}
if (!isset($_POST['act'])) {
    $_POST['act'] = null;
}
if (!isset($_GET['modact'])) {
    $_GET['modact'] = null;
}
if (!isset($_POST['modact'])) {
    $_POST['modact'] = null;
}
if (!isset($_GET['id'])) {
    $_GET['id'] = null;
}
if (!isset($_GET['debug'])) {
    $_GET['debug'] = "off";
}
if (!isset($_GET['post'])) {
    $_GET['post'] = null;
}
if (!isset($_POST['License'])) {
    $_POST['License'] = null;
}
if (!isset($_SERVER['HTTPS'])) {
    $_SERVER['HTTPS'] = "off";
}
require_once($SettDir['misc'].'utf8.php');
require_once($SettDir['inc'].'filename.php');
if ($_GET['act'] == "versioninfo") {
    @header("Content-Type: text/plain; charset=UTF-8"); ?>
<charset><?php echo $Settings['charset']; ?></charset> 
<title><?php echo $Settings['board_name']; ?></title> 
<?php echo "<name>JakeBBS|".$VER2[1]."|".$VER1[0].".".$VER1[1].".".$VER1[2]."|".$VER2[2]."|".$SubVerN."</name>";
    die();
}
if (!isset($Settings['use_hashtype'])) {
    $Settings['use_hashtype'] = "sha256";
}
if (!function_exists('hash') || !function_exists('hash_algos')) {
    if ($Settings['use_hashtype'] != "md5" &&
       $Settings['use_hashtype'] != "sha1" &&
       $Settings['use_hashtype'] != "sha256") {
        $Settings['use_hashtype'] = "sha256";
    }
}
if (function_exists('hash') && function_exists('hash_algos')) {
    if (!in_array($Settings['use_hashtype'], hash_algos())) {
        $Settings['use_hashtype'] = "sha256";
    }
    if ($Settings['use_hashtype'] != "md2" &&
       $Settings['use_hashtype'] != "md4" &&
       $Settings['use_hashtype'] != "md5" &&
       $Settings['use_hashtype'] != "sha1" &&
       $Settings['use_hashtype'] != "sha256" &&
       $Settings['use_hashtype'] != "sha386" &&
       $Settings['use_hashtype'] != "sha512") {
        $Settings['use_hashtype'] = "sha256";
    }
}
require_once($SettDir['inc'].'function.php');
if ($Settings['enable_pathinfo'] == "on") {
    mrstring(); /* Change Path info to Get Vars :P */
}
// Check to see if variables are set
require_once($SettDir['misc'].'setcheck.php');
$qstrhtml = htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']);
if ($Settings['enable_https'] == "on" && $_SERVER['HTTPS'] == "on") {
    if ($Settings['jakebbsurl'] != null && $Settings['jakebbsurl'] != "localhost") {
        $HTTPsTest = parse_url($Settings['jakebbsurl']);
        if ($HTTPsTest['scheme'] == "http") {
            $Settings['jakebbsurl'] = preg_replace("/http\:\/\//i", "https://", $Settings['jakebbsurl']);
        }
    }
}
$cookieDomain = null;
$cookieSecure = false;
if ($Settings['jakebbsurl'] != null && $Settings['jakebbsurl'] != "localhost") {
    $URLsTest = parse_url($Settings['jakebbsurl']);
    $cookieDomain = $URLsTest['host'];
    if ($Settings['enable_https'] == "on") {
        if ($URLsTest['scheme'] == "https") {
            $cookieSecure = true;
        }
        if ($URLsTest['scheme'] != "https") {
            $cookieSecure = false;
        }
    }
}
@ini_set("default_charset", $Settings['charset']);
$File1Name = dirname($_SERVER['SCRIPT_NAME'])."/";
$File2Name = $_SERVER['SCRIPT_NAME'];
$File3Name = str_replace($File1Name, null, $File2Name);
if ($File3Name == "mysql.php" || $File3Name == "/mysql.php") {
    require($SettDir['inc'].'forbidden.php');
    exit();
}
//error_reporting(E_ERROR);
// Check if gzip is on and if user's browser can accept gzip pages
if ($_GET['act'] == "MkCaptcha" || $_GET['act'] == "Captcha") {
    $Settings['use_gzip'] = 'off';
}
if ($Settings['use_gzip'] == "on") {
    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
        $GZipEncode['Type'] = "gzip";
    } else {
        if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate")) {
            $GZipEncode['Type'] = "deflate";
        } else {
            $Settings['use_gzip'] = "off";
            $GZipEncode['Type'] = "none";
        }
    }
}
if ($Settings['use_gzip'] == "gzip") {
    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "gzip")) {
        $Settings['use_gzip'] = "on";
        $GZipEncode['Type'] = "gzip";
    } else {
        $Settings['use_gzip'] = "off";
    }
}
if ($Settings['use_gzip'] == "deflate") {
    if (strstr($_SERVER['HTTP_ACCEPT_ENCODING'], "deflate")) {
        $Settings['use_gzip'] = "on";
        $GZipEncode['Type'] = "deflate";
    } else {
        $Settings['use_gzip'] = "off";
    }
}
if ($Settings['clean_ob'] == "on") {
    /* Check for other output handlers/buffers are open
       and close and get the contents in an array */
    $numob = count(ob_list_handlers());
    $iob = 0;
    while ($iob < $numob) {
        $old_ob_var[$iob] = @ob_get_clean();
        ++$iob;
    }
} @ob_start();
if ($Settings['use_gzip'] == "on") {
    if ($GZipEncode['Type'] != "gzip") {
        if ($GZipEncode['Type'] != "deflate") {
            $GZipEncode['Type'] = "gzip";
        }
    }
    if ($GZipEncode['Type'] == "gzip") {
        @header("Content-Encoding: gzip");
    }
    if ($GZipEncode['Type'] == "deflate") {
        @header("Content-Encoding: deflate");
    }
}
/* if(eregi("msie",$browser) && !eregi("opera",$browser)){
@header('P3P: CP="NOI ADM DEV PSAi COM NAV OUR OTRo STP IND DEM"'); } */
// Some http stuff
$SQLStat = @ConnectMysql($Settings['sqlhost'], $Settings['sqluser'], $Settings['sqlpass'], $Settings['sqldb']);
$SQLCollate = "latin1_general_ci";
$SQLCharset = "latin1";
if ($Settings['charset'] == "ISO-8859-1") {
    $SQLCollate = "latin1_general_ci";
    $SQLCharset = "latin1";
}
if ($Settings['charset'] == "ISO-8859-15") {
    $SQLCollate = "latin1_general_ci";
    $SQLCharset = "latin1";
}
if ($Settings['charset'] == "UTF-8") {
    $SQLCollate = "utf8_unicode_ci";
    $SQLCharset = "utf8";
}
@mysql_set_charset($SQLCharset);
if ($SQLStat === false) {
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    @mysql_free_result($peresult);
    ob_clean();
    echo "Sorry could not connect to mysql database.\nContact the board admin about error. Error log below.";
    echo "\n".mysql_errno().": ".mysql_error();
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
$sqltable = $Settings['sqltable'];
function sqlsession_open($save_path, $session_name)
{
    global $sess_save_path;
    $sess_save_path = $save_path;
    return true;
}
function sqlsession_close()
{
    return true;
}
function sqlsession_read($id)
{
    global $sqltable;
    $data = "";
    $time = GMTimeStamp();
    $sqlr = query("SELECT `session_data` FROM `".$sqltable."sessions` WHERE `session_id` = '%s'", array($id,$time));
    $rs = exec_query($sqlr);
    $a = mysql_num_rows($rs);
    if ($a > 0) {
        $row = mysql_fetch_assoc($rs);
        $data = $row['session_data'];
    }
    return $data;
}
function sqlsession_write($id, $data)
{
    global $sqltable;
    $time = GMTimeStamp();
    $sqlw = query("REPLACE `".$sqltable."sessions` VALUES('$id','$data', $time)", array($id,$data,$time));
    $rs = exec_query($sqlw);
    return true;
}
function sqlsession_destroy($id)
{
    global $sqltable;
    $sqld = query("DELETE FROM `".$sqltable."sessions` WHERE `session_id` = '$id'", array($id));
    exec_query($sqld);
    return true;
}
function sqlsession_gc($maxlifetime)
{
    global $sqltable;
    $time = GMTimeStamp() - $maxlifetime;
    //$sqlg = query('DELETE FROM `'.$sqltable.'sessions` WHERE `expires` < UNIX_TIMESTAMP();', array(null));
    $sqlg = query('DELETE FROM `'.$sqltable.'sessions` WHERE `expires` < %i', array($time));
    exec_query($sqlg);
    return true;
}
@session_set_save_handler("sqlsession_open", "sqlsession_close", "sqlsession_read", "sqlsession_write", "sqlsession_destroy", "sqlsession_gc");
if ($cookieDomain == null) {
    @session_set_cookie_params(0, $cbasedir);
}
if ($cookieDomain != null) {
    if ($cookieSecure === true) {
        @session_set_cookie_params(0, $cbasedir, $cookieDomain, 1);
    }
    if ($cookieSecure === false) {
        @session_set_cookie_params(0, $cbasedir, $cookieDomain);
    }
}
@session_cache_limiter("private, no-cache, must-revalidate");
@header("Cache-Control: private, no-cache, must-revalidate");
@header("Pragma: private, no-cache, must-revalidate");
@header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
@session_name($Settings['sqltable']."sess");
@session_start();
//@header("Set-Cookie: PHPSESSID=" . session_id() . "; path=".$cbasedir);
@output_reset_rewrite_vars();
if ($_GET['act'] == "bsdl" || $_GET['act'] == "BSDL" || $_GET['act'] == "license" ||
    $_GET['act'] == "LICENSE" || $_GET['act'] == "License") {
    $_GET['act'] = "bsd";
}
if ($_GET['act'] == "bsd") {
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    require("LICENSE");
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    die();
}
if ($_GET['act'] == "README" || $_GET['act'] == "ReadME") {
    $_GET['act'] = "readme";
}
if ($_GET['act'] == "readme" || $_GET['act'] == "ReadMe") {
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    require("README");
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    die();
}
if ($_GET['act'] == "js" || $_GET['act'] == "javascript") {
    @header("Content-Script-Type: text/javascript");
    if (stristr($_SERVER["HTTP_ACCEPT"], "application/x-javascript")) {
        @header("Content-Type: application/x-javascript; charset=".$Settings['charset']);
    } else {
        if (stristr($_SERVER["HTTP_ACCEPT"], "application/javascript")) {
            @header("Content-Type: application/javascript; charset=".$Settings['charset']);
        } else {
            @header("Content-Type: text/javascript; charset=".$Settings['charset']);
        }
    }
    require($SettDir['inc'].'javascript.php');
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    die();
}
if ($Settings['use_captcha'] == "on") {
    if ($_GET['act'] == "MkCaptcha" || $_GET['act'] == "Captcha") {
        if ($Settings['captcha_clean'] == "on") {
            @ob_clean();
        }
        require($SettDir['inc']."captcha.php");
        $aFonts = array('inc/fonts/VeraBd.ttf', 'inc/fonts/VeraBI.ttf', 'inc/fonts/VeraIt.ttf', 'inc/fonts/Vera.ttf');
        $oPhpCaptcha = new PhpCaptcha($aFonts, 200, 60);
        $RNumSize = rand(7, 17);
        $i = 0;
        $RandNum = null;
        while ($i <= $RNumSize) {
            $RandNum = $RandNum.dechex(rand(1, 15));
            ++$i;
        }
        $RandNum = strtoupper($RandNum);
        $oPhpCaptcha->SetOwnerText("Fake Code: ".$RandNum);
        $oPhpCaptcha->UseColour(true);
        $oPhpCaptcha->Create();
        @session_write_close();
        die();
    }
}
if (!isset($_SESSION['CheckCookie'])) {
    if (isset($_COOKIE['SessPass']) && isset($_COOKIE['MemberName'])) {
        require($SettDir['inc'].'prelogin.php');
    }
}
require($SettDir['inc'].'groupsetup.php');
if ($Settings['board_offline'] == "on" && $GroupInfo['CanViewOffLine'] != "yes") {
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    @mysql_free_result($peresult);
    ob_clean();
    if (!isset($Settings['offline_text'])) {
        echo "Sorry the board is off line.\nIf you are a admin you can login by the admin cp.";
    }
    if (isset($Settings['offline_text'])) {
        echo $Settings['offline_text'];
    }
    //echo "\n".mysql_errno().": ".mysql_error();
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
$dayconv = array('second' => 1, 'minute' => 60, 'hour' => 3600, 'day' => 86400, 'week' => 604800, 'month' => 2630880, 'year' => 31570560, 'decade' => 15705600);
//Time Zone Set
if (!isset($_SESSION['UserTimeZone'])) {
    if (isset($Settings['DefaultTimeZone'])) {
        $_SESSION['UserTimeZone'] = $Settings['DefaultTimeZone'];
        if (!isset($Settings['DefaultTimeZone'])) {
            $_SESSION['UserTimeZone'] = SeverOffSet().":00";
        }
    }
}
$checktime = explode(":", $_SESSION['UserTimeZone']);
if (count($checktime) != 2) {
    if (!isset($checktime[0])) {
        $checktime[0] = "0";
    }
    if (!isset($checktime[1])) {
        $checktime[1] = "00";
    }
    $_SESSION['UserTimeZone'] = $checktime[0].":".$checktime[1];
}
if (!is_numeric($checktime[0])) {
    $checktime[0] = "0";
}
if ($checktime[0] > 12) {
    $checktime[0] = "12";
    $_SESSION['UserTimeZone'] = $checktime[0].":".$checktime[1];
}
if ($checktime[0] < -12) {
    $checktime[0] = "-12";
    $_SESSION['UserTimeZone'] = $checktime[0].":".$checktime[1];
}
if (!is_numeric($checktime[1])) {
    $checktime[1] = "00";
}
if ($checktime[1] > 59) {
    $checktime[1] = "59";
    $_SESSION['UserTimeZone'] = $checktime[0].":".$checktime[1];
}
if ($checktime[1] < 0) {
    $checktime[1] = "00";
    $_SESSION['UserTimeZone'] = $checktime[0].":".$checktime[1];
}
$checktimea = array("offset" => $_SESSION['UserTimeZone'], "hour" => $checktime[0], "minute" => $checktime[1]);
if (!isset($_SESSION['UserDST'])) {
    $_SESSION['UserDST'] = null;
}
if ($_SESSION['UserDST'] == null) {
    if ($Settings['DefaultDST'] == "off") {
        $_SESSION['UserDST'] = "off";
    }
    if ($Settings['DefaultDST'] == "on") {
        $_SESSION['UserDST'] = "on";
    }
}
// Guest Stuff
if (isset($_SESSION['MemberName']) ||
   isset($_COOKIE['MemberName'])) {
    $_SESSION['GuestName'] = null;
    $_COOKIE['GuestName'] = null;
}
if (!isset($_SESSION['MemberName']) && !isset($_COOKIE['MemberName'])) {
    if (!isset($_SESSION['GuestName']) && isset($_COOKIE['GuestName'])) {
        $_SESSION['GuestName'] = $_COOKIE['GuestName'];
    }
}
if (!isset($_SESSION['LastPostTime'])) {
    $_SESSION['LastPostTime'] = "0";
}
// Skin Stuff
if (!isset($_SESSION['Theme'])) {
    $_SESSION['Theme'] = null;
}
if (!isset($_GET['theme'])) {
    $_GET['theme'] = null;
}
if (!isset($_POST['theme'])) {
    $_POST['theme'] = null;
}
if (!isset($_GET['skin'])) {
    $_GET['skin'] = null;
}
if (!isset($_POST['skin'])) {
    $_POST['skin'] = null;
}
if (!isset($_GET['style'])) {
    $_GET['style'] = null;
}
if (!isset($_POST['style'])) {
    $_POST['style'] = null;
}
if (!isset($_GET['css'])) {
    $_GET['css'] = null;
}
if (!isset($_POST['css'])) {
    $_POST['css'] = null;
}
if ($_GET['theme'] == null) {
    if ($_POST['theme'] != null) {
        $_GET['theme'] = $_POST['theme'];
    }
    if ($_POST['skin'] != null) {
        $_GET['theme'] = $_POST['skin'];
    }
    if ($_POST['style'] != null) {
        $_GET['theme'] = $_POST['style'];
    }
    if ($_POST['css'] != null) {
        $_GET['theme'] = $_POST['css'];
    }
    if ($_GET['skin'] != null) {
        $_GET['theme'] = $_GET['skin'];
    }
    if ($_GET['style'] != null) {
        $_GET['theme'] = $_GET['style'];
    }
    if ($_GET['css'] != null) {
        $_GET['theme'] = $_GET['css'];
    }
}
if ($_GET['theme'] != null) {
    $_GET['theme'] = chack_themes($_GET['theme']);
    if ($_GET['theme'] == "../" || $_GET['theme'] == "./") {
        $_GET['theme'] = "JakeBBS";
        $_SESSION['Theme'] = "JakeBBS";
    }
    if (file_exists($SettDir['themes'].$_GET['theme']."/settings.php")) {
        if ($_SESSION['UserGroup'] != $Settings['GuestGroup']) {
            $NewDay = GMTimeStamp();
            $qnewskin = query("UPDATE `".$Settings['sqltable']."members` SET `UseTheme`='%s',`LastActive`='%s' WHERE `id`=%i", array($_GET['theme'],$NewDay,$_SESSION['UserID']));
            exec_query($qnewskin);
        }
        /* The file Theme Exists */
    } else {
        $_GET['theme'] = $Settings['DefaultTheme'];
        $_SESSION['Theme'] = $Settings['DefaultTheme'];
        /* The file Theme Dose Not Exists */
    }
}
if ($_GET['theme'] == null) {
    if ($_SESSION['Theme'] != null) {
        $OldTheme = $_SESSION['Theme'];
        $_SESSION['Theme'] = chack_themes($_SESSION['Theme']);
        if ($OldTheme != $_SESSION['Theme']) {
            $NewDay = GMTimeStamp();
            $qnewskin = query("UPDATE `".$Settings['sqltable']."members` SET `UseTheme`='%s',`LastActive`='%s' WHERE `id`=%i", array($_SESSION['Theme'],$NewDay,$_SESSION['UserID']));
            exec_query($qnewskin);
        }
        $_GET['theme'] = $_SESSION['Theme'];
    }
    if ($_SESSION['Theme'] == null) {
        $_SESSION['Theme'] = $Settings['DefaultTheme'];
        $_GET['theme'] = $Settings['DefaultTheme'];
    }
}
$PreSkin['skindir1'] = $_SESSION['Theme'];
$PreSkin['skindir2'] = $SettDir['themes'].$_SESSION['Theme'];
require($SettDir['themes'].$_GET['theme']."/settings.php");
$_SESSION['Theme'] = $_GET['theme'];
if (!isset($ThemeSet['TableStyle'])) {
    $ThemeSet['TableStyle'] = "table";
}
if (isset($ThemeSet['TableStyle'])) {
    if ($ThemeSet['TableStyle'] != "div" &&
        $ThemeSet['TableStyle'] != "table") {
        $ThemeSet['TableStyle'] = "table";
    }
}
if (!isset($_SESSION['DBName'])) {
    $_SESSION['DBName'] = null;
}
if ($_SESSION['DBName'] == null) {
    $_SESSION['DBName'] = $Settings['sqldb'];
}
if ($_SESSION['DBName'] != null) {
    if ($_SESSION['DBName'] != $Settings['sqldb']) {
        @redirect("location", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=logout", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
    }
}
?>
