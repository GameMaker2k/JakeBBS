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
    JakeBBS Installer made by JakeBBS Inc. - http://jakebbs.berlios.net/

    $FileInfo: mkconfig.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mkconfig.php" || $File3Name == "/mkconfig.php") {
    require('index.php');
    exit();
}
require_once('settings.php');
if (!isset($SetupDir['setup'])) {
    $SetupDir['setup'] = "setup/";
}
if (!isset($SetupDir['convert'])) {
    $SetupDir['convert'] = "setup/convert/";
}
$_POST['DatabaseHost'] = $Settings['sqlhost'];
$_POST['DatabaseUserName'] = $Settings['sqluser'];
$_POST['DatabasePassword'] = $Settings['sqlpass'];
$Settings['charset'] = $_POST['charset'];
?>
<tr class="TableRow3" style="text-align: center;">
<td class="TableColumn3" colspan="2">
<?php
$dayconv = array('second' => 1, 'minute' => 60, 'hour' => 3600, 'day' => 86400, 'week' => 604800, 'month' => 2630880, 'year' => 31570560, 'decade' => 15705600);
$_POST['tableprefix'] = strtolower($_POST['tableprefix']);
$_POST['tableprefix'] = preg_replace("/[^A-Za-z0-9_$]/", "", $_POST['tableprefix']);
if ($_POST['tableprefix'] == null || $_POST['tableprefix'] == "_") {
    $_POST['tableprefix'] = "jakebbs_";
}
if ($_POST['sessprefix'] == null || $_POST['sessprefix'] == "_") {
    $_POST['sessprefix'] = "jakebbs_";
}
$checkfile = "settings.php";
@chmod("settings.php", 0766);
@chmod("settingsbak.php", 0766);
if (!is_writable($checkfile)) {
    echo "<br />Settings is not writable.";
    @chmod("settings.php", 0766);
    $Error = "Yes";
    @chmod("settingsbak.php", 0766);
} else { /* settings.php is writable install JakeBBS. ^_^ */
}
@session_name($_POST['tableprefix']."sess");
$URLsTest = parse_url($_POST['BoardURL']);
@session_set_cookie_params(0, $this_dir, $URLsTest['host']);
@session_cache_limiter("private, must-revalidate");
@header("Cache-Control: private, must-revalidate"); // IE 6 Fix
@header("Pragma: private, must-revalidate");
@header("Date: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Last-Modified: ".gmdate("D, d M Y H:i:s")." GMT");
@header("Expires: ".gmdate("D, d M Y H:i:s")." GMT");
@session_start();
if (pre_strlen($_POST['AdminPasswords']) < "3") {
    $Error = "Yes";
    echo "<br />Your password is too small.";
}
if (pre_strlen($_POST['AdminUser']) < "3") {
    $Error = "Yes";
    echo "<br />Your user name is too small.";
}
if (pre_strlen($_POST['AdminUser']) < "3") {
    $Error = "Yes";
    echo "<br />Your user name is too small.";
}
if (pre_strlen($_POST['AdminEmail']) < "3") {
    $Error = "Yes";
    echo "<br />Your email name is too small.";
}
if (pre_strlen($_POST['AdminPasswords']) > "60") {
    $Error = "Yes";
    echo "<br />Your password is too big.";
}
if (pre_strlen($_POST['AdminUser']) > "30") {
    $Error = "Yes";
    echo "<br />Your user name is too big.";
}
if ($_POST['AdminPasswords'] != $_POST['ReaPassword']) {
    $Error = "Yes";
    echo "<br />Your passwords did not match.";
}
if ($_POST['HTMLType'] == "xhtml11") {
    $_POST['HTMLLevel'] = "Strict";
}
$_POST['BoardURL'] = addslashes($_POST['BoardURL']);
$YourDate = GMTimeStamp();
$YourEditDate = $YourDate + $dayconv['minute'];
$GSalt = salt_hmac();
$YourSalt = salt_hmac();
/* Fix The User Info for JakeBBS */
$_POST['NewBoardName'] = stripcslashes(htmlspecialchars($_POST['NewBoardName'], ENT_QUOTES, $Settings['charset']));
//$_POST['NewBoardName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['NewBoardName']);
$_POST['NewBoardName'] = @remove_spaces($_POST['NewBoardName']);
//$_POST['AdminPassword'] = stripcslashes(htmlspecialchars($_POST['AdminPassword'], ENT_QUOTES, $Settings['charset']));
//$_POST['AdminPassword'] = preg_replace("/\&amp;#(.*?);/is", "&#$1;", $_POST['AdminPassword']);
$_POST['AdminUser'] = stripcslashes(htmlspecialchars($_POST['AdminUser'], ENT_QUOTES, $Settings['charset']));
//$_POST['AdminUser'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['AdminUser']);
$_POST['AdminUser'] = @remove_spaces($_POST['AdminUser']);
$_POST['AdminEmail'] = @remove_spaces($_POST['AdminEmail']);
if (!function_exists('hash') && !function_exists('hash_algos')) {
    if ($_POST['usehashtype'] != "md5" &&
       $_POST['usehashtype'] != "sha1" &&
       $_POST['usehashtype'] != "sha256") {
        $_POST['usehashtype'] = "sha256";
    }
}
if (function_exists('hash') && function_exists('hash_algos')) {
    if (!in_array($_POST['usehashtype'], hash_algos())) {
        $_POST['usehashtype'] = "sha256";
    }
    if ($_POST['usehashtype'] != "md2" &&
       $_POST['usehashtype'] != "md4" &&
       $_POST['usehashtype'] != "md5" &&
       $_POST['usehashtype'] != "sha1" &&
       $_POST['usehashtype'] != "sha256" &&
       $_POST['usehashtype'] != "sha386" &&
       $_POST['usehashtype'] != "sha512") {
        $_POST['usehashtype'] = "sha256";
    }
}
if ($_POST['usehashtype'] == "md2") {
    $JakeBBSHashType = "JakeBBSH2";
}
if ($_POST['usehashtype'] == "md4") {
    $JakeBBSHashType = "JakeBBSH4";
}
if ($_POST['usehashtype'] == "md5") {
    $JakeBBSHashType = "JakeBBSH5";
}
if ($_POST['usehashtype'] == "sha1") {
    $JakeBBSHashType = "JakeBBSH";
}
if ($_POST['usehashtype'] == "sha256") {
    $JakeBBSHashType = "JakeBBSH256";
}
if ($_POST['usehashtype'] == "sha386") {
    $JakeBBSHashType = "JakeBBSH386";
}
if ($_POST['usehashtype'] == "sha512") {
    $JakeBBSHashType = "JakeBBSH512";
}
if ($_POST['AdminUser'] == "Guest") {
    $Error = "Yes";
    echo "<br />You can not use Guest as your name.";
}
/* We are done now with fixing the info. ^_^ */
$mydbtest = @ConnectMysql($_POST['DatabaseHost'], $_POST['DatabaseUserName'], $_POST['DatabasePassword'], $_POST['DatabaseName']);
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
if ($mydbtest !== true) {
    $Error = "Yes";
    echo "<br />".mysql_errno().": ".mysql_error()."\n";
}
if ($Error != "Yes") {
    $ServerUUID = uuid(false, true, false, $_POST['usehashtype'], null);
    if (!is_numeric($_POST['YourOffSet'])) {
        $_POST['YourOffSet'] = "0";
    }
    if ($_POST['YourOffSet'] > 12) {
        $_POST['YourOffSet'] = "12";
    }
    if ($_POST['YourOffSet'] < -12) {
        $_POST['YourOffSet'] = "-12";
    }
    if (!is_numeric($_POST['MinOffSet'])) {
        $_POST['MinOffSet'] = "00";
    }
    if ($_POST['MinOffSet'] > 59) {
        $_POST['MinOffSet'] = "59";
    }
    if ($_POST['MinOffSet'] < 0) {
        $_POST['MinOffSet'] = "00";
    }
    $YourOffSet = $_POST['YourOffSet'].":".$_POST['MinOffSet'];
    $AdminDST = $_POST['DST'];
    $MyDay = GMTimeGet("d", $YourOffSet, 0, $AdminDST);
    $MyMonth = GMTimeGet("m", $YourOffSet, 0, $AdminDST);
    $MyYear = GMTimeGet("Y", $YourOffSet, 0, $AdminDST);
    $MyYear10 = $MyYear + 10;
    $YourDateEnd = $YourDate + $dayconv['month'];
    $EventMonth = GMTimeChange("m", $YourDate, 0, 0, "off");
    $EventMonthEnd = GMTimeChange("m", $YourDateEnd, 0, 0, "off");
    $EventDay = GMTimeChange("d", $YourDate, 0, 0, "off");
    $EventDayEnd = GMTimeChange("d", $YourDateEnd, 0, 0, "off");
    $EventYear = GMTimeChange("Y", $YourDate, 0, 0, "off");
    $EventYearEnd = GMTimeChange("Y", $YourDateEnd, 0, 0, "off");
    $KarmaBoostDay = $EventMonth.$EventDay;
    $NewPassword = b64e_hmac($_POST['AdminPasswords'], $YourDate, $YourSalt, $_POST['usehashtype']);
    //$Name = stripcslashes(htmlspecialchars($AdminUser, ENT_QUOTES, $Settings['charset']));
    //$YourWebsite = "http://".$_SERVER['HTTP_HOST'].$this_dir."index.php?act=view";
    $YourWebsite = $_POST['WebURL'];
    $UserIP = $_SERVER['REMOTE_ADDR'];
    $PostCount = 2;
    $Email = "admin@".$_SERVER['HTTP_HOST'];
    $AdminTime = $_POST['YourOffSet'].":".$_POST['MinOffSet'];
    $GEmail = "guest@".$_SERVER['HTTP_HOST'];
    $grand = rand(6, 16);
    $i = 0;
    $gpass = "";
    while ($i < $grand) {
        $csrand = rand(1, 3);
        if ($csrand != 1 && $csrand != 2 && $csrand != 3) {
            $csrand = 1;
        }
        if ($csrand == 1) {
            $gpass .= chr(rand(48, 57));
        }
        if ($csrand == 2) {
            $gpass .= chr(rand(65, 90));
        }
        if ($csrand == 3) {
            $gpass .= chr(rand(97, 122));
        }
        ++$i;
    } $GuestPassword = b64e_hmac($gpass, $YourDate, $GSalt, $_POST['usehashtype']);
    $url_this_dir = "http://".$_SERVER['HTTP_HOST'].$this_dir."index.php?act=view";
    $YourIP = $_SERVER['REMOTE_ADDR'];
    require($SetupDir['setup'].'mktable.php');
    $CHMOD = $_SERVER['PHP_SELF'];
    $JakeBBSRDate = $SVNDay[0]."/".$SVNDay[1]."/".$SVNDay[2];
    $JakeBBSRSVN = $VER2[2]." ".$SubVerN;
    $LastUpdateS = "Last Update: ".$JakeBBSRDate." ".$JakeBBSRSVN;
    $pretext = "<?php\n/*\n    This program is free software; you can redistribute it and/or modify\n    it under the terms of the GNU General Public License as published by\n    the Free Software Foundation; either version 2 of the License, or\n    (at your option) any later version.\n\n    This program is distributed in the hope that it will be useful,\n    but WITHOUT ANY WARRANTY; without even the implied warranty of\n    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the\n    Revised BSD License for more details.\n\n    Copyright 2004-".$SVNDay[2]." JakeBBS - http://google.com/search?q=JakeBBS+Inc.&btnI=I%27m+Feeling+Lucky\n    Copyright 2004-".$SVNDay[2]." JakeBBS Inc. - http://google.com/search?q=JakeBBS+Inc.&btnI=I%27m+Feeling+Lucky\n    JakeBBS Installer made by JakeBBS Inc. - http://jakebbs.berlios.net/\n\n    \$FileInfo: settings.php & settingsbak.php - ".$LastUpdateS." - Author: Jake \$\n*/\n";
    $pretext2 = array("/*   Board Setting Section Begins   */\n\$Settings = array();","/*   Board Setting Section Ends  \n     Board Info Section Begins   */\n\$SettInfo = array();","/*   Board Setting Section Ends   \n     Board Dir Section Begins   */\n\$SettDir = array();","/*   Board Dir Section Ends   */");
    $settcheck = "\$File3Name = basename(\$_SERVER['SCRIPT_NAME']);\nif (\$File3Name==\"settings.php\"||\$File3Name==\"/settings.php\"||\n    \$File3Name==\"settingsbak.php\"||\$File3Name==\"/settingsbak.php\") {\n    @header('Location: index.php');\n    exit(); }\n";
    $BoardSettings = $pretext2[0]."\n\$Settings['sqlhost'] = '".$_POST['DatabaseHost']."';\n\$Settings['sqldb'] = '".$_POST['DatabaseName']."';\n\$Settings['sqltable'] = '".$_POST['tableprefix']."';\n\$Settings['sqluser'] = '".$_POST['DatabaseUserName']."';\n\$Settings['sqlpass'] = '".$_POST['DatabasePassword']."';\n\$Settings['board_name'] = '".$_POST['NewBoardName']."';\n\$Settings['jakebbsdir'] = '".$jakebbsdir."';\n\$Settings['jakebbsurl'] = '".$_POST['BoardURL']."';\n\$Settings['enable_https'] = 'off';\n\$Settings['weburl'] = '".$_POST['WebURL']."';\n\$Settings['use_gzip'] = '".$_POST['GZip']."';\n\$Settings['html_type'] = '".$_POST['HTMLType']."';\n\$Settings['html_level'] = '".$_POST['HTMLLevel']."';\n\$Settings['output_type'] = '".$_POST['OutPutType']."';\n\$Settings['GuestGroup'] = 'Guest';\n\$Settings['MemberGroup'] = 'Member';\n\$Settings['ValidateGroup'] = 'Validate';\n\$Settings['AdminValidate'] = 'off';\n\$Settings['TestReferer'] = '".$_POST['TestReferer']."';\n\$Settings['DefaultTheme'] = 'JakeBBS';\n\$Settings['DefaultTimeZone'] = '".$AdminTime."';\n\$Settings['DefaultDST'] = '".$AdminDST."';\n\$Settings['use_hashtype'] = '".$_POST['usehashtype']."';\n\$Settings['charset'] = '".$_POST['charset']."';\n\$Settings['add_power_by'] = 'off';\n\$Settings['send_pagesize'] = 'off';\n\$Settings['max_posts'] = '10';\n\$Settings['max_topics'] = '10';\n\$Settings['max_memlist'] = '10';\n\$Settings['max_pmlist'] = '10';\n\$Settings['hot_topic_num'] = '15';\n\$Settings['qstr'] = '&';\n\$Settings['qsep'] = '=';\n\$Settings['file_ext'] = '.php';\n\$Settings['rss_ext'] = '.php';\n\$Settings['js_ext'] = '.js';\n\$Settings['showverinfo'] = 'on';\n\$Settings['enable_rss'] = 'on';\n\$Settings['enable_search'] = 'on';\n\$Settings['sessionid_in_urls'] = 'off';\n\$Settings['fixpathinfo'] = 'off';\n\$Settings['fixbasedir'] = 'off';\n\$Settings['fixcookiedir'] = 'off';\n\$Settings['enable_pathinfo'] = 'off';\n\$Settings['rssurl'] = 'off';\n\$Settings['board_offline'] = 'off';\n\$Settings['BoardUUID'] = '".$ServerUUID."';\n\$Settings['KarmaBoostDays'] = '".$KarmaBoostDay."';\n\$Settings['KBoostPercent'] = '6|10';\n".$pretext2[1]."\n\$SettInfo['board_name'] = '".$_POST['NewBoardName']."';\n\$SettInfo['Author'] = '".$_POST['AdminUser']."';\n\$SettInfo['Keywords'] = '".$_POST['NewBoardName'].",".$_POST['AdminUser']."';\n\$SettInfo['Description'] = '".$_POST['NewBoardName'].",".$_POST['AdminUser']."';\n".$pretext2[2]."\n\$SettDir['maindir'] = '".$jakebbsdir."';\n\$SettDir['inc'] = 'inc/';\n\$SettDir['misc'] = 'inc/misc/';\n\$SettDir['admin'] = 'inc/admin/';\n\$SettDir['mod'] = 'inc/mod/';\n\$SettDir['themes'] = 'themes/';\n".$pretext2[3]."\n?>";
    $BoardSettingsBak = $pretext.$settcheck.$BoardSettings;
    $BoardSettings = $pretext.$settcheck.$BoardSettings;
    $fp = fopen("settings.php", "w+");
    fwrite($fp, $BoardSettings);
    fclose($fp);
    //	@cp("settings.php","settingsbak.php");
    $fp = fopen("settingsbak.php", "w+");
    fwrite($fp, $BoardSettingsBak);
    fclose($fp);
    if ($_POST['storecookie'] == "true") {
        @setcookie("MemberName", $_POST['AdminUser'], time() + (7 * 86400), $this_dir, $URLsTest['host']);
        @setcookie("UserID", 1, time() + (7 * 86400), $this_dir, $URLsTest['host']);
        @setcookie("SessPass", $NewPassword, time() + (7 * 86400), $this_dir, $URLsTest['host']);
    }
    @mysql_close();
    $chdel = true;
    if ($Error != "Yes") {
        if ($_POST['unlink'] == "true") {
            $chdel1 = @unlink($SetupDir['setup'].'presetup.php');
            $chdel2 = @unlink($SetupDir['setup'].'setup.php');
            $chdel3 = @unlink($SetupDir['setup'].'mkconfig.php');
            $chdel4 = @unlink($SetupDir['setup'].'mktable.php');
            $chdel5 = @unlink($SetupDir['setup'].'index.php');
            $chdel6 = @unlink($SetupDir['setup'].'license.php');
            $chdel7 = @unlink($SetupDir['setup'].'preinstall.php');
            $chdel8 = @unlink($SetupDir['convert'].'index.php');
            if ($ConvertInfo['ConvertFile'] != null) {
                $chdel0 = @unlink($ConvertInfo['ConvertFile']);
            }
            $chdel9 = @unlink($SetupDir['convert'].'info.php');
            $chdel10 = @rmdir($SetupDir['convert']);
            $chdel11 = @rmdir('setup');
            $chdel12 = @unlink('install.php');
        }
    }
    if ($chdel1 === false || $chdel2 === false || $chdel3 === false || $chdel4 === false) {
        $chdel = false;
    }
    if ($chdel5 === false || $chdel6 === false || $chdel7 === false || $chdel8 === false) {
        $chdel = false;
    }
    if ($chdel9 === false || $chdel10 === false || $chdel11 === false || $chdel12 === false) {
        $chdel = false;
    }
    if ($ConvertInfo['ConvertFile'] != null) {
        if ($chdel0 === false) {
            $chdel = false;
        }
    }
    ?><span class="TableMessage">
<br />Install Finish <a href="index.php?act=view">Click here</a> to goto board. ^_^</span>
<?php if ($chdel === false) { ?><span class="TableMessage">
<br />Error: Cound not delete installer. Read readme.txt for more info.</span>
<?php } ?><br /><br />
</td>
</tr>
<?php } ?>
