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

    $FileInfo: function.php - Last Update: 07/01/1867 Ver 142 Author: Jake $
*/
$File3Name = basename($_SERVER['SCRIPT_NAME']);
if ($File3Name == "function.php" || $File3Name == "/function.php") {
    require('index.php');
    exit();
}
require_once($SettDir['misc'].'sha256.php');
require_once($SettDir['misc'].'functions.php');
/* Change Some PHP Settings Fix the & to &amp;
if($Settings['use_iniset']==true&&$Settings['qstr']!="/") {
@ini_set("arg_separator.output",htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset']));
@ini_set("arg_separator.input",$Settings['qstr']);
@ini_set("arg_separator",htmlentities($Settings['qstr'], ENT_QUOTES, $Settings['charset'])); }
//$basepath = pathinfo($_SERVER['REQUEST_URI']);
if(dirname($_SERVER['REQUEST_URI'])!="."||
    dirname($_SERVER['REQUEST_URI'])!=null) {
$basedir = dirname($_SERVER['REQUEST_URI'])."/"; }*/
// Get the base dir name
/*if(dirname($_SERVER['SCRIPT_NAME'])!="."||
    dirname($_SERVER['SCRIPT_NAME'])!=null) {
$basedir = dirname($_SERVER['SCRIPT_NAME'])."/"; }
if($basedir==null||$basedir==".") {
if(dirname($_SERVER['SCRIPT_NAME'])=="."||
    dirname($_SERVER['SCRIPT_NAME'])==null) {
$basedir = dirname($_SERVER['PHP_SELF'])."/"; } }
if($basedir=="\/") { $basedir="/"; }
$basedir = str_replace("//", "/", $basedir);*/
if ($Settings['qstr'] != "/") {
    $JakeBBSURLCHK = $Settings['jakebbsurl'];
}
if ($Settings['qstr'] == "/") {
    $JakeBBSURLCHK = preg_replace("/\/$/", "", $Settings['jakebbsurl']);
}
$basecheck = parse_url($JakeBBSURLCHK);
$basedir = $basecheck['path'];
$cbasedir = $basedir;
if ($Settings['fixbasedir'] != null && $Settings['fixbasedir'] != "off") {
    $basedir = $Settings['fixbasedir'];
}
if ($Settings['fixcookiedir'] != null && $Settings['fixcookiedir'] != "") {
    $cbasedir = $Settings['fixcookiedir'];
}
$BaseURL = $basedir;
// Get our Host Name and Referer URL's Host Name
if (!isset($_SERVER['HTTP_REFERER'])) {
    $_SERVER['HTTP_REFERER'] = null;
}
$REFERERurl = parse_url($_SERVER['HTTP_REFERER']);
if (!isset($REFERERurl['host'])) {
    $REFERERurl['host'] = null;
}
$URL['REFERER'] = $REFERERurl['host'];
$URL['HOST'] = $basecheck['host'];
$REFERERurl = null;
// http://www.ajaxray.com/blog/2008/02/06/php-uuid-generator-function/
/**
  * Generates an UUID
  *
  * @author     Anis uddin Ahmad <admin@ajaxray.com>
  * @param      string  an optional prefix
  * @return     string  the formatted uuid
  */
