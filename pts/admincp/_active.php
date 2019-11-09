<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_active($sub);

class func_active{
      var $html="";
      var $output="";
      var $base_url="";

      function func_active($sub){
               global $func,$DB,$conf;
               switch ($sub) {
                       case 'del': $this->do_Del(); break;
                       default: $this->do_Manage(); break;
               }
               echo $this->output;
      }

      function do_Del(){
               global $func,$DB,$conf;
               if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
               $del=0; $qr="";
               if ($id!=0) {
                   $ok=1;
                   $qr = " OR newsid='{$id}' ";
                   $qr1 = " OR news_id='{$id}' ";
               }
               // Display Only News
               if ((isset($_GET['display_id'])) && (is_numeric($_GET['display_id']))){
                  $display_id = $_GET['display_id'];
                  $query = $DB->query("update ".$conf['perfix']."news set isdisplay='1' WHERE newsid='{$display_id}'");
                  $mess = "Display News successfull";
                  $url = "index.php?act=active&sub=manage";
                  $this->output .= $this->html_ann($url,$mess);
               }
               // Submit Selected News
               if (isset($_POST["delnews"])) $key=$_POST["delnews"];
               for ($i=0;$i<count($key);$i++) {
                   $ok=1;
                   $qr .= " OR newsid='{$key[$i]}' ";
               }
               // Display Selected News
               if (isset($_POST["btnDisplay"])){
                   if ($ok){
                       $query = "update ".$conf['perfix']."news set isdisplay='1' WHERE newsid=-1".$qr;
                       if ($DB->query($query)){
                           $mess = "Display News successfull";
                       }else
                           $mess = "News not Found";
                       $url = "index.php?act=active&sub=manage";
                       $this->output .= $this->html_ann($url,$mess);
                   } else
                       $this->do_Manage();
               }
               // Delete Selected News
               if (isset($_POST["btnDel"])){
                   if ($ok){
                       // Begin Del Image
                       $query = $DB->query("SELECT picture,adddate,user_id FROM ".$conf['perfix']."news WHERE newsid=-1".$qr);
                       while ($img=$DB->fetch_row($query)) {
                              // Cap nhat Num_post cho Username post News bi xoa
                              $savebv  = $DB->query("UPDATE ".$conf['perfix']."user SET num_post=num_post-1 WHERE user_id=".$img['user_id']);

                              if (!empty($img['picture'])){
                                   // Del Pic in Local Only he he
                                   if ( (!strstr($img['picture'],"http://"))){
                                          // Lay thu muc chua anh News
                                          $adddate = $img['adddate'];
                                          $tmp = explode("-",$adddate);
                                          $dir1 = $tmp[0];
                                          $dir2 = $tmp[1];
                                          $dir3 = $tmp[2];
                                          $upload_pic = $conf['rootpath']."images/news/";
                                          $fname0 = $upload_pic.$dir1."/".$dir2."/".$dir3."/".$img['picture'];
                                          $fname1 = $upload_pic.$dir1."/".$dir2."/".$dir3."/thumb_".$img['picture'];
                                          $fname2 = $upload_pic.$dir1."/".$dir2."/".$dir3."/icon_".$img['picture'];
                                          @unlink($fname0);
                                          @unlink($fname1);
                                          @unlink($fname2);
                                   }
                              } // Het kiem tra co PIC hay khong
                       }
                       // End del image - Update CSDL
                       $query = "DELETE FROM ".$conf['perfix']."news WHERE newsid=-1".$qr;
                       if ($ok1 = $DB->query($query)){
                           $DB->query("DELETE FROM ".$conf['perfix']."focus_news WHERE newsid=-1".$qr);
                           $DB->query("DELETE FROM ".$conf['perfix']."focus_cat WHERE newsid=-1".$qr);
                           $mess = "Delete News successfull";
                       } else
                           $mess = "News not found !";
                       $url = "index.php?act=active&sub=manage";
                       $this->output .= $this->html_ann($url,$mess);
                   } else
                       $this->do_Manage();
               }
      }

