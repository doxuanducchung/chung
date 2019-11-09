<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_poll($sub);

class func_poll{
      var $html="";
      var $output="";
      var $base_url="";

      function func_poll($sub){
               global $func,$DB;
               switch ($sub) {
                       case 'add' : $this->do_Add(); break;
                       case 'edit' : $this->do_Edit(); break;
                       case 'editpoll' : $this->do_Editpoll(); break;
                       case 'del' : $this->do_Del(); break;
                       default : $this->do_Manage(); break;
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
               $data['tittle'] = "Add poll";
               $data["option_html"]="";
               $data['listcat']=$this->Get_Cat();
               if (isset($_POST["selPoll"])) $poll_id=$_POST["selPoll"];
               if ($check=$DB->fetch_row($query)) $data['err']="poll Tittle existed";
               if (!empty($_POST['btnAddPoll'])) {
                   $data = $_POST;
                   $data['tittle'] = "Add Poll Name";
                   $data['poll'] = $func->txt_HTML($data['poll']);
                   $data['ht'] = $_POST["ht"];
                   $data['err'] = "";
                   // Check for Error
                   $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll WHERE poll_name='{$data['poll']}'");
                   if ($check=$DB->fetch_row($query)) $data['err']="poll Name existed";
                   // End check
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
                      $insert_q = $DB->query("INSERT INTO ".$conf['perfix']."poll (poll_id,poll_name,incat,ht) VALUES ('','{$data['poll']}','{$data['incat']}','{$data['ht']}') ");
                      $data['err'] = "Add poll Successfull";
                   }
               }
               if (!empty($_POST['btnAddOption'])) {
                   $data = $_POST;
                   $data['tittle'] = "Add Poll Option";
                   $data['option'] = $func->txt_HTML($data['option']);
                   $data['poll_id']=$data['selPoll'];
                   $data['err'] = "";
                   // Check for Error
                   $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll_option WHERE option_name='{$data['option']}' where poll_id ='{$data['poll_id']}'");
                   if ($check=$DB->fetch_row($query)) $data['err']="Option Name existed";
                   // End check
                   if (empty($data['err'])) {
                       $insert_q = $DB->query("INSERT INTO ".$conf['perfix']."poll_option (option_id,poll_id,option_name,hits) VALUES ('','{$data['poll_id']}','{$data['option']}',0) ");
                       $data['err'] = "Add Option Successfull";
                   }
               }
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll order by poll_id ");
               while ($row=$DB->fetch_row($query)){
                      if ($poll_id==$row["poll_id"])
                          $data['option_html'].='<option value="'.$row["poll_id"].'" selected>'.$row["poll_name"]."</option>";
                      else
                          $data['option_html'].='<option value="'.$row["poll_id"].'">'.$row["poll_name"]."</option>";
               }
               $this->output .= $this->html_add($data);
      }