function uuid($useold = false, $more_entropy = false, $mtrand = false, $hash = 'sha1', $prefix = '')
{
    if ($useold === true && $mtrand === false) {
        $chars = uniqid($hash(rand()), $more_entropy);
    }
    if ($useold === false && $mtrand === false) {
        $chars = $hash(uniqid(rand(), $more_entropy));
    }
    if ($useold === true && $mtrand === true) {
        $chars = uniqid($hash(mt_rand()), $more_entropy);
    }
    if ($useold === false && $mtrand === true) {
        $chars = $hash(uniqid(mt_rand(), $more_entropy));
    }
    $uuid  = substr($chars, 0, 8) . '-';
    $uuid .= substr($chars, 8, 4) . '-';
    $uuid .= substr($chars, 12, 4) . '-';
    $uuid .= substr($chars, 16, 4) . '-';
    $uuid .= substr($chars, 20, 12);
    if (isset($prefix)) {
        return $prefix . $uuid;
    }
    if (!isset($prefix)) {
        return $uuid;
    }
}
// unserialize sessions variables
function unserialize_session($data)
{
    $vars = preg_split(
        '/([a-zA-Z_\x7f-\xff][a-zA-Z0-9_\x7f-\xff^|]*)\|/',
        $data,
        -1,
        PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE
    );
    $i = 0;
    for ($i = 0; isset($vars[$i]); $i++) {
        $l = $i + 1;
        if (!isset($vars[$i])) {
            $vars[$i] = null;
        }
        if (!isset($vars[$l])) {
            $vars[$l] = null;
        }
        $result[$vars[$i]] = unserialize($vars[$l]);
        $i++;
    }
    if (!isset($result)) {
        $result = null;
    }
    return $result;
}
// Make the Query String if we are not useing &=
function qstring($qstr = ";", $qsep = "=")
{
    $_GET = null;
    $_GET = array();
    if (!isset($_SERVER['QUERY_STRING'])) {
        $_SERVER['QUERY_STRING'] = getenv('QUERY_STRING');
    }
    @ini_get("arg_separator.input", $qstr);
    $_SERVER['QUERY_STRING'] = urldecode($_SERVER['QUERY_STRING']);
    $preqs = explode($qstr, $_SERVER["QUERY_STRING"]);
    $qsnum = count($preqs);
    $qsi = 0;
    while ($qsi < $qsnum) {
        $preqst = explode($qsep, $preqs[$qsi], 2);
        $fix1 = array(" ",'$');
        $fix2  = array("_","_");
        $preqst[0] = str_replace($fix1, $fix2, $preqst[0]);
        $preqst[0] = killbadvars($preqst[0]);
        if ($preqst[0] != null) {
            $_GET[$preqst[0]] = $preqst[1];
        }
        ++$qsi;
    } return true;
}
if ($_SERVER['PATH_INFO'] == null) {
    if (@getenv('PATH_INFO') != null && @getenv('PATH_INFO') != "1") {
        $_SERVER['PATH_INFO'] = @getenv('PATH_INFO');
    }
    if (@getenv('PATH_INFO') == null) {
        $myscript = $_SERVER["SCRIPT_NAME"];
        $myphpath = $_SERVER["PHP_SELF"];
        $mypathinfo = str_replace($myscript, "", $myphpath);
        @putenv("PATH_INFO=".$mypathinfo);
    }
}
// Change Path info to Get Vars :
function mrstring()
{
    $urlvar = explode('/', $_SERVER['PATH_INFO']);
    $num = count($urlvar);
    $i = 1;
    while ($i < $num) {
        //$urlvar[$i] = urldecode($urlvar[$i]);
        if (!isset($_GET[$urlvar[$i]])) {
            $_GET[$urlvar[$i]] = null;
        }
        if (!isset($urlvar[$i])) {
            $urlvar[$i] = null;
        }
        if ($_GET[$urlvar[$i]] == null && $urlvar[$i] != null) {
            $fix1 = array(" ",'$');
            $fix2  = array("_","_");
            $urlvar[$i] = str_replace($fix1, $fix2, $urlvar[$i]);
            $urlvar[$i] = killbadvars($urlvar[$i]);
            $_GET[$urlvar[$i]] = $urlvar[$i + 1];
        }
        ++$i;
        ++$i;
    } return true;
}
// Redirect to another file with ether timed or nontimed redirect
function redirect($type, $file, $time = 0, $url = null, $dbsr = true)
{
    if ($type != "location" && $type != "refresh") {
        $type == "location";
    }
    if ($url != null) {
        $file = $url.$file;
    }
    if ($dbsr === true) {
        $file = str_replace("//", "/", $file);
    }
    if ($type == "refresh") {
        header("Refresh: ".$time."; URL=".$file);
    }
    if ($type == "location") {
        @session_write_close();
        header("Location: ".$file);
    } return true;
}
function redirects($type, $url, $time = 0)
{
    if ($type != "location" && $type != "refresh") {
        $type == "location";
    }
    if ($type == "refresh") {
        header("Refresh: ".$time."; URL=".$url);
    }
    if ($type == "location") {
        header("Location: ".$url);
    } return true;
}
// Make xhtml tags
function html_tag_make($name = "br", $emptytag = true, $attbvar = null, $attbval = null, $extratest = null)
{
    $var_num = count($attbvar);
    $value_num = count($attbval);
    if ($var_num != $value_num) {
        trigger_error("Erorr Number of Var and Values dont match!", E_USER_ERROR);
        return false;
    } $i = 0;
    while ($i < $var_num) {
        if ($i == 0) {
            $mytag = "<".$name." ".$attbvar[$i]."=\"".$attbval[$i]."\"";
        }
        if ($i >= 1) {
            $mytag = $mytag." ".$attbvar[$i]."=\"".$attbval[$i]."\"";
        }
        if ($i == $var_num - 1) {
            if ($emptytag === false) {
                $mytag = $mytag.">";
            }
            if ($emptytag === true) {
                $mytag = $mytag." />";
            }
        }	++$i;
    }
    if ($attbvar == null && $attbval == null) {
        $mytag = "<".$name;
        if ($emptytag === true) {
            $mytag = $mytag." />";
        }
        if ($emptytag === false) {
            $mytag = $mytag.">";
        }
    }
    if ($emptytag === false && $extratest != null) {
        $mytag = $mytag.$extratest;
        $mytag = $mytag."</".$name.">";
    }
    return $mytag;
}
// Start a xml document
function xml_tag_make($type, $attbs, $retval = false)
{
    $renee1 = explode("&", $attbs);
    $reneenum = count($renee1);
    $reneei = 0;
    $attblist = null;
    while ($reneei < $reneenum) {
        $renee2 = explode("=", $renee1[$reneei]);
        if ($renee2[0] != null || $renee2[1] != null) {
            $attblist = $attblist.' '.$renee2[0].'="'.$renee2[1].'"';
        }
        ++$reneei;
    }
    if ($retval !== false && $retval !== true) {
        $retval = false;
    }
    if ($retval === false) {
        echo '<?'.$type.$attblist.'?>'."\n";
    }
    if ($retval === true) {
        return '<?'.$type.$attblist.'?>'."\n";
    }
}
// Start a xml document (old version)
function xml_doc_start($ver, $encode, $retval = false)
{
    if ($retval === false) {
        echo xml_tag_make('xml', 'version='.$ver.'&encoding='.$encode, true);
    }
    if ($retval === true) {
        return xml_tag_make('xml', 'version='.$ver.'&encoding='.$encode, true);
    }
}
$icharset = $Settings['charset'];
$debug_on = false;
if (isset($_GET['debug'])) {
    if ($_GET['debug'] == "true" ||
        $_GET['debug'] == "on") {
        $debug_on = true;
    }
}
// Make a url with query string
function url_maker($file = "index", $ext = ".php", $qvarstr = null, $qstr = ";", $qsep = "=", $prexqstr = null, $exqstr = null, $fixhtml = true)
{
    global $sidurls, $icharset, $debug_on;
    $fileurl = null;
    if (!isset($ext)) {
        $ext = null;
    }
    if ($ext == null) {
        $ext = ".php";
    }
    if ($ext == "noext" || $ext == "no ext" || $ext == "no+ext") {
        $ext = null;
    }
    $file = $file.$ext;
    if ($sidurls == "on" && $qstr != "/") {
        if (defined('SID')) {
            if ($qvarstr == null) {
                $qvarstr = SID;
            }
            if ($qvarstr != null) {
                $qvarstr = SID."&".$qvarstr;
            }
        }
    }
    if ($debug_on === true) {
        if ($qvarstr == null) {
            $qvarstr = "debug=on";
        }
        if ($qvarstr != null) {
            $qvarstr = $qvarstr."&debug=on";
        }
    }
    if ($qvarstr == null) {
        $fileurl = $file;
    }
    if ($fixhtml === true) {
        $qstr = htmlentities($qstr, ENT_QUOTES, $icharset);
        $qsep = htmlentities($qsep, ENT_QUOTES, $icharset);
    }
    if ($prexqstr != null) {
        $rene1 = explode("&", $prexqstr);
        $renenum = count($rene1);
        $renei = 0;
        $reneqstr = "index.php?";
        if ($qstr != "/") {
            $fileurl = $file."?";
        }
        if ($qstr == "/") {
            $fileurl = $file."/";
        }
        while ($renei < $renenum) {
            $rene2 = explode("=", $rene1[$renei]);
            if (!isset($rene2[0])) {
                $rene2[0] = null;
            }
            $rene2[1] = urlencode($rene2[1]);
            if (!isset($rene2[0])) {
                $rene2[0] = null;
            }
            $rene2[1] = urlencode($rene2[1]);
            if ($qstr != "/") {
                $fileurl = $fileurl.$rene2[0].$qsep.$rene2[1];
            }
            if ($qstr == "/") {
                $fileurl = $fileurl.$rene2[0]."/".$rene2[1]."/";
            }
            $reneis = $renei + 1;
            if ($qstr != "/") {
                if ($reneis < $renenum) {
                    $fileurl = $fileurl.$qstr;
                }
            }
            ++$renei;
        }
    }
    if ($qvarstr != null && $qstr != "/") {
        $fileurl = $fileurl.$qstr;
    }
    if ($qvarstr != null) {
        if ($prexqstr == null) {
            if ($qstr != "/") {
                $fileurl = $file."?";
            }
            if ($qstr == "/") {
                $fileurl = $file."/";
            }
        }
        $cind1 = explode("&", $qvarstr);
        $cindnum = count($cind1);
        $cindi = 0;
        $cindqstr = "index.php?";
        while ($cindi < $cindnum) {
            $cind2 = explode("=", $cind1[$cindi]);
            if (!isset($cind2[0])) {
                $cind2[0] = null;
            }
            $cind2[0] = urlencode($cind2[0]);
            if (!isset($cind2[1])) {
                $cind2[1] = null;
            }
            $cind2[1] = urlencode($cind2[1]);
            if ($qstr != "/") {
                $fileurl = $fileurl.$cind2[0].$qsep.$cind2[1];
            }
            if ($qstr == "/") {
                $fileurl = $fileurl.$cind2[0]."/".$cind2[1]."/";
            }
            $cindis = $cindi + 1;
            if ($qstr != "/") {
                if ($cindis < $cindnum) {
                    $fileurl = $fileurl.$qstr;
                }
            }
            ++$cindi;
        }
    }
    if ($exqstr != null && $qstr != "/") {
        $fileurl = $fileurl.$qstr;
    }
    if ($exqstr != null) {
        if ($qvarstr == null && $prexqstr == null) {
            if ($qstr != "/") {
                $fileurl = $file."?";
            }
            if ($qstr == "/") {
                $fileurl = $file."/";
            }
        }
        $sand1 = explode("&", $exqstr);
        $sanum = count($sand1);
        $sandi = 0;
        $sandqstr = "index.php?";
        while ($sandi < $sanum) {
            $sand2 = explode("=", $sand1[$sandi]);
            if (!isset($sand2[0])) {
                $sand2[0] = null;
            }
            $sand2[0] = urlencode($sand2[0]);
            if (!isset($sand2[1])) {
                $sand2[1] = null;
            }
            $sand2[1] = urlencode($sand2[1]);
            if ($qstr != "/") {
                $fileurl = $fileurl.$sand2[0].$qsep.$sand2[1];
            }
            if ($qstr == "/") {
                $fileurl = $fileurl.$sand2[0]."/".$sand2[1]."/";
            }
            $sandis = $sandi + 1;
            if ($qstr != "/") {
                if ($sandis < $sanum) {
                    $fileurl = $fileurl.$qstr;
                }
            }
            ++$sandi;
        }
    }
    return $fileurl;
}
$thisdir = dirname(realpath("Preindex.php"))."/";
// Get the Query String
function GetQueryStr($qstr = ";", $qsep = "=", $fixhtml = true)
{
    $pregqstr = preg_quote($qstr, "/");
    $pregqsep = preg_quote($qsep, "/");
    $oqstr = $qstr;
    $oqsep = $qsep;
    if ($fixhtml === true || $fixhtml == null) {
        $qstr = htmlentities($qstr, ENT_QUOTES, $icharset);
        $qsep = htmlentities($qsep, ENT_QUOTES, $icharset);
    }
    $OldBoardQuery = preg_replace("/".$pregqstr."/isxS", $qstr, $_SERVER['QUERY_STRING']);
    $BoardQuery = "?".$OldBoardQuery;
    return $BoardQuery;
}
