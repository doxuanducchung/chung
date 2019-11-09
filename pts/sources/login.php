<?
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
// Nho khong nham thi Permission cua VBB la the nay nen su dung sau nay ket hop do phai chuyen doi ^_^
// Permission Admin : 6
// Permission Mod : 3 (MOD)
// Permission Mem : 1 (POSS)
$mess = "";
$ok1=0;
if (isset($_POST['Submit'])){
    if (isset($_POST['txtUsername'])) $admin_user=$_POST['txtUsername']; else $admin_user='';
    if (isset($_POST['txtPassword'])) $admin_pass=$_POST['txtPassword']; else $admin_pass='';
    if (isset($_POST['txtPassSec'])) $admin_sec=$_POST['txtPassSec']; else $admin_sec='';
    $admin_user = str_replace("\'","",$admin_user);
    $admin_pass = str_replace("\'","",$admin_pass);
    $admin_sec = md5(base64_encode($admin_sec));
    if ($admin_sec == "db69fc039dcbd2962cb4d28f5891aae1") {
        if ( (!empty($admin_user)) && (!empty($admin_pass)) ){
               $admin_pass = md5($admin_pass);
               $query = "select * from ".$conf['perfix']."user WHERE (username='$admin_user' AND password='$admin_pass') AND permission='6'";
               $data_arr = $DB->query($query);
               $ok = $DB->fetch_row($data_arr);
               if ($ok) {
                   $admin = $admin_user;
                   $ok1=1;
                   $_SESSION['user_id'] = $ok['user_id'];
                   session_register("admin");
                   header("Location: index.php?act=welcome");
                   echo "<meta http-equiv='refresh' content='0; url=index.php?act=welcome'>";
               } else
               $mess="The Username or Password you entered is not correct";
        } else
        $mess="Verification Required";
    }else
        $mess="Security Password you entered is not correct";
}
// Neu logout
if ( (!empty($_GET['code'])) && ($_GET['code']==2) ) {
      $result = session_unregister("admin");
      $_SESSION['user_id'] = "";
      session_destroy();
      $mess="Logged Out ! See you again";
      echo "<meta http-equiv='refresh' content='0; url=../?act=main'>";
}
if (!$ok1) {
?>
<DIV id=content>
<DIV class="content-center fl">
<div class="thumuc">
	Website Sitemap
	</div>
	<div class="folder-news" style="BORDER-top: 0px; padding-left: 30px; width: 463px">

<table width="100%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td width="30%">&nbsp;</td>
    <td height="70">&nbsp;</td>
    <td width="30%">&nbsp;</td>
  </tr>
  <tr>
    <td>&nbsp;</td>
    <td><!--- NAV START --->
        <table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>CHF Media - Administrator Login</td>
                <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td bgcolor="#FFFFFF" class="main_table" align=center>
              <form name="form1" method="post" action="index.php?act=login">
              <table width="100%"  border="0" cellspacing="2" cellpadding="2" align="center">
                <tr>
                   <td colspan="2" align="center"><font color="red"><?=$mess?></font></td>
                </tr>
                <tr>
                   <td width="40%" align="right">Username : </td>
                   <td width="60%" align="left"><input name="txtUsername" type="text" id="txtUsername" size="25" maxlength="50"></td>
                </tr>
                <tr>
                   <td align="right">Password : </td>
                   <td align="left"><input name="txtPassword" type="password" id="txtPassword" size="25" maxlength="100"></td>
                </tr>
                <tr>
                   <td align="right">Security Password : </td>
                   <td align="left"><input name="txtPassSec" type="password" id="txtPassSec" size="25" maxlength="100"></td>
                </tr>
                <tr>
                   <td colspan="2"><input type="submit" name="Submit" value="Login"></td>
                </tr>
              </table>
            </form></td>
          </tr>
          <tr>
            <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="6" height="6" align="left"><img src="images/nav_botleft.gif" width="6" height="6"></td>
                <td height="6" background="images/nav_botbg.gif"><img src="images/nav_botbg.gif" width="7" height="6"></td>
                <td width="6" height="6" align="right"><img src="images/nav_botright.gif" width="6" height="6"></td>
              </tr>
            </table></td>
          </tr>
        </table>
     <!--- NAV END ---></td>
     <td>&nbsp;</td>
  </tr>
</table>

</DIV>

</DIV>





<DIV style="float: left">
<DIV class="linksite1 fl">
<DIV class="toplist-content fl" id=toplist>
<DIV class=baivietmoi>tjityi</DIV>
<UL>
<?php
	$dem=0;
	
	   $sql1 = "select * from ".$conf['perfix']."news where isdisplay!=0 order by newsid DESC LIMIT 0,9";
	   
       $result1 = $DB->query ($sql1) ;
       while ($row1 = $DB->fetch_row($result1)){
              $newsid = $row1["newsid"];
              $sql1 ="select * from ".$conf['perfix']."news where newsid=$newsid";
              $resultNews=$DB->query ($sql1);
              if($news1=$DB->fetch_row($resultNews)){
			  $solanxem=$news1["viewnum"];
			  $newsid1 = $news1["newsid"];
			  $tieude1 = $news1["title"];
			  $short1 = $news1["short"];
			  $ttieude1 = mahoa($tieude1);
			  $linknews1 = "".$func->seolinknews($ttieude1, $newsid1)."";
			  $adddate1 = $news1["adddate"];
			 
                 if (!empty($news1['picture'])) {
                     if ( (!strstr($news1['picture'],"http://"))){
                           $folder = explode("-",$adddate1);
                           $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/thumb_";
                           $src = $path.$news1['picture'];
                     } else
                           $src = $news1['picture'];
                     }
                 
				
              }
  ?>


  <LI style="BACKGROUND-IMAGE: url(<?=$conf['thumucroot']?>images/background/gray-square.gif)">
  <A class=link-toplist href="<?=$linknews1?>"><?=$tieude1?></A> 
  
  <?
  }
  ?>
  
  </UL></DIV>
</DIV>
<DIV class="linksite fl" style="float: right;">
<DIV class=adv-header>
<DIV class="adv-title fl"><IMG alt="" src="<?=$conf['thumucroot']?>images/adv-title.gif"> 
</DIV></DIV>
<!--Quang cao 1-->
<?
get_trangchu1($catid);
?>
<!-- Het quang cao 1-->

</DIV>
</DIV>	

		
<div class="content-left fl">		
<?
require_once("module/topmostview.php");
get_newsmain1($catid);
require_once("module/topcomment.php");
?>
</div>

<?php } ?>