      function do_Manage(){
               global $func,$DB,$conf;
               if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."news where isdisplay=0");
               $totals_news = $DB->num_rows($query);
               $n=$conf["record"];
               $num_pages = ceil($totals_news/$n) ;
               if ($p > $num_pages) $p=$num_pages;
               if ($p < 1 ) $p=1;
               $start = ($p-1) * $n ;
               $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
               for ($i=1; $i<$num_pages+1; $i++ ) {
                    if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                    else $nav.="[<a href='?act=active&sub=manage&p={$i}'>$i</a>] ";
               }
               $nav .= "</div></center>";
               $list = "";
               $stt=0;
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."news where isdisplay=0 ORDER BY newsid,adddate,timepost DESC LIMIT $start,$n");
               while ($data=$DB->fetch_row($query)) {
                      $data['stt'] = $stt;
                      $user_id = $data['user_id'];
                      $check_mem = $DB->query("SELECT * FROM ".$conf['perfix']."user WHERE user_id='{$user_id }'");
                      if ($mem = $DB->fetch_row($check_mem)) {
                          $data["name"] = $mem["username"];
                      } else
                          $data["name"] = "News_Bot";
                      $data['title']=$func->HTML($data['title']);
                      $data["link_view"] = $conf["rooturl"]."?cmd=".$func->Link("act:news|newsid:".$data["newsid"]);
                      $data['date'] = $func->makedate($data["adddate"]);
                      $list .= $this->html_row($data);
                      $stt++;
               }
               $nd['title'] = "Manage News of User Post";
               $nd['nd'] = $list;
               $nd['num'] = $stt+2;
               $this->output .= $this->html_nav($nd);
               $this->output .= $nav."<br>";
      }


      function html_nav($data){
               return<<<EOF
                        <script language="javascript">
                                function checkall(num){
                                         for ( i=0;i < document.manage.elements.length ; i++ ){
                                               if ( document.manage.all.checked==true ){
                                                    document.manage.elements[i].checked = true;
                                               } else {
                                                    document.manage.elements[i].checked  = false;
                                               }
                                         }
                                }
                        </script>
                        <br><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                            <tr>
                               <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                      <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                      <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['title']}</td>
                                      <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                   </tr>
                               </table></td>
                            </tr>
                            <tr>
                               <td bgcolor="#FFFFFF" class="main_table" align=center>
                               <form action="?act=active&sub=del" method="post" name="manage" id="manage">
                                  <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                     <tr>
                                        <td width="7%" class="row_tittle">Delete</td>
                                        <td width="17%" class="row_tittle">User Post</td>
                                        <td width="50%" class="row_tittle">Title</td>
                                        <td width="13%" class="row_tittle">Date</td>
                                        <td width="13%" class="row_tittle">Display</td>
                                     </tr>
                                     {$data['nd']}
                                     <tr>
                                        <td width="7%" class="row_tittle"><input type="checkbox" name="all" onclick="javascript:checkall({$data['num']});"></td>
                                        <td colspan=4 class="row_tittle" align=left>
                                            <input type="submit" name="btnDel" value="Delete seleted News">&nbsp;&nbsp;
                                            <input type="submit" name="btnDisplay" value="Display seleted News">
                                        </td>
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
                        </table><br>
EOF;
       }

       function html_row($data){
                return<<<EOF
                            <tr>
                               <td class="row"><input name="delnews[]" type="checkbox" value="{$data['newsid']}"></td>
                               <td class="row" align="center">{$data['name']}</td>
                               <td class="row" align="left" style="padding-left:10px">{$data['title']}</td>
                               <td class="row" align="center">{$data['date']}</td>
                               <td class="row" align="center">
                                   <a href="?act=news&sub=edit&id={$data['newsid']}" title="Edit News"><img src="images/edit.gif" width="22" height="22"></a>&nbsp;&nbsp;&nbsp;&nbsp;
                                   <a href="?act=active&sub=del&display_id={$data['newsid']}" title="Display News"><img src="images/dispay.gif" width="16" height="16"></a></td>
                            </tr>
EOF;
       }

       function html_ann($url,$mess){
                return<<<EOF
                        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                        <meta http-equiv='refresh' content='1; url={$url}'>
                        <br><br><br><br><br>
                        <table width="50%" border="0" align="center" cellpadding="0" cellspacing="0">
                           <tr>
                              <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                     <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                     <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>Announcement</td>
                                     <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                  </tr>
                              </table></td>
                           </tr>
                           <tr>
                              <td bgcolor="#FFFFFF" class="main_table" align=center>
                                 <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                    <tr>
                                       <td width="100%" align=center><font color="red">{$mess}</font></td>
                                    </tr>
                                 </table>
                              </td>
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
                        <br><br><br><br><br>
EOF;
       }
       // Het Function
}
?>