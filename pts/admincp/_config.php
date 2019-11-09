<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_config();
class func_config{
      var $output="";
      var $base_url="";

      function func_config(){
               global $func,$DB;
               $this->do_Manage();
               echo $this->output;
      }

      function List_Email ($num,$array){
               global $func,$DB,$conf;
               $text = "";
               for ($i=0;$i<$num;$i++){
                    $text .="<input name=\"email[]\" type=\"text\"  size=\"50\" value =\"{$array[$i]}\"><br>";
               }
               return $text ;
      }

      function do_Manage (){
               global $func,$DB,$conf;
               $mess="";
               if  (isset($_POST["num"])) $num=$_POST["num"];
               if  (isset($_POST["btnThem"]))
               $num++;
               if (isset($_POST["btnBot"])){
                   $num--;
                   if ($num <1) $num=1;
               }
               if (isset($_POST["btnEdit"])){
                   $email  = $_POST["email"];
                   for ($i=0;$i<count($email);$i++) {
                        $txt_email .= $email[$i].",";
                   }
                   $txt_email = substr($txt_email,0,-1);
                   if (empty($_POST["rooturl"])){
                       $rootWeb =  $_SERVER['HTTP_REFERER'] ;
                       $rootWeb = str_replace( "index.php?act=menu", "",$rootWeb);
                       $rootWeb = str_replace( "index.php?act=config", "", $rootWeb);
                       $rootWeb = str_replace( "index.php?act=welcome", "", $rootWeb);
                   } else  $rootWeb = $_POST["rooturl"];
                   if (empty($_POST["rootpath"]))
                       $rootpath = str_replace( "chf/","",str_replace( '\\', '/',dirname(__FILE__)."/"));
                   else
                       $rootpath  = $_POST["rootpath"];
                   if (empty($_POST["dbuser"])) $dbuser = "root"; else $dbuser =$_POST["dbuser"];
                   if (empty($_POST["dbpass"])) $dbpass = ""; else $dbpass =$_POST["dbpass"];
                   if (empty($_POST["dbname"])) $dbname = "chf"; else $dbname =$_POST["dbname"];
                   if (empty($_POST["perfix"])) $perfixn = "chf_"; else $perfixn =$_POST["perfix"];
                   if (empty($_POST["logohot"])) $logohot = "images/logo/"; else $logohot =$_POST["logohot"];
                   if (empty($_POST["banner"])) $banner = "images/banner/"; else $banner =$_POST["banner"];
                   if (empty($_POST["newspic"])) $newspic = "images/newspic/"; else $newspic =$_POST["newspic"];
                   if (empty($_POST["event"])) $event = "images/event/"; else $event =$_POST["event"];
                   if (empty($_POST["backup_day"])) $backup_day = 30; else $backup_day =$_POST["backup_day"];
                   if (empty($_POST["del_after_days_local"])) $del_after_days_local = 15; else $del_after_days_local =$_POST["del_after_days_local"];
                   if (empty($_POST["time_limit"])) $time_limit = 3200; else $time_limit =$_POST["time_limit"];
                   if (empty($_POST["charset"])) $charset = "UTF-8"; else $charset =$_POST["charset"];
                   if (empty($_POST["indextitle"])) $indextitle = ":: CHF Media News ::"; else $indextitle =$_POST["indextitle"];
                   if (empty($_POST["record"])) $record = 10; else $record =$_POST["record"];
                   if (empty($_POST["hottopic_days"])) $hottopic =10; else $hottopic = intval($_POST["hottopic_days"]);
                   if (empty($_POST["cat_short"])) $cat_short =10; else $cat_short = intval($_POST["cat_short"]);
                   if (empty($_POST["cat_link"])) $cat_link =10; else $cat_link = intval($_POST["cat_link"]);
                   $new  = array( 'dbuser'      => $dbuser,
                                  'dbpass'      => $dbpass,
                                  'dbname'      => $dbname,
                                  'perfix'      => $perfixn,
                                  'rooturl'     => $rootWeb,
                                  'rootpath'    => $rootpath,
                                  'logohot'     => $logohot,
                                  'banner'      => $banner,
                                  'newspic'     => $newspic,
                                  'event'       => $event,
                                  'backup_day'  => $backup_day,
                                  'time_limit'  => $time_limit,
                                  'del_after_days_local'  => $del_after_days_local,
                                  'charset'     => $charset,
                                  'indextitle'  => $indextitle,
                                  'email'       => $txt_email,
                                  'record'      => $record,
                                  'hottopic_days'  => $hottopic,
                                  'cat_short'  => $cat_short,
                                  'cat_link'  => $cat_link,
                                );
                    foreach ( $new as $k => $v ){
                              $sql = "UPDATE ".$conf['perfix']."config set $k = '{$v}' ";
                              $ok  = $DB->query ($sql) ;
                    }
                    $file_string = "<?php\n";
                    foreach ( $new as $k => $v ){
                              $file_string .="$"."conf['".$k."']\t\t\t=\t'".$v."';\n";
                    }
                    $file_string .= "\n".'?'.'>';
                    $file = "../libs/_config.php";
                    @chmod($file,0777);
                    if ( $fh = fopen( $file, 'w' ) ){
                         fputs($fh, $file_string, strlen($file_string) );
                         fclose($fh);
                         $mess = "C&#7853;p nh&#7853;t th&#224;nh c&#244;ng c&#7845;u h&#236;nh m&#7899;i !";
                    } else {
                         $mess="Kh&#244;ng th&#7875; ghi th&#244;ng tin v&#224;o File config.php";
                    }
                    @chmod($file,0644);
               }
               $sql = "select * from ".$conf['perfix']."config";
               $result = $DB->query ($sql);
               if ($data = $DB->fetch_row($result) ){
                   if (empty($data["rooturl"])){
                       $rootWeb =  $_SERVER['HTTP_REFERER'] ;
                       $rootWeb = str_replace( "index.php?act=menu", "",$rootWeb);
                       $rootWeb = str_replace( "index.php?act=config", "", $rootWeb);
                       $rootWeb = str_replace( "index.php?act=welcome", "", $rootWeb);
                       $data["rooturl"] = $rootWeb;
                   }
                   if (empty($data["rootpath"])){
                       $data["rootpath"] = str_replace( "chf/","",str_replace( '\\', '/',dirname(__FILE__)."/"));
                   }
                   if (empty($data["record"])){
                       $data["record"] =10;
                   }
                   $arr_email = explode(",",$data['email']);
                   if (empty($num)) $num = count($arr_email);
               }
               $data['err'] = $mess;
               $data['f_tittle'] = "Manage Configure";
               $data['list_email'] = $this->List_Email ($num,$arr_email);
               $data['num'] = $num;
               $this->output.=$this->html_config ($data);
      }

