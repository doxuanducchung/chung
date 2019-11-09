<?
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

session_start();
require_once("../libs/_config.php");
require_once("../libs/_mysql.php");
$DB = new DB;
$DB->connect();

if ( !empty($_GET["newsid"]) && is_numeric($_GET["newsid"]) ) {
      $news_id =$_GET["newsid"];
}
$query="select * from ".$conf['perfix']."news where newsid=$news_id";
$newsresult = $DB->query($query);
    if ($news=$DB->fetch_row($newsresult)){
        $title = $news["title"];
        $short =$news["short"];
        $content =$news["content"];
        $source = $news["source"];
        $picture = $news["picture"];
        $timepost = $news["timepost"];
        $pic_des = $news["pic_des"];
        $adddate =$news["adddate"];
        $datepost = gmdate("d/m/Y, H:i", $timepost +7*3600)." GMT+7";
        $datenow = gmdate("d/m/Y, H:i", time() +7*3600)." GMT+7";
        if (!empty($picture)){
            $tmpv = explode("-",$adddate);
            $pathv = $conf['rooturl']."images/news/".$tmpv[0]."/".$tmpv[1]."/".$tmpv[2]."/";
            if (empty($pic_des)) {
                $picture = "<center><img src='".$pathv.$picture."' border='0' align='center' style='margin-top:6px'></center>";
            } else {
                $picture = "<center><img src='".$pathv.$picture."' border='0' align='center' style='margin-top:6px'><br><font color='blue' size='1'>".$pic_des."</font></center>";
            }
        }
        if (empty($source)) $source = "<b>VGT Media</b>";
        else $source = "Theo <b>".$source."</b>";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?=$conf['charset']?>">
<title><?=$title;?> || <?=$conf['indextitle']?></title>
<link rel="stylesheet" href="../libs/style.css" type="text/css">
</head>
<!--body onload="print();"-->
<body>
<table width="100%" border="0" cellspacing="5" cellpadding="5"  bgcolor="#FFFFFF">
  <tr>
      <td><br><h2>VGT MEDIA - TRANG TIN T&#7912;C GI&#7842;I TR&#205; T&#7892;NG H&#7906;P</h2>
              <h5>VGT Media - www.vuigiaitri.org || Ng&#224;y <?=$datenow;?></h5><hr width="100%" size="0" noshade color="#000000" style="border-style:dotted"></td>
  </tr>
   <tr>
      <td><table width="100%" border="0" cellspacing="2" cellpadding="2">
          <tr>
             <td width="100%" height="20" class="art-header"><?=$title;?></td>
          </tr>
          <tr>
             <td><i>C&#7853;p nh&#7853;t ng&#224;y : </i><?=$datepost;?></td>
          </tr>
          <tr>
             <td align="left"><div align="justify"><b><?=$short;?></b><br><?=$picture;?><br><?=$content;?></div></td>
          </tr>
          <tr>
             <td align="right"><i>( <?=$source;?> )</i></td>
          </tr>
          <tr>
             <td><hr width="100%" size="0" noshade color="#000000" style="border-style:dotted"><i>VGT Media - Trang tin t&#7913;c gi&#7843;i tr&#237; t&#7893;ng h&#7907;p - www.vuigiaitri.org</i><br></td>
          </tr>
      </table></td>
   </tr>
<?php
    }else print "<tr><td align=center >Tin t&#7913;c n&#224;y kh&#244;ng t&#7891;n t&#7841;i</td></tr>";
$DB->close();
?>
</table>