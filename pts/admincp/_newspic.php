<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Powered by CHF Media                           # ||
|| # Y!M: ducmanh11hn - Email: admin@chf.com.vn -  www.chf.com.vn               # ||
\*================================================================================*/
if (!defined('CHF_MEDIA')) Header("Location: ../index.php");
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_newspic($sub);
class func_newspic{
      var $html="";
      var $output="";
      var $base_url="";
      var $list_cat = "";

      function func_newspic($sub){
               global $func,$DB;
               switch ($sub) {
                       case 'add': $this->do_Add(); break;
                       case 'edit': $this->do_Edit(); break;
                       case 'del': $this->do_Del(); break;
                       default: $this->do_Manage(); break;
               }
               echo $this->output;
      }

      function Get_Cat($did=-1){
               global $func,$DB,$conf;
               $text.="<input name=\"incat[]\" type=checkbox value=\"0\"> <font color=\"#FF1111\">&raquo;&raquo;&raquo; <b><i>Main Page</i></b> &laquo;&laquo;&laquo;</font><br>";
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=0 ORDER BY cat_order ASC");
               while ($cat=$DB->fetch_row($query)) {
                      if ($cat['catalogid']==$did)
                          $text.="<input name=\"incat[]\" type=checkbox value=\"{$cat['catalogid']}\" checked>&nbsp;<b>{$cat['catalogname']}</b><br>";
                      else
                          $text.="<input name=\"incat[]\" type=checkbox value=\"{$cat['catalogid']}\">&nbsp;<b>{$cat['catalogname']}</b><br>";
                      $n=1;
                      $text.=$this->Get_Sub($cat['catalogid'],$n,$did);
               }
               $text.="</select>";
               return $text;
      }

      function Get_Sub($cid,$n,$did=-1){
               global $func,$DB,$conf;
               $output="";
               $k=$n;
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid={$cid} ORDER BY cat_order ASC");
               while ($cat=$DB->fetch_row($query)) {
                      if ($cat['catalogid']==$did){
                          for ($i=0;$i<$k;$i++) $output.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                          $output.="<input name=\"incat[]\" type=checkbox value=\"{$cat['catalogid']}\" checked>&nbsp;";
                          $output.="&nbsp;{$cat['catalogname']}<br>";
                      } else {
                          for ($i=0;$i<$k;$i++) $output.= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
                          $output.="<input name=\"incat[]\" type=checkbox value=\"{$cat['catalogid']}\">&nbsp;";
                          $output.="&nbsp;{$cat['catalogname']}<br>";
                      }
                      $n=$k+1;
                      $output.=$this->Get_Sub($cat['catalogid'],$n,$did);
               }
               return $output;
        }

