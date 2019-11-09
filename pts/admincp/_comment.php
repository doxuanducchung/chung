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
                    $data['e_pic'] = $func->txt_HTML($data['eventpic']);
                    $data['e_post'] = time();
                    $data['err'] = "";
                    // Check for Error
                    $query ="SELECT * FROM ".$conf['perfix']."comment WHERE eventtitle='{$data['e_title']}'";
                    $query = $DB->query($query);
                    if ($check=$DB->fetch_row($query)) $data['err']="Nguoi viet Existed";
                    // Kiem tra xem co update anh khong
                    if (!empty($data['e_pic'])) {
                         $query = "INSERT INTO ".$conf['perfix']."comment VALUES ('','{$data['e_title']}','{$data['e_des']}','{$data['e_pic']}','{$data['e_post']}','0','{$data['display']}')";
                         $insert_q = $DB->query($query);
                         $mess = "Update Pic & Add Event Successfull";
                    } else {
                         // Neu khong update pic for Event
                         if (empty($data['err'])) {
                             $query="INSERT INTO ".$conf['perfix']."comment VALUES ('','{$data['e_title']}','{$data['e_des']}','','{$data['e_post']}','0','{$data['display']}')";
                             $insert_q = $DB->query($query);
                             $mess = "No Update Pic & Add Event Successfull";
                         }
                    } // Het kiem tra Update Event Pic
                    $url = "index.php?act=comment";
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
                     $data['e_des'] = $data['eventdes'];
                     $data['e_pic'] = $func->txt_HTML($data['eventpic']);
                     $data['err'] = "";
                     // Check for Error Nguoi viet
                     $query = $DB->query("SELECT * FROM ".$conf['perfix']."comment WHERE cmten='{$data['e_title']}' AND cmid <> '{$data['e_id']}'");
                     if ($check = $DB->fetch_row($query)) $data['err'] = "Nguoi viet Existed";
                     // Check Update New Pic For Event
                     if ( empty($data['e_pic'])){
                         // Update vao CSDL neu co PIC moi
                         $query ="UPDATE ".$conf['perfix']."comment SET cmten='{$data['e_title']}', cmnoidung='{$data['e_des']}', cmhienthi='{$data['display']}' WHERE cmid='{$data['e_id']}'";
                         $update_q = $DB->query($query);
                         $mess = "Update New Pic & Edit Event Successfull !";
                     } 
                     $url = "index.php?act=comment";
                     $this->output .= $this->html_ann($url,$mess);
                } else {
                // Het Update Edit Event
                     $query = $DB->query("SELECT * FROM ".$conf['perfix']."comment WHERE cmid='{$data['e_id']}'");
                     if ($event=$DB->fetch_row($query)){
                         $event['f_tittle'] = "Edit Event";
                         $event['err'] = $data['err'];
                         $event['postold'] = $event['eventpost'];
                         $event['adddate'] = gmdate("d/m/Y, h:i A",$event['postold'] + 7*3600);
                         if (!empty($event['eventpic'])){
                              $src = $event['eventpic'];
                              $event['pic'] = "<img src=\"{$src}\" border=\"0\"><br>";
                         } else $event['pic'] = "";
                         if ($event['cmhienthi']=="1"){
                             $event['dis_option']='<option value=1 selected> Yes </option>';
                             $event['dis_option'].='<option value=0 > No </option>';
                         } else {
                             $event['dis_option']='<option value=1 > Yes </option>';
                             $event['dis_option'].='<option value=0 selected> No </option>';
                         }
                         $this->output .= $this->html_edit($event);
                     } else {
                     $mess = "Your can not Edit Event";
                     $url = "index.php?act=comment";
                     $this->output .= $this->html_ann($url,$mess);
                     }
                }
      }

      function do_Del(){
                global $func,$DB,$conf;
                if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                $queryn = "DELETE FROM ".$conf['perfix']."comment WHERE cmid=".$id;
                if ($ok = $DB->query($queryn)){
                    
                    $mess = "Delete Comment successfull";
                
                $url = "index.php?act=comment";
                $this->output .= $this->html_ann($url,$mess);
				}
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
                    else $nav.="[<a href='?act=comment&sub=view&p={$i}'>$i</a>] ";
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
                            $src = $data['picture'];
                            $data['picture']="<img onclick=\"javascript: popupImage('{$src}','','{$data["title"]}');\" src=\"images/photo.gif\" style=\"cursor:hand\" title=\"View Picture\">";
                      }
                      $data['timepost'] = gmdate("d/m/Y, h:i A",$data['timepost'] + 7*3600);
                      $list .= $this->html_row_view($data);
                      $stt++;
               }
               $check_event = $DB->query("SELECT * FROM ".$conf['perfix']."comment WHERE eventid='{$eventid}'");
               $event = $DB->fetch_row($check_event);
               $nd['eid'] = $event["eventid"];
               $nd['title'] = $event["eventtitle"];
               $nd['des'] = $event["eventdes"];
               $nd['post'] = "Update time: ".gmdate("d/m/Y, H:i",$event["eventpost"] + 7*3600)." GMT+7";
               if (empty($event['eventpic'])) $nd['pic'] = "<i>No Image</i>";
               else {
                    $src = $event['eventpic'];
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
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."comment");
               $totals_event = $DB->num_rows($query);
               $num_pages = ceil($totals_event/$n) ;
               if ($p > $num_pages) $p=$num_pages;
               if ($p < 1 ) $p=1;
               $start = ($p-1) * $n ;
               $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
               for ($i=1; $i<$num_pages+1; $i++ ) {
                    if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                    else $nav.="[<a href='?act=comment&p={$i}'>$i</a>] ";
               }
               $nav .= "</div></center>";
               $list = "";
               $stt=1;
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."comment LIMIT $start,$n");
               while ($data=$DB->fetch_row($query)) {
                      $data['stt'] = $stt+(($p-1)*$n);
                      $eventid = $data["cmid"];
                      $active = $data["cmhienthi"];
                      if ($active==0) $data['view'] = "no";
                      else $data['view'] = "";
                      if (empty($data['eventpic'])) $data['eventpic'] = "<i>No Image</i>";
                      else {
                            $src = $data['eventpic'];
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
               $nd['tittle'] = "Manager Comment";
               $this->output .= $this->html_manage($nd);
               $this->output .= $nav."<br>";
      }

      function html_add($data){
               return<<<EOF
                        <script language=javascript>
                                function checkform(f) {
                                         var eventtitle = f.eventtitle.value;
                                         if (eventtitle == '') {
                                             alert('Plz enter Nguoi viet');
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
                       <form action="index.php?act=comment&sub=add" method="post" enctype="multipart/form-data" name="event"  onSubmit="return checkform(this);">
                          <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                             <tr>
                                 <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                             </tr>
                             <tr>
                                 <td width="15%" align="right">Nguoi viet : </td>
                                 <td width="85%" align="left"><input name="eventtitle" type="text" id="eventtitle" size="80" maxlength="250" style="width:445px"></td>
                             </tr>
                             <tr>
                                 <td align="right">Noi dung : </td>
                                 <td align="left"><textarea name="eventdes" cols="60" rows="5" id="eventdes" style="width:445px"></textarea></td>
                             </tr>
                             <tr>
                                 <td align="right">Image Url: </td>
                                 <td align="left"><input name="eventpic" type="text" id="eventpic" size="80" maxlength="250" style="width:445px"></td>
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
                                    <input type="submit" name="btnAdd" value="Submit"> <input type="reset" name="Submit2" value="Reset"> <input type="button" OnClick="window.location='?act=comment'" value="Back To Manager Events">
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
                                             alert('Plz enter Nguoi viet');
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
                                <form action="index.php?act=comment&sub=edit" method="post" enctype="multipart/form-data" name="event" onSubmit="return checkform(this);">
                                   <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                                      <tr>
                                          <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                      </tr>
                                      <tr>
                                          <td width="15%" align="right">Nguoi viet : </td>
                                          <td width="85%" align="left"><input name="eventtitle" type="text" id="eventtitle" size="80" maxlength="250" value="{$data['cmten']}" style="width:445px"></td>
                                      </tr>
									  <tr>
                                          <td width="15%" align="right">Noi dung : </td>
                                          <td width="85%" align="left">{$data['cmnoidung']}</td>
                                      </tr>
                                      <tr>
                                          <td align="right">Event Description : </td>
                                          <td align="left"><textarea name="eventdes" cols="50" rows="5" id="eventdes" style="width:445px">{$data['cmnoidung']}</textarea></td>
                                      </tr>
                                      
                                      <tr>
                                          <td align="right">Display : </td>
                                          <td align="left"><select name="display">{$data['dis_option']}</select></td>
                                      </tr>
                                      <tr>
                                          <td colspan="2">
                                              <input name="e_id" type="hidden" value="{$data['cmid']}">
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
                               <form action="index.php?act=comment" method="post" name="manage" id="manage">
                                  <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                     <tr>
                                         <td width="6%" class="row_tittle">STT</td>
                                         <td width="42%" class="row_tittle">Nguoi viet</td>
                                         <td width="7%" class="row_tittle">Hien thi</td>
                                         <td width="10%" class="row_tittle">News ID</td>
                                         <td width="10%" class="row_tittle">Action</td>
                                     </tr>
                                     {$data['nd']}
                                     
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
							 
                             <td class="row" align=left>&nbsp;&nbsp;<a href="?act=comment&sub=edit&id={$data['cmid']}" title="View Detail Event"><b>{$data['cmten']}</b></a></td>
                         <td class="row1">{$data['cmhienthi']}</td>
						 <td class="row">{$data['newsid']}</td>
                             <td class="row">
                                 <a href="?act=comment&sub=edit&id={$data['cmid']}"><img src="images/edit.gif" width="22" height="22" border="0" alt="Edit Event"></a>&nbsp;
								 <a href="?act=comment&sub=del&id={$data['cmid']}"><img src="images/delete.gif" width="22" height="22" border="0" alt="Delete Event" hspace="4"></a>
                                 
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
                          <center><br><div style="width:90%" align="left"><a href="?act=comment"> <b>&laquo; Back to Main Manager Event</b></a></div></center><br>
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
                                             <a href="?act=comment&sub=edit&id={$data['eid']}"><img src="images/{$data['active']}dispay.gif" width="16" height="16" border="0" alt="Active Event"></a>
                                             <a href="?act=comment&sub=del&id={$data['eid']}"><img src="images/delete.gif" width="22" height="22" border="0" alt="Delete Event" hspace="5"></a>
                                             <a href="?act=comment&sub=edit&id={$data['eid']}"><img src="images/edit.gif" width="22" height="22" border="0" alt="Edit Event"></a>&nbsp;
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