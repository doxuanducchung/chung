<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_member($sub);
class func_member{
      var $html="";
      var $output="";
      var $base_url="";

      function func_member($sub){
               global $func,$DB,$conf;
               switch ($sub) {
                       case 'add' : $this->do_Add(); break;
                       case 'edit' : $this->do_Edit(); break;
                       case 'del' : $this->do_Del(); break;
                       default : $this->do_Manage(); break;
               }
               echo $this->output;
      }

       function List_perm($perid){
                global $func,$DB,$conf;
                $output="<select name=\"perm\">";
                if ($perid=="1")
                    $output.=" <option value=\"1\" selected>&nbsp; Member</option>";
                else
                    $output.=" <option value=\"1\">&nbsp; Member</option>";
                if ($perid=="3")
                    $output.=" <option value=\"3\" selected>&nbsp; Moderator</option>";
                else
                    $output.=" <option value=\"3\">&nbsp; Moderator</option>";
                if ($perid=="6")
                    $output.=" <option value=\"6\" selected>&nbsp; Administrator</option>";
                else
                    $output.=" <option value=\"6\">&nbsp; Administrator</option>";
                $output.="</select>";
                return $output;
       }

       function List_act($actn){
                global $func,$DB,$conf;
                $output="<select name=\"active\">";
                if ($actn=="NO")
                    $output.=" <option value=\"NO\" selected>&nbsp; No</option>";
                else
                    $output.=" <option value=\"NO\">&nbsp; No</option>";
                if ($actn=="YES")
                    $output.=" <option value=\"YES\" selected>&nbsp; Yes</option>";
                else
                    $output.=" <option value=\"YES\">&nbsp; Yes</option>";
                $output.="</select>";
                return $output;
       }