        function do_Add(){
                 global $func,$DB,$conf;
                 $data = array();
                 $data['f_tittle'] = "Add News In Picture";
                 $data['listcat']=$this->Get_Cat();
                 if (!empty($_POST['add'])) {
                     $data['l_content'] = chop($func->txt_HTML($_POST['l_content']));
                     $data['l_active'] = $_POST['l_active'];
                     $data['err'] = "";
                     $data['l_time'] = time();
                     $tmpnp = gmdate("Y",$data['l_time'] + 7*3600);
                     if ($_POST['chk_upload']==1 && !empty($_FILES['image'])) {
                         $up['path']= $conf['rootpath'];
                         $up['dir'] = $conf['newspic'].$tmpnp."/";
                         $up['file']= $_FILES['image'];
                         $up['type']= "hinh";
                         $up['resize']= 0;
                         $up['thum']= 1;
                         $up['w']= 200; //Chieu ngang toi da cua anh dai dien thumbnail
                         $up['ato']= 500; //Chieu ngang toi da cua anh to
                         $up['timepost']= $data['l_time']; //Lay thoi gian luc dang bai
                         if (!is_dir($up['path'].$up['dir'])) {
                              mkdir($up['path'].$up['dir'],0777);
                              $handle = fopen($up['path'].$up['dir']."/index.html", "w");
                              fwrite($handle,"<script>window.location='http://www.chf.com.vn';</script>");
                              fclose($handle);
                              @chmod($up['path'].$up['dir']."/index.html", 0644);
                         }
                         $result = $func->Upload($up);
                         if (empty($result['err'])) {
                             $data['n_image']=$result['link'];
                             $data['t_image']=$result['type'];
                         } else {
                             $data['err'] = $result['err'];
                         }
                     } else
                         $data['err'] = "No NewsPicture Image selected";
                     if (empty($data['err'])) {
                         // Location In Cat
                         $data['incat']="all";
                         if ($_POST['chk_allcat']==0) {
                             $catlist = "|";
                             $incat=$_POST['incat'] ;
                             for ($i=0;$i<count($incat);$i++) {
                                  $catlist .= intval($incat[$i])."|";
                             }
                             $data['incat']=$catlist;
                         }
                         // End Location
                         // Insert CSDL
                         $insert_q = $DB->query("INSERT INTO ".$conf['perfix']."newspic (taid,taimg,tatype,tacontent,incat,active) VALUES('','{$data['l_time']}','{$data['t_image']}','{$data['l_content']}','{$data['incat']}','{$data['l_active']}')");
                         $mess = "Add News In Picture Successfull";
                         $url = "index.php?act=newspic";
                         $this->output .= $this->html_ann($url,$mess);
                      } else $this->output .= $this->html_add($data);
                 } else $this->output .= $this->html_add($data);
        }