      function html_config ($data){
               return<<<EOF
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
                              <td bgcolor="#FFFFFF" class="main_table" align="center">
                              <form action="?act=config" method="post" name="f_config" id="f_config" onSubmit="return checkform(this);">
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
                                  <tr>
                                     <td colspan=3 align="center"><font color=red>{$data['err']}</font>
                                       <input name="dbuser" type="hidden" value="{$data['dbuser']}">
                                       <input name="dbpass" type="hidden" value="{$data['dbpass']}">
                                       <input name="dbname" type="hidden" value="{$data['dbname']}">
                                     </td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Root URL: </strong></td>
                                     <td colspan="2" align="left"><input name="rooturl" type="text" size="50" maxlength="250" value="{$data['rooturl']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Root PATH: </strong></td>
                                     <td colspan="2" align="left"><input name="rootpath" type="text" size="50" maxlength="250" value="{$data['rootpath']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Banner PATH: </strong></td>
                                     <td colspan="2" align="left"><input name="banner" type="text" size="50" maxlength="250" value="{$data['banner']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Logo Hot PATH: </strong></td>
                                     <td colspan="2" align="left"><input name="logohot" type="text" size="50" maxlength="250" value="{$data['logohot']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Newspicture Images PATH: </strong></td>
                                     <td colspan="2" align="left"><input name="newspic" type="text" size="50" maxlength="250" value="{$data['newspic']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Event Picture PATH: </strong></td>
                                     <td colspan="2" align="left"><input name="event" type="text" size="50" maxlength="250" value="{$data['event']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Charset: </strong></td>
                                     <td colspan="2" align="left"><input name="charset" type="text"  size="50" maxlength="250" value="{$data['charset']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Index title : </strong></td>
                                     <td colspan="2" align="left"><input name="indextitle" type="text"  size="50" maxlength="250" value="{$data['indextitle']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Database User: </strong></td>
                                     <td colspan="2" align="left"><input name="dbuser" type="text" size="50" maxlength="250" value="{$data['dbuser']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Database Name: </strong></td>
                                     <td colspan="2" align="left"><input name="dbname" type="text"  size="50" maxlength="250" value="{$data['dbname']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Database Pass: </strong></td>
                                     <td colspan="2" align="left"><input name="dbpass" type="text" size="50" maxlength="250" value="{$data['dbpass']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Database Perfix: </strong></td>
                                     <td colspan="2" align="left"><input name="perfix" type="text" size="50" maxlength="250" value="{$data['perfix']}">&nbsp;<font color=red> * </font></td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Backup Day: </strong></td>
                                     <td colspan="2" align="left"><input name="backup_day" type="text" size="50" maxlength="250" value="{$data['backup_day']}"></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Set time limit: </strong></td>
                                     <td colspan="2" align="left"><input name="time_limit" type="text"  size="50" maxlength="250" value="{$data['time_limit']}"></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Delete backup day: </strong></td>
                                     <td colspan="2" align="left"><input name="del_after_days_local" type="text" size="50" maxlength="250" value="{$data['del_after_days_local']}"></td>
                                  </tr>
                                  <tr>
                                     <td>&nbsp;</td>
                                     <td colspan="2" align="left">-------------------------- o0o -------------------------</td>
                                  </tr>
                                  <tr>
                                     <td width="20%" align="right"><strong>Email contact:</strong></td>
                                     <td width="50%" align="left">{$data['list_email']}</td>
                                     <td width="30%" align="left"><input type="submit" name="btnThem" style="width:23px" value=" + ">&nbsp;&nbsp;Th&#234;m Email<br><input type="submit" name="btnBot" style="width:23px" value=" - ">&nbsp;&nbsp;B&#7899;t Email</td>
                                  </tr>
                                  <tr>
                                     <td>&nbsp;</td>
                                     <td colspan="2" align="left">------------------------- o0o --------------------------</td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>No. record/page:</strong></td>
                                     <td align="left"><input name="record" type="text"  size="50" maxlength="250" value="{$data['record']}"></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Hot Topic List Days:</strong></td>
                                     <td align="left"><input name="hottopic_days" type="text"  size="50" maxlength="250" value="{$data['hottopic_days']}"></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Short News per page:</strong></td>
                                     <td align="left"><input name="cat_short" type="text"  size="50" maxlength="250" value="{$data['cat_short']}"></td>
                                  </tr>
                                  <tr>
                                     <td align="right"><strong>Other News per page:</strong></td>
                                     <td align="left"><input name="cat_link" type="text"  size="50" maxlength="250" value="{$data['cat_link']}"></td>
                                  </tr>
                                  <tr>
                                     <td align="right">&nbsp;</td>
                                     <td colspan="2">&nbsp;</td>
                                  </tr>
                                  <tr>
                                     <td colspan="3"><input type="hidden" name="num" value="{$data['num']}"><input type="submit" name="btnEdit" value="Update >>"></td>
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
                       </table><br>
EOF;

}

         function html_ann($url,$mess){
                  return<<<EOF
                           <br><br><br><br><br>
                           <meta http-equiv='refresh' content='20; url={$url}'>
                           <table width="50%"  border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                       <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                       <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>Announcement</td>
                                       <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                    </tr>
                                </table></td>
                             </tr>
                             <tr>
                                <td bgcolor="#FFFFFF" class="main_table" align=center>
                                   <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                     <tr>
                                        <td width="100%" align=center><font color="red">{$mess}</font></td>
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
                           </table>
                           <br><br><br><br><br>
EOF;
       }
}
?>