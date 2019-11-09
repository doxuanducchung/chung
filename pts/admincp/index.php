<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

session_start();
require_once("../libs/_config.php");
require_once("../libs/_mysql.php");
require_once("../libs/code.php");
$DB = new DB;
$DB->connect();
require_once("_function.php");
$func = new func;
if (isset($_GET['act'])) $act=$_GET['act']; else $act='welcome';
if (isset($_GET['sub'])) $sub=$_GET['sub']; else $sub='';
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>
<head><title>[:: CHF Media - Administrator ::]</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<LINK href="style.css" rel=stylesheet type=text/css>
<script language="javascript1.2" src="../js/CViet.js"></script>
</head>
<?
if ($act!="login") {
    if (!session_is_registered("admin")) $act='login';
}
if ($act=="welcome") {
    printf("<frameset cols=\"180,*\" framespacing=0 frameborder=\"no\" border=0>
               <frame name=\"menu\" noresize scrolling=\"YES\"target=\"Main\" src=\"index.php?act=menu\">
               <frame name=\"Main\" framespacing=0 frameborder=\"no\" border=0 src=\"index.php?act=main\">
               <noframes>
                  <body>
                      <p>This page uses frames, but your browser doesn\'t support them.</p>
                  </body>
               </noframes>
            </frameset>");
} else {
?>
<body>
<?
$page_tittle = $tittle_ad[$act];
if ( ($act!="menu") && ($act!="login") ) include "s_header.php";
switch ($act) {
        case 'main':
              include "_main.php";
              break;
        case 'login':
              include "_login.php";
              break;
        case 'member':
              include "_member.php";
              break;
        case 'config':
              include "_config.php";
              break;
        case 'statistic':
              include "_statistic.php";
              break;
        case 'event':
              include "_event.php";
              break;
        case 'newspic':
              include "_newspic.php";
              break;
        case 'menu':
              include "_menu.php";
              break;
        case 'cataloge':
              include "_cataloge.php";
              break;
        case 'news':
              include "_news.php";
              break;
		case 'docngay':
              include "_docngay.php";
              break;
        case 'active':
              include "_active.php";
              break;
        case 'focus':
              include "_focus.php";
              break;
		case 'focusmodule':
              include "_focusmodule.php";
              break;
        case 'contact':
              include "_contact.php";
              break;
        case 'poll':
              include "_poll.php";
              break;
        case 'logoman':
              include "_logomanager.php";
              break;
        case 'logohot':
              include "_logohot.php";
              break;
        case 'logotop':
              include "_logotop.php";
              break;
        case 'logomid':
              include "_logomid.php";
              break;
        case 'logoleft':
              include "_logoleft.php";
              break;
        case 'logotrangchu1':
              include "_trangchu1.php";
              break;
		case 'logotrangchu2':
              include "_trangchu2.php";
              break;
		case 'logotrangchu3':
              include "_trangchu3.php";
              break;
		case 'logobot':
              include "_logobot.php";
              break;
        case 'logoright':
              include "_logoright.php";
              break;
        case 'scroll':
              include "_scroll.php";
              break;
        case 'quicklink':
              include "_quicklink.php";
              break;
		case 'menu1':
              include "_menu1.php";
              break;
		case 'menu2':
              include "_menu2.php";
              break;
		case 'menu3':
              include "_menu3.php";
              break;
		case 'menu4':
              include "_menu4.php";
              break;
		case 'menu5':
              include "_menu5.php";
              break;
		case 'menu6':
              include "_menu6.php";
              break;
		case 'menu7':
              include "_menu7.php";
              break;
		case 'menu8':
              include "_menu8.php";
              break;
		case 'newsmodule':
              include "_newsmodule.php";
              break;
		case 'catalogemodule':
              include "_catalogemodule.php";
              break;
		case 'newstruyencuoi':
              include "_newstruyencuoi.php";
              break;
		case 'catalogetruyencuoi':
              include "_catalogetruyencuoi.php";
              break;
		case 'comment':
              include "_comment.php";
              break;
		case 'trangchu1':
              include "_trangchu1.php";
              break;
		case 'trangchu2':
              include "_trangchu2.php";
              break;
		case 'trangchu3':
              include "_trangchu3.php";
              break;
		case 'trangchu4':
              include "_trangchu4.php";
              break;
		case 'header1':
              include "_header1.php";
              break;
		case 'header2':
              include "_header2.php";
              break;
		case 'newsmain1':
              include "_newsmain1.php";
              break;
		case 'newsmain2':
              include "_newsmain2.php";
              break;
		
        default : {
                $fileactname = "_{$act}.php";
                if (file_exists($fileactname)) include $fileactname;
                else include "_main.php";
                }
                break;
}
if ( ($act!="menu") && ($act!="login") ) include "s_footer.php";
}
;
?>
</body>
</html>