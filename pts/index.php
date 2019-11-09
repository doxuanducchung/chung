<?php
session_start();
require_once("libs/_config.php");
require_once("libs/_mysql.php");
require_once("libs/code.php");
$DB = new DB;
$DB->connect();
require_once("libs/_functions.php");
$func = new func;
include ("libs/lib.php");

if (!empty($_GET['cmd'])) $cmd=$_GET['cmd']; else $cmd="";
$input = $func->Get_Input($cmd);

// Title mac dinh cho website
$sitetitle = "Homepage | ".$conf['indextitle'];

if(!empty($input["cat"]) && is_numeric($input["cat"])) {
        $catid = $input["cat"];
        $query = $DB->query("SELECT catalogname FROM ".$conf['perfix']."catalog WHERE catalogid='$catid'");
        $laycat111 = $DB->fetch_row($query);
        $catname111 = $laycat111['catalogname'];
		$ccatname111 = mahoa1($catname111);
        //Title cho website khi dang o chuyen muc rieng
        $sitetitle = $catname111." | ".$ccatname111." | ".$conf['indextitle'];
}

if(!empty($input["eventid"]) && is_numeric($input["eventid"])) {
        $eventid = $input["eventid"];
        $query = $DB->query("SELECT eventtitle FROM ".$conf['perfix']."events WHERE eventid='$eventid'");
        $laycat111 = $DB->fetch_row($query);
        $catname111 = $laycat111['eventtitle'];
		$ccatname111 = mahoa1($catname111);
        //Title cho website khi dang o chuyen muc rieng
        $sitetitle = $catname111." | ".$ccatname111." | ".$conf['indextitle'];
}


if(!empty($input["newsid"]) && is_numeric($input["newsid"])) {
        $newsid = $input["newsid"];
        $query = $DB->query("SELECT title FROM ".$conf['perfix']."news WHERE newsid='$newsid'");
        $laytitle = $DB->fetch_row($query);
        $newstitle = $laytitle['title'];
		$nnewstitle = mahoa1($newstitle);
        $catid = $func->tablelockup("news","newsid",$input["newsid"],1);
        $query = $DB->query("SELECT catalogname FROM ".$conf['perfix']."catalog WHERE catalogid='$catid'");
        $laycat111 = $DB->fetch_row($query);
        $catname111 = $laycat['catalogname'];
        // Title cho website khi dang xem noi dung tin tuc
        $sitetitle = $newstitle." | ".$nnewstitle." | ".$conf['indextitle'];
}

$parentcat_id = $func->tablelockup("catalog","catalogid",$catid,3);
require_once("libs/header.php");

            if (empty($input['act'])) $input['act']="home";
                switch ($input['act']) {
                        case 'main':
                              include "sources/main.php";
                              break;
                        case 'news':
                              include "sources/news.php";
                              break;
                        case 'event':
                              include "sources/event.php";
                              break;
                        case 'login':
                              include "sources/login.php";
                              break;
                        case 'poll':
                              include "sources/poll.php";
                              break;
                        case 'search':
                              include "sources/search.php";
                              break;
                        case 'comment':
                              include "sources/comment.php";
                              break;
                        case 'sitemap':
                              include "sources/sitemap.php";
                              break;
                        case 'quangcao':
                              include "sources/quangcao.php";
                              break;
                        case 'rss':
                              include "sources/rss.php";
                              break;
						case 'tag':
                              include "sources/tag.php";
                              break;
                        default: {
                              $input['act'] = str_replace("\\'","&#039;",$input['act']);
                              $filesource = "sources/_".$input['act'].".php";
                              $filesource = str_replace("..","",$filesource);
                              if (!file_exists($filesource)) {
                                  $filesource = "sources/home.php";
                              }
                              include $filesource;
                              }
                              break;
                  }
?>
   <?php require_once("libs/footer.php");
$DB->close();
?>