        function do_Edit(){
                 global $func,$DB,$conf;
                 if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                 $data['f_tittle'] = "Edit News In Picture";
                 if ( (isset($_POST['btnEdit'])) ) {
                       $data['l_id'] =$id;
                       $data['l_content'] = $func->txt_HTML($_POST['l_content']);
                       $data['l_active'] = $_POST['l_active'];
                       $data['l_oldurl'] = $conf['rootpath'].$conf['newspic'].$_POST['img'];
                       $data['err'] = "";
                       $data['l_time'] = time();
                       $tmpnp = gmdate("Y",$data['l_time'] + 7*3600);
                       if ($_POST['chk_upload']==1 && !empty($_FILES['image'])) {
                           // Del Old Image
                           $query = $DB->query("SELECT taimg,tatype FROM ".$conf['perfix']."newspic WHERE taid='{$data['l_id']}'");
                           $img = $DB->fetch_row($query);
                           $data['l_timeold'] = $img['taimg'];
                           $tmpnpold = gmdate("Y",$data['l_timeold'] + 7*3600);
                           if ( (file_exists($conf['rootpath'].$conf['newspic'].$tmpnpold."/".$img['taimg'].".".$img['tatype'])) && (!empty($img['taimg'])) )
                                @unlink($conf['rootpath'].$conf['newspic'].$tmpnpold."/".$img['taimg'].".".$img['tatype']);
                           if ( (file_exists($conf['rootpath'].$conf['newspic'].$tmpnpold."/thumb_".$img['taimg'].".".$img['tatype'])) && (!empty($img['taimg'])) )
                                @unlink($conf['rootpath'].$conf['newspic'].$tmpnpold."/thumb_".$img['taimg'].".".$img['tatype']);
                           // End Del Old Image
                           $up['path']= $conf['rootpath'];
                           $up['dir'] = $conf['newspic'].$tmpnp."/";
                           $up['file']= $_FILES['image'];
                           $up['type']= "hinh";
                           $up['resize']= 0;
                           $up['thum']= 1;
                           $up['w']= 200; //Chieu ngang toi da cua anh dai dien thumbnail
                           $up['ato']= 500; //Chieu ngang toi da cua anh to
                           $up['timepost']= $data['l_time']; //Lay thoi gian luc dang bai
                           if (!is_dir($up['path'].$up['dir'])) {
                                mkdir($up['path'].$up['dir'],0777);
                                $handle = fopen($up['path'].$up['dir']."/index.html", "w");
                                fwrite($handle,"<script>window.location='http://www.chf.com.vn';</script>");
                                fclose($handle);
                                @chmod($up['path'].$up['dir']."/index.html", 0644);
                           }
                           $result = $func->Upload($up);
                           if (empty($result['err'])) {
                               $data['n_image']=$result['link'];
                               $data['t_image']=$result['type'];
                               $sql = $DB->query("UPDATE ".$conf['perfix']."newspic SET taimg='{$data['l_time']}', tatype ='{$data['t_image']}' WHERE taid='{$data['l_id']}'");
                           } else {
                               $data['err'] = $result['err'];
                           }
                       }
                       if (empty($data['err'])) {
                           // Location
                           $data['incat']="all";
                           if ($_POST['chk_allcat']==0) {
                               $catlist = "|";
                               $incat=$_POST['incat'] ;
                               for ($i=0;$i<count($incat);$i++) {
                                    $catlist .= intval($incat[$i])."|";
                               }
                               $data['incat']=$catlist;
                           }
                           // End
                           $sql ="UPDATE ".$conf['perfix']."newspic SET tacontent='{$data['l_content']}', incat='{$data['incat']}', active ='{$data['l_active']}' WHERE taid='{$data['l_id']}'";
                           $update_q = $DB->query($sql);
                           $mess = "Edit News In Picture Successfull";
                       }
                       $url = "?act=newspic";
                       $this->output .= $this->html_ann($url,$mess);
                 } else {
                       // Doc thong tin cu cua tin anh de edit
                       $query = $DB->query("SELECT * FROM ".$conf['perfix']."newspic WHERE taid='{$id}'");
                       if ($data=$DB->fetch_row($query)) {
                           $data['f_tittle'] = "Edit News In Picture";
                           $data['l_id'] = $data['taid'];
                           $data['l_content'] = $data['tacontent'];
                           $data['l_active'] = $data['active'];
                           if ( $data['l_active'] == "0" ) $data['taac'] = "<option value=\"1\">-- Yes --</option><option value=\"0\" selected>-- No --</option>";
                           if ( $data['l_active'] == "1" ) $data['taac'] = "<option value=\"1\" selected>-- Yes --</option><option value=\"0\">-- No --</option>";
                           $tmnp = gmdate("Y",$data['taimg'] + 7*3600)."/";
                           $data['l_oldurl'] = $conf['rooturl'].$conf['newspic'].$tmnp.$data['taimg'].".".$data['tatype'];
                           if ($data['type']=="swf"){
                               $src = $conf['rooturl'].$conf['newspic'].$tmnp.$data['taimg'].".".$data['tatype'];
                               $data[html_img] ='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="200" height="275">
                                                  <param name="movie" value="'.$src.'"><param name="quality" value="high">
                                                  <embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="200" height="275"></embed>
                                                  </object>';
                           } else
                               $data[html_img] ="<img src=\"{$data['l_oldurl']}\" width=\"200\" height=\"275\">";
                           $data['err']=$err;
                           // In Cat
                           $data['listcat']=$this->Get_Cat();
                           if ($data['incat']=="all") {
                               $data['location']="<input name=\"chk_allcat\" type=\"radio\" value=\"1\" checked> All categories <br>
                                                  <input name=\"chk_allcat\" type=\"radio\" value=\"0\"> Custom categories <font color=\"#0000FF\"><b>&rsaquo;&rsaquo;&rsaquo;&rsaquo;&rsaquo;</b></font>";
                           } else {
                               $data['location']="<input name=\"chk_allcat\" type=\"radio\" value=\"1\"> All categories <br>
                                                  <input name=\"chk_allcat\" type=\"radio\" value=\"0\" checked> Custom categories <font color=\"#0000FF\"><b>&rsaquo;&rsaquo;&rsaquo;&rsaquo;&rsaquo;</b></font>";
                               $catarr = explode("|",$data['incat']);
                               for ($i=0;$i<count($catarr);$i++) {
                                    $catid = $catarr[$i];
                                    if ($catid!='') {
                                        $data['listcat']=str_replace("value=\"{$catid}\"","value=\"{$catid}\" checked",$data['listcat']);
                                    }
                               }
                           }
                           // End
                           $this->output .= $this->html_edit($data);
                       } else $this->output .= $this->html_ann("?act=newspic","News In Picture Not Found!");
                 }
        }