      function do_Editpoll(){
               global $func,$DB,$conf;
               if ((isset($_GET['poll_id'])) && (is_numeric($_GET['poll_id']))) $id=$_GET['poll_id']; else $id=0;
               if (isset($_POST['btnEditOption'])) {
                   $data = $_POST;
                   $data['tittle'] = "Edit Poll Name";
                   $data['poll_name'] = $func->txt_HTML($data['poll_name']);
                   $data['poll_id'] = $_POST['poll_id'];
                   $data['ht'] = $_POST['ht'];
                   $data['err'] = "";
                   // Check for Error
                   $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll WHERE poll_name ='{$data['poll_name']}' AND poll_id <> {$id}");
                   if ($check=$DB->fetch_row($query)) $data['err']="Poll Name Existed";
                   // End check
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
                       $query="UPDATE ".$conf['perfix']."poll SET poll_name='{$data['poll_name']}', incat='{$data['incat']}', ht='{$data['ht']}' WHERE poll_id='{$id}'";
                       $update_q = $DB->query($query);
                       $data['err'] = "Edit Poll Successfull !";
                   }
               }
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll WHERE poll_id='{$id}'");
               if ($check=$DB->fetch_row($query)){
                   $data['poll_id'] = $check['poll_id'];
                   $data['poll_name'] = $func->txt_unHTML($check['poll_name']);
                   $data['incat'] = $check['incat'];
                   $data['ht'] = $check['ht'];
                   if ($data['ht']=="0") $data['html_show'] = "<option value='0' selected>-- No --</option><option value='1'>-- Yes --</option>";
                   else $data['html_show'] = "<option value='0'>-- No --</option><option value='1' selected>-- Yes --</option>";
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
               }
               $data['tittle'] = "Edit Poll Name";
               $this->output .= $this->html_editpoll($data);
      }

      function do_Edit(){
               global $func,$DB,$conf;
               if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
               if (isset($_POST['btnEditOption'])) {
                   $data = $_POST;
                   $data['tittle'] = "Edit Poll Option";
                   $data['option'] = $func->txt_HTML($data['option']);
                   $data['poll_id'] = $_POST["poll"];
                   $data['hits'] = $_POST["hits"];
                   $data['err'] = "";
                   // Check for Error
                   $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll_option WHERE option_name ='{$data['option']}' and option_id <> {$id} ");
                   if ($check=$DB->fetch_row($query)) $data['err']="Option Name existed";
                   // End check
                   if (empty($data['err'])) {
                       $query="UPDATE ".$conf['perfix']."poll_option SET option_name='{$data['option']}', hits='{$data['hits']}' WHERE option_id='{$id}'";
                       $update_q = $DB->query($query);
                       $data['err'] = "Edit Option Successfull !";
                   }
               }
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll_option WHERE option_id='{$id}'");
               if ($check=$DB->fetch_row($query)){
                   $data['option_id'] =$check['option_id'];
                   $data['option'] = $func->txt_unHTML($check['option_name']);
                   $data['poll_id']=$check['poll_id'];
                   $data['hits']=$check['hits'];
               }
               $query = $DB->query("SELECT ".$conf['perfix']."poll_name FROM poll WHERE poll_id='{$data['poll_id']}'");
               $row=$DB->fetch_row($query);
               $data['poll_name'] = $row["poll_name"];

               $data['tittle'] = "Edit Poll Option";
               $this->output .= $this->html_edit($data);
      }

      function do_Del(){
               global $func,$DB,$conf;
               if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
               if ((isset($_GET['poll_id'])) && (is_numeric($_GET['poll_id']))){
                    $poll_id=$_GET['poll_id'];
                    $query = "DELETE FROM ".$conf['perfix']."poll WHERE poll_id=$poll_id";
                    if ($ok = $DB->query($query)){
                        $DB->query ("DELETE FROM ".$conf['perfix']."poll_option WHERE poll_id=$poll_id");
                        $mess = "Delete poll successfull";
                        $this->output .= $this->html_ann($mess);
                    }
               }
               $del=0; $qr="";
               if ($id!=0) {
                   $del=1;
                   $qr = " OR option_id='{$id}' ";
               }
               for ($i=0;$i<10;$i++) {
                   $key = "deloption_".$i;
                   if ( (isset($_POST[$key])) && (is_numeric($_POST[$key])) && ($_POST[$key]!=0) ) {
                        $del=1;
                        $qr .= " OR option_id='{$_POST[$key]}' ";
                   }
               }
               if ($del) {
                   $query = "DELETE FROM ".$conf['perfix']."poll_option WHERE option_id=-1".$qr;
                   if ($ok = $DB->query($query)){
                       $mess = "Delete Option successfull";
                   }
                   else $mess = "Optionnot found !";
                   $this->output .= $this->html_ann($mess);
               } else $this->do_Manage();
      }

      function do_Manage(){
               global $func,$DB,$conf;
               if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
               if(isset($_POST["poll"])) $poll_id =$_POST["poll"];
               elseif(isset($_GET["poll_id"])) $poll_id =$_GET["poll_id"];
                   else $poll_id ="";
               if (!empty($poll_id))
                    $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll where poll_id=$poll_id ");
               else
                    $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll");
               $totals_news = $DB->num_rows($query);
               $n=10;
               $num_pages = ceil($totals_news/$n) ;
               if ($p > $num_pages) $p=$num_pages;
               if ($p < 1 ) $p=1;
               $start = ($p-1) * $n ;
               $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
               for ($i=1; $i<$num_pages+1; $i++ ) {
                    if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                    else $nav.="[<a href='?act=poll&sub=manage&poll_id={$poll_id}&p={$i}'>$i</a>] ";
               }
               $nav .= "</div></center>";
               $list = "";
               $stt=0;
               if (!empty($poll_id)){
                   $query ="SELECT * FROM ".$conf['perfix']."poll_option WHERE poll_id ='{$poll_id}' ORDER BY option_id  DESC LIMIT $start,$n";
                   $result = $DB->query($query);
               } else {
                   $query ="SELECT * FROM ".$conf['perfix']."poll_option ORDER BY option_id  DESC LIMIT $start,$n";
                   $result = $DB->query($query);
               }
               while ($poll=$DB->fetch_row($result)) {
                      $poll['stt'] = $stt;
                      $res = $DB->query ("select * from ".$conf['perfix']."poll where poll_id =".$poll["poll_id"]);
                      if ($row=$DB->fetch_row($res))  $poll["poll_name"] = $row["poll_name"] ;
                      $list .= $this->html_row($poll);
                      $stt++;
               }
               $nd['tittle'] = "Manage poll";
               $nd['html_option']="";
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."poll order by poll_id ");
               while ($row=$DB->fetch_row($query)){
                      if ($poll_id==$row["poll_id"])
                          $nd['html_option'].='<option value="'.$row["poll_id"].'" selected>'.$row["poll_name"]."</option>";
                      else
                          $nd['html_option'].='<option value="'.$row["poll_id"].'">'.$row["poll_name"]."</option>";
               }
               $nd['nd'] = $list;
               $nd['poll_id']=$poll_id;
               $nd['num'] = $stt+2;
               $this->output .= $this->html_nav($nd);
               $this->output .= $nav."<br>";
      }

      function html_add($data){
               return<<<EOF
                       <script language=javascript>
                          function checkform(f) {
                                   var poll = f.poll.value;
                                   var song = f.option.value;
                                   if (poll == '' && option == '') {
                                       alert('Plz enter poll name or option name');
                                       f.name.focus();
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
                                   <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle']}</td>
                                   <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                </tr>
                             </table></td>
                          </tr>
                          <tr>
                             <td bgcolor="#FFFFFF" class="main_table" align=center>
                               <form action="index.php?act=poll&sub=add" method="post" name="add_news" onSubmit="return checkform(this);">
                                 <table width="100%" border="0" cellspacing="2" cellpadding="2" align=center>
                                    <tr>
                                       <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                    </tr>
                                    <tr>
                                       <td width="20%" align="right">Poll Name : </td>
                                       <td width="80%" align="left"><input name="poll" type="text" size="50" maxlength="250" value="{$data['poll']}"></td>
                                    </tr>
                                   <tr>
                                      <td width="20%" valign="top" align="right">Location : </td>
                                      <td width="80%" align="left">
                                         <table width="100%" border="0" cellspacing="1" cellpadding="1" align=left>
                                            <tr>
                                               <td width="26%" valign="top"><input name="chk_allcat" type="radio" value="1" checked> All categories <br>
                                                  <input name="chk_allcat" type="radio" value="0"> Custom categories <font color="#0000FF"><b>&rsaquo;&rsaquo;&rsaquo;&rsaquo;&rsaquo;</b> <br><br>Show Poll : <select name="ht"><option value="">- Ch&#7885;n -</option><option value="1">-- Yes --</option><option value="0">-- No --</option></select></font>
                                               </td>
                                               <td width="74%" valign="top"><div style="display:block; height:220px; border:1px solid #999999;overflow:auto;padding:2px; padding-left:20px">{$data['listcat']}</div></td>
                                            </tr>
                                         </table>
                                      </td>
                                   </tr>
                                    <tr>
                                       <td colspan="2" align="center"><input type="submit" name="btnAddPoll" value="Add Poll"></td>
                                    </tr>
                                    <tr>
                                       <td colspan="2"><hr noshade></td>
                                    </tr>
                                    <tr>
                                       <td align="right"> Poll Name : </td>
                                       <td align="left">
                                       <select name="selPoll">
                                           {$data['option_html']}
                                       </select>
                                       </td>
                                    </tr>
                                    <tr>
                                       <td align="right">Option name: </td>
                                       <td align="left"><input name="option" type="text" id="name" size="50" maxlength="250" value="{$data['option']}"></td>
                                    </tr>
                                    <tr>
                                       <td colspan="2">
                                          <input type="submit" name="btnAddOption" value="Add Poll Option">&nbsp;&nbsp;
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

        function html_edit($data){
                 return<<<EOF
                         <script language=javascript>
                            function checkform(f) {
                                     var poll = f.poll.value;
                                     var song = f.song.value;
                                     if (song == '') {
                                         alert('Plz enter poll name or song name');
                                         f.name.focus();
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
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle']}</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                    </tr>
                                </table></td>
                             </tr>
                             <tr>
                                <td bgcolor="#FFFFFF" class="main_table" align=center>
                                <form action="index.php?act=poll&sub=edit&id={$data['option_id']}" method="post" name="add_news" onSubmit="return checkform(this);">
                                  <table width="100%"  border="0" cellspacing="2" cellpadding="2" align=center>
                                    <tr>
                                       <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                    </tr>
                                    <tr>
                                       <td align="right">Poll Name : </td>
                                       <td align="left"><b>{$data['poll_name']}</b></td>
                                    </tr>
                                    <tr>
                                       <td align="right">Option name: </td>
                                       <td align="left"><input name="option" type="text" id="name" size="50" maxlength="250" value="{$data['option']}"></td>
                                    </tr>
                                    <tr>
                                       <td align="right">Option Hits: </td>
                                       <td align="left"><input name="hits" type="text" id="name" size="10" maxlength="10" value="{$data['hits']}"></td>
                                    </tr>
                                    <tr>
                                       <td colspan="2">
                                          <input type="submit" name="btnEditOption" value="Edit Option">&nbsp;&nbsp;
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

        function html_editpoll($data){
                 return<<<EOF
                         <script language=javascript>
                            function checkform(f) {
                                     var poll = f.poll.value;
                                     var song = f.song.value;
                                     if (song == '') {
                                         alert('Plz enter poll name or song name');
                                         f.name.focus();
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
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['tittle']}</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                    </tr>
                                </table></td>
                             </tr>
                             <tr>
                                <td bgcolor="#FFFFFF" class="main_table" align=center>
                                <form action="index.php?act=poll&sub=editpoll&poll_id={$data['poll_id']}" method="post" name="add_news" onSubmit="return checkform(this);">
                                  <table width="100%"  border="0" cellspacing="2" cellpadding="2" align=center>
                                    <tr>
                                       <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                    </tr>
                                    <tr>
                                       <td align="right">Poll Name Old: </td>
                                       <td align="left"><b>{$data['poll_name']}</b></td>
                                    </tr>
                                    <tr>
                                       <td align="right">Poll Name New: </td>
                                       <td align="left"><input name="poll_name" type="text" id="poll_name" size="50" maxlength="250" value="{$data['poll_name']}"></td>
                                    </tr>
                                    <tr>
                                      <td align="right" valign="top">Location : </td>
                                      <td align="left">
                                         <table width="100%" border="0" cellspacing="1" cellpadding="1" align=left>
                                           <tr>
                                              <td width="30%" valign="top">{$data['location']} <br><br>Show Poll : <select name="ht"><option value="">- Ch&#7885;n -</option>{$data['html_show']}</select></td>
                                              <td width="70%" valign="top"><div style="display:block; height:220px; border:1px solid #999999;overflow:auto;padding:2px; padding-left:20px">{$data['listcat']}</div></td>
                                           </tr>
                                         </table>
                                      </td>
                                    </tr>
                                    <tr>
                                       <td colspan="2">
                                          <input type="submit" name="btnEditOption" value="Edit Poll Name">&nbsp;&nbsp;
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

        function html_nav($data){
                 return<<<EOF
                 <script language="javascript">
                     function checkall(num){
                              for (var i = 0; i < num; i++){
                                   if ( document.manage.all.checked==true ){
                                        document.manage.elements[i].checked = true;
                                   } else {
                                        document.manage.elements[i].checked = false;
                                   }
                              }
                     }
                 </script>
                 <br><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                    <form action="index.php?act=poll&sub=manage" method="post" name="myform">
                       <tr><td><strong>Poll Name :</strong> &nbsp;
                            <select name="poll" onChange="document.myform.submit()">
                                 <option value="">--- Select Poll ---</option>
                                 {$data['html_option']}
                            </select>&nbsp;&nbsp;
                            <a href="?act=poll&sub=editpoll&poll_id={$data['poll_id']}"><img src="images/edit.gif" width="22" height="22" alt="Edit poll"></a>&nbsp;
                            <a href="?act=poll&sub=del&poll_id={$data['poll_id']}"><img src="images/delete.gif" width="22" height="22" alt="Delete poll"></a>
                       </td></tr>
                       <tr><td>&nbsp;</td></tr>
                    </form>
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
                          <form action="index.php?act=poll&sub=del" method="post" name="manage" id="manage">
                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                    <td width="10%" class="row_tittle">Delete</td>
                                    <td width="40%" class="row_tittle">Option Name</td>
                                    <td width="40%" class="row_tittle">Poll Name</td>
                                    <td width="40%" class="row_tittle">Hits</td>
                                    <td width="20%" class="row_tittle">Actions</td>
                                </tr>
                                {$data['nd']}
                                <tr>
                                    <td width="10%" class="row_tittle"><input type="checkbox" name="all" onclick="javascript:checkall({$data['num']});"></td>
                                    <td colspan=4 class="row_tittle" align=left><input type="submit" name="Submit" value="Delete seleted Option"></td>
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
                             <td class="row1"><input name="deloption_{$data['stt']}" type="checkbox" value="{$data['option_id']}"></td>
                             <td class="row" align=left><a href="?act=poll&sub=edit&id={$data['option_id']}">{$data['option_name']}</a></td>
                             <td class="row" align=left>{$data['poll_name']}</td>
                             <td class="row" align=left>{$data['hits']}</td>
                             <td class="row">
                                 <a href="?act=poll&sub=edit&id={$data['option_id']}"><img src="images/edit.gif" width="22" height="22" alt="Edit Song"></a>&nbsp;
                                 <a href="?act=poll&sub=del&id={$data['option_id']}"><img src="images/delete.gif" width="22" height="22" alt="Delete option "></a>
                             </td>
                         </tr>
EOF;
        }

        function html_ann($mess){
                 return<<<EOF
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
                         </table><br><br><br><br><br>
EOF;
        }
}

?>