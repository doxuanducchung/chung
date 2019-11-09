<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_event($sub);
class func_event{
      var $html="";
      var $output="";
      var $base_url="";

      function func_event($sub){
               global $func,$DB,$conf;
               switch ($sub) {
                       case 'add' : $this->do_Add(); break;
                       case 'edit' : $this->do_Edit(); break;
                       case 'del' : $this->do_Del(); break;
                       case 'view' : $this->do_View(); break;
                       default : $this->do_Manage(); break;
               }
               echo $this->output;
      }

      function do_Add(){
               global $func,$DB,$conf;
               $data['f_title'] = "Add Event";
               $data['html_option']="";
               if (!empty($_POST['btnAdd'])) {
                    $data = $_POST;
                    $data['e_title'] = $func->txt_HTML($data['eventtitle']);
                    $data['e_des'] = $func->txt_HTML($data['eventdes']);
                    $data['e_post'] = time();
                    $data['err'] = "";
                    // Check for Error
                    $query ="SELECT * FROM ".$conf['perfix']."events WHERE eventtitle='{$data['e_title']}'";
                    $query = $DB->query($query);
                    if ($check=$DB->fetch_row($query)) $data['err']="Event Title Existed";
                    // Kiem tra xem co upload anh khong
                    if (!empty($_FILES['image'][name])) {
                         $dir1 = gmdate("Y",$data['e_post'] + 7*3600);
                         $dir2 = gmdate("m",$data['e_post'] + 7*3600);
                         $upload_pic = $conf['rootpath']."images/event/";
                         if (!is_dir($upload_pic.$dir1)) {
                              mkdir($upload_pic.$dir1,0777);
                              $handle = fopen($upload_pic.$dir1."/index.html", "w");
                              fwrite($handle,"<script>window.location='http://www.chf.com.vn';</script>");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         if (!is_dir($upload_pic.$dir1."/".$dir2)) {
                              mkdir($upload_pic.$dir1."/".$dir2,0777);
                              $handle = fopen($upload_pic.$dir1."/".$dir2."/index.html", "w");
                              fwrite($handle,"<script>window.location='http://www.chf.com.vn';</script>");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         // Het phan kiem tra thu muc va tao thu muc chua anh moi
                         $up['path']= $conf['rootpath'];
                         $up['dir'] = "images/event/".$dir1."/".$dir2;
                         $up['file']= $_FILES['image'];
                         $up['type']= "hinh";
                         $up['resize']= 0;
                         $up['thum']= 1;
                         $up['w']= 128; //Chieu ngang toi da cua anh dai dien thumbnail
                         $up['ato']= 248; //Chieu ngang toi da cua anh to
                         $up['ico']= 80; //Chieu ngang toi da cua anh icon
                         $up['timepost']= $data['e_post']; //Lay thoi gian luc dang bai
                         $result = $func->Upload($up);
                         if (empty($result['err'])) {
                             $data['n_image']=$result['link'];
                             $data['t_image']=$result['type'];
                         } else {
                             $data['err'] = $result['err'];
                         }
                         if (empty($data['err'])) {
                             $query="INSERT INTO ".$conf['perfix']."events VALUES ('','{$data['e_title']}','{$data['e_des']}','{$data['n_image']}','{$data['t_image']}','{$up['timepost']}','0','{$data['display']}')";
                             $insert_q = $DB->query($query);
                             $mess = "Upload Pic & Add Event Successfull";
                         }
                    } else {
                         // Neu khong upload pic for Event
                         if (empty($data['err'])) {
                             $query="INSERT INTO ".$conf['perfix']."events VALUES ('','{$data['e_title']}','{$data['e_des']}','','','{$data['e_post']}','0','{$data['display']}')";
                             $insert_q = $DB->query($query);
                             $mess = "No Upload Pic & Add Event Successfull";
                         }
                    } // Het kiem tra Upload Event Pic
                    $url = "index.php?act=event";
                    $this->output .= $this->html_ann($url,$mess);
                } else {
                    // Neu khong ton tai POST
                    $this->output .= $this->html_add($data);
                }
       }

       function do_Edit(){
                global $func,$DB,$conf;
                if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                $data['e_id']=$id;
                if ( isset($_POST['btnEdit'])){
                     $data = $_POST;
                     $data['e_title'] = $func->txt_HTML($data['eventtitle']);
                     $data['e_des'] = $func->txt_HTML($data['eventdes']);
                     $data['err'] = "";
                     // Check for Error Event Title
                     $query = $DB->query("SELECT * FROM ".$conf['perfix']."events WHERE eventtitle='{$data['e_title']}' AND eventid <> '{$data['e_id']}'");
                     if ($check = $DB->fetch_row($query)) $data['err'] = "Event Title Existed";
                     // Check Upload New Pic For Event
                     if ( !empty($_FILES['image'][name])){
                         // Lay thong tin cu cua Event
                         $queryold = $DB->query("SELECT * FROM ".$conf['perfix']."events WHERE eventid='{$data['e_id']}'");
                         $oldinfo = $DB->fetch_row($queryold);
                         $picture = $oldinfo['eventpic'];
                         $datepost = $oldinfo['eventpost'];
                         $dir1 = gmdate("Y",$datepost + 7*3600);
                         $dir2 = gmdate("m",$datepost + 7*3600);
                         $upload_pic = $conf['rootpath']."images/event/";
                         // Xoa anh cu cua Event
                         if ( (file_exists($upload_pic.$dir1."/".$dir2."/".$picture)) && (!empty($picture)) )
                               @unlink($upload_pic.$dir1."/".$dir2."/".$picture);

                         if ( (file_exists($upload_pic.$dir1."/".$dir2."/thumb_".$picture)) && (!empty($picture)) )
                               @unlink($upload_pic.$dir1."/".$dir2."/thumb_".$picture);

                         if ( (file_exists($upload_pic.$dir1."/".$dir2."/icon_".$picture)) && (!empty($picture)) )
                               @unlink($upload_pic.$dir1."/".$dir2."/icon_".$picture);

                         // Kiem tra va tao thu muc upload anh moi
                         if (!is_dir($upload_pic.$dir1)) {
                              mkdir($upload_pic.$dir1,0777);
                              $handle = fopen($upload_pic.$dir1."/index.html", "w");
                              fwrite($handle,"<script>window.location='http://www.chf.com.vn';</script>");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         if (!is_dir($upload_pic.$dir1."/".$dir2)) {
                              mkdir($upload_pic.$dir1."/".$dir2,0777);
                              $handle = fopen($upload_pic.$dir1."/".$dir2."/index.html", "w");
                              fwrite($handle,"<script>window.location='http://www.chf.com.vn';</script>");
                              fclose($handle);
                              @chmod($upload_pic.$dir1."/index.html", 0644);
                         }
                         // Het phan kiem tra thu muc va tao thu muc chua anh moi
                         $up['path']= $conf['rootpath'];
                         $up['dir'] = "images/event/".$dir1."/".$dir2;
                         $up['file']= $_FILES['image'];
                         $up['type']= "hinh";
                         $up['resize']= 0;
                         $up['thum']= 1;
                         $up['w']= 128; //Chieu ngang toi da cua anh dai dien thumbnail
                         $up['ato']= 248; //Chieu ngang toi da cua anh to
                         $up['ico']= 80; //Chieu ngang toi da cua anh icon
                         $up['timepost']= time(); //Lay thoi gian luc dang bai
                         $result = $func->Upload($up);
                         if (empty($result['err'])) {
                             $data['n_image']=$result['link'];
                             $data['t_image']=$result['type'];
                         } else {
                             $data['err'] = $result['err'];
                         }
                         // Update vao CSDL neu co PIC moi
                         if (empty($data['err'])){
                             $query ="UPDATE ".$conf['perfix']."events SET eventtitle='{$data['e_title']}', eventdes='{$data['e_des']}', eventpic='{$data['n_image']}', eventpic_type='{$data['t_image']}', active='{$data['display']}' WHERE eventid='{$data['e_id']}'";
                             $update_q = $DB->query($query);
                             $mess = "Upload New Pic & Edit Event Successfull !";
                         }
                     } else {
                        // Update vao CSDL neu khong Upload New Pic
                        if (empty($data['err'])){
                            $query ="UPDATE ".$conf['perfix']."events SET eventtitle='{$data['e_title']}', eventdes='{$data['e_des']}', active='{$data['display']}' WHERE eventid='{$data['e_id']}'";
                            $update_q = $DB->query($query);
                            $mess = "No Upload New Pic & Edit Event Successfull !";
                        }
                     }// End check Upload New Pic
                     $url = "index.php?act=event";
                     $this->output .= $this->html_ann($url,$mess);
                } else {
                // Het Update Edit Event
                     $query = $DB->query("SELECT * FROM ".$conf['perfix']."events WHERE eventid='{$data['e_id']}'");
                     if ($event=$DB->fetch_row($query)){
                         $event['f_tittle'] = "Edit Event";
                         $event['err'] = $data['err'];
                         $event['postold'] = $event['eventpost'];
                         $event['adddate'] = gmdate("d/m/Y, h:i A",$event['postold'] + 7*3600);
                         if (!empty($event['eventpic'])){
                              if ( (!strstr($event['eventpic'],"http://"))){
                                     $folder1 = gmdate("Y",$event['postold'] + 7*3600);
                                     $folder2 = gmdate("m",$event['postold'] + 7*3600);
                                     $pic_folder = $conf['rooturl']."images/event/".$folder1."/".$folder2;
                                     $src = $pic_folder."/thumb_".$event['eventpic'];
                              } else
                                     $src = $event['eventpic'];
                              $event['pic'] = "<img src=\"{$src}\" border=\"0\"><br>";
                         } else $event['pic'] = "";
                         if ($event['active']=="1"){
                             $event['dis_option']='<option value=1 selected> Yes </option>';
                             $event['dis_option'].='<option value=0 > No </option>';
                         } else {
                             $event['dis_option']='<option value=1 > Yes </option>';
                             $event['dis_option'].='<option value=0 selected> No </option>';
                         }
                         $this->output .= $this->html_edit($event);
                     } else {
                     $mess = "Your can not Edit Event";
                     $url = "index.php?act=event";
                     $this->output .= $this->html_ann($url,$mess);
                     }
                }
      }

      function do_Del(){
                global $func,$DB,$conf;
                if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                // Begin Del Image
                $query = $DB->query("SELECT eventpic,eventpost FROM ".$conf['perfix']."events WHERE eventid=".$id);
                while ($img=$DB->fetch_row($query)) {
                       if (!empty($img['eventpic'])){
                           // Del Pic in Local Only he he
                           if ( (!strstr($img['eventpic'],"http://"))){
                                 // Lay thu muc chua anh News
                                 $dir1 = gmdate("Y",$img["eventpost"] + 7*3600);
                                 $dir2 = gmdate("m",$img["eventpost"] + 7*3600);
                                 $upload_pic = $conf['rootpath']."images/event/".$dir1."/".$dir2;
                                 $fname0 = $upload_pic."/".$img['eventpic'];
                                 $fname1 = $upload_pic."/thumb_".$img['eventpic'];
                                 $fname2 = $upload_pic."/icon_".$img['eventpic'];
                                 @unlink($fname0);
                                 @unlink($fname1);
                                 @unlink($fname2);
                           }
                       } // Het kiem tra co PIC hay khong
                }
                // End del image
                $queryn = "UPDATE ".$conf['perfix']."news SET eventid=0 WHERE eventid=".$id;
                if ($ok = $DB->query($queryn)){
                    $DB->query("DELETE FROM ".$conf['perfix']."events WHERE eventid=".$id);
                    $mess = "Delete Event successfull";
                } else
                    $mess = "Event Not Found !";
                $url = "index.php?act=event";
                $this->output .= $this->html_ann($url,$mess);
      }

      function do_View(){
               global $func,$DB,$conf;
               if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $eventid=$_GET['id']; else $eventid=0;
               if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
               $n=$conf["record"];
               $post_arr = $DB->query("SELECT * FROM ".$conf['perfix']."news WHERE eventid='{$eventid}'");
               $totals_news = $DB->num_rows($post_arr);
               $num_pages = ceil($totals_news/$n) ;
               if ($p > $num_pages) $p=$num_pages;
               if ($p < 1 ) $p=1;
               $start = ($p-1) * $n ;
               $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
               for ($i=1; $i<$num_pages+1; $i++ ) {
                    if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                    else $nav.="[<a href='?act=event&sub=view&p={$i}'>$i</a>] ";
               }
               $nav .= "</div></center>";
               $list = "";
               $stt=1;
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."news WHERE eventid='{$eventid}' LIMIT $start,$n");
               while ($data=$DB->fetch_row($query)) {
                      $data['stt'] = $stt+(($p-1)*$n);
                      $newsid = $data["newsid"];
                      $active = $data["isdisplay"];
                      if ($active==0) $data['view'] = "no";
                      else $data['view'] = "";
                      if (empty($data['picture'])) $data['picture'] = "<i>No Image</i>";
                      else {
                            if ( (!strstr($data['picture'],"http://"))){
                                  $tmp = explode("-",$data['adddate']);
                                  $folder = $conf['rooturl']."images/news/".$tmp[0]."/".$tmp[1]."/".$tmp[2]."/";
                                  $src = $folder.$data['picture'];
                            } else  $src = $data['picture'];
                            $data['picture']="<img onclick=\"javascript: popupImage('{$src}','','{$data["title"]}');\" src=\"images/photo.gif\" style=\"cursor:hand\" title=\"View Picture\">";
                      }
                      $data['timepost'] = gmdate("d/m/Y, h:i A",$data['timepost'] + 7*3600);
                      $list .= $this->html_row_view($data);
                      $stt++;
               }
               $check_event = $DB->query("SELECT * FROM ".$conf['perfix']."events WHERE eventid='{$eventid}'");
               $event = $DB->fetch_row($check_event);
               $nd['eid'] = $event["eventid"];
               $nd['title'] = $event["eventtitle"];
               $nd['des'] = $event["eventdes"];
               $nd['post'] = "Update time: ".gmdate("d/m/Y, H:i",$event["eventpost"] + 7*3600)." GMT+7";
               if (empty($event['eventpic'])) $nd['pic'] = "<i>No Image</i>";
               else {
                    if ( (!strstr($event['eventpic'],"http://"))){
                          $tmp1 = gmdate("Y",$event['eventpost'] + 7*3600);
                          $tmp2 = gmdate("m",$event['eventpost'] + 7*3600);
                          $folder = $conf['rooturl']."images/event/".$tmp1."/".$tmp2."/";
                          $src = $folder.$event['eventpic'];
                    } else  $src = $event['eventpic'];
                    $nd['pic']="<img onclick=\"javascript: popupImage('{$src}','','{$nd["title"]}');\" src=\"images/photo.gif\" style=\"cursor:hand\" title=\"View Picture\">";
               }
               $nd['hits'] = $event["eventview"];
               $nd['active'] = $event["active"];
               if ($nd['active']==0) $nd['active'] = "no";
               else $nd['active'] = "";
               $nd['nd'] = $list;
               $nd['tittle'] = "News Of Event &quot; {$nd['title']} &quot;";
               $this->output .= $this->html_view($nd);
               $this->output .= $nav."<br>";
      }

      function do_Manage(){
               global $func,$DB,$conf;
               if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
               $n=$conf["record"];
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."events");
               $totals_event = $DB->num_rows($query);
               $num_pages = ceil($totals_event/$n) ;
               if ($p > $num_pages) $p=$num_pages;
               if ($p < 1 ) $p=1;
               $start = ($p-1) * $n ;
               $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
               for ($i=1; $i<$num_pages+1; $i++ ) {
                    if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                    else $nav.="[<a href='?act=event&p={$i}'>$i</a>] ";
               }
               $nav .= "</div></center>";
               $list = "";
               $stt=1;
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."events LIMIT $start,$n");
               while ($data=$DB->fetch_row($query)) {
                      $data['stt'] = $stt+(($p-1)*$n);
                      $eventid = $data["eventid"];
                      $active = $data["active"];
                      if ($active==0) $data['view'] = "no";
                      else $data['view'] = "";
                      if (empty($data['eventpic'])) $data['eventpic'] = "<i>No Image</i>";
                      else {
                            if ( (!strstr($data['eventpic'],"http://"))){
                                  $tmp1 = gmdate("Y",$data['eventpost'] + 7*3600);
                                  $tmp2 = gmdate("m",$data['eventpost'] + 7*3600);
                                  $folder = $conf['rooturl']."images/event/".$tmp1."/".$tmp2."/";
                                  $src = $folder.$data['eventpic'];
                            } else  $src = $data['eventpic'];
                            $data['eventpic']="<img onclick=\"javascript: popupImage('{$src}','','{$data["eventtitle"]}');\" src=\"images/photo.gif\" style=\"cursor:hand\" title=\"View Picture\">";
                      }
                      $data['eventpost'] = gmdate("d/m/Y",$data['eventpost'] + 7*3600);
                      // Lay so News of Event
                      $queryn = $DB->query("SELECT * FROM ".$conf['perfix']."news WHERE eventid=".$eventid);
                      $data['totalnews'] = $DB->num_rows($queryn);
                      $list .= $this->html_row($data);
                      $stt++;
               }
               $nd['nd'] = $list;
               $nd['tittle'] = "Manager Events";
               $this->output .= $this->html_manage($nd);
               $this->output .= $nav."<br>";
      }

