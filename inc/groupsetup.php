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

    $FileInfo: groupsetup.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "groupsetup.php" || $File3Name == "/groupsetup.php") {
    require('index.php');
    exit();
}
//Set members temp location
$_SESSION['ViewingPage'] = url_maker(null, "no+ext", "act=view", "&", "=", $prexqstr['index'], $exqstr['index']);
if ($Settings['file_ext'] != "no+ext" && $Settings['file_ext'] != "no ext") {
    $_SESSION['ViewingFile'] = $exfile['index'].$Settings['file_ext'];
}
if ($Settings['file_ext'] == "no+ext" || $Settings['file_ext'] == "no ext") {
    $_SESSION['ViewingFile'] = $exfile['index'];
}
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Board index";
/*$ggidquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `name`='%s' LIMIT 1", array($Settings['GuestGroup']));
$ggidresult=exec_query($ggidquery);
$Settings['GuestGroupID']=mysql_result($ggidresult,0,"id");*/
// Check to make sure MemberInfo is right
$MyPostCountChk = null;
$MyKarmaCount = null;
if (!isset($_SESSION['UserID'])) {
    $_SESSION['UserID'] = 0;
}
if ($_SESSION['UserID'] != 0 && $_SESSION['UserID'] != null) {
    $BanError = null;
    $kgbquerychkusr = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `Name`='%s' AND `Password`='%s' AND `id`=%i LIMIT 1", array($_SESSION['MemberName'],$_SESSION['UserPass'],$_SESSION['UserID']));
    $resultchkusr = exec_query($kgbquerychkusr);
    $numchkusr = mysql_num_rows($resultchkusr);
    if ($numchkusr == 1) {
        $ChkUsrID = mysql_result($resultchkusr, 0, "id");
        $ChkUsrName = mysql_result($resultchkusr, 0, "Name");
        $ChkUsrGroup = mysql_result($resultchkusr, 0, "GroupID");
        $ChkUsrGroupID = $ChkUsrGroup;
        $ChkUsrPass = mysql_result($resultchkusr, 0, "Password");
        $ChkUsrTimeZone = mysql_result($resultchkusr, 0, "TimeZone");
        $ChkUsrTheme = mysql_result($resultchkusr, 0, "UseTheme");
        $ChkUsrLastPostTime = mysql_result($resultchkusr, 0, "LastPostTime");
        $MyPostCountChk = mysql_result($resultchkusr, 0, "PostCount");
        $MyKarmaCount = mysql_result($resultchkusr, 0, "Karma");
        $MyKarmaUpdate = mysql_result($resultchkusr, 0, "KarmaUpdate");
        $MyRepliesPerPage = mysql_result($resultchkusr, 0, "RepliesPerPage");
        $Settings['max_posts'] = $MyRepliesPerPage;
        $MyTopicsPerPage = mysql_result($resultchkusr, 0, "TopicsPerPage");
        $Settings['max_topics'] = $MyTopicsPerPage;
        $MyMessagesPerPage = mysql_result($resultchkusr, 0, "MessagesPerPage");
        $Settings['max_memlist'] = $MyMessagesPerPage;
        $Settings['max_pmlist'] = $MyMessagesPerPage;
        $ChkUsrDST = mysql_result($resultchkusr, 0, "DST");
        $svrquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($ChkUsrGroup));
        $svrgresultkgb = exec_query($svrquery);
        $ChkUsrGroup = mysql_result($svrgresultkgb, 0, "Name");
        $ChkUsrBanTime = mysql_result($resultchkusr, 0, "BanTime");
        $ChkUsrGMTime = GMTimeStamp();
        if ($ChkUsrBanTime != 0 && $ChkUsrBanTime != null) {
            if ($ChkUsrBanTime >= $ChkUsrGMTime) {
                $BanError = "yes";
            }
        }
        if ($BanError != "yes") {
            $_SESSION['Theme'] = $ChkUsrTheme;
            $_SESSION['MemberName'] = $ChkUsrName;
            $_SESSION['UserID'] = $ChkUsrID;
            $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
            $_SESSION['UserTimeZone'] = $ChkUsrTimeZone;
            $_SESSION['UserGroup'] = $ChkUsrGroup;
            $_SESSION['UserGroupID'] = $ChkUsrGroupID;
            $_SESSION['UserDST'] = $ChkUsrDST;
            $_SESSION['UserPass'] = $ChkUsrPass;
            $_SESSION['LastPostTime'] = $ChkUsrLastPostTime;
        }
    }
    if ($numchkusr <= 0 || $numchkusr > 1 || $BanError == "yes") {
        @session_unset();
        if ($cookieDomain == null) {
            @setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir);
            @setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir);
            @setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir);
            @setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir);
        }
        if ($cookieDomain != null) {
            if ($cookieSecure === true) {
                @setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
                @setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
                @setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
                @setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain, 1);
            }
            if ($cookieSecure === false) {
                @setcookie("MemberName", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
                @setcookie("UserID", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
                @setcookie("SessPass", null, GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
                @setcookie(session_name(), "", GMTimeStamp() - 3600, $cbasedir, $cookieDomain);
            }
        }
        unset($_COOKIE[session_name()]);
        $_SESSION = array();
        @session_unset();
        @session_destroy();
        @redirect("location", $basedir.url_maker($exfile['member'], $Settings['file_ext'], "act=login", $Settings['qstr'], $Settings['qsep'], $prexqstr['member'], $exqstr['member'], false));
        @mysql_free_result($numchkusr);
        @mysql_free_result($svrgresultkgb);
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @session_write_close();
        die();
    }
    @mysql_free_result($numchkusr);
    @mysql_free_result($svrgresultkgb);
}
if ($_SESSION['UserID'] == 0 || $_SESSION['UserID'] == null) {
    $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['MemberName'] = null;
    $_SESSION['UserGroup'] = $Settings['GuestGroup'];
    $gidquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `name`='%s' LIMIT 1", array($Settings['GuestGroup']));
    $gidresult = exec_query($gidquery);
    $_SESSION['UserGroupID'] = mysql_result($gidresult, 0, "id");
    @mysql_free_result($gidresult);
}
if ($_SESSION['MemberName'] == null) {
    $_SESSION['UserID'] = "0";
    $_SESSION['UserIP'] = $_SERVER['REMOTE_ADDR'];
    $_SESSION['UserGroup'] = $Settings['GuestGroup'];
    $gidquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `name`='%s' LIMIT 1", array($Settings['GuestGroup']));
    $gidresult = exec_query($gidquery);
    $_SESSION['UserGroupID'] = mysql_result($gidresult, 0, "id");
    @mysql_free_result($gidresult);
}
// Member Group Setup
if (!isset($_SESSION['UserGroup'])) {
    $_SESSION['UserGroup'] = null;
}
if ($_SESSION['UserGroup'] == null) {
    $_SESSION['UserGroup'] = $Settings['GuestGroup'];
} $GruError = null;
$gruquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `Name`='%s' LIMIT 1", array($_SESSION['UserGroup']));
$gruresult = exec_query($gruquery);
$grunum = mysql_num_rows($gruresult);
if ($grunum <= 0) {
    $GruError = true;
    @mysql_free_result($gruresult);
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    ob_clean();
    echo "Sorry could not find group data in database.\nContact the board admin about error.";
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
if ($grunum >= 1) {
    $GroupInfo['ID'] = mysql_result($gruresult, 0, "id");
    if (!is_numeric($GroupInfo['ID'])) {
        $GruError = true;
    }
    $GroupInfo['Name'] = mysql_result($gruresult, 0, "Name");
    $GroupInfo['PermissionID'] = mysql_result($gruresult, 0, "PermissionID");
    if (!is_numeric($GroupInfo['PermissionID'])) {
        $GruError = true;
    }
    $GroupInfo['NamePrefix'] = mysql_result($gruresult, 0, "NamePrefix");
    $GroupInfo['NameSuffix'] = mysql_result($gruresult, 0, "NameSuffix");
    $GroupInfo['CanViewBoard'] = mysql_result($gruresult, 0, "CanViewBoard");
    if ($GroupInfo['CanViewBoard'] != "yes" && $GroupInfo['CanViewBoard'] != "no") {
        $GruError = true;
    }
    $GroupInfo['CanViewOffLine'] = mysql_result($gruresult, 0, "CanViewOffLine");
    if ($GroupInfo['CanViewOffLine'] != "yes" && $GroupInfo['CanViewOffLine'] != "no") {
        $GruError = true;
    }
    $GroupInfo['FloodControl'] = mysql_result($gruresult, 0, "FloodControl");
    if (!is_numeric($GroupInfo['FloodControl'])) {
        $GroupInfo['FloodControl'] = 30;
    }
    $GroupInfo['SearchFlood'] = mysql_result($gruresult, 0, "SearchFlood");
    if (!is_numeric($GroupInfo['SearchFlood'])) {
        $GroupInfo['SearchFlood'] = 30;
    }
    $GroupInfo['CanEditProfile'] = mysql_result($gruresult, 0, "CanEditProfile");
    if ($GroupInfo['CanEditProfile'] != "yes" && $GroupInfo['CanEditProfile'] != "no") {
        $GruError = true;
    }
    $GroupInfo['CanAddEvents'] = mysql_result($gruresult, 0, "CanAddEvents");
    if ($GroupInfo['CanAddEvents'] != "yes" && $GroupInfo['CanAddEvents'] != "no") {
        $GruError = true;
    }
    $GroupInfo['CanPM'] = mysql_result($gruresult, 0, "CanPM");
    if ($GroupInfo['CanPM'] != "yes" && $GroupInfo['CanPM'] != "no") {
        $GruError = true;
    }
    $GroupInfo['CanSearch'] = mysql_result($gruresult, 0, "CanSearch");
    if ($GroupInfo['CanSearch'] != "yes" && $GroupInfo['CanSearch'] != "no") {
        $GruError = true;
    }
    $GroupInfo['PromoteTo'] = mysql_result($gruresult, 0, "PromoteTo");
    $GroupInfo['PromotePosts'] = mysql_result($gruresult, 0, "PromotePosts");
    if (!is_numeric($GroupInfo['PromotePosts'])) {
        $GroupInfo['PromotePosts'] = 0;
        $GroupInfo['PromoteTo'] = 0;
    }
    $GroupInfo['PromoteKarma'] = mysql_result($gruresult, 0, "PromoteKarma");
    if (!is_numeric($GroupInfo['PromoteKarma'])) {
        $GroupInfo['PromoteKarma'] = 0;
        $GroupInfo['PromoteTo'] = 0;
    }
    if (!isset($Settings['KarmaBoostDays'])) {
        $Settings['KarmaBoostDays'] = null;
    }
    if (!isset($Settings['KBoostPercent'])) {
        $Settings['KBoostPercent'] = "6|10";
    }
    //Update karma and group upgrade on post count or karma count.
    if ($_SESSION['UserID'] != 0) {
        $BoostTotal = null;
        $NewKarmaUpdate = GMTimeGet("Ymd", $_SESSION['UserTimeZone'], 0, $_SESSION['UserDST']);
        $ThisYearUpdate = GMTimeGet("Y", $_SESSION['UserTimeZone'], 0, $_SESSION['UserDST']);
        if ($MyKarmaUpdate < $NewKarmaUpdate && $MyPostCountChk > 0) {
            $KarmaBoostDay = $Settings['KarmaBoostDays'];
            $KBoostPercent = explode("|", $Settings['KBoostPercent']);
            if (count($KBoostPercent) < 1) {
                $KBoostPercent[0] = rand(1, 4);
            }
            if (!is_numeric($KBoostPercent[0])) {
                $KBoostPercent[0] = 6;
            }
            if (count($KBoostPercent) == 1) {
                $KBoostPercent[1] = $KBoostPercent[0] + rand(3, 6);
            }
            if (!is_numeric($KBoostPercent[1])) {
                $KBoostPercent[0] = 10;
            }
            $KBoostPercent = rand($KBoostPercent[0], $KBoostPercent[1]);
            if ($ThisYearUpdate.$KarmaBoostDay == $NewKarmaUpdate &&
            is_numeric($KarmaBoostDay)) {
                $KBoostPercent = $KBoostPercent / 100;
                $BoostTotal = $MyKarmaCount * $KBoostPercent;
                $BoostTotal = round($BoostTotal, 0);
            }
            if ($BoostTotal != null) {
                $MyKarmaCount = $MyKarmaCount + $BoostTotal;
            }
            if ($BoostTotal == null) {
                $MyKarmaCount = $MyKarmaCount + 1;
            }
            $querykarmaup = query("UPDATE `".$Settings['sqltable']."members` SET `Karma`=%i,`KarmaUpdate`=%i WHERE `id`=%i", array($MyKarmaCount,$NewKarmaUpdate,$_SESSION['UserID']));
            exec_query($querykarmaup);
        }
        if ($GroupInfo['PromoteTo'] != 0 && $MyPostCountChk >= $GroupInfo['PromotePosts']) {
            $sql_group_check = exec_query(query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($GroupInfo['PromoteTo'])));
            $group_check = mysql_num_rows($sql_group_check);
            @mysql_free_result($sql_group_check);
            if ($group_check > 0) {
                $queryupgrade = query("UPDATE `".$Settings['sqltable']."members` SET `GroupID`=%i WHERE `id`=%i", array($GroupInfo['PromoteTo'],$_SESSION['UserID']));
                exec_query($queryupgrade);
            }
        }
        if ($GroupInfo['PromotePosts'] == 0 && $GroupInfo['PromoteTo'] != 0 && $MyKarmaCount >= $GroupInfo['PromoteKarma']) {
            $sql_group_check = exec_query(query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($GroupInfo['PromoteTo'])));
            $group_check = mysql_num_rows($sql_group_check);
            @mysql_free_result($sql_group_check);
            if ($group_check > 0) {
                $queryupgrade = query("UPDATE `".$Settings['sqltable']."members` SET `GroupID`=%i WHERE `id`=%i", array($GroupInfo['PromoteTo'],$_SESSION['UserID']));
                exec_query($queryupgrade);
            }
        }
    }
    $GroupInfo['HasModCP'] = mysql_result($gruresult, 0, "HasModCP");
    if ($GroupInfo['HasModCP'] != "yes" && $GroupInfo['HasModCP'] != "no") {
        $GroupInfo['HasModCP'] = "no";
    }
    $GroupInfo['HasAdminCP'] = mysql_result($gruresult, 0, "HasAdminCP");
    if ($GroupInfo['HasAdminCP'] != "yes" && $GroupInfo['HasAdminCP'] != "no") {
        $GroupInfo['HasAdminCP'] = "no";
    }
    $GroupInfo['ViewDBInfo'] = mysql_result($gruresult, 0, "ViewDBInfo");
    if ($GroupInfo['ViewDBInfo'] != "yes" && $GroupInfo['ViewDBInfo'] != "no") {
        $GroupInfo['ViewDBInfo'] = "no";
    }
    if ($GruError == true) {
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        @mysql_free_result($gruresult);
        ob_clean();
        echo "Sorry could not load all group data in database.\nContact the board admin about error.";
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @session_write_close();
        die();
    }
}
@mysql_free_result($gruresult);
if ($GroupInfo['CanViewBoard'] == "no") {
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    ob_clean();
    echo "Sorry you can not view the board.";
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
// Member Group Permissions Setup
$perquery = query("SELECT * FROM `".$Settings['sqltable']."permissions` WHERE `PermissionID`=%i ORDER BY `ForumID` ASC", array($GroupInfo['PermissionID']));
$peresult = exec_query($perquery);
$pernum = mysql_num_rows($peresult);
$peri = 0;
$PerError = null;
if ($pernum <= 0) {
    $PerError = true;
    @mysql_free_result($peresult);
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    ob_clean();
    echo "Sorry could not find permission data in database.\nContact the board admin about error.";
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
if ($pernum >= 1) {
    while ($peri < $pernum) {
        $PerForumID = mysql_result($peresult, $peri, "ForumID");
        if (!is_numeric($PerForumID)) {
            $PerError = true;
        }
        $PermissionInfo['ID'][$PerForumID] = mysql_result($peresult, $peri, "ID");
        if (!is_numeric($PermissionInfo['ID'][$PerForumID])) {
            $PerError = true;
        }
        $PermissionInfo['PermissionID'][$PerForumID] = mysql_result($peresult, $peri, "PermissionID");
        if (!is_numeric($PermissionInfo['PermissionID'][$PerForumID])) {
            $PerError = true;
        }
        $PermissionInfo['Name'][$PerForumID] = mysql_result($peresult, $peri, "Name");
        $PermissionInfo['ForumID'][$PerForumID] = mysql_result($peresult, $peri, "ForumID");
        if (!is_numeric($PermissionInfo['ForumID'][$PerForumID])) {
            $PerError = true;
        }
        $PermissionInfo['CanViewForum'][$PerForumID] = mysql_result($peresult, $peri, "CanViewForum");
        if ($PermissionInfo['CanViewForum'][$PerForumID] != "yes" && $PermissionInfo['CanViewForum'][$PerForumID] != "no") {
            $PerError = true;
        }
        $PermissionInfo['CanMakeTopics'][$PerForumID] = mysql_result($peresult, $peri, "CanMakeTopics");
        if ($PermissionInfo['CanMakeTopics'][$PerForumID] != "yes" && $PermissionInfo['CanMakeTopics'][$PerForumID] != "no") {
            $PerError = true;
        }
        $PermissionInfo['CanMakeReplys'][$PerForumID] = mysql_result($peresult, $peri, "CanMakeReplys");
        if ($PermissionInfo['CanMakeReplys'][$PerForumID] != "yes" && $PermissionInfo['CanMakeReplys'][$PerForumID] != "no") {
            $PerError = true;
        }
        $PermissionInfo['CanMakeReplysClose'][$PerForumID] = mysql_result($peresult, $peri, "CanMakeReplysCT");
        if ($PermissionInfo['CanMakeReplysClose'][$PerForumID] != "yes" && $PermissionInfo['CanMakeReplysClose'][$PerForumID] != "no") {
            $PerError = true;
        }
        $PermissionInfo['CanEditTopics'][$PerForumID] = mysql_result($peresult, $peri, "CanEditTopics");
        if ($PermissionInfo['CanEditTopics'][$PerForumID] != "yes" && $PermissionInfo['CanEditTopics'][$PerForumID] != "no") {
            $PermissionInfo['CanEditTopics'][$PerForumID] = "no";
        }
        $PermissionInfo['CanEditTopicsClose'][$PerForumID] = mysql_result($peresult, $peri, "CanEditTopicsCT");
        if ($PermissionInfo['CanEditTopicsClose'][$PerForumID] != "yes" && $PermissionInfo['CanEditTopicsClose'][$PerForumID] != "no") {
            $PermissionInfo['CanEditTopicsClose'][$PerForumID] = "no";
        }
        $PermissionInfo['CanEditReplys'][$PerForumID] = mysql_result($peresult, $peri, "CanEditReplys");
        if ($PermissionInfo['CanEditReplys'][$PerForumID] != "yes" && $PermissionInfo['CanEditReplys'][$PerForumID] != "no") {
            $PermissionInfo['CanEditReplys'][$PerForumID] = "no";
        }
        $PermissionInfo['CanEditReplysClose'][$PerForumID] = mysql_result($peresult, $peri, "CanEditReplysCT");
        if ($PermissionInfo['CanEditReplysClose'][$PerForumID] != "yes" && $PermissionInfo['CanEditReplysClose'][$PerForumID] != "no") {
            $PermissionInfo['CanEditReplysClose'][$PerForumID] = "no";
        }
        $PermissionInfo['CanDeleteTopics'][$PerForumID] = mysql_result($peresult, $peri, "CanDeleteTopics");
        if ($PermissionInfo['CanDeleteTopics'][$PerForumID] != "yes" && $PermissionInfo['CanDeleteTopics'][$PerForumID] != "no") {
            $PermissionInfo['CanDeleteTopics'][$PerForumID] = "no";
        }
        $PermissionInfo['CanDeleteTopicsClose'][$PerForumID] = mysql_result($peresult, $peri, "CanDeleteTopicsCT");
        if ($PermissionInfo['CanDeleteTopicsClose'][$PerForumID] != "yes" && $PermissionInfo['CanDeleteTopicsClose'][$PerForumID] != "no") {
            $PermissionInfo['CanDeleteTopicsClose'][$PerForumID] = "no";
        }
        $PermissionInfo['CanDeleteReplys'][$PerForumID] = mysql_result($peresult, $peri, "CanDeleteReplys");
        if ($PermissionInfo['CanDeleteReplys'][$PerForumID] != "yes" && $PermissionInfo['CanDeleteReplys'][$PerForumID] != "no") {
            $PermissionInfo['CanDeleteReplys'][$PerForumID] = "no";
        }
        $PermissionInfo['CanDeleteReplysClose'][$PerForumID] = mysql_result($peresult, $peri, "CanDeleteReplysCT");
        if ($PermissionInfo['CanDeleteReplysClose'][$PerForumID] != "yes" && $PermissionInfo['CanDeleteReplysClose'][$PerForumID] != "no") {
            $PermissionInfo['CanDeleteReplysClose'][$PerForumID] = "no";
        }
        $PermissionInfo['CanCloseTopics'][$PerForumID] = mysql_result($peresult, $peri, "CanCloseTopics");
        if ($PermissionInfo['CanCloseTopics'][$PerForumID] != "yes" && $PermissionInfo['CanCloseTopics'][$PerForumID] != "no") {
            $PermissionInfo['CanCloseTopics'][$PerForumID] = "no";
        }
        $PermissionInfo['CanPinTopics'][$PerForumID] = mysql_result($peresult, $peri, "CanPinTopics");
        if ($PermissionInfo['CanPinTopics'][$PerForumID] != "yes" && $PermissionInfo['CanPinTopics'][$PerForumID] != "no") {
            $PermissionInfo['CanPinTopics'][$PerForumID] = "no";
        }
        $PermissionInfo['CanDohtml'][$PerForumID] = mysql_result($peresult, $peri, "CanDohtml");
        if ($PermissionInfo['CanDohtml'][$PerForumID] != "yes" && $PermissionInfo['CanDohtml'][$PerForumID] != "no") {
            $PermissionInfo['CanDohtml'][$PerForumID] = "no";
        }
        $PermissionInfo['CanUseBBags'][$PerForumID] = mysql_result($peresult, $peri, "CanUseBBags");
        if ($PermissionInfo['CanUseBBags'][$PerForumID] != "yes" && $PermissionInfo['CanUseBBags'][$PerForumID] != "no") {
            $PermissionInfo['CanUseBBags'][$PerForumID] = "no";
        }
        $PermissionInfo['CanModForum'][$PerForumID] = mysql_result($peresult, $peri, "CanModForum");
        if ($PermissionInfo['CanModForum'][$PerForumID] != "yes" && $PermissionInfo['CanModForum'][$PerForumID] != "no") {
            $PermissionInfo['CanModForum'][$PerForumID] = "no";
        }
        if ($PerError === true) {
            $peri = $pernum;
        }
        ++$peri;
    } if ($PerError === true) {
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        @mysql_free_result($peresult);
        ob_clean();
        echo "Sorry could not load all permission data in database.\nContact the board admin about error.";
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @session_write_close();
        die();
    }
}
@mysql_free_result($peresult);
$per2query = query("SELECT * FROM `".$Settings['sqltable']."catpermissions` WHERE `PermissionID`=%i ORDER BY `CategoryID` ASC", array($GroupInfo['PermissionID']));
$per2esult = exec_query($per2query);
$per2num = mysql_num_rows($per2esult);
$per2i = 0;
$Per2Error = null;
if ($per2num <= 0) {
    $Per2Error = true;
    @mysql_free_result($per2esult);
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    ob_clean();
    echo "Sorry could not find permission data in database.\nContact the board admin about error.";
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
if ($per2num >= 1) {
    while ($per2i < $per2num) {
        $PerCatID = mysql_result($per2esult, $per2i, "CategoryID");
        if (!is_numeric($PerCatID)) {
            $Per2Error = true;
        }
        $CatPermissionInfo['ID'][$PerCatID] = mysql_result($per2esult, $per2i, "id");
        if (!is_numeric($CatPermissionInfo['ID'][$PerCatID])) {
            $Per2Error = true;
        }
        $CatPermissionInfo['PermissionID'][$PerCatID] = mysql_result($per2esult, $per2i, "PermissionID");
        if (!is_numeric($CatPermissionInfo['PermissionID'][$PerCatID])) {
            $Per2Error = true;
        }
        $CatPermissionInfo['Name'][$PerCatID] = mysql_result($per2esult, $per2i, "Name");
        $CatPermissionInfo['CategoryID'][$PerCatID] = mysql_result($per2esult, $per2i, "CategoryID");
        if (!is_numeric($CatPermissionInfo['CategoryID'][$PerCatID])) {
            $Per2Error = true;
        }
        $CatPermissionInfo['CanViewCategory'][$PerCatID] = mysql_result($per2esult, $per2i, "CanViewCategory");
        if ($CatPermissionInfo['CanViewCategory'][$PerCatID] != "yes" && $CatPermissionInfo['CanViewCategory'][$PerCatID] != "no") {
            $Per2Error = true;
        }
        if ($Per2Error === true) {
            $per2i = $per2num;
        }
        ++$per2i;
    } if ($Per2Error === true) {
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        @mysql_free_result($per2esult);
        ob_clean();
        echo "Sorry could not load all permission data in database.\nContact the board admin about error.";
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @session_write_close();
        die();
    }
}
@mysql_free_result($per2esult);