        function do_Del(){
                 global $func,$DB,$conf;
                 if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                 $del=0; $qr="";
                 if ($id!=0) {
                     $del=1;
                     $qr = " OR taid='{$id}' ";
                 }
                 if (isset($_POST["delta"]))
                     $key=$_POST["delta"] ;
                     for ($i=0;$i<count($key);$i++) {
                          $del=1;
                          $qr .= " OR taid='{$key[$i]}' ";
                     }
                     if ($del) {
                         // Del Image
                         $query = $DB->query("SELECT taimg,tatype FROM ".$conf['perfix']."newspic WHERE taid=-1".$qr);
                         while ($img=$DB->fetch_row($query)) {
                                $tmnp = $conf['rootpath'].$conf['newspic'].gmdate("Y",$img['taimg'] + 7*3600);
                                if ( (file_exists($tmnp."/".$img['taimg'].".".$img['tatype'])) && (!empty($img['taimg'])) )
                                @unlink($tmnp."/".$img['taimg'].".".$img['tatype']);
                                if ( (file_exists($tmnp."/thumb_".$img['taimg'].".".$img['tatype'])) && (!empty($img['taimg'])) )
                                @unlink($tmnp."/thumb_".$img['taimg'].".".$img['tatype']);
                         }
                         // End del image
                         $query = "DELETE FROM ".$conf['perfix']."newspic WHERE taid=-1".$qr;
                         if ($ok = $DB->query($query)) $mess = "Delete News In Picture successfull";
                         else $mess = "News In Picture not found!";
                         $url = "?act=newspic";
                         $this->output .= $this->html_ann($url,$mess);
                     } else $this->do_Manage();
        }

        function do_Manage(){
                 global $func,$DB,$conf;
                 if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
                 $query = $DB->query("SELECT * FROM ".$conf['perfix']."newspic");
                 $totals_news = $DB->num_rows($query);
                 $n=10;
                 $num_pages = ceil($totals_news/$n) ;
                 if ($p > $num_pages) $p=$num_pages;
                 if ($p < 1 ) $p=1;
                 $start = ($p-1) * $n ;
                 $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
                 for ($i=1; $i<$num_pages+1; $i++ ) {
                      if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                      else $nav.="[<a href='?act=newspic&p={$i}'>$i</a>] ";
                 }
                 $nav .= "</div></center>";
                 $list = "";
                 $stt=0;
                 $query = $DB->query("SELECT * FROM ".$conf['perfix']."newspic ORDER BY taid DESC LIMIT $start,$n");
                 while ($logo=$DB->fetch_row($query)) {
                        $logo['l_id'] = $logo['taid'];
                        if ($logo['active']=="0") $logo['l_active'] = "no";
                        else $logo['l_active'] = "";
                        $tmnp = gmdate("Y",$logo['taimg'] + 7*3600);
                        $full = $conf['rooturl'].$conf['newspic'].$tmnp."/".$logo['taimg'].".".$logo['tatype'];
                        $src = $conf['rooturl'].$conf['newspic'].$tmnp."/thumb_".$logo['taimg'].".".$logo['tatype'];
                        if ($logo['tatype']=="swf" ){
                            $logo['l_img'] = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="200" height="275">
                                              <param name="movie" value="'.$src.'"><param name="quality" value="high">
                                              <embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="200" height="275"></embed>
                                              </object>';
                        } else
                            $logo['l_img']="<img src=\"{$src}\" width=\"200\" height=\"275\" onclick=\"javascript: popupImage('{$full}','','Full Picture For News');\" style=\"cursor:hand\" title=\"View Full Picture\">";
                        $logo['stt'] = $stt;
                        // In Cat
                        if ($logo['incat']=="all") {
                            $logo['location']="<b>All Categories</b>";
                        } else {
                            $logo['location']="";
                            $catarr = explode("|",$logo['incat']);
                            $index=1;
                            $numcat=0;
                            for ($i=0;$i<count($catarr);$i++) {
                                 $catid = $catarr[$i];
                                 if ($catid!='') {
                                     if (($catid==0)&&($index)) {
                                          $logo['location'].="<b>Main Page</b>";
                                          $index=0;
                                     } else $numcat++;
                                 }
                            }
                            if ($numcat>0) {
                                if (!$index) $logo['location'].=" + ";
                                $logo['location'].="{$numcat} categories";
                            }
                        }
                        // End
                        $list .= $this->html_row($logo);
                        $stt++;
                 }
                 $nd['tittle'] = "Manage News In Picture (Thumbnail Fixed: 200.275 Pixel)";
                 $nd['nd'] = $list;
                 $nd['num'] = $stt+2;
                 $this->output .= $listcat."<br>".$this->html_nav($nd);
                 $this->output .= $nav."<br>";
        }

