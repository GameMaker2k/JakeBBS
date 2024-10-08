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

    $FileInfo: rssfeed.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "rssfeed.php" || $File3Name == "/rssfeed.php") {
    require('index.php');
    exit();
}
if (!is_numeric($_GET['id'])) {
    $_GET['id'] = null;
}
$boardsname = htmlentities($Settings['board_name'], ENT_QUOTES, $Settings['charset']);
$boardsname = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $boardsname);
$_GET['feedtype'] = strtolower($_GET['feedtype']);
if ($_GET['feedtype'] != "rss" && $_GET['feedtype'] != "atom" &&
    $_GET['feedtype'] != "oldrss" && $_GET['feedtype'] != "opensearch") {
    $_GET['feedtype'] = "rss";
}
//$basepath = pathinfo($_SERVER['REQUEST_URI']);
/*if(dirname($_SERVER['REQUEST_URI'])!="."||
    dirname($_SERVER['REQUEST_URI'])!=null) {
$basedir = dirname($_SERVER['REQUEST_URI'])."/"; }*/
if (dirname($_SERVER['SCRIPT_NAME']) != "." ||
    dirname($_SERVER['SCRIPT_NAME']) != null) {
    $basedir = dirname($_SERVER['SCRIPT_NAME'])."/";
}
if ($basedir == null || $basedir == ".") {
    if (dirname($_SERVER['SCRIPT_NAME']) == "." ||
        dirname($_SERVER['SCRIPT_NAME']) == null) {
        $basedir = dirname($_SERVER['PHP_SELF'])."/";
    }
}
if ($basedir == "\/") {
    $basedir = "/";
}
$basedir = str_replace("//", "/", $basedir);
if ($Settings['fixpathinfo'] != "on" &&
    $Settings['fixpathinfo'] != "off" &&
    $Settings['fixpathinfo'] !== null) {
    $basedir = "/";
} $BaseURL = $basedir;
if (!isset($_SERVER['HTTPS'])) {
    $_SERVER['HTTPS'] = 'off';
}
if ($_SERVER['HTTPS'] == "on") {
    $prehost = "https://";
}
if ($_SERVER['HTTPS'] != "on") {
    $prehost = "http://";
}
if ($Settings['jakebbsurl'] == "localhost" || $Settings['jakebbsurl'] == null) {
    $BoardURL = $prehost.$_SERVER["HTTP_HOST"].$BaseURL;
}
if ($Settings['jakebbsurl'] != "localhost" && $Settings['jakebbsurl'] != null) {
    $BoardURL = $Settings['jakebbsurl'];
    if ($Settings['qstr'] != "/") {
        $AltBoardURL = $BoardURL;
    }
    if ($Settings['qstr'] == "/") {
        $AltBoardURL = preg_replace("/\/$/", "", $BoardURL);
    }
}
if ($_GET['id'] == null) {
    $_GET['id'] = "1";
}
if ($rssurlon == "on") {
    $BoardURL =  $rssurl;
}
$feedsname = basename($_SERVER['SCRIPT_NAME']);
if ($_SERVER['PATH_INFO'] != null) {
    $feedsname .= htmlentities($_SERVER['PATH_INFO'], ENT_QUOTES, $Settings['charset']);
}
if ($_SERVER['QUERY_STRING'] != null) {
    $feedsname .= "?".htmlentities($_SERVER['QUERY_STRING'], ENT_QUOTES, $Settings['charset']);
}
$checkfeedtype = "application/rss+xml";
if ($_GET['feedtype'] == "oldrss") {
    $checkfeedtype = "application/xml";
}
if ($_GET['feedtype'] == "rss") {
    $checkfeedtype = "application/rss+xml";
}
if ($_GET['feedtype'] == "atom") {
    $checkfeedtype = "application/atom+xml";
}
if ($_GET['feedtype'] == "opensearch") {
    $checkfeedtype = "application/opensearchdescription+xml";
}
if (stristr($_SERVER["HTTP_ACCEPT"], $checkfeedtype)) {
    @header("Content-Type: ".$checkfeedtype."; charset=".$Settings['charset']);
} else {
    if (stristr($_SERVER["HTTP_ACCEPT"], "application/xml")) {
        @header("Content-Type: application/xml; charset=".$Settings['charset']);
    } else {
        if (stristr($_SERVER["HTTP_USER_AGENT"], "FeedValidator")) {
            @header("Content-Type: application/xml; charset=".$Settings['charset']);
        } else {
            @header("Content-Type: text/xml; charset=".$Settings['charset']);
        }
    }
}
@header("Content-Language: en");
@header("Vary: Accept");
$prequery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `id`=%i", array($_GET['id']));
$preresult = exec_query($prequery);
$prenum = mysql_num_rows($preresult);
$prei = 0;
$ForumID = mysql_result($preresult, 0, "id");
$ForumName = mysql_result($preresult, 0, "Name");
$ForumName = htmlentities($ForumName, ENT_QUOTES, $Settings['charset']);
$ForumName = preg_replace("/&amp;#(x[a-f0-9]+|[0-9]+);/i", "&#$1;", $ForumName);
$ForumCatID = mysql_result($preresult, 0, "CategoryID");
$ForumType = mysql_result($preresult, 0, "ForumType");
$ForumType = strtolower($ForumType);
@mysql_free_result($preresult);
if ($PermissionInfo['CanViewForum'][$ForumID] == "no" ||
    $PermissionInfo['CanViewForum'][$ForumID] != "yes") {
    redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
if ($CatPermissionInfo['CanViewCategory'][$ForumCatID] == "no" ||
    $CatPermissionInfo['CanViewCategory'][$ForumCatID] != "yes") {
    redirect("location", $basedir.url_maker($exfile['index'], $Settings['file_ext'], "act=view", $Settings['qstr'], $Settings['qsep'], $prexqstr['index'], $exqstr['index'], false));
    ob_clean();
    @header("Content-Type: text/plain; charset=".$Settings['charset']);
    gzip_page($Settings['use_gzip'], $GZipEncode['Type']);
    @session_write_close();
    die();
}
$gltf = array(null);
$gltf[0] = $ForumID;
if ($ForumType == "subforum") {
    $apcquery = query("SELECT * FROM `".$Settings['sqltable']."forums` WHERE `ShowForum`='yes' AND `InSubForum`=%i ORDER BY `id`", array($ForumID));
    $apcresult = exec_query($apcquery);
    $apcnum = mysql_num_rows($apcresult);
    $apci = 0;
    $apcl = 1;
    if ($apcnum >= 1) {
        while ($apci < $apcnum) {
            $SubsForumID = mysql_result($apcresult, $apci, "id");
            if (isset($PermissionInfo['CanViewForum'][$SubsForumID]) &&
                $PermissionInfo['CanViewForum'][$SubsForumID] == "yes") {
                $gltf[$apcl] = $SubsForumID;
                ++$apcl;
            }
            ++$apci;
        }
        @mysql_free_result($apcresult);
    }
}
$Atom = null;
$RSS = null;
$PreRSS = null;
$gltnum = count($gltf);
$glti = 0;
while ($glti < $gltnum) {
    $query = query("SELECT * FROM `".$Settings['sqltable']."topics` WHERE `ForumID`=%i ORDER BY `Pinned` DESC, `LastUpdate` DESC LIMIT %i", array($gltf[$glti],$Settings['max_topics']));
    $result = exec_query($query);
    $num = mysql_num_rows($result);
    $i = 0;
    while ($i < $num) {
        $TopicID = mysql_result($result, $i, "id");
        $ForumID = mysql_result($result, $i, "ForumID");
        $CategoryID = mysql_result($result, $i, "CategoryID");
        $pquery = query("SELECT * FROM `".$Settings['sqltable']."posts` WHERE `TopicID`=%i ORDER BY `TimeStamp` ASC LIMIT %i", array($TopicID,1));
        $presult = exec_query($pquery);
        $pnum = mysql_num_rows($presult);
        $MyDescription = mysql_result($presult, 0, "Post");
        $MyDescription = preg_replace("/\<br\>/", "<br />\n", nl2br($MyDescription));
        $MyDescription = text2icons($MyDescription, $Settings['sqltable']);
        $UsersID = mysql_result($result, $i, "UserID");
        $GuestsName = mysql_result($result, $i, "GuestName");
        $requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($UsersID));
        $reresult = exec_query($requery);
        $renum = mysql_num_rows($reresult);
        if ($renum < 1) {
            $UsersID = -1;
            $requery = query("SELECT * FROM `".$Settings['sqltable']."members` WHERE `id`=%i LIMIT 1", array($UsersID));
            $reresult = exec_query($requery);
            $renum = mysql_num_rows($reresult);
        }
        $UsersName = mysql_result($reresult, 0, "Name");
        $UsersGroupID = mysql_result($reresult, 0, "GroupID");
        if ($UsersName == "Guest") {
            $UsersName = $GuestsName;
            if ($UsersName == null) {
                $UsersName = "Guest";
            }
        }
        @mysql_free_result($reresult);
        $gquery = query("SELECT * FROM `".$Settings['sqltable']."groups` WHERE `id`=%i LIMIT 1", array($UsersGroupID));
        $gresult = exec_query($gquery);
        $UsersGroup = mysql_result($gresult, 0, "Name");
        $GroupNamePrefix = mysql_result($gresult, 0, "NamePrefix");
        $GroupNameSuffix = mysql_result($gresult, 0, "NameSuffix");
        @mysql_free_result($gresult);
        if (isset($GroupNamePrefix) && $GroupNamePrefix != null) {
            $UsersName = $GroupNamePrefix.$UsersName;
        }
        if (isset($GroupNameSuffix) && $GroupNameSuffix != null) {
            $UsersName = $UsersName.$GroupNameSuffix;
        }
        $TheTime = mysql_result($result, $i, "TimeStamp");
        $AtomTime = GMTimeChange("Y-m-d\TH:i:s\Z", $TheTime, 0);
        //$OldRSSTime=GMTimeChange("Y-m-d\TH:i:s+0:00",$TheTime,0);
        $OldRSSTime = $AtomTime;
        $TheTime = GMTimeChange("D, j M Y G:i:s \G\M\T", $TheTime, 0);
        $TopicName = mysql_result($result, $i, "TopicName");
        $ForumDescription = mysql_result($result, $i, "Description");
        if (isset($PermissionInfo['CanViewForum'][$ForumID]) &&
            $PermissionInfo['CanViewForum'][$ForumID] == "yes" &&
            isset($CatPermissionInfo['CanViewCategory'][$CategoryID]) &&
            $CatPermissionInfo['CanViewCategory'][$CategoryID] == "yes") {
            if ($_GET['feedtype'] == "atom") {
                $CDataDescription = "<![CDATA[\n".$MyDescription."\n]]>";
                $Atom .= '<entry>'."\n".'<title>'.$TopicName.'</title>'."\n".'<summary>'.$CDataDescription.'</summary>'."\n".'<link rel="alternate" href="'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'" />'."\n".'<id>'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'</id>'."\n".'<author>'."\n".'<name>'.$UsersName.'</name>'."\n".'</author>'."\n".'<updated>'.$AtomTime.'</updated>'."\n".'</entry>'."\n";
            }
            if ($_GET['feedtype'] == "oldrss") {
                $CDataDescription = "<![CDATA[\n".$MyDescription."\n]]>";
                $PreRSS .= '      <rdf:li rdf:resource="'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'" />'."\n";
                $RSS .= '<item rdf:about="'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'">'."\n".'<title>'.$TopicName.'</title>'."\n".'<description>'.$CDataDescription.'</description>'."\n".'<dc:publisher>'.$UsersName.'</dc:publisher>'."\n".'<dc:creator>'.$UsersName.'</dc:creator>'."\n".'<dc:date>'.$OldRSSTime.'</dc:date>'."\n".'</item>'."\n";
            }
            if ($_GET['feedtype'] == "rss") {
                $CDataDescription = "<![CDATA[\n".$MyDescription."\n]]>";
                $RSS .= '<item>'."\n".'<pubDate>'.$TheTime.'</pubDate>'."\n".'<author>'.$UsersName.'</author>'."\n".'<title>'.$TopicName.'</title>'."\n".'<description>'.$CDataDescription.'</description>'."\n".'<link>'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'</link>'."\n".'<guid>'.$BoardURL.url_maker($exfilerss['topic'], $Settings['file_ext'], "act=view&id=".$TopicID."&page=1", $Settings['qstr'], $Settings['qsep'], $prexqstrrss['topic'], $exqstrrss['topic']).'</guid>'."\n".'</item>'."\n";
            }
        }
        ++$i;
    } @mysql_free_result($result);
    @mysql_free_result($result);
    ++$glti;
}
xml_doc_start("1.0", $Settings['charset']);
if ($Settings['showverinfo'] == "on") { ?>
<!-- generator="<?php echo $VerInfo['JakeBBS_Ver_Show']; ?>" -->
<?php } if ($Settings['showverinfo'] != "on") { ?>
<!-- generator="<?php echo $JakeBBS; ?>" -->
<?php } echo "\n";
if ($_GET['feedtype'] == "oldrss") { ?>
<rdf:RDF 
 xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
 xmlns="http://purl.org/rss/1.0/"
 xmlns:dc="http://purl.org/dc/elements/1.1/"
>
<channel rdf:about="<?php echo $BoardURL.$feedsname; ?>">
 <title><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Viewing Forum <?php echo $ForumName; ?></title>
  <link><?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?></link>
  <description>RSS Feed of the Topics in Forum <?php echo $ForumName; ?></description>
  <image rdf:resource="<?php echo $AltBoardURL.$SettDir['inc']; ?>rss.gif" />
 
  <items>
    <rdf:Seq>
<?php echo $PreRSS; ?>
    </rdf:Seq>
  </items>
</channel>

<image rdf:about="<?php echo $AltBoardURL.$SettDir['inc']; ?>rss.gif">
  <title><?php echo $boardsname; ?></title>
  <link><?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?></link>
  <url><?php echo $AltBoardURL.$SettDir['inc']; ?>rss.gif</url>
</image>
<!-- Renee Sabonis ^_^ -->
<?php echo "\n".$RSS."\n"; ?></rdf:RDF>
<?php } if ($_GET['feedtype'] == "rss") { ?>
<rss version="2.0">
<channel>
   <title><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Viewing Forum <?php echo $ForumName; ?></title>
   <description>RSS Feed of the Topics in Forum <?php echo $ForumName; ?></description>
   <link><?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?></link>
   <language>en</language>
   <?php if ($Settings['showverinfo'] == "on") { ?>
   <generator><?php echo $VerInfo['JakeBBS_Ver_Show']; ?></generator>
   <?php } if ($Settings['showverinfo'] != "on") { ?>
   <generator><?php echo $JakeBBS; ?></generator>
   <?php } echo "\n"; ?>
   <copyright><?php echo $SettInfo['Author']; ?></copyright>
   <ttl>120</ttl>
   <image>
	<url><?php echo $AltBoardURL.$SettDir['inc']; ?>rss.gif</url>
	<title><?php echo $boardsname; ?></title>
	<link><?php echo $AltBoardURL; ?></link>
   </image>
   <!-- Renee Sabonis ^_^ -->
 <?php echo "\n".$RSS."\n"; ?></channel>
</rss>
<?php } if ($_GET['feedtype'] == "atom") { ?>
<feed xmlns="http://www.w3.org/2005/Atom">
  <title><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Viewing Forum <?php echo $ForumName; ?></title>
   <subtitle>Atom Feed of the Topics in Forum <?php echo $ForumName; ?></subtitle>
   <link rel="self" href="<?php echo $BoardURL.$feedsname; ?>" />
   <id><?php echo $BoardURL.url_maker($exfile[$ForumType], $Settings['file_ext'], "act=view&id=".$ForumID, $Settings['qstr'], $Settings['qsep'], $prexqstr[$ForumType], $exqstr[$ForumType]); ?></id>
   <updated><?php echo gmdate("Y-m-d\TH:i:s\Z"); ?></updated>
   <?php if ($Settings['showverinfo'] == "on") { ?>
   <generator><?php echo $VerInfo['JakeBBS_Ver_Show']; ?></generator>
   <?php } if ($Settings['showverinfo'] != "on") { ?>
   <generator><?php echo $JakeBBS; ?></generator>
   <?php } ?>
  <icon><?php echo $AltBoardURL.$SettDir['inc']; ?>rss.gif</icon>
  <!-- Renee Sabonis ^_^ -->
 <?php echo "\n".$Atom."\n"; ?>
</feed>
<?php } if ($_GET['feedtype'] == "opensearch") { ?>
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/">
<ShortName><?php echo $boardsname." ".$ThemeSet['TitleDivider']; ?> Search</ShortName>
<Description><?php echo $SettInfo['Description']; ?></Description>
<InputEncoding><?php echo $Settings['charset']; ?></InputEncoding>
<Image width="16" height="16" type="image/x-icon"><?php echo $AltBoardURL.$ThemeSet['FavIcon']; ?></Image>
<Url type="text/html" method="POST" template="<?php echo $BoardURL.url_maker("search", $Settings['file_ext'], null, "search", "search"); ?>">
  <Param name="act" value="topics"/>
  <Param name="search" value="{searchTerms}"/>
  <Param name="type" value="wildcard"/>
  <Param name="page" value="1"/>
</Url>
  <!-- Renee Sabonis ^_^ -->
<SearchForm><?php echo $BoardURL.url_maker("search", $Settings['file_ext'], null, "search", "search"); ?></SearchForm>
</OpenSearchDescription>
<?php }
function execution_time($starttime)
{
    list($uetime, $etime) = explode(" ", microtime());
    $endtime = $uetime + $etime;
    return bcsub($endtime, $starttime, 4);
}
echo "<!-- execution_time=\"".execution_time($starttime)."\" -->";
gzip_page($Settings['use_gzip'], $GZipEncode['Type']); ?>