      function html_add($data){
               return<<<EOF
                        <script language=javascript>
                                function checkform(f) {
                                         var eventtitle = f.eventtitle.value;
                                         if (eventtitle == '') {
                                             alert('Plz enter Event Title');
                                             f.eventtitle.focus();
                                             return false;
                                         }
                                         var eventdes = f.eventdes.value;
                                         if (eventdes == '') {
                                             alert('Plz enter Event Description');
                                             f.eventdes.focus();
                                             return false;
                                         }
                                return true;
                        }
                </script>
                <br><table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <tr>
                       <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                          <tr>
                             <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                             <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['f_title']}</td>
                             <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                          </tr>
                       </table></td>
                    </tr>
                    <tr>
                       <td bgcolor="#FFFFFF" class="main_table" align=center>
                       <form action="index.php?act=event&sub=add" method="post" enctype="multipart/form-data" name="event"  onSubmit="return checkform(this);">
                          <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                             <tr>
                                 <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                             </tr>
                             <tr>
                                 <td width="15%" align="right">Event Title : </td>
                                 <td width="85%" align="left"><input name="eventtitle" type="text" id="eventtitle" size="80" maxlength="250" style="width:445px"></td>
                             </tr>
                             <tr>
                                 <td align="right">Event Description : </td>
                                 <td align="left"><textarea name="eventdes" cols="60" rows="5" id="eventdes" style="width:445px"></textarea></td>
                             </tr>
                             <tr>
                                 <td align="right">Image : </td>
                                 <td align="left"><input name="image" type="file" id="image" size="80" maxlength="250" style="width:445px"></td>
                             </tr>
                             <tr>
                                 <td align="right">Display : </td>
                                 <td align="left"><select name="display">
                                     <option value="1" selected>Yes</option>
                                     <option value="0">No</option>
                                 </select></td>
                             </tr>
                             <tr>
                                 <td colspan="2">
                                    <input type="submit" name="btnAdd" value="Submit"> <input type="reset" name="Submit2" value="Reset"> <input type="button" OnClick="window.location='?act=event'" value="Back To Manager Events">
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

