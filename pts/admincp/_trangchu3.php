<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_logo($sub);
class func_logo{
      var $html="";
      var $output="";
      var $base_url="";
      var $list_cat = "";

      function func_logo($sub){
               global $func,$DB;
               switch ($sub) {
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

        function do_Edit(){
                 global $func,$DB,$conf;
                 if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                 $data['f_tittle'] = "Edit Banner Trang chu 3";
                 if ( (isset($_POST['btnEdit'])) ) {
                       $data['l_id'] =$id;
                       $data['l_name'] = $func->txt_HTML($_POST['l_name']);
                       $data['l_click'] = $_POST['l_click'];
                       $data['l_url'] = $func->txt_HTML($_POST['l_url']);
                       $data['l_link'] = $func->txt_HTML($_POST['l_link']);
                       $data['err'] = "";
                       if ($_POST['chk_upload']==1 && !empty($_FILES['image'])) {
                           $data['path']= $conf['rootpath'];
                           $data['dir']= $conf['banner'];
                           $image = $_FILES['image'];
                           $data['type'] = strtolower(substr($image['name'],strrpos($image['name'],".")+1));
                           $image['name'] = time();
                           $link_file = $data['path'].$data['dir'].$image['name'].".".$data['type'];
                           $res = copy($image['tmp_name'],$link_file);
                           $data['l_url'] = $image['name'];
                       } else {
                           if ($data['l_url']!="") {
                               $fext = strtolower(substr($data['l_url'],strrpos($data['l_url'],".")+1));
                               $data['type'] = $fext;
                               if ( ($fext=="jpg") || ($fext=="gif") || ($fext=="png") || ($fext=="bmp") || ($fext=="swf")  ) {
                                     $pname = time();
                                     $fname = $conf['rootpath'].$conf['banner'].$pname.".".$fext;
                                     $file = @fopen($fname,"w");
                                     if ( $f = @fopen($data['l_url'],"r") ) {
                                          while (! @feof($f)) {
                                                   @fwrite($file, fread($f, 1024));
                                          }
                                          @fclose($f);
                                          @fclose($file);
                                          $data['l_url'] = $pname;
                                     } else $data['err'] = "Cannot Read from this Image ! Plz save to your Computer and Upload It";
                               } else $data['err'] = "Image Type Not Support";
                           }
                       }
                       if (!empty($data['l_url'])) {
                            // Del Image
                            $query = $DB->query("SELECT img,type FROM ".$conf['perfix']."banner WHERE logo_id='{$data['l_id']}'");
                            while ($img=$DB->fetch_row($query)) {
                                   if ( (file_exists($conf['rootpath'].$conf['banner'].$img['img'].".".$img['type'])) && (!empty($img['img'])) )
                                         @unlink($conf['rootpath'].$conf['banner'].$img['img'].".".$img['type']);
                            }
                            // end del
                            $update_q = $DB->query("UPDATE ".$conf['perfix']."banner SET img='{$data['l_url']}', type ='{$data['type']}', click ='{$data['l_click']}' WHERE logo_id='{$data['l_id']}'");
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
                           $sql ="UPDATE ".$conf['perfix']."banner SET title='{$data['l_name']}',link='{$data['l_link']}',incat='{$data['incat']}', click ='{$data['l_click']}' WHERE logo_id='{$data['l_id']}'";
                           $update_q = $DB->query($sql);
                           $err = "Edit Banner Trang chu 3 Successfull";
                       }
                 }
                 $query = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE logo_id='{$id}'");
                 if ($data=$DB->fetch_row($query)) {
                     $data['f_tittle'] = "Edit Banner Trang chu 3";
                     $data['l_id'] = $data['logo_id'];
                     $data['l_name'] = $data['title'];
                     $data['l_click'] = $data['click'];
                     $src =$conf['rooturl'].$conf['banner'].$data['img'].".".$data['type'];
                     $data['l_link'] =$data['link'] ;
                     if ($data['type']=="swf"){
                         $data[html_img] ='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="415" height="80">
                                         <param name="movie" value="'.$src.'"><param name="quality" value="high">
                                         <embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="415" height="80"></embed>
                                         </object>';
                     } else
                         $data[html_img] ="<img src=\"{$src}\" width=\"415\">";
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
                 } else $this->output .= $this->html_ann("Banner Trang chu 3 not found !");
        }

        function do_Del(){
                 global $func,$DB,$conf;
                 if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
                 $del=0; $qr="";
                 if ($id!=0) {
                     $del=1;
                     $qr = " OR logo_id='{$id}' ";
                 }
                 if (isset($_POST["dellogo"]))
                     $key=$_POST["dellogo"] ;
                     for ($i=0;$i<count($key);$i++) {
                          $del=1;
                          $qr .= " OR logo_id='{$key[$i]}' ";
                     }
                     if (isset ($_POST["btnOrder"])){
                         if (isset($_POST["hlogoid"])) $logoid = $_POST["hlogoid"] ; else $logoid="";
                         if (isset($_POST["txtOrder"])) $order = $_POST["txtOrder"] ; else $order="";
                         for ($i=0;$i<count($order);$i++){
                              $update_q = $DB->query("UPDATE ".$conf['perfix']."banner SET logo_order ='{$order[$i]}' WHERE logo_id='{$logoid[$i]}'");
                         }
                     }
                     if ($del) {
                         // Del Image
                         $query = $DB->query("SELECT img,type FROM ".$conf['perfix']."banner WHERE logo_id=-1".$qr);
                         while ($img=$DB->fetch_row($query)) {
                                if ( (file_exists($conf['rootpath'].$conf['banner'].$img['img'].".".$img['type'])) && (!empty($img['img'])) )
                                @unlink($conf['rootpath'].$conf['banner'].$img['img'].".".$img['type']);
                         }
                         // End del image
                         $query = "DELETE FROM ".$conf['perfix']."banner WHERE logo_id=-1".$qr;
                         if ($ok = $DB->query($query)) $mess = "Delete Pictures successfull";
                         else $mess = "Pictures not found !";
                         $url = "?act=trangchu3&sub=manage";
                         $this->output .= $this->html_ann($url,$mess);
                     } else $this->do_Manage();
        }

        function do_Manage(){
                 global $func,$DB,$conf;
                 if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
                 $query = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE vitri='trangchu3'");
                 $totals_news = $DB->num_rows($query);
                 $n=10;
                 $num_pages = ceil($totals_news/$n) ;
                 if ($p > $num_pages) $p=$num_pages;
                 if ($p < 1 ) $p=1;
                 $start = ($p-1) * $n ;
                 $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
                 for ($i=1; $i<$num_pages+1; $i++ ) {
                      if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                      else $nav.="[<a href='?act=trangchu3&sub=manage&p={$i}'>$i</a>] ";
                 }
                 $nav .= "</div></center>";
                 $list = "";
                 $stt=0;
                 $query = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE vitri='trangchu3' ORDER BY logo_order LIMIT $start,$n");
                 while ($logo=$DB->fetch_row($query)) {
                        $logo['l_id'] = $logo['logo_id'];
                        $logo['l_order'] = $logo['logo_order'];
                        $src = $conf['rooturl'].$conf['banner'].$logo['img'].".".$logo['type'];
                        if ($logo['type']=="swf" ){
                            $logo['l_img'] = '<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="300">
                                              <param name="movie" value="'.$src.'"><param name="quality" value="high">
                                              <embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300"></embed>
                                              </object>';
                        } else
                            $logo['l_img']="<img src=\"{$src}\" width=\"300\">";
                        $logo['stt'] = $stt;
                        $logo['l_link'] =$logo['link'];
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
                 $nd['tittle'] = "Manage Banner Trang chu 3 (300.XXX Pixel)";
                 $nd['nd'] = $list;
                 $nd['num'] = $stt+2;
                 $this->output .= $listcat."<br>".$this->html_nav($nd);
                 $this->output .= $nav."<br>";
        }

       //=================Skin===================

       function html_edit($data){
                return<<<EOF
                        <br><br>
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
                        </script>
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
                             <form action="?act=trangchu3&sub=edit&id={$data['l_id']}" method="post" enctype="multipart/form-data" name="add_pic" id="add_pic"  onSubmit="return checkform(this);">
                               <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                                  <tr>
                                      <td colspan="3" align="center"><font color="red">{$data['err']}</font></td>
                                  </tr>
                                  <tr>
                                      <td align="right">Banner Trang chu 3 Title  : </td>
                                      <td align="left" colspan="2"><input name="l_name" type="text" size="66" maxlength="250" value="{$data['l_name']}">&nbsp;&nbsp;
                                      <input name="l_click" type="text" size="8" maxlength="15" value="{$data['l_click']}" style="text-align:center"></td>
                                  </tr>
                                  <tr>
                                      <td  colspan="3" align="center">{$data[html_img]}</td>
                                  </tr>
                                  <tr>
                                      <td align="right">Banner Trang chu 3 Image : </td>
                                      <td align="left" colspan="2"><input name="chk_upload" type="radio" value="0" checked> Insert Banner Trang chu 3 URL&nbsp;
                                          <input name="l_url" type="text" size="50" maxlength="250" value="{$data['l_url']}"><br>
                                          <input name="chk_upload" type="radio" value="1"> Upload Picture &nbsp;
                                          <input name="image" type="file" id="image" size="46" maxlength="250">
                                      </td>
                                  </tr>
                                  <tr>
                                      <td align="right">Banner Trang chu 3 Link : </td>
                                      <td align="left" colspan="2"><input name="l_link" type="text" size="80" maxlength="250" value="{$data['l_link']}"></td>
                                  </tr>
                                  <tr>
                                      <td align="right" valign="top" width="130">Location : </td>
                                      <td valign="top" align="left" width="185">{$data['location']}</td>
                                      <td width="230" valign="top"><div style="display:block; height:230px; width:210px; border:1px solid #999999;overflow:auto;padding:2px; padding-left:20px; text-align:left">{$data['listcat']}</div></td>
                                  </tr>
                                  <tr>
                                      <td colspan="3"><input type="submit" name="btnEdit" value="Submit"> <input type="reset" name="Submit2" value="Reset"></td>
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
                              <form action="?act=trangchu3&sub=del" method="post" name="manage" id="manage">
                                 <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                   <tr>
                                      <td width="5%" class="row_tittle">Delete</td>
                                      <td width="5%" class="row_tittle">Order</td>
                                      <td width="25%" class="row_tittle">Banner Trang chu 3 Title</td>
                                      <td width="25%" class="row_tittle">Banner Trang chu 3 Images</td>
                                      <td width="32%" class="row_tittle">Banner Trang chu 3 Link</td>
                                      <td width="8%" class="row_tittle">Actions</td>
                                   </tr>
                                   {$data['nd']}
                                   <tr>
                                      <td width="5%" class="row_tittle"><input type="checkbox" name="all" onclick="javascript:checkall({$data['num']});"></td>
                                      <td colspan=5 class="row_tittle" align=left><input type="submit" name="btnOrder" value="Edit Order"> <input type="submit" name="btnDel" value="Delete Seleted Banner Trang chu 3"> <input type="button" OnClick="window.location='?act=logoman'" value="Add New Banner Trang chu 3"></td>
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
                              <td class="row1"><input name="dellogo[]" type="checkbox" value="{$data['l_id']}"></td>
                              <td class="row"><input name="hlogoid[]" type="hidden" value="{$data['l_id']}"><input name="txtOrder[]" type="text" size="2" maxlength="2" value="{$data['l_order']}" style="text-align:center"></td>
                              <td class="row" align=left><a href="?act=trangchu3&sub=edit&id={$data['l_id']}"><b>{$data['title']}</b>&nbsp;</a><br>Location: {$data['location']}</td>
                              <td class="row">{$data['l_img']}</td>
                              <td class="row">{$data['l_link']}</td>
                              <td class="row">
                                  <a href="?act=trangchu3&sub=edit&id={$data['l_id']}"><img src="images/edit.gif" width="22" height="22" alt="Edit Banner"></a>&nbsp;
                                  <a href="?act=trangchu3&sub=del&id={$data['l_id']}"><img src="images/delete.gif" width="22" height="22" alt="Delete Banner"></a>
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