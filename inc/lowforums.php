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

    $FileInfo: lowforums.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "lowforums.php" || $File3Name == "/lowforums.php") {
    require('index.php');
    exit();
}
$prequery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `ShowCategory`='yes' AND `InSubCategory`=0 ORDER BY `OrderID` ASC, `id` ASC", array());
$preresult = exec_query($prequery);
$prenum = mysql_num_rows($preresult);
$prei = 0;
$_SESSION['ViewingPage'] = url_maker(null, "no+ext", "act=lowview", "&", "=", $prexqstr['index'], $exqstr['index']);
if ($Settings['file_ext'] != "no+ext" && $Settings['file_ext'] != "no ext") {
    $_SESSION['ViewingFile'] = $exfile['index'].$Settings['file_ext'];
}
if ($Settings['file_ext'] == "no+ext" || $Settings['file_ext'] == "no ext") {
    $_SESSION['ViewingFile'] = $exfile['index'];
}
$_SESSION['PreViewingTitle'] = "Viewing";
$_SESSION['ViewingTitle'] = "Board index";
?>
<div style="font-size: 1.0em; font-weight: bold; margin-bottom: 10px; padding-top: 3px; width: auto;">Full Version: <a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>"><?php echo $Settings['board_name']; ?></a></div>
<div style="font-size: 11px; font-weight: bold; padding: 10px; border: 1px solid gray;"><a href="<?php echo url_maker($exfile['index'], $Settings['file_ext'], "act=lowview", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index']); ?>">Board index</a></div>
<div>&nbsp;</div>
<div style="padding: 10px; border: 1px solid gray;">
<ul style="list-style-type: none;">
<?php
while ($prei < $prenum) {
    $CategoryID = mysql_result($preresult, $prei, "id");
    $CategoryName = mysql_result($preresult, $prei, "Name");
    $CategoryShow = mysql_result($preresult, $prei, "ShowCategory");
    $CategoryType = mysql_result($preresult, $prei, "CategoryType");
    $SubShowForums = mysql_result($preresult, $prei, "SubShowForums");
    $CategoryDescription = mysql_result($preresult, $prei, "Description");
    $CategoryType = strtolower($CategoryType);
    $SubShowForums = strtolower($SubShowForums);
    $CategoryPostCountView = mysql_result($preresult, 0, "PostCountView");
    $CategoryKarmaCountView = mysql_result($preresult, 0, "KarmaCountView");
    if ($MyPostCountChk == null) {
        $MyPostCountChk = 0;
    }
    if ($MyKarmaCount == null) {
        $MyKarmaCount = 0;
    }
    if ($GroupInfo['HasAdminCP'] != "yes" || $GroupInfo['HasModCP'] != "yes") {
        if ($CategoryPostCountView != 0 && $MyPostCountChk < $CategoryPostCountView) {
            redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=lowview", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
        }
        if ($CategoryKarmaCountView != 0 && $MyKarmaCount < $CategoryKarmaCountView) {
            redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=lowview", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
        }
    }
    if (isset($CatPermissionInfo['CanViewCategory'][$CategoryID]) &&
        $CatPermissionInfo['CanViewCategory'][$CategoryID] == "yes") {
        $query = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `CategoryID`=%i AND `InSubForum`=0 ORDER BY `OrderID` ASC, `id` ASC", array($CategoryID));
        $result = exec_query($query);
        $num = mysql_num_rows($result);
        $i = 0;
        if ($num >= 1) {
            ?>
<li style="font-weight: bold;"><a href="<?php echo url_maker($exfile[$CategoryType], $Settings['file_ext'], "act=lowview&id=".$CategoryID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$CategoryType], $exqstr[$CategoryType]); ?>"><?php echo $CategoryName; ?></a></li><li>
<?php }
        while ($i < $num) {
            $ForumID = mysql_result($result, $i, "id");
            $ForumName = mysql_result($result, $i, "Name");
            $ForumShow = mysql_result($result, $i, "ShowForum");
            $ForumType = mysql_result($result, $i, "ForumType");
            $ForumShowTopics = mysql_result($result, $i, "CanHaveTopics");
            $ForumShowTopics = strtolower($ForumShowTopics);
            $NumTopics = mysql_result($result, $i, "NumTopics");
            $NumPosts = mysql_result($result, $i, "NumPosts");
            $NumRedirects = mysql_result($result, $i, "Redirects");
            $ForumDescription = mysql_result($result, $i, "Description");
            $ForumType = strtolower($ForumType);
            $sflist = null;
            $gltf = array(null);
            $gltf[0] = $ForumID;
            if ($ForumType == "subforum") {
                $apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `InSubForum`=%i ORDER BY `OrderID` ASC, `id` ASC", array($ForumID));
                $apcresult = exec_query($apcquery);
                $apcnum = mysql_num_rows($apcresult);
                $apci = 0;
                $apcl = 1;
                if ($apcnum >= 1) {
                    while ($apci < $apcnum) {
                        $NumsTopics = mysql_result($apcresult, $apci, "NumTopics");
                        $NumTopics = $NumsTopics + $NumTopics;
                        $NumsPosts = mysql_result($apcresult, $apci, "NumPosts");
                        $NumPosts = $NumsPosts + $NumPosts;
                        $SubsForumID = mysql_result($apcresult, $apci, "id");
                        $SubsForumName = mysql_result($apcresult, $apci, "Name");
                        $SubsForumType = mysql_result($apcresult, $apci, "ForumType");
                        if (isset($PermissionInfo['CanViewForum'][$SubsForumID]) &&
                            $PermissionInfo['CanViewForum'][$SubsForumID] == "yes") {
                            $shownum = null;
                            if ($SubsForumType == "redirect") {
                                $shownum = "(".$NumRedirects." redirects)";
                            }
                            if ($SubsForumType != "redirect") {
                                $shownum = "(".$NumPosts." posts)";
                            }
                            $sfurl = "<a href=\"";
                            $sfurl = url_maker($exfile[$SubsForumType], $Settings['file_ext'], "act=lowview&id=".$SubsForumID.$ExStr, $Settings['qstr'], $Settings['qsep'], $prexqstr[$SubsForumType], $exqstr[$SubsForumType]);
                            $sfurl = "<li><ul style=\"list-style-type: none;\"><li><a href=\"".$sfurl."\">".$SubsForumName."</a> <span style=\"color: gray; font-size: 10px;\">".$shownum."</span></li></ul></li>";
                            if ($apcl == 1) {
                                $sflist = null;
                                $sflist = $sflist." ".$sfurl;
                            }
                            if ($apcl > 1) {
                                $sflist = $sflist." ".$sfurl;
                            }
                            $gltf[$apcl] = $SubsForumID;
                            ++$apcl;
                        }
                        ++$apci;
                    }
                    @mysql_free_result($apcresult);
                }
            }
            if (isset($PermissionInfo['CanViewForum'][$ForumID]) &&
                $PermissionInfo['CanViewForum'][$ForumID] == "yes") {
                $LastTopic = "&nbsp;<br />&nbsp;<br />&nbsp;";
                if (!isset($LastTopic)) {
                    $LastTopic = null;
                }
                $gltnum = count($gltf);
                $glti = 0;
                $OldUpdateTime = 0;
                $UseThisFonum = null;
                if ($ForumType == "subforum") {
                    while ($glti < $gltnum) {
                        $gltfoquery = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `ForumID`=%i ORDER BY `LastUpdate` DESC LIMIT 1", array($gltf[$glti]));
                        $gltforesult = exec_query($gltfoquery);
                        $gltfonum = mysql_num_rows($gltforesult);
                        if ($gltfonum > 0) {
                            $NewUpdateTime = mysql_result($gltforesult, 0, "LastUpdate");
                            if ($NewUpdateTime > $OldUpdateTime) {
                                $UseThisFonum = $gltf[$glti];
                                $OldUpdateTime = $NewUpdateTime;
                            }
                        }
                        @mysql_free_result($gltforesult);
                        ++$glti;
                    }
                }
                $shownum = null;
                if ($ForumType == "redirect") {
                    $shownum = "(".$NumRedirects." redirects)";
                }
                if ($ForumType != "redirect") {
                    $shownum = "(".$NumPosts." posts)";
                }
                $PreForum = $ThemeSet['ForumIcon'];
                if ($ForumType == "forum") {
                    $PreForum = $ThemeSet['ForumIcon'];
                }
                if ($ForumType == "subforum") {
                    $PreForum = $ThemeSet['SubForumIcon'];
                }
                if ($ForumType == "redirect") {
                    $PreForum = $ThemeSet['RedirectIcon'];
                }
                $ExStr = "";
                if ($ForumType != "redirect" &&
                    $ForumShowTopics != "no") {
                    $ExStr = "&page=1";
                }
                ?>
<ul style="list-style-type: none;"><li>
<a href="<?php echo url_maker($exfile[$ForumType], $Settings['file_ext'], "act=lowview&id=".$ForumID.$ExStr, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?>"<?php if ($ForumType == "redirect") {
    echo " onclick=\"window.open(this.href);return false;\"";
} ?>><?php echo $ForumName; ?></a> <span style="color: gray; font-size: 10px;"><?php echo $shownum; ?></span></li>
<?php echo $sflist; ?></ul>
<?php } ++$i;
        } @mysql_free_result($result);
        if ($num >= 1) {
            ?>
<?php }
        }
    ?></li><?php
    ++$prei;
}
@mysql_free_result($preresult); ?>
</ul></div>
<div>&nbsp;</div>