      //=================Skin===================

       function html_add($data){
                return<<<EOF
                         <script language=javascript>
                                 function checkform(f) {
                                          var cat = f.p_cat.value;
                                          if ( cat == '0' ){
                                               alert("Plz choice Category");
                                               f.p_cat.focus();
                                               return false;
                                          }
                                          return true;
                                 }
                         </script><br>
                         <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
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
                              <form action="?act=newspic&sub=add" method="post" enctype="multipart/form-data" onSubmit="return checkform(this);">
                                 <table width="100%"  border="0" cellspacing="2" cellpadding="2" align=center>
                                   <tr>
                                      <td colspan="3" align="center"><input name="add" type="hidden" id="add" value="1">{$data['err']}</td>
                                   </tr>
                                   <tr>
                                      <td align="right">Picture : </td>
                                      <td align="left" colspan="2"><input name="chk_upload" type="radio" value="1" checked> Upload
                                          <input name="image" type="file" id="image" size="80" maxlength="250" style="width:380px"></td>
                                   </tr>
                                   <tr>
                                      <td align="right">Content : </td>
                                      <td align="left" colspan="2"><textarea name="l_content" id="l_content" cols="20" rows="10" style="width:435px;height:80px"></textarea></td>
                                   </tr>
                                   <tr>
                                      <td valign="top" align="right" width="130" height="40">Location : </td>
                                      <td width="165" align="left" valign="top"><input name="chk_allcat" type="radio" value="1" checked> All categories <br>
                                                  <input name="chk_allcat" type="radio" value="0"> Custom categories <font color="#0000FF"><b>&rsaquo;&rsaquo;&rsaquo;&rsaquo;&rsaquo;</b></font></td>
                                      <td width="230" valign="top" rowspan="2"><div style="display:block; height:210px; width:250px; border:1px solid #999999; overflow:auto; padding:2px; padding-left:15px; text-align:left">{$data['listcat']}</div></td>
                                   </tr>
                                   <tr>
                                      <td valign="top" align="right" width="130" height="170">Active : </td>
                                      <td align="left" valign="top">
                                          <select name="l_active" id="l_active" style="width:70px">
                                              <option value="1">-- Yes --</option>
                                              <option value="0">-- No --</option>
                                          </select><br><br>
                                      </td>
                                   </tr>
                                   <tr>
                                      <td colspan="3"><input type="submit" name="Submit" value="Submit"> <input type="reset" name="Submit2" value="Reset"> <input type="button" OnClick="window.location='?act=newspic'" value="Back To Manager News In Picture"></td>
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
                         </table><br><br>
EOF;
       }

