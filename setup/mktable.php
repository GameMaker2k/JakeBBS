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

    $FileInfo: mktable.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "mktable.php" || $File3Name == "/mktable.php") {
    require('index.php');
    exit();
}
if (!isset($SetupDir['setup'])) {
    $SetupDir['setup'] = "setup/";
}
if (!isset($SetupDir['convert'])) {
    $SetupDir['convert'] = "setup/convert/";
}
$query = query("ALTER DATABASE `".$_POST['DatabaseName']."` DEFAULT CHARACTER SET ".$SQLCharset." COLLATE ".$SQLCollate.";", array(null));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."categories` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `OrderID` int(15) NOT NULL default '0',\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ShowCategory` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CategoryType` varchar(15) collate ".$SQLCollate." NOT NULL default '',\n".
"  `SubShowForums` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `InSubCategory` int(15) NOT NULL default '0',\n".
"  `PostCountView` int(15) NOT NULL default '0',\n".
"  `KarmaCountView` int(15) NOT NULL default '0',\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."categories` (`OrderID`, `Name`, `ShowCategory`, `CategoryType`, `SubShowForums`, `InSubCategory`, `PostCountView`, `KarmaCountView`, `Description`)\n".
"VALUES (1, 'A Test Category', 'yes', 'category', 'yes', 0, 0, 0, 'A test category that may be removed at any time.')", array(null));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."catpermissions` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `PermissionID` int(15) NOT NULL default '0',\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CategoryID` int(15) NOT NULL default '0',\n".
"  `CanViewCategory` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."catpermissions` (`PermissionID`, `Name`, `CategoryID`, `CanViewCategory`) VALUES\n".
"(1, 'Admin', 1, 'yes'),\n".
"(2, 'Moderator', 1, 'yes'),\n".
"(3, 'Member', 1, 'yes'),\n".
"(4, 'Guest', 1, 'yes'),\n".
"(5, 'Banned', 1, 'no'),\n".
"(6, 'Validate', 1, 'yes');", array(null));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."events` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `UserID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `EventName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `EventText` text collate ".$SQLCollate." NOT NULL,\n".
"  `TimeStamp` int(15) NOT NULL default '0',\n".
"  `TimeStampEnd` int(15) NOT NULL default '0',\n".
"  `EventMonth` int(5) NOT NULL default '0',\n".
"  `EventMonthEnd` int(5) NOT NULL default '0',\n".
"  `EventDay` int(5) NOT NULL default '0',\n".
"  `EventDayEnd` int(5) NOT NULL default '0',\n".
"  `EventYear` int(5) NOT NULL default '0',\n".
"  `EventYearEnd` int(5) NOT NULL default '0',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."events` (`UserID`, `GuestName`, `EventName`, `EventText`, `TimeStamp`, `TimeStampEnd`, `EventMonth`, `EventMonthEnd`, `EventDay`, `EventDayEnd`, `EventYear`, `EventYearEnd`) VALUES\n".
"(-1, '".$JakeBBS_Author."', 'Opening', 'This is the day the Board was made. ^_^', %i, %i, %i, %i, %i, %i, %i, %i)", array($YourDate,$YourDateEnd,$EventMonth,$EventMonthEnd,$EventDay,$EventDayEnd,$EventYear,$EventYearEnd));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."forums` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `CategoryID` int(15) NOT NULL default '0',\n".
"  `OrderID` int(15) NOT NULL default '0',\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ShowForum` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ForumType` varchar(15) collate ".$SQLCollate." NOT NULL default '',\n".
"  `InSubForum` int(15) NOT NULL default '0',\n".
"  `RedirectURL` text collate ".$SQLCollate." NOT NULL,\n".
"  `Redirects` int(15) NOT NULL default '0',\n".
"  `NumViews` int(15) NOT NULL default '0',\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  `PostCountAdd` varchar(15) character set ".$SQLCharset." NOT NULL default '',\n".
"  `PostCountView` int(15) NOT NULL default '0',\n".
"  `KarmaCountView` int(15) NOT NULL default '0',\n".
"  `CanHaveTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `HotTopicPosts` int(15) NOT NULL default '0',\n".
"  `NumPosts` int(15) NOT NULL default '0',\n".
"  `NumTopics` int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."forums` (`CategoryID`, `OrderID`, `Name`, `ShowForum`, `ForumType`, `InSubForum`, `RedirectURL`, `Redirects`, `NumViews`, `Description`, `PostCountAdd`, `PostCountView`, `KarmaCountView`, `CanHaveTopics`, `HotTopicPosts`, `NumPosts`, `NumTopics`) VALUES\n".
"(1, 1, 'A Test Forum', 'yes', 'forum', 0, 'http://', 0, 0, 'A test forum that may be removed at any time.', 'off', 0, 0, 'yes', 15, 1, 1)", array(null));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."groups` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `PermissionID` int(15) NOT NULL default '0',\n".
"  `NamePrefix` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `NameSuffix` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanViewBoard` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanViewOffLine` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditProfile` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanAddEvents` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanPM` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanSearch` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `FloodControl` int(5) NOT NULL default '0',\n".
"  `SearchFlood` int(5) NOT NULL default '0',\n".
"  `PromoteTo` int(15) NOT NULL default '0',\n".
"  `PromotePosts` int(15) NOT NULL default '0',\n".
"  `PromoteKarma` int(15) NOT NULL default '0',\n".
"  `HasModCP` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `HasAdminCP` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ViewDBInfo` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`),\n".
"  UNIQUE KEY `Name` (`Name`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."groups` (`Name`, `PermissionID`, `NamePrefix`, `NameSuffix`, `CanViewBoard`, `CanViewOffLine`, `CanEditProfile`, `CanAddEvents`, `CanPM`, `CanSearch`, `FloodControl`, `SearchFlood`, `PromoteTo`, `PromotePosts`, `PromoteKarma`, `HasModCP`, `HasAdminCP`, `ViewDBInfo`) VALUES\n".
"('Admin', 1, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 'yes', 'yes', 'yes'),\n".
"('Moderator', 2, '', '', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 'yes', 'no', 'no'),\n".
"('Member', 3, '', '', 'yes', 'no', 'yes', 'yes', 'yes', 'yes', 30, 30, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Guest', 4, '', '', 'yes', 'no', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Banned', 5, '', '', 'no', 'no', 'no', 'no', 'no', 'no', 30, 30, 0, 0, 0, 'no', 'no', 'no'),\n".
"('Validate', 6, '', '', 'yes', 'no', 'yes', 'no', 'no', 'yes', 30, 30, 0, 0, 0, 'no', 'no', 'no');", array(null));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."members` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Password` varchar(250) collate ".$SQLCollate." NOT NULL default '',\n".
"  `HashType` varchar(50) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Email` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `GroupID` int(15) NOT NULL default '0',\n".
"  `Validated` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  `HiddenMember` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  `WarnLevel` int(10) NOT NULL default '0',\n".
"  `Interests` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Title` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Joined` int(15) NOT NULL default '0',\n".
"  `LastActive` int(15) NOT NULL default '0',\n".
"  `LastPostTime` int(15) NOT NULL default '0',\n".
"  `BanTime` int(15) NOT NULL default '0',\n".
"  `BirthDay` int(5) NOT NULL default '0',\n".
"  `BirthMonth` int(5) NOT NULL default '0',\n".
"  `BirthYear` int(5) NOT NULL default '0',\n".
"  `Signature` text collate ".$SQLCollate." NOT NULL,\n".
"  `Notes` text collate ".$SQLCollate." NOT NULL,\n".
"  `Avatar` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `AvatarSize` varchar(10) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Website` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Gender` varchar(15) collate ".$SQLCollate." NOT NULL default '',\n".
"  `PostCount` int(15) NOT NULL default '0',\n".
"  `Karma` int(15) NOT NULL default '0',\n".
"  `KarmaUpdate` int(15) NOT NULL default '0',\n".
"  `RepliesPerPage` int(5) NOT NULL default '0',\n".
"  `TopicsPerPage` int(5) NOT NULL default '0',\n".
"  `MessagesPerPage` int(5) NOT NULL default '0',\n".
"  `TimeZone` varchar(5) collate ".$SQLCollate." NOT NULL default '0',\n".
"  `DST` varchar(5) collate ".$SQLCollate." NOT NULL default '0',\n".
"  `UseTheme` varchar(26) collate ".$SQLCollate." NOT NULL default '0',\n".
"  `IP` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Salt` varchar(50) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`),\n".
"  UNIQUE KEY `Name` (`Name`),\n".
"  UNIQUE KEY `Email` (`Email`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."members` (`id`, `Name`, `Password`, `HashType`, `Email`, `GroupID`, `Validated`, `HiddenMember`, `WarnLevel`, `Interests`, `Title`, `Joined`, `LastActive`, `LastPostTime`, `BanTime`, `BirthDay`, `BirthMonth`, `BirthYear`, `Signature`, `Notes`, `Avatar`, `AvatarSize`, `Website`, `Gender`, `PostCount`, `Karma`, `KarmaUpdate`, `RepliesPerPage`, `TopicsPerPage`, `MessagesPerPage`, `TimeZone`, `DST`, `UseTheme`, `IP`, `Salt`) VALUES\n".
"(-1, 'Guest', '%s', '".$JakeBBSHashType."', '%s', 4, 'no', 'yes', 0, 'Guest Account', 'Guest', %i, %i, '0', '0', '0', '0', '0', '[B]Test[/B] :)', 'Your Notes', 'http://', '100x100', '%s', 'UnKnow', 1, 0, 0, 10, 10, 10, '%s', '%s', 'JakeBBS', '127.0.0.1', '%s'),\n".
"(1, '%s', '%s', '".$JakeBBSHashType."', '%s', 1, 'yes', 'no', 0, '%s', 'Admin', %i, %i, '0', '0', '0', '0', '0', '%s', 'Your Notes', '%s', '100x100', '%s', 'UnKnow', 0, 0, 0, 10, 10, 10, '%s', '%s', 'JakeBBS', '%s', '%s');", array($GuestPassword,$GEmail,$YourDate,$YourDate,$YourWebsite,$AdminTime,$AdminDST,$GSalt,$_POST['AdminUser'],$NewPassword,$_POST['AdminEmail'],$Interests,$YourDate,$YourDate,$NewSignature,$Avatar,$YourWebsite,$AdminTime,$AdminDST,$UserIP,$YourSalt));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."messenger` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `SenderID` int(15) NOT NULL default '0',\n".
"  `ReciverID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `MessageTitle` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `MessageText` text collate ".$SQLCollate." NOT NULL,\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  `DateSend` int(15) NOT NULL default '0',\n".
"  `Read` int(5) NOT NULL default '0',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."messenger` (`SenderID`, `ReciverID`, `GuestName`, `MessageTitle`, `MessageText`, `Description`, `DateSend`, `Read`) VALUES\n".
"(-1, 1, '".$JakeBBS_Author."', 'Test', 'Hello Welcome to your board.\r\nThis is a Test PM. :P ', 'Hello Welcome', %i, 0)", array($YourDate));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."permissions` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `PermissionID` int(15) NOT NULL default '0',\n".
"  `Name` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ForumID` int(15) NOT NULL default '0',\n".
"  `CanViewForum` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanMakeTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanMakeReplys` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanMakeReplysCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditTopicsCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditReplys` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanEditReplysCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDeleteTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDeleteTopicsCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDeleteReplys` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDeleteReplysCT` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanCloseTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanPinTopics` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanDohtml` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanUseBBags` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CanModForum` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."permissions` (`PermissionID`, `Name`, `ForumID`, `CanViewForum`, `CanMakeTopics`, `CanMakeReplys`, `CanMakeReplysCT`, `CanEditTopics`, `CanEditTopicsCT`, `CanEditReplys`, `CanEditReplysCT`, `CanDeleteTopics`, `CanDeleteTopicsCT`, `CanDeleteReplys`, `CanDeleteReplysCT`, `CanCloseTopics`, `CanPinTopics`, `CanDohtml`, `CanUseBBags`, `CanModForum`) VALUES\n".
"(1, 'Admin', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),\n".
"(2, 'Moderator', 1, 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes', 'yes'),\n".
"(3, 'Member', 1, 'yes', 'yes', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'yes', 'no', 'no', 'no', 'no', 'yes', 'no'),\n".
"(4, 'Guest', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
"(5, 'Banned', 1, 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no'),\n".
"(6, 'Validate', 1, 'yes', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no', 'no');", array(null));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."posts` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `TopicID` int(15) NOT NULL default '0',\n".
"  `ForumID` int(15) NOT NULL default '0',\n".
"  `CategoryID` int(15) NOT NULL default '0',\n".
"  `UserID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `TimeStamp` int(15) NOT NULL default '0',\n".
"  `LastUpdate` int(15) NOT NULL default '0',\n".
"  `EditUser` int(15) NOT NULL default '0',\n".
"  `EditUserName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Post` text collate ".$SQLCollate." NOT NULL,\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  `IP` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  `EditIP` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."posts` (`TopicID`, `ForumID`, `CategoryID`, `UserID`, `GuestName`, `TimeStamp`, `LastUpdate`, `EditUser`, `EditUserName`, `Post`, `Description`, `IP`, `EditIP`) VALUES\n".
"(1, 1, 1, -1, '".$JakeBBS_Author."', %i, %i, 1, '".$_POST['AdminUser']."', 'Welcome to your new Internet Discussion Board! :) ', 'Welcome %s', '127.0.0.1', '127.0.0.1')", array($YourDate,$YourEditDate,$_POST['AdminUser']));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."restrictedwords` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `Word` text collate ".$SQLCollate." NOT NULL,\n".
"  `RestrictedUserName` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `RestrictedTopicName` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `RestrictedEventName` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `RestrictedMessageName` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `CaseInsensitive` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `WholeWord` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."sessions` (\n".
"  `session_id` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `session_data` text collate ".$SQLCollate." NOT NULL,\n".
"  `expires` int(15) NOT NULL default '0',\n".
"  PRIMARY KEY  (`session_id`)\n".
") ENGINE=MyISAM DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."smileys` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `FileName` text collate ".$SQLCollate." NOT NULL,\n".
"  `SmileName` text collate ".$SQLCollate." NOT NULL,\n".
"  `SmileText` text collate ".$SQLCollate." NOT NULL,\n".
"  `Directory` text collate ".$SQLCollate." NOT NULL,\n".
"  `Show` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `ReplaceCI` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."smileys` (`FileName`, `SmileName`, `SmileText`, `Directory`, `Show`, `ReplaceCI`) VALUES\n".
"('smile.gif', 'Happy', ':)', 'smileys/', 'yes', 'no'),\n".
"('tongue.gif', 'Tongue', ':P', 'smileys/', 'yes', 'yes'),\n".
"('tongue2.gif', 'Tongue', ':tongue:', 'smileys/', 'no', 'yes'),\n".
"('sweat.gif', 'Sweat', ':sweat:', 'smileys/', 'yes', 'yes'),\n".
"('sweat.gif', 'Sweat', '^_^', 'smileys/', 'no', 'yes'),\n".
"('laugh.gif', 'lol', ':lol:', 'smileys/', 'yes', 'yes'),\n".
"('cool.gif', 'Cool', 'B)', 'smileys/', 'yes', 'no'),\n".
"('sleep.gif', 'Sleep', '-_-', 'smileys/', 'yes', 'no'),\n".
"('sad.gif', 'Sad', ':(', 'smileys/', 'yes', 'no'),\n".
"('angry.gif', 'Angry', ':angry:', 'smileys/', 'yes', 'yes'),\n".
"('huh.gif', 'huh', ':huh:', 'smileys/', 'yes', 'yes'),\n".
"('ohmy.gif', 'ohmy', ':o', 'smileys/', 'yes', 'yes'),\n".
"('hmm.gif', 'hmm', ':unsure:', 'smileys/', 'yes', 'yes'),\n".
"('mad.gif', 'Mad', ':mad:', 'smileys/', 'yes', 'yes'),\n".
"('wub.gif', 'Wub', ':wub:', 'smileys/', 'yes', 'yes'),\n".
"('x.gif', 'X', ':x:', 'smileys/', 'yes', 'yes');", array(null));
exec_query($query);
/*
$query=query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."tagboard` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `UserID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `TimeStamp` int(15) NOT NULL default '0',\n".
"  `Post` text collate ".$SQLCollate." NOT NULL,\n".
"  `IP` varchar(20) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."tagboard` VALUES (1,-1,'".$JakeBBS_Author."',".$YourDate.",'Welcome to Your New Tag Board. ^_^','127.0.0.1'), array(null));
exec_query($query);
*/
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."topics` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `ForumID` int(15) NOT NULL default '0',\n".
"  `CategoryID` int(15) NOT NULL default '0',\n".
"  `OldForumID` int(15) NOT NULL default '0',\n".
"  `OldCategoryID` int(15) NOT NULL default '0',\n".
"  `UserID` int(15) NOT NULL default '0',\n".
"  `GuestName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `TimeStamp` int(15) NOT NULL default '0',\n".
"  `LastUpdate` int(15) NOT NULL default '0',\n".
"  `TopicName` varchar(150) collate ".$SQLCollate." NOT NULL default '',\n".
"  `Description` text collate ".$SQLCollate." NOT NULL,\n".
"  `NumReply` int(15) NOT NULL default '0',\n".
"  `NumViews` int(15) NOT NULL default '0',\n".
"  `Pinned` int(5) NOT NULL default '0',\n".
"  `Closed` int(5) NOT NULL default '0',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
$query = query("INSERT INTO `".$_POST['tableprefix']."topics` (`ForumID`, `CategoryID`, `OldForumID`, `OldCategoryID`, `UserID`, `GuestName`, `TimeStamp`, `LastUpdate`, `TopicName`, `Description`, `NumReply`, `NumViews`, `Pinned`, `Closed`) VALUES\n".
"(1, 1, 1, 1, -1, '".$JakeBBS_Author."', %i, %i, 'Welcome', 'Welcome %s', 0, 0, 1, 1)", array($YourDate,$YourDate,$_POST['AdminUser']));
exec_query($query);
$query = query("CREATE TABLE IF NOT EXISTS `".$_POST['tableprefix']."wordfilter` (\n".
"  `id` int(15) NOT NULL auto_increment,\n".
"  `Filter` text collate ".$SQLCollate." NOT NULL,\n".
"  `Replace` text collate ".$SQLCollate." NOT NULL,\n".
"  `CaseInsensitive` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  `WholeWord` varchar(5) collate ".$SQLCollate." NOT NULL default '',\n".
"  PRIMARY KEY  (`id`)\n".
") ENGINE=MyISAM  DEFAULT CHARSET=".$SQLCharset." COLLATE=".$SQLCollate.";", array(null));
exec_query($query);