      function do_Manage(){
               global $func,$DB,$conf;
               if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
               $n = $conf["record"];
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."user");
               $totals_user = $DB->num_rows($query);
               $num_pages = ceil($totals_user/$n) ;
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
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."user LIMIT $start,$n");
               while ($data=$DB->fetch_row($query)) {
                      $data['stt'] = $stt+(($p-1)*$n);
                      $user_id = $data['user_id'];
                      $username = $data['username'];
                      // Truy van va luu tong so bai viet moi nhat cua thanh vien nay
                      $querypost = $DB->query("SELECT * FROM ".$conf['perfix']."news WHERE user_id=".$user_id);
                      $num_post = $DB->num_rows($querypost);
                      $savepost = $DB->query("UPDATE ".$conf['perfix']."user SET num_post=$num_post WHERE user_id=".$user_id);
                      // Het luu tong so bai viet moi
                      $data['date_post'] = $func->makedate($data["date_post"]);
                      if( $data['active'] == "NO") $data['active'] = "Disable";
                      else $data['active'] = "Enable";
                      if ( $data['permission'] == "1" ) $data['permission'] = "Member";
                      if ( $data['permission'] == "3" ) $data['permission'] = "Moderator";
                      if ( $data['permission'] == "6" ) $data['permission'] = "Administrator";
                      $list .= $this->html_row($data);
                      $stt++;
               }
               $nd['nd'] = $list;
               $nd['tittle'] = "Members List";
               $this->output .= $this->html_manage($nd);
               $this->output .= $nav."<br>";
      }

      function do_Add(){
               global $func,$DB,$conf;
               $data['err']="";
               if (!empty($_POST['btnAdd'])) {
                   $data['user']     = $func->txt_HTML($_POST['user']);
                   $data['pass1']    = md5($func->txt_HTML(chop($_POST['pass1'])));
                   $data['pass2']    = md5($func->txt_HTML(chop($_POST['pass2'])));
                   $data['perm']     = $_POST['perm'];
                   $data['active']   = $func->txt_HTML($_POST['active']);
                   $data['datepost'] = date("Y-m-d",time(now));

                   // Kiem tra Username
                   $row_arr = $DB->query("SELECT * FROM ".$conf['perfix']."user WHERE username='{$data['user']}'");
                   if ($row=$DB->fetch_row($row_arr)) {
                       $data['err'] = "Username ".$data['user']." Existed! Plz Enter New Username";
                   } else { // Khong ton tai Username

                       // Kiem tra mat khau va xac nhan mat khau cho User moi
                       if ($data['pass1']!=$data['pass2']) $data['err'] = "Password Confirm Not Match!";
                       else { // Mat khau va xac nhan dung
                           $query = "INSERT INTO ".$conf['perfix']."user VALUE('','{$data['user']}','{$data['pass1']}','{$data['perm']}','','{$data['datepost']}','{$data['active']}')";
                           if ($DB->query($query)) {
                               $data['err']="Add New User Successfull!";
                               echo "<meta http-equiv='refresh' content='2; url=?act=member'>";
                           } else $data['err']="Add New User Failt!";
                       }
                   }
               }
               $data['f_tittle'] = "Add New User Post";
               $this->output.=$this->html_add($data);
       }

      function do_Edit(){
               global $func,$DB,$conf;
               if ((isset($_GET['id'])) && (is_numeric($_GET['id']))){
                    $id=$_GET['id'];
               } else {
                    $mess = "Username It Not Exit!";
                    $url = "?act=member";
                    $this->output .= $this->html_ann($url,$mess);
               }
               $data['user_id']=$id;
               if (!empty($_POST['btnAdd'])) {
                   $data['user']     = $func->txt_HTML($_POST['user']);
                   $data['pass']     = $func->txt_HTML(chop($_POST['pass']));
                   $data['pass1']    = $func->txt_HTML(chop($_POST['pass1']));
                   $data['pass2']    = $func->txt_HTML(chop($_POST['pass2']));
                   $data['perm']     = $_POST['perm'];
                   $data['active']   = $func->txt_HTML($_POST['active']);
                   $data['datepost'] = date("Y-m-d",time(now));

                   // Kiem tra Username
                   $row_arr = $DB->query("SELECT * FROM ".$conf['perfix']."user WHERE username='{$data['user']}' AND user_id!='{$data['user_id']}'");
                   if ($row=$DB->fetch_row($row_arr)) {
                       $mess = "Username It Not Exit!";
                   } else {
                       if (!empty($data['pass1']) && !empty($data['pass2'])){
                           $data['pass']     = md5($data['pass']);
                           $data['pass1']    = md5($data['pass1']);
                           $data['pass2']    = md5($data['pass2']);
                           // Kiem tra mat khau cu
                           $chk_pass = $DB->query("SELECT * FROM ".$conf['perfix']."user WHERE (user_id='{$data['user_id']}' AND username='{$data['user']}') AND password='{$data['pass']}'");
                           if ($old_pass=$DB->fetch_row($chk_pass)){
                               // Kiem tra mat khau moi
                               if ($data['pass1']!=$data['pass2']) $mess = "Confirm Password Not Match!";
                               else {
                                    $query = "UPDATE ".$conf['perfix']."user SET username='{$data['user']}', password='{$data['pass1']}', permission='{$data['perm']}', active='{$data['active']}' WHERE user_id='{$data['user_id']}'";
                                    if ($DB->query($query)){
                                        $mess = "Update Successull!";
                                    } else {
                                        $mess = "Update Failt!";
                                    }
                               }
                           } else { // Mat khau cu khong dung
                               $mess = "Old Password Not Match...!?";
                           }
                       } else { // Neu khong doi mat khau
                           $query = "UPDATE ".$conf['perfix']."user SET username='{$data['user']}', permission='{$data['perm']}', active='{$data['active']}' WHERE user_id='{$data['user_id']}'";
                           if ($DB->query($query)){
                               $mess = "Update Successull!";
                           } else
                               $mess = "Update Failt!";
                       }
                   } // Het kiem tra
                   $url = "?act=member";
                   $this->output .= $this->html_ann($url,$mess);
               } else {
                   $result = $DB->query("SELECT * FROM ".$conf['perfix']."user WHERE user_id='{$data['user_id']}'");
                   $data = $DB->fetch_row($result);
                   $data['listperm'] = $this->List_perm($data['permission']);
                   $data['listact']=$this->List_act($data['active']);
                   $data['f_tittle'] = "Edit User Setting";
                   $this->output.=$this->html_edit($data);
               }
       }

      function do_Del(){
               global $func,$DB,$conf;
               if ((isset($_GET['id'])) && (is_numeric($_GET['id']))){
                    $id=$_GET['id'];
                    if ($id == "1" OR $id == "2"){
                        $mess = "Do Not Delete GOD Admin";
                    } else {
                        $data['user_id']=$id;
                        // Neu ton tai Username voi Id nhu tren thi
                        $row_arr = $DB->query("SELECT newsid FROM ".$conf['perfix']."news WHERE user_id='{$data['user_id']}'");
                        $numpost = $DB->num_rows($row_arr);
                        // Neu ton tai bai viet cua Username bi xoa thi
                        if ($numpost != "0"){
                            $userid_bot = "2";
                            $up_post = $DB->query("UPDATE ".$conf['perfix']."user SET num_post='{$numpost}' WHERE user_id='{$userid_bot}'");
                            $upd_bot = $DB->query("UPDATE ".$conf['perfix']."news SET user_id='{$userid_bot}' WHERE user_id='{$data['user_id']}'");
                            if ($up_post && $upd_bot){
                                $del_user = $DB->query("DELETE FROM ".$conf['perfix']."user WHERE user_id='{$data['user_id']}'");
                                $mess = "Delete User & Update BOT Successull!";
                            }else {
                                $mess = "Delete User & Update BOT Failt!";
                            }
                        } else {
                            // Khong ton tai bai viet cua Username bi xoa
                            $del_user = $DB->query("DELETE FROM ".$conf['perfix']."user WHERE user_id='{$data['user_id']}'");
                            if ($del_user){
                                $mess = "Delete User & Update BOT Successull!";
                            }else {
                                $mess = "Delete User & Update BOT Failt!";
                            }
                        }
                    }
                    $url = "?act=member";
                    $this->output .= $this->html_ann($url,$mess);
               } else { // Het kiem tra User_id
                    $mess = "Username It Not Exit!";
                    $url = "?act=member";
                    $this->output .= $this->html_ann($url,$mess);
               }
       }

       // Skin for Manager

       function html_manage($data){
                return<<<EOF
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
                               <form action="index.php?act=statistic" method="post" name="manage" id="manage">
                                  <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                     <tr>
                                         <td width="8%" class="row_tittle">STT</td>
                                         <td width="30%" class="row_tittle">Username</td>
                                         <td width="15%" class="row_tittle">Permission</td>
                                         <td width="15%" class="row_tittle">Number posts</td>
                                         <td width="14%" class="row_tittle">Date Begin Post</td>
                                         <td width="10%" class="row_tittle">Active</td>
                                         <td width="8%" class="row_tittle">Actions</td>
                                     </tr>
                                     {$data['nd']}
                                     <tr>
                                         <td colspan="7" class="row_tittle" align="center"><input type="button" OnClick="window.location='?act=member&sub=add'" value="Add New User Post For Site"></td>
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
                             <td class="row" align=left>&nbsp;&nbsp;<a href="?act=member&sub=edit&id={$data['user_id']}"><b>{$data['username']}</b></a></td>
                             <td class="row">{$data['permission']}</td>
                             <td class="row" align="center">{$data['num_post']}</td>
                             <td class="row" align="center">{$data['date_post']}</td>
                             <td class="row" align="center">{$data['active']}</td>
                             <td class="row">
                                 <a href="?act=member&sub=edit&id={$data['user_id']}"><img src="images/edit.gif" width="22" height="22" alt="Edit User"></a>&nbsp;
                                 <a href="?act=member&sub=del&id={$data['user_id']}"><img src="images/delete.gif" width="22" height="22" alt="Delete User"></a>
                             </td>
                          </tr>
EOF;
        }

       function html_add($data){
                return<<<EOF
                         <script language=javascript>
                             function checkform(f) {
                                      var user = f.user.value;
                                      if (user == '') {
                                          alert('Plz enter Username');
                                          f.user.focus();
                                          return false;
                                      }
                                      if (user.length <= 3) {
                                          alert('Username must at least 4 character');
                                          f.user.focus();
                                          return false;
                                      }
                                      var pass1 = f.pass1.value;
                                      if (pass1 == '') {
                                          alert('Plz enter new password');
                                          f.pass1.focus();
                                          return false;
                                      }
                                      if (pass1.length <= 5) {
                                          alert('New password must at least 6 character');
                                          f.pass1.focus();
                                          return false;
                                      }
                                      var pass2 = f.pass2.value;
                                      if (pass2 == ''){
                                          alert("Plz enter confirm password");
                                          f.pass2.focus();
                                          return false;
                                      }
                                      if (pass2.length <= 5) {
                                          alert('Confirm password must at least 6 character');
                                          f.pass2.focus();
                                          return false;
                                      }
                                      if (pass1 != pass2){
                                          alert("Password confirm not match");
                                          f.pass2.focus();
                                          return false;
                                      }
                                      return true;
                             }
                         </script>
                         <br><br><br>
                         <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                               <form action="?act=member&sub=add" method="post" name="add_user" id="add_user" onSubmit="return checkform(this);">
                                  <table width="100%"  border="0" cellspacing="2" cellpadding="2" align=center>
                                     <tr>
                                         <td colspan=2 align="center"><font color="red">{$data['err']}</font></td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Username : </td>
                                         <td width="75%" align="left"><input name="user" type="text" id="user" size="50" maxlength="250"></td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Password : </td>
                                         <td width="75%" align="left"><input name="pass1" type="password" id="pass1" size="50" maxlength="250"></td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Re-Enter Password : </td>
                                         <td width="75%" align="left"><input name="pass2" type="password" id="pass2" size="50" maxlength="250"></td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Permission : </td>
                                         <td width="75%" align="left"><Select name="perm" id="perm">
                                                                       <option value="1">&nbsp;&nbsp; Member</option>
                                                                       <option value="3">&nbsp;&nbsp; Moderator</option>
                                                                       <option value="6">&nbsp;&nbsp; Administrator</option>
                                                                      </Select>
                                         </td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Active : </td>
                                         <td width="75%" align="left"><Select name="active" id="active">
                                                                       <option value="NO">&nbsp;&nbsp; No</option>
                                                                       <option value="YES">&nbsp;&nbsp; Yes</option>
                                                                      </Select>
                                         </td>
                                     </tr>
                                     <tr>
                                         <td width="25%"></td>
                                         <td width="75%" align="left"><input type="submit" name="btnAdd" value="Submit"></td>
                                     </tr>
                                   </table>
                                </form></td>
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
                             </table>
                             <br><br><br>
EOF;
        }
       function html_edit($data){
                return<<<EOF
                         <script language=javascript>
                             function checkform(f) {
                                      var user = f.user.value;
                                      if (user == '') {
                                          alert('Plz enter Username');
                                          f.user.focus();
                                          return false;
                                      }
                                      if (user.length <= 3) {
                                          alert('Username must at least 4 character');
                                          f.user.focus();
                                          return false;
                                      }
                                      if (pass1 != pass2){
                                          alert("Password confirm not match");
                                          f.pass2.focus();
                                          return false;
                                      }
                                      return true;
                             }
                         </script>
                         <br><br><br>
                         <table width="75%" border="0" align="center" cellpadding="0" cellspacing="0">
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
                               <form action="?act=member&sub=edit&id={$data['user_id']}" method="post" name="cons_user" id="cons_user" onSubmit="return checkform(this);">
                                  <table width="100%"  border="0" cellspacing="2" cellpadding="2" align=center>
                                     <tr>
                                         <td width="25%" align="right">Username : </td>
                                         <td width="75%" align="left"><input name="user" type="text" id="user" size="50" maxlength="250" value="{$data['username']}"></td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Old Password : </td>
                                         <td width="75%" align="left"><input name="pass" type="password" id="pass" size="50" maxlength="250" value=""></td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">New Password : </td>
                                         <td width="75%" align="left"><input name="pass1" type="password" id="pass1" size="50" maxlength="250" value=""></td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Re-Enter New Password : </td>
                                         <td width="75%" align="left"><input name="pass2" type="password" id="pass2" size="50" maxlength="250" value=""></td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Permission : </td>
                                         <td width="75%" align="left">{$data['listperm']}</td>
                                     </tr>
                                     <tr>
                                         <td width="25%" align="right">Active : </td>
                                         <td width="75%" align="left">{$data['listact']}</td>
                                     </tr>
                                     <tr>
                                         <td width="25%"></td>
                                         <td width="75%" align="left"><input type="submit" name="btnAdd" value="Submit"></td>
                                     </tr>
                                   </table>
                                </form></td>
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
                             </table>
                             <br><br><br>
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
                                       <td width="100%" align="center" height="50" valign="middle"><font color="red">{$mess}</font></td>
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