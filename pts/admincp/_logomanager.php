<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright � 2006 by Duc Manh - CHF  15/07/06                               # ||
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
                       default: $this->do_Add(); break;
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
                 $data['f_tittle'] = "Add Banner";
                 $data['listcat']=$this->Get_Cat();
                 if (!empty($_POST['add'])) {
                     $data['l_name'] = chop($func->txt_HTML($_POST['l_name']));
                     $data['l_url'] = chop($func->txt_HTML($_POST['l_url']));
                     $data['l_link'] = chop($func->txt_HTML($_POST['l_link']));
                     $data['l_vitri'] = $func->txt_HTML($_POST['l_vitri']);
                     $data['err'] = "";
                     if ($_POST['chk_upload']==1) {
                         $data['path']= $conf['rootpath'];
                         $data['dir']= $conf['logohot'];
                         if ($data['l_vitri'] != "hot"){
                             $data['dir']= $conf['banner'];
                         }
                         $image = $_FILES['image'];
                         $data['type'] = strtolower(substr($image['name'],strrpos($image['name'],".")+1));
                         $image['name'] = time();
                         $link_file = $data['path'].$data['dir'].$image['name'].".".$data['type'];
                         $res = copy($image['tmp_name'],$link_file);
                         $data['l_url'] = $image['name'];
                     }else{
                         $fext = strtolower(substr($data['l_url'],strrpos($data['l_url'],".")+1));
                         $data['type'] =$fext;
                         if ( ($fext=="jpg") || ($fext=="gif") || ($fext=="png") || ($fext=="bmp") || ($fext=="swf") ) {
                               $pname = time();
                               if ($data['l_vitri'] == "hot"){
                                   $fname = $conf['rootpath'].$conf['logohot'].$pname.".".$fext;
                               } else {
                                   $fname = $conf['rootpath'].$conf['banner'].$pname.".".$fext;
                               }
                               $file = @fopen($fname,"w");
                               if ( $f = @fopen($data['l_url'],"r") ) {
                                     while (! @feof($f)) {
                                              @fwrite($file, fread($f, 1024));
                                     }
                                     @fclose($f); @fclose($file);
                                     $data['l_url'] = $pname;
                               } else $data['err'] = "Cannot Read from this Image ! Plz save to your Computer and Upload It";
                         } else $data['err'] = "Image Type Not Support";
                     }
                     $data['l_url'] = chop($data['l_url']);
                     if (empty($data['l_url'])) $data['err'] = "No Banner Image selected";
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
                         $insert_q = $DB->query("INSERT INTO ".$conf['perfix']."banner (vitri,logo_id,img,type,link,title,incat,logo_order,click) VALUES('{$data['l_vitri']}','','{$data['l_url']}','{$data['type']}','{$data['l_link']}','{$data['l_name']}','{$data['incat']}','0','0')");
                         $data['err'] = "Add Banner Successfull";
                         $this->output .= $this->html_add($data);
                      } else $this->output .= $this->html_add($data);
                 } else $this->output .= $this->html_add($data);
        }

      //=================Skin===================

       function html_add($data){
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
                              <form action="?act=logoman" method="post" enctype="multipart/form-data" name="add_pic" id="add_pic"  onSubmit="return checkform(this);">
                                 <table width="100%"  border="0" cellspacing="2" cellpadding="2" align=center>
                                   <tr>
                                      <td colspan="3" align="center"><input name="add" type="hidden" id="add" value="1">{$data['err']}</td>
                                   </tr>
                                   <tr>
                                      <td align="right">Banner Title : </td>
                                      <td align="left" colspan="2"><input name="l_name" type="text" id="l_name" size="80" maxlength="250" style="width:435px"></td>
                                   </tr>
                                   <tr>
                                      <td align="right">Banner Image : </td>
                                      <td align="left" colspan="2"><input name="chk_upload" type="radio" value="0" checked> Insert Banner URL &nbsp;
                                          <input name="l_url" type="text" size="80" maxlength="250" style="width:313px"> <br>
                                          <input name="chk_upload" type="radio" value="1"> Upload Picture &nbsp;
                                          <input name="image" type="file" id="image" size="80" maxlength="250" style="width:336px"></td>
                                   </tr>
                                   <tr>
                                      <td align="right">Banner Link : </td>
                                      <td align="left" colspan="2"><input name="l_link" type="text" size="80" maxlength="250" value="http://" style="width:435px"></td>
                                   </tr>
                                   <tr>
                                      <td valign="top" align="right" width="130" height="40">Location : </td>
                                      <td align="left" valign="top"><input name="chk_allcat" type="radio" value="1" checked> All categories <br>
                                                  <input name="chk_allcat" type="radio" value="0"> Custom categories <font color="#0000FF"><b>&rsaquo;&rsaquo;&rsaquo;&rsaquo;&rsaquo;</b></font></td>
                                      <td width="230" valign="top" rowspan="2"><div style="display:block; height:230px; width:210px; border:1px solid #999999; overflow:auto; padding:2px; padding-left:15px; text-align:left">{$data['listcat']}</div></td>
                                   </tr>
                                   <tr>
                                      <td valign="top" align="right" width="130" height="190">Banner Type : </td>
                                      <td align="left" valign="top">
                                          <select name="l_vitri" id="l_vitri" style="width:150px">
                                              <option value="">-- Select Type --</option>
											  <option value="header1">-- Banner Header 1</option>
											  <option value="header2">-- Banner Header 2</option>
											  <option value="trangchu1">-- Trang chu 1 </option>
											  <option value="trangchu2">-- Trang chu 2 </option>
											  <option value="trangchu3">-- Trang chu 3 </option>
											  <option value="trangchu4">-- Trang chu 4 </option>
                                              <option value="newsmain1">-- News Main 1 </option>
											  <option value="newsmain2">-- News Main 2 </option>
                                              <option value="right">-- Banner Right(Lien ket) </option>
                                              
                                          </select><br><br>
										  <div style="height:18px" align="left">&raquo; Header 1: Hình ảnh( 354x80 px)<br>Flash( 300x92 px).</div>
										  <div style="height:18px" align="left">&raquo; Header 2: Hình ảnh( 354x80 px)<br>Flash( 300x92 px).</div>
                                          <div style="height:18px" align="left">&raquo; Banner Trang chu 1: Hình ảnh( 288x110 px)<br>Flash( 300x122 px)</div>
                                          <div style="height:18px" align="left">&raquo; Banner Trang chu 2: Hình ảnh( 288.xxx px)<br>Flash( 300.xxx px)</div>
                                          
                                          <div style="height:18px" align="left">&raquo; Banner Trang chu 3: Hình ảnh( 288.xxx px)<br>Flash( 300.xxx px)</div>
										  <div style="height:18px" align="left">&raquo; Banner Trang chu 4: Hình ảnh( 288.xxx px)<br>Flash( 300.xxx px)</div>
										  <div style="height:18px" align="left">&raquo; Banner Newsmain1: Hình ảnh( 288.xxx px)<br>Flash( 300.xxx px)</div>
										  <div style="height:18px" align="left">&raquo; Banner Newsmain2: Hình ảnh( 288.xxx px)<br>Flash( 300.xxx px)</div>
                                          <div style="height:18px" align="left">&raquo; Liên kết: Hình ảnh( 176.xxx px)<br>Flash( 184.xxx px)</div>
                                      </td>

                                   </tr>
                                   <tr>
                                      <td colspan="3"><input type="submit" name="Submit" value="Submit"> <input type="reset" name="Submit2" value="Reset"></td>
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
}

?>