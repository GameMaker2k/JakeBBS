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

    $FileInfo: categories.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "categories.php" || $File3Name == "/categories.php") {
    require('index.php');
    exit();
}

// Check if we can goto admin cp
if ($_SESSION['UserGroup'] == $Settings['GuestGroup'] || $GroupInfo['HasAdminCP'] == "no") {
    redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
if (!isset($_POST['update'])) {
    $_POST['update'] = null;
}
$Error = null;
$errorstr = null;
?>
<table class="Table3">
<tr style="width: 100%; vertical-align: top;">
	<td style="width: 15%; vertical-align: top;">
<?php
require($SettDir['admin'].'table.php');
?>
</td>
	<td style="width: 85%; vertical-align: top;">
<?php if ($_GET['act'] == "addcategory" && $_POST['update'] != "now") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Adding new Category";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">JakeBBS Category Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">JakeBBS Category Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Adding new Category: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryID">Insert ID for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryID" class="TextBox" id="CategoryID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OrderID">Insert order id category:</label></td>
	<td style="width: 50%;"><input type="text" name="OrderID" class="TextBox" id="OrderID" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryName">Insert name for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryName" class="TextBox" id="CategoryName" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryDesc">Insert description for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryDesc" class="TextBox" id="CategoryDesc" size="20" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ShowCategory">Show category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ShowCategory" id="ShowCategory">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryType">Insert category type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CategoryType" id="CategoryType">
	<option selected="selected" value="category">Category</option>
	<option value="subcategory">SubCategory</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="InSubCategory">In SubCategory:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="InSubCategory" id="InSubCategory">
	<option selected="selected" value="0">none</option>
<?php
    $fq = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `InSubCategory`=0 ORDER BY `OrderID` ASC, `id` ASC", array(null));
    $fr = exec_query($fq);
    $ai = mysql_num_rows($fr);
    $fi = 0;
    while ($fi < $ai) {
        $InCategoryID = mysql_result($fr, $fi, "id");
        $InCategoryName = mysql_result($fr, $fi, "Name");
        $InCategoryType = mysql_result($fr, $fi, "CategoryType");
        $AiFiInSubCategory = mysql_result($fr, $fi, "InSubCategory");
        if ($AiFiInSubCategory == "0") {
            ?>
	<option value="<?php echo $InCategoryID; ?>"><?php echo $InCategoryName; ?></option>
<?php } ++$fi;
    }
    @mysql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumPostView">Number of posts to view category:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumPostView" id="NumPostView" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumKarmaView">Amount of karma to view category:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumKarmaView" id="NumKarmaView" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="addcategory" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Add Category" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if ($_POST['act'] == "addcategory" && $_POST['update'] == "now" && $_GET['act'] == "addcategory") {
    $_POST['CategoryName'] = stripcslashes(htmlspecialchars($_POST['CategoryName'], ENT_QUOTES, $Settings['charset']));
    //$_POST['CategoryName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryName']);
    $_POST['CategoryName'] = @remove_spaces($_POST['CategoryName']);
    $_POST['CategoryDesc'] = stripcslashes(htmlspecialchars($_POST['CategoryDesc'], ENT_QUOTES, $Settings['charset']));
    //$_POST['CategoryDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryDesc']);
    $_POST['CategoryDesc'] = @remove_spaces($_POST['CategoryDesc']);
    $sql_id_check = exec_query(query("SELECT `id` FROM `".$Settings['sqltable']."categories` WHERE `id`=%i LIMIT 1", array($_POST['CategoryID'])));
    $sql_order_check = exec_query(query("SELECT `OrderID` FROM `".$Settings['sqltable']."categories` WHERE `OrderID`=%i LIMIT 1", array($_POST['OrderID'])));
    $id_check = mysql_num_rows($sql_id_check);
    $order_check = mysql_num_rows($sql_order_check);
    @mysql_free_result($sql_id_check);
    @mysql_free_result($sql_order_check);
    $errorstr = "";
    if ($_POST['NumPostView'] == null ||
        !is_numeric($_POST['NumPostView'])) {
        $_POST['NumPostView'] = 0;
    }
    if ($_POST['NumKarmaView'] == null ||
        !is_numeric($_POST['NumKarmaView'])) {
        $_POST['NumKarmaView'] = 0;
    }
    if ($Settings['hot_topic_num'] == null ||
        !is_numeric($Settings['hot_topic_num'])) {
        $Settings['hot_topic_num'] = 10;
    }
    if ($_POST['NumPostHotTopic'] == null ||
        !is_numeric($_POST['NumPostHotTopic'])) {
        $_POST['NumPostHotTopic'] = $Settings['hot_topic_num'];
    }
    if ($_POST['CategoryName'] == null ||
        $_POST['CategoryName'] == "ShowMe") {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a category name.<br />\n";
    }
    if ($_POST['CategoryDesc'] == null) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a description.<br />\n";
    }
    if ($_POST['CategoryID'] == null ||
        !is_numeric($_POST['CategoryID'])) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a category id.<br />\n";
    }
    if ($id_check > 0) {
        $Error = "Yes";
        $errorstr = $errorstr."This ID number is already used.<br />\n";
    }
    if ($order_check > 0) {
        $Error = "Yes";
        $errorstr = $errorstr."This order number is already used.<br />\n";
    }
    if (pre_strlen($_POST['CategoryName']) > "150") {
        $Error = "Yes";
        $errorstr = $errorstr."Your category name is too big.<br />\n";
    }
    if (pre_strlen($_POST['CategoryDesc']) > "300") {
        $Error = "Yes";
        $errorstr = $errorstr."Your category description is too big.<br />\n";
    }
    if ($Error != "Yes") {
        @redirect("refresh", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
        $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
        $query = query("INSERT INTO `".$Settings['sqltable']."categories` (`id`, `OrderID`, `Name`, `ShowCategory`, `CategoryType`, `SubShowForums`, `InSubCategory`, `PostCountView`, `KarmaCountView`, `Description`) VALUES\n".
        "(%i, %i, '%s', '%s', '%s', 'yes', %i, %i, %i, '%s')", array($_POST['CategoryID'],$_POST['OrderID'],$_POST['CategoryName'],$_POST['ShowCategory'],$_POST['CategoryType'],$_POST['InSubCategory'],$_POST['CategoryDesc'],$_POST['NumPostView'],$_POST['NumKarmaView'],$_POST['CategoryDesc']));
        exec_query($query);
        $getperidq = query("SELECT DISTINCT `PermissionID` FROM `".$Settings['sqltable']."catpermissions` ORDER BY `PermissionID` ASC", array(null));
        $getperidr = exec_query($getperidq);
        $getperidnum = mysql_num_rows($getperidr);
        $getperidi = 0;
        $nextperid = getnextid($Settings['sqltable'], "catpermissions");
        while ($getperidi < $getperidnum) {
            $getperidID = mysql_result($getperidr, $getperidi, "PermissionID");
            $getperidq2 = query("SELECT * FROM `".$Settings['sqltable']."catpermissions` WHERE `PermissionID`=%i", array($getperidID));
            $getperidr2 = exec_query($getperidq2);
            $getperidnum2 = mysql_num_rows($getperidr2);
            $getperidName = mysql_result($getperidr2, 0, "Name");
            @mysql_free_result($getperidr2);
            $query = query("INSERT IGNORE INTO `".$Settings['sqltable']."catpermissions` VALUES (%i, %i, '%s', %i, 'yes')", array($nextperid,$getperidID,$getperidName,$_POST['CategoryID']));
            exec_query($query);
            ++$getperidi;
            ++$nextperid;
        }
        @mysql_free_result($getperidr);
        ?>
<?php }
    } if ($_GET['act'] == "deletecategory" && $_POST['update'] != "now") {
        $admincptitle = " ".$ThemeSet['TitleDivider']." Deleting a Category";
        ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">JakeBBS Category Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=addcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">JakeBBS Category Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Deleting a Category: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=deletecategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelCategories">Delete all categories in subcategory:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelCategories" id="DelCategories">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelForums">Delete all forums in (sub)category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelForums" id="DelForums">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelTopics">Delete all topics in (sub)category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelTopics" id="DelTopics">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelPermission">Delete all permission sets in (sub)category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelPermission" id="DelPermission">
	<option selected="selected" value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="DelID">Delete Category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="DelID" id="DelID">
<?php
        $fq = query("SELECT * FROM `".$Settings['sqltable']."categories` ORDER BY `OrderID` ASC, `id` ASC", array(null));
        $fr = exec_query($fq);
        $ai = mysql_num_rows($fr);
        $fi = 0;
        while ($fi < $ai) {
            $InCategoryID = mysql_result($fr, $fi, "id");
            $InCategoryName = mysql_result($fr, $fi, "Name");
            $InCategoryType = mysql_result($fr, $fi, "CategoryType");
            $AiFiInSubCategory = mysql_result($fr, $fi, "InSubCategory");
            ?>
	<option value="<?php echo $InCategoryID; ?>"><?php echo $InCategoryName; ?></option>
<?php ++$fi;
        }
        @mysql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="deletecategory" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="submit" class="Button" value="Delete Category" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if ($_GET['act'] == "deletecategory" && $_POST['update'] == "now" && $_GET['act'] == "deletecategory") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    $prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i LIMIT 1", array($_POST['DelID']));
    $preresult = exec_query($prequery);
    $prenum = mysql_num_rows($preresult);
    $errorstr = "";
    $Error = null;
    if (!is_numeric($_POST['DelID'])) {
        $Error = "Yes";
        $errorstr = $errorstr."You need to enter a forum ID.<br />\n";
    }
    if ($prenum > 0 && $Error != "Yes") {
        $dtquery = query("DELETE FROM `".$Settings['sqltable']."categories` WHERE `id`=%i", array($_POST['DelID']));
        exec_query($dtquery);
        if ($_POST['DelCategories'] == "yes") {
            $dscquery = query("DELETE FROM `".$Settings['sqltable']."categories` WHERE `InSubCategory`=%i", array($_POST['DelID']));
            exec_query($dscquery);
        }
        if ($_POST['DelForums'] == "yes") {
            $dsfquery = query("DELETE FROM `".$Settings['sqltable']."forums` WHERE `CategoryID`=%i", array($_POST['DelID']));
            exec_query($dsfquery);
        }
        if ($_POST['DelForums'] == "yes") {
            $dstquery = query("DELETE FROM `".$Settings['sqltable']."topics` WHERE `CategoryID`=%i", array($_POST['DelID']));
            exec_query($dstquery);
        }
        if ($_POST['DelForums'] == "yes") {
            $dstquery = query("DELETE FROM `".$Settings['sqltable']."topics` WHERE `CategoryID`=%i", array($_POST['DelID']));
            exec_query($dstquery);
            $dstquery = query("DELETE FROM `".$Settings['sqltable']."posts` WHERE `CategoryID`=%i", array($_POST['DelID']));
            exec_query($dstquery);
        }
        if ($_POST['DelPermission'] == "yes") {
            $apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `CategoryID`=%i ORDER BY `OrderID` ASC, `id` ASC", array($_POST['DelID']));
            $apcresult = exec_query($apcquery);
            $apcnum = mysql_num_rows($apcresult);
            $apci = 0;
            $apcl = 1;
            if ($apcnum >= 1) {
                while ($apci < $apcnum) {
                    $DelForumID = mysql_result($apcresult, $apci, "id");
                    if ($_POST['DelPermission'] == "yes") {
                        $dtquery = query("DELETE FROM `".$Settings['sqltable']."permissions` WHERE `ForumID`=%i", array($DelForumID));
                        exec_query($dtquery);
                    }
                    ++$apci;
                }
                @mysql_free_result($apcresult);
            }
        }
        if ($_POST['DelPermission'] == "yes") {
            $apcquery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `InSubCategory`=%i ORDER BY `OrderID` ASC, `id` ASC", array($_POST['DelID']));
            $apcresult = exec_query($apcquery);
            $apcnum = mysql_num_rows($apcresult);
            $apci = 0;
            $apcl = 1;
            if ($apcnum >= 1) {
                while ($apci < $apcnum) {
                    $DelSubsCategoryID = mysql_result($apcresult, $apci, "id");
                    if ($_POST['DelPermission'] == "yes") {
                        $dtquery = query("DELETE FROM `".$Settings['sqltable']."catpermissions` WHERE `CategoryID`=%i", array($DelSubsCategoryID));
                        exec_query($dtquery);
                    }
                    ++$apci;
                }
                @mysql_free_result($apcresult);
            }
        }
        ?>
<?php }
    } if ($_GET['act'] == "editcategory" && $_POST['update'] != "now") {
        $admincptitle = " ".$ThemeSet['TitleDivider']." Editing a Category";
        if (!isset($_POST['id'])) {
            ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">JakeBBS Category Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">JakeBBS Category Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing a Category: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Category to Edit:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php
            $fq = query("SELECT * FROM `".$Settings['sqltable']."categories` ORDER BY `OrderID` ASC, `id` ASC", array(null));
            $fr = exec_query($fq);
            $ai = mysql_num_rows($fr);
            $fi = 0;
            while ($fi < $ai) {
                $InCategoryID = mysql_result($fr, $fi, "id");
                $InCategoryName = mysql_result($fr, $fi, "Name");
                $InCategoryType = mysql_result($fr, $fi, "CategoryType");
                $AiFiInSubCategory = mysql_result($fr, $fi, "InSubCategory");
                ?>
	<option value="<?php echo $InCategoryID; ?>"><?php echo $InCategoryName; ?></option>
<?php ++$fi;
            }
            @mysql_free_result($fr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editcategory" style="display: none;" />
<input type="submit" class="Button" value="Edit Category" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if (isset($_POST['id'])) {
    $prequery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `id`=%i LIMIT 1", array($_POST['id']));
    $preresult = exec_query($prequery);
    $prenum = mysql_num_rows($preresult);
    if ($prenum == 0) {
        redirect("location", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false));
        @mysql_free_result($preresult);
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @session_write_close();
        die();
    }
    if ($prenum >= 1) {
        $CategoryID = mysql_result($preresult, 0, "id");
        $CategoryOrder = mysql_result($preresult, 0, "OrderID");
        $CategoryName = mysql_result($preresult, 0, "Name");
        $CategoryName = htmlspecialchars($CategoryName, ENT_QUOTES, $Settings['charset']);
        $ShowCategory = mysql_result($preresult, 0, "ShowCategory");
        $CategoryType = mysql_result($preresult, 0, "CategoryType");
        $SubShowForums = mysql_result($preresult, 0, "SubShowForums");
        $InSubCategory = mysql_result($preresult, 0, "InSubCategory");
        $CategoryDescription = mysql_result($preresult, 0, "Description");
        $CategoryDescription = htmlspecialchars($CategoryDescription, ENT_QUOTES, $Settings['charset']);
        $KarmaCountView = mysql_result($preresult, 0, "KarmaCountView");
        $PostCountView = mysql_result($preresult, 0, "PostCountView");
        @mysql_free_result($preresult);
        $CategoryType = strtolower($CategoryType);
        ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">JakeBBS Category Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">JakeBBS Category Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing a Category: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=editcategory", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryID">Insert id for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryID" class="TextBox" id="CategoryID" size="20" value="<?php echo $CategoryID; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="OrderID">Insert order id category:</label></td>
	<td style="width: 50%;"><input type="text" name="OrderID" class="TextBox" id="OrderID" size="20" value="<?php echo $CategoryOrder; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryName">Insert name for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryName" class="TextBox" id="CategoryName" size="20" value="<?php echo $CategoryName; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryDesc">Insert description for category:</label></td>
	<td style="width: 50%;"><input type="text" name="CategoryDesc" class="TextBox" id="CategoryDesc" size="20" value="<?php echo $CategoryDescription; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="ShowCategory">Show category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="ShowCategory" id="ShowCategory">
	<option <?php if ($ShowCategory == "yes") {
	    echo "selected=\"selected\" ";
	} ?>value="yes">yes</option>
	<option <?php if ($ShowCategory == "no") {
	    echo "selected=\"selected\" ";
	} ?>value="no">no</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CategoryType">Insert category type:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CategoryType" id="CategoryType">
	<option <?php if ($CategoryType == "category") {
	    echo "selected=\"selected\" ";
	} ?>value="category">Category</option>
	<option <?php if ($CategoryType == "subcategory") {
	    echo "selected=\"selected\" ";
	} ?>value="subcategory">SubCategory</option>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="InSubCategory">In SubCategory:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="InSubCategory" id="InSubCategory">
	<option selected="selected" value="0">none</option>
<?php
$fq = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `InSubCategory`=0 AND `id`<>%i ORDER BY `OrderID` ASC, `id` ASC", array($CategoryID));
        $fr = exec_query($fq);
        $ai = mysql_num_rows($fr);
        $fi = 0;
        while ($fi < $ai) {
            $InCategoryID = mysql_result($fr, $fi, "id");
            $InCategoryName = mysql_result($fr, $fi, "Name");
            $InCategoryType = mysql_result($fr, $fi, "CategoryType");
            $AiFiInSubCategory = mysql_result($fr, $fi, "InSubCategory");
            if ($AiFiInSubCategory == "0") {
                if ($InSubCategory == $InCategoryID) {
                    ?>
	<option value="<?php echo $InCategoryID; ?>" selected="selected"><?php echo $InCategoryName; ?></option>
<?php } if ($InSubCategory != $InCategoryID) { ?>
	<option value="<?php echo $InCategoryID; ?>"><?php echo $InCategoryName; ?></option>
<?php }
} ++$fi;
        }
        @mysql_free_result($fr); ?>
	</select></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumPostView">Number of posts to view categories:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumPostView" id="NumPostView" value="<?php echo $PostCountView; ?>" /></td>
</tr><tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="NumKarmaView">Amount of karma to view categories:</label></td>
	<td style="width: 50%;"><input type="text" class="TextBox" size="20" name="NumKarmaView" id="NumKarmaView" value="<?php echo $KarmaCountView; ?>" /></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="editcategory" style="display: none;" />
<input type="hidden" name="update" value="now" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $CategoryID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Category" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php }
    }
    } if ($_POST['act'] == "editcategory" && $_POST['update'] == "now" && $_GET['act'] == "editcategory" &&
        isset($_POST['id'])) {
        $_POST['CategoryName'] = stripcslashes(htmlspecialchars($_POST['CategoryName'], ENT_QUOTES, $Settings['charset']));
        //$_POST['CategoryName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryName']);
        $_POST['CategoryName'] = @remove_spaces($_POST['CategoryName']);
        $_POST['CategoryDesc'] = stripcslashes(htmlspecialchars($_POST['CategoryDesc'], ENT_QUOTES, $Settings['charset']));
        //$_POST['CategoryDesc'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryDesc']);
        $_POST['CategoryDesc'] = @remove_spaces($_POST['CategoryDesc']);
        $prequery = query("SELECT * FROM `".$Settings['sqltable']."categories` WHERE `id`=%i LIMIT 1", array($_POST['id']));
        $preresult = exec_query($prequery);
        $prenum = mysql_num_rows($preresult);
        if ($prenum == 0) {
            redirect("location", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false));
            @mysql_free_result($preresult);
            ob_clean();
            @header("Content-Type: text/plain; charset=".$Settings['charset']);
            gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
            @session_write_close();
            die();
        }
        if ($prenum >= 1) {
            $OldID = mysql_result($preresult, 0, "id");
            $OldOrder = mysql_result($preresult, 0, "OrderID");
            @mysql_free_result($preresult);
            $sql_id_check = exec_query(query("SELECT `id` FROM `".$Settings['sqltable']."categories` WHERE `id`=%i LIMIT 1", array($_POST['ForumID'])));
            $sql_order_check = exec_query(query("SELECT `OrderID` FROM `".$Settings['sqltable']."categories` WHERE `OrderID`=%i LIMIT 1", array($_POST['OrderID'])));
            $id_check = mysql_num_rows($sql_id_check);
            $order_check = mysql_num_rows($sql_order_check);
            @mysql_free_result($sql_id_check);
            @mysql_free_result($sql_order_check);
            if ($_POST['NumPostView'] == null ||
                !is_numeric($_POST['NumPostView'])) {
                $_POST['NumPostView'] = 0;
            }
            if ($_POST['NumKarmaView'] == null ||
                !is_numeric($_POST['NumKarmaView'])) {
                $_POST['NumKarmaView'] = 0;
            }
            if ($_POST['CategoryName'] == null ||
                $_POST['CategoryName'] == "ShowMe") {
                $Error = "Yes";
                $errorstr = $errorstr."You need to enter a category name.<br />\n";
            }
            if ($_POST['CategoryDesc'] == null) {
                $Error = "Yes";
                $errorstr = $errorstr."You need to enter a description.<br />\n";
            }
            if ($_POST['CategoryID'] == null ||
                !is_numeric($_POST['CategoryID'])) {
                $Error = "Yes";
                $errorstr = $errorstr."You need to enter a category ID.<br />\n";
            }
            if ($id_check > 0 && $_POST['CategoryID'] != $OldID) {
                $Error = "Yes";
                $errorstr = $errorstr."This ID number is already used.<br />\n";
            }
            if ($order_check > 0 && $_POST['OrderID'] != $OldOrder) {
                $Error = "Yes";
                $errorstr = $errorstr."This order number is already used.<br />\n";
            }
            if (pre_strlen($_POST['CategoryName']) > "150") {
                $Error = "Yes";
                $errorstr = $errorstr."Your category name is too big.<br />\n";
            }
            if (pre_strlen($_POST['CategoryDesc']) > "300") {
                $Error = "Yes";
                $errorstr = $errorstr."Your category description is too big.<br />\n";
            }
            if ($Error != "Yes") {
                @redirect("refresh", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
                $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
                $query = query("UPDATE `".$Settings['sqltable']."categories` SET `id`=%i,`OrderID`=%i,`Name`='%s',`ShowCategory`='%s',`CategoryType`='%s',`InSubCategory`=%i,`Description`='%s',`PostCountView`=%i,`KarmaCountView`=%i WHERE `id`=%i", array($_POST['CategoryID'],$_POST['OrderID'],$_POST['CategoryName'],$_POST['ShowCategory'],$_POST['CategoryType'],$_POST['InSubCategory'],$_POST['CategoryDesc'],$_POST['NumPostView'],$_POST['NumKarmaView'],$_POST['id']));
                exec_query($query);
                ?>
<?php }
            }
    } if ($_GET['act'] == "cpermissions" && $_POST['update'] != "now") {
        $admincptitle = " ".$ThemeSet['TitleDivider']." Category Permissions Manager";
        if (!isset($_POST['id'])) {
            ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Category Permissions Manager: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="id">Permission to view:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="id" id="id">
<?php
            $getperidq = query("SELECT DISTINCT `PermissionID` FROM `".$Settings['sqltable']."catpermissions`", array(null));
            $getperidr = exec_query($getperidq);
            $getperidnum = mysql_num_rows($getperidr);
            $getperidi = 0;
            while ($getperidi < $getperidnum) {
                $getperidID = mysql_result($getperidr, $getperidi, "PermissionID");
                $getperidq2 = query("SELECT * FROM `".$Settings['sqltable']."catpermissions` WHERE `PermissionID`=%i ORDER BY `CategoryID` ASC", array($getperidID));
                $getperidr2 = exec_query($getperidq2);
                $getperidnum2 = mysql_num_rows($getperidr2);
                $getperidName = mysql_result($getperidr2, 0, "Name");
                @mysql_free_result($getperidr2);
                ?>
	<option value="<?php echo $getperidID; ?>"><?php echo $getperidName; ?></option>
<?php ++$getperidi;
            }
            @mysql_free_result($getperidr); ?>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="submit" class="Button" value="View Permission" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if (isset($_POST['id']) && $_POST['subact'] == null) { ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Category Permissions Manager: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<?php
            $fq = query("SELECT * FROM `".$Settings['sqltable']."categories` ORDER BY `OrderID` ASC, `id` ASC", array(null));
    $fr = exec_query($fq);
    $ai = mysql_num_rows($fr);
    $fi = 0;
    while ($fi < $ai) {
        $InCategoryID = mysql_result($fr, $fi, "id");
        $InCategoryName = mysql_result($fr, $fi, "Name");
        $getperidq = query("SELECT * FROM `".$Settings['sqltable']."catpermissions` WHERE PermissionID=%i AND `CategoryID`=%i LIMIT 1", array($_POST['id'],$InCategoryID));
        $getperidr = exec_query($getperidq);
        $getperidnum = mysql_num_rows($getperidr);
        $getperidNumz = null;
        $getperidID = null;
        if ($getperidnum > 0) {
            $getperidNumz = mysql_result($getperidr, 0, "id");
            $getperidID = mysql_result($getperidr, 0, "PermissionID");
        }
        ?>
<form style="display: inline;" method="post" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<?php if ($getperidnum > 0) { ?>
Permissions for <?php echo $InCategoryName; ?> are set: <br />
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="hidden" name="subact" value="edit" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $getperidNumz; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Permissions" name="Apply_Changes" />
<?php } if ($getperidnum <= 0) { ?>
Permissions for <?php echo $InCategoryName; ?> are not set: <br />
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="hidden" name="subact" value="create" style="display: none;" />
<input type="hidden" name="permid" value="<?php echo $_POST['id']; ?>" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $InCategoryID; ?>" style="display: none;" />
<input type="submit" class="Button" value="Create Permissions" name="Apply_Changes" />
<?php } ?>
</td></tr></table>
</form>
<?php
        @mysql_free_result($getperidr);
        ++$fi;
    }
    @mysql_free_result($fr); ?>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if (isset($_POST['id']) && $_POST['subact'] == "edit") {
    $prequery = query("SELECT * FROM `".$Settings['sqltable']."catpermissions` WHERE `id`=%i LIMIT 1", array($_POST['id']));
    $preresult = exec_query($prequery);
    $prenum = mysql_num_rows($preresult);
    if ($prenum == 0) {
        redirect("location", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false));
        @mysql_free_result($preresult);
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @session_write_close();
        die();
    }
    if ($prenum >= 1) {
        $PermissionNum = mysql_result($preresult, 0, "id");
        $PermissionID = mysql_result($preresult, 0, "PermissionID");
        $PermissionName = mysql_result($preresult, 0, "Name");
        $PermissionCategoryID = mysql_result($preresult, 0, "CategoryID");
        $CanViewCategory = mysql_result($preresult, 0, "CanViewCategory");
        @mysql_free_result($preresult);
    }
    $PermissionName = stripcslashes(htmlspecialchars($PermissionName, ENT_QUOTES, $Settings['charset']));
    //$_POST['CategoryName'] = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $_POST['CategoryName']);
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Category Permissions: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewCategory">Can view Category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewCategory" id="CanViewCategory">
	<option <?php if ($CanViewCategory == "yes") {
	    echo "selected=\"selected\" ";
	} ?>value="yes">yes</option>
	<option <?php if ($CanViewCategory == "no") {
	    echo "selected=\"selected\" ";
	} ?>value="no">no</option>
	</select></td>
</tr> 
</table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="hidden" name="subact" value="editnow" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $PermissionNum; ?>" style="display: none;" />
<input type="submit" class="Button" value="Edit Permissions" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if (isset($_POST['id']) && $_POST['subact'] == "editnow") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    @redirect("refresh", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
    $query = query("UPDATE `".$Settings['sqltable']."catpermissions` SET `CanViewCategory`='%s' WHERE `id`=%i", array($_POST['CanViewCategory'], $_POST['id']));
    exec_query($query);
} if (isset($_POST['id']) && $_POST['subact'] == "create") {
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Category Permissions Manager</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr class="TableMenuRow2">
<th class="TableMenuColumn2" style="width: 100%; text-align: left;">
<span style="float: left;">&nbsp;Editing Category Permissions: </span>
<span style="float: right;">&nbsp;</span>
</th>
</tr>
<tr class="TableMenuRow3">
<td class="TableMenuColumn3">
<form style="display: inline;" method="post" id="acptool" action="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=cpermissions", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">
<table style="text-align: left;">
<tr style="text-align: left;">
	<td style="width: 50%;"><label class="TextBoxLabel" for="CanViewCategory">Can view category:</label></td>
	<td style="width: 50%;"><select size="1" class="TextBox" name="CanViewCategory" id="CanViewCategory">
	<option value="yes">yes</option>
	<option value="no">no</option>
	</select></td>
</tr></table>
<table style="text-align: left;">
<tr style="text-align: left;">
<td style="width: 100%;">
<input type="hidden" name="act" value="cpermissions" style="display: none;" />
<input type="hidden" name="subact" value="makenow" style="display: none;" />
<input type="hidden" name="id" value="<?php echo $_POST['id']; ?>" style="display: none;" />
<input type="hidden" name="permid" value="<?php echo $_POST['permid']; ?>" style="display: none;" />
<input type="submit" class="Button" value="Create Permissions" name="Apply_Changes" />
<input type="reset" value="Reset Form" class="Button" name="Reset_Form" />
</td></tr></table>
</form>
</td>
</tr>
<tr class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr>
</table>
</div>
<?php } if (isset($_POST['id']) && isset($_POST['permid']) && $_POST['subact'] == "makenow") {
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    @redirect("refresh", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
    $prequery = query("SELECT * FROM `".$Settings['sqltable']."catpermissions` WHERE `id`=%i LIMIT 1", array($_POST['permid']));
    $preresult = exec_query($prequery);
    $prenum = mysql_num_rows($preresult);
    if ($prenum == 0) {
        redirect("location", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false));
        @mysql_free_result($preresult);
        ob_clean();
        @header("Content-Type: text/plain; charset=".$Settings['charset']);
        gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
        @session_write_close();
        die();
    }
    if ($prenum >= 1) {
        $PermissionName = mysql_result($preresult, 0, "Name");
        @mysql_free_result($preresult);
    }
    $nextidnum = getnextid($Settings['sqltable'], "catpermissions");
    $query = query("INSERT INTO `".$Settings['sqltable']."catpermissions` (`PermissionID`, `Name`, `CategoryID`, `CanViewCategory`) VALUES\n".
    "(%i, '%s', %i, '%s')", array($_POST['permid'], $PermissionName, $_POST['id'], $_POST['CanViewCategory']));
    exec_query($query);
}
    ?>
<?php } $doupdate = false;
if (isset($_POST['id']) && $_POST['subact'] == "editnow") {
    $doupdate = true;
}
if (isset($_POST['id']) && isset($_POST['permid']) && $_POST['subact'] == "makenow") {
    $doupdate = true;
}
if ($_POST['act'] == "addcategory" && $_POST['update'] == "now" && $_GET['act'] == "addcategory") {
    $doupdate = true;
}
if ($_GET['act'] == "deletecategory" && $_POST['update'] == "now" && $_GET['act'] == "deletecategory") {
    $doupdate = true;
}
if ($_POST['act'] == "editcategory" && $_POST['update'] == "now" && $_GET['act'] == "editcategory" &&
    isset($_POST['id'])) {
    $doupdate = true;
}
if ($doupdate === true && $Error != "Yes") { ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<?php if (isset($_POST['id']) && $_POST['subact'] == "editnow") { ?>
<div style="text-align: center;">
	<br />The permission was edited successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if (isset($_POST['id']) && isset($_POST['permid']) && $_POST['subact'] == "makenow") { ?>
<div style="text-align: center;">
	<br />The permission was created successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if ($_POST['act'] == "addcategory" && $_POST['update'] == "now" && $_GET['act'] == "addcategory") { ?>
<div style="text-align: center;">
	<br />The category was created successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if ($_GET['act'] == "deletecategory" && $_POST['update'] == "now" && $_GET['act'] == "deletecategory") { ?>
<div style="text-align: center;">
	<br />The category was deleted successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } if ($_POST['act'] == "editcategory" && $_POST['update'] == "now" && $_GET['act'] == "editcategory" &&
    isset($_POST['id'])) { ?>
<div style="text-align: center;">
	<br />The category was edited successfully. <a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to go back. ^_^<br />&nbsp;
	</div>
<?php } ?>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } if ($_GET['act'] != null && $Error == "Yes") {
    @redirect("refresh", $basedir.url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin'], false), "4");
    $admincptitle = " ".$ThemeSet['TitleDivider']." Updating Settings";
    ?>
<div class="TableMenuBorder">
<?php if ($ThemeSet['TableStyle'] == "div") { ?>
<div class="TableMenuRow1">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a></div>
<?php } ?>
<table class="TableMenu" style="width: 100%;">
<?php if ($ThemeSet['TableStyle'] == "table") { ?>
<tr class="TableMenuRow1">
<td class="TableMenuColumn1"><span style="float: left;">
<?php echo $ThemeSet['TitleIcon']; ?><a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Updating Settings</a>
</span><span style="float: right;">&nbsp;</span></td>
</tr><?php } ?>
<tr id="ProfileTitle" class="TableMenuRow2">
<th class="TableMenuColumn2">Updating Settings</th>
</tr>
<tr class="TableMenuRow3" id="ProfileUpdate">
<td class="TableMenuColumn3">
<div style="text-align: center;">
	<br /><?php echo $errorstr; ?>
	<a href="<?php echo url_maker($exfile['admin'], $Settings['file_ext'], "act=".$_GET['act']."&menu=categories", $Settings['qstr'], $Settings['qsep'], $prexqstr['admin'], $exqstr['admin']); ?>">Click here</a> to back to admin cp.<br />&nbsp;
	</div>
</td></tr>
<tr id="ProfileTitleEnd" class="TableMenuRow4">
<td class="TableMenuColumn4">&nbsp;</td>
</tr></table></div>
<?php } ?>
</td></tr>
</table>
<div>&nbsp;</div>