       function html_edit($data){
               return<<<EOF
                        <script language=javascript>
                                function checkform(f) {
                                         var eventtitle = f.eventtitle.value;
                                         if (eventtitle == '') {
                                             alert('Plz enter Event Title');
                                             f.eventtitle.focus();
                                             return false;
                                         }
                                         var eventdes = f.eventdes.value;
                                         if (eventdes == '') {
                                             alert('Plz enter Event Description');
                                             f.eventdes.focus();
                                             return false;
                                         }
                                return true;
                        }
                </script>
                         <br><table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['f_tittle']}</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                   </tr>
                                </table></td>
                             </tr>
                             <tr>
                                <td bgcolor="#FFFFFF" class="main_table" align=center>
                                <form action="index.php?act=event&sub=edit" method="post" enctype="multipart/form-data" name="event" onSubmit="return checkform(this);">
                                   <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                                      <tr>
                                          <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                      </tr>
                                      <tr>
                                          <td width="15%" align="right">Event Title : </td>
                                          <td width="85%" align="left"><input name="eventtitle" type="text" id="eventtitle" size="80" maxlength="250" value="{$data['eventtitle']}" style="width:445px"></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Event Description : </td>
                                          <td align="left"><textarea name="eventdes" cols="50" rows="5" id="eventdes" style="width:445px">{$data['eventdes']}</textarea></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Image : </td>
                                          <td align="left">{$data['pic']}<input name="image" type="file" id="image" size="40" maxlength="250" style="width:445px"></td>
                                      </tr>
                                      <tr>
                                          <td align="right">Display : </td>
                                          <td align="left"><select name="display">{$data['dis_option']}</select>&nbsp;&nbsp;&nbsp;&nbsp;Update Time : &nbsp;&nbsp;&nbsp;&nbsp;{$data['adddate']}</td>
                                      </tr>
                                      <tr>
                                          <td colspan="2">
                                              <input name="e_id" type="hidden" value="{$data['eventid']}">
                                              <input type="submit" name="btnEdit" value="Submit">
                                              <input type="reset" name="Submit2" value="Reset">
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