       function html_edit($data){
                return<<<EOF
                        <script language=javascript>
                           function checkform(f) {
                                    var cat = f.p_cat.value;
                                    if ( cat == '0' ){
                                         alert("Plz choice Category");
                                         f.p_cat.focus();
                                         return false;
                                    }
                                    return true;
                           }
                        </script><br>
                        <table width="650" border="0" align="center" cellpadding="0" cellspacing="0">
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
                             <form action="?act=newspic&sub=edit&id={$data['l_id']}" method="post" enctype="multipart/form-data" onSubmit="return checkform(this);">
                               <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                                  <tr>
                                      <td colspan="3" align="center"><font color="red">{$data['err']}</font></td>
                                  </tr>
                                  <tr>
                                      <td  colspan="3" align="center">{$data[html_img]}<input name="img" type="hidden" value="{$data['img']}"></td>
                                  </tr>
                                  <tr>
                                      <td align="right">Picture : </td>
                                      <td align="left" colspan="2"><input name="chk_upload" type="radio" value="1"> Upload
                                          <input name="image" type="file" id="image" size="46" maxlength="250" style="width:380px">
                                      </td>
                                  </tr>
                                  <tr>
                                      <td align="right">Content  : </td>
                                      <td align="left" colspan="2"><textarea name="l_content" cols="20" rows="10" style="width:435px;height:70px">{$data['l_content']}</textarea></td>
                                  </tr>
                                  <tr>
                                      <td valign="top" align="right" width="130" height="40">Location : </td>
                                      <td width="165" align="left" valign="top">{$data['location']}</td>
                                      <td width="230" valign="top" rowspan="2"><div style="display:block; height:210px; width:250px; border:1px solid #999999; overflow:auto; padding:2px; padding-left:15px; text-align:left">{$data['listcat']}</div></td>
                                  </tr>
                                  <tr>
                                      <td valign="top" align="right" width="130" height="150">Active : </td>
                                      <td align="left" valign="top">
                                          <select name="l_active" id="l_active" style="width:70px">{$data['taac']}</select>
                                      </td>
                                  </tr>
                                  <tr>
                                      <td colspan="3"><input type="submit" name="btnEdit" value="Submit"> <input type="reset" name="Submit2" value="Reset"> <input type="button" OnClick="window.location='?act=newspic'" value="Back To Manager News In Picture"></td>
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
                        </table><br><br>
EOF;
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
                                     doc.write('<img src="' + src + '" id="ppImg" onclick="self.close();" title="Close">');
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
                        <br><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                              <form action="?act=newspic&sub=del" method="post" name="manage" id="manage">
                                 <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                   <tr>
                                      <td width="5%" class="row_tittle">Delete</td>
                                      <td width="40%" class="row_tittle">Content</td>
                                      <td width="25%" class="row_tittle">Images</td>
                                      <td width="22%" class="row_tittle">Active In Catalog</td>
                                      <td width="8%" class="row_tittle">Actions</td>
                                   </tr>
                                   {$data['nd']}
                                   <tr>
                                      <td width="5%" class="row_tittle"><input type="checkbox" name="all" onclick="javascript:checkall({$data['num']});"></td>
                                      <td colspan="4" class="row_tittle" align=left><input type="submit" name="btnDel" value="Delete Seleted News In Picture"> <input type="button" OnClick="window.location='?act=newspic&sub=add'" value="Add News In Picture"></td>
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
                              <td class="row1"><input name="delta[]" type="checkbox" value="{$data['l_id']}"></td>
                              <td class="row" align="left" style="padding-left:5px"><a href="?act=newspic&sub=edit&id={$data['l_id']}">{$data['tacontent']}</b>&nbsp;</a></td>
                              <td class="row">{$data['l_img']}</td>
                              <td class="row"><img src="images/{$data['l_active']}dispay.gif" width="16" height="16" border="0"><br><br>Location: {$data['location']}</td>
                              <td class="row">
                                  <a href="?act=newspic&sub=edit&id={$data['l_id']}"><img src="images/edit.gif" width="22" height="22" alt="Edit News In Picture"></a>&nbsp;
                                  <a href="?act=newspic&sub=del&id={$data['l_id']}"><img src="images/delete.gif" width="22" height="22" alt="Delete News In Picture"></a>
                              </td>
                          </tr>
EOF;
       }

       function html_ann($url,$mess){
                return<<<EOF
                         <br><br><br><br><br><meta http-equiv='refresh' content='2; url={$url}'>
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
                         </table><br><br><br><br><br>
EOF;
        }
}

?>