      function html_manage($data){
               return<<<EOF
                          <script language="javascript1.2">
                                  var ppimgNW;
                                  function popupImage(src, note, title, css, border) {
                                           if (border==null) border = 0;
                                           if (note==null) note = '';
                                           if (ppimgNW != null) ppimgNW.close();

                                           ppimgNW = window.open('','POPUPIMAGE','width=1,height=1');
                                           var doc = ppimgNW.document;
                                           doc.write('<html>');
                                           doc.write('<head>');

                                           if (title!=null) doc.write('<title>'+ title +'</title>');
                                           doc.write('<style> body {'+css+'} #ppImgText{'+ css +'} #ppImg{cursor:hand}</style></head>');
                                           doc.write('<body leftmargin="0" topmargin="' + border + '" onload="doResize();">');
                                           doc.write('<div align="center">');
                                           doc.write('<img src="' + src + '" id="ppImg" onclick="self.close();" title="Close Window">');
                                           doc.write('</div>');
                                           doc.write('<div style="height:1; width:' + border + '; font-size:4pt;">');
                                           doc.write('</div>');
                                           doc.write('<div id="ppImgText" align="center">');
                                           doc.write(note);
                                           doc.write('</div>');
                                           doc.write('</body>');
                                           doc.write('</html>');

                                           doc.write('<' + 'script>');
                                           doc.write('function doResize() {');
                                           doc.write('  try { var imgW = ppImg.width, imgH = ppImg.height;');
                                           doc.write('  window.resizeTo(imgW + 8 +' + border*2 +', imgH + ppImgText.offsetHeight + 26 + '+ border*2 +');');
                                           doc.write('  setTimeout(\'doResize()\', 1000); } catch (ex) {} ');
                                           doc.write('}');
                                           doc.write('doResize(); ');
                                           doc.write('</' + 'script>');
                                         }
                          </script>
                        <br><table width="90%" border="0" cellspacing="0" cellpadding="0" align="center">
                            <tr>
                               <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                  <tr>
                                     <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                     <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle']}</td>
                                     <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                  </tr>
                               </table></td>
                            </tr>
                            <tr>
                               <td bgcolor="#FFFFFF" class="main_table" align=center>
                               <form action="index.php?act=event" method="post" name="manage" id="manage">
                                  <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                     <tr>
                                         <td width="6%" class="row_tittle">STT</td>
                                         <td width="42%" class="row_tittle">Event Title</td>
                                         <td width="7%" class="row_tittle">Picture</td>
                                         <td width="10%" class="row_tittle">Number News</td>
                                         <td width="10%" class="row_tittle">Hits</td>
                                         <td width="13%" class="row_tittle">Date Start Event</td>
                                         <td width="12%" class="row_tittle">Actions</td>
                                     </tr>
                                     {$data['nd']}
                                     <tr>
                                         <td colspan="7" class="row_tittle" align="center"><input type="button" OnClick="window.location='?act=event&sub=add'" value="Add News Event"></td>
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
                             <td class="row1">{$data['stt']}</td>
                             <td class="row" align=left>&nbsp;&nbsp;<a href="?act=event&sub=view&id={$data['eventid']}" title="View Detail Event"><b>{$data['eventtitle']}</b></a></td>
                             <td class="row">{$data['eventpic']}</td>
                             <td class="row" align="center">{$data['totalnews']}</td>
                             <td class="row" align="center">{$data['eventview']}</td>
                             <td class="row" align="center">{$data['eventpost']}</td>
                             <td class="row">
                                 <a href="?act=event&sub=view&id={$data['eventid']}"><img src="images/{$data['view']}dispay.gif" width="16" height="16" border="0" alt="View Event"></a>
                                 <a href="?act=event&sub=del&id={$data['eventid']}"><img src="images/delete.gif" width="22" height="22" border="0" alt="Delete Event" hspace="4"></a>
                                 <a href="?act=event&sub=edit&id={$data['eventid']}"><img src="images/edit.gif" width="22" height="22" border="0" alt="Edit Event"></a>&nbsp;
                             </td>
                          </tr>
EOF;
        }
        function html_view($data){
                 return<<<EOF
                          <script language="javascript1.2">
                                  var ppimgNW;
                                  function popupImage(src, note, title, css, border) {
                                           if (border==null) border = 0;
                                           if (note==null) note = '';
                                           if (ppimgNW != null) ppimgNW.close();

                                           ppimgNW = window.open('','POPUPIMAGE','width=1,height=1');
                                           var doc = ppimgNW.document;
                                           doc.write('<html>');
                                           doc.write('<head>');

                                           if (title!=null) doc.write('<title>'+ title +'</title>');
                                           doc.write('<style> body {'+css+'} #ppImgText{'+ css +'} #ppImg{cursor:hand}</style></head>');
                                           doc.write('<body leftmargin="0" topmargin="' + border + '" onload="doResize();">');
                                           doc.write('<div align="center">');
                                           doc.write('<img src="' + src + '" id="ppImg" onclick="self.close();" title="Close Window">');
                                           doc.write('</div>');
                                           doc.write('<div style="height:1; width:' + border + '; font-size:4pt;">');
                                           doc.write('</div>');
                                           doc.write('<div id="ppImgText" align="center">');
                                           doc.write(note);
                                           doc.write('</div>');
                                           doc.write('</body>');
                                           doc.write('</html>');

                                           doc.write('<' + 'script>');
                                           doc.write('function doResize() {');
                                           doc.write('  try { var imgW = ppImg.width, imgH = ppImg.height;');
                                           doc.write('  window.resizeTo(imgW + 8 +' + border*2 +', imgH + ppImgText.offsetHeight + 26 + '+ border*2 +');');
                                           doc.write('  setTimeout(\'doResize()\', 1000); } catch (ex) {} ');
                                           doc.write('}');
                                           doc.write('doResize(); ');
                                           doc.write('</' + 'script>');
                                         }
                          </script>
                          <center><br><div style="width:90%" align="left"><a href="?act=event"> <b>&laquo; Back to Main Manager Event</b></a></div></center><br>
                          <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>&nbsp;Event Detail</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                    </tr>
                                </table></td>
                             </tr>
                             <tr>
                                 <td bgcolor="#FFFFFF" class="main_table" align=center>
                                    <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                       <tr>
                                          <td width="73%" class="row" align="left" style="padding-left:5px">{$data['post']}&nbsp;&nbsp;&nbsp;| &nbsp;&nbsp;&nbsp;Views: <b>{$data['hits']}</b></td>
                                          <td width="25%" class="row">
                                             <a href="?act=event&sub=edit&id={$data['eid']}"><img src="images/{$data['active']}dispay.gif" width="16" height="16" border="0" alt="Active Event"></a>
                                             <a href="?act=event&sub=del&id={$data['eid']}"><img src="images/delete.gif" width="22" height="22" border="0" alt="Delete Event" hspace="5"></a>
                                             <a href="?act=event&sub=edit&id={$data['eid']}"><img src="images/edit.gif" width="22" height="22" border="0" alt="Edit Event"></a>&nbsp;
                                             {$data['pic']}
                                          </td>
                                       </tr>
                                       <tr>
                                          <td width="98%" colspan="2" class="row" align="left" style="padding-left:5px; padding-top:5px; padding-right:5px; padding-bottom:5px;"><div align="justify"><b>{$data['title']}</b><br><br>{$data['des']}</div></td>
                                       </tr>
                                    </table>
                                 </td>
                             </tr>
                             <tr>
                                 <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                     <tr>
                                         <td width="6" height="6" align="left"><img src="images/nav_botleft.gif" width="6" height="6"></td>
                                         <td height="6" background="images/nav_botbg.gif"><img src="images/nav_botbg.gif" width="7" height="6"></td>
                                         <td width="6" height="6" align="right"><img src="images/nav_botright.gif" width="6" height="6"></td>
                                     </tr>
                                 </table></td>
                             </tr>
                          </table><br>
                          <table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle']}</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                    </tr>
                                </table></td>
                             </tr>
                             <tr>
                                 <td bgcolor="#FFFFFF" class="main_table" align=center>
                                    <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                       <tr>
                                          <td width="6%" class="row_tittle">STT</td>
                                          <td width="49%" class="row_tittle">News Title</td>
                                          <td width="17%" class="row_tittle">Date News Post</td>
                                          <td width="8%" class="row_tittle">Hits</td>
                                          <td width="8%" class="row_tittle">Picture</td>
                                          <td width="12%" class="row_tittle">Actions</td>
                                       </tr>
                                       {$data['nd']}
                                    </table>
                                 </td>
                             </tr>
                             <tr>
                                 <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
        function html_row_view($data){
                 return<<<EOF
                          <tr>
                            <td class="row1">{$data['stt']}</td>
                            <td class="row" align="left" style="padding-left:5px">{$data['title']}</td>
                            <td class="row" align="center">{$data['timepost']}</td>
                            <td class="row" align="center">{$data['viewnum']}</td>
                            <td class="row" align="center">{$data['picture']}</td>
                            <td class="row" align="center">
                                 <a href="?act=news&sub=edit&id={$data['newsid']}"><img src="images/{$data['view']}dispay.gif" width="16" height="16" border="0" alt="Active News"></a>
                                 <a href="?act=news&sub=del&id={$data['newsid']}"><img src="images/delete.gif" width="22" height="22" border="0" alt="Delete News" hspace="4"></a>
                                 <a href="?act=news&sub=edit&id={$data['newsid']}"><img src="images/edit.gif" width="22" height="22" border="0" alt="Edit News"></a>&nbsp;
                            </td>
                          </tr>
EOF;
        }

        function html_ann($url,$mess){
                 return<<<EOF
                         <br><br><br><br><br>
                         <head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
                               <meta http-equiv='refresh' content='1; url={$url}'>
                         </head>
                         <body>
                            <table width="60%"border="0" align="center" cellpadding="0" cellspacing="0">
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
                                          <td width="100%" height="40" align="center"><font color="red">{$mess}</font></td>
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
                         </body><br><br><br><br><br>
EOF;
        }
}
?>