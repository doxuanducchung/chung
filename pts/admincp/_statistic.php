<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_statistic($sub);
class func_statistic{
      var $html="";
      var $output="";
      var $base_url="";

      function func_statistic($sub){
               global $func,$DB,$conf;
               switch ($sub) {
                       case 'view' : $this->do_View(); break;
                       default : $this->do_Manage(); break;
               }
               echo $this->output;
      }

      function do_View(){
               global $func,$DB,$conf;
               if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $user_id=$_GET['id']; else $user_id=0;
               $post_arr = $DB->query("SELECT * FROM ".$conf['perfix']."news WHERE user_id='{$user_id}'");
               $totals = 0;
               $today = 0;
               $listnumday = 10;
               $postperday = array();
               for ($i=0;$i<$listnumday;$i++) {
                    $postperday[$i] = 0;
               }
               while ($post = $DB->fetch_row($post_arr)) {
                      $time = $post['timepost']+(60*60*8);
                      $totals++;
                      for ($i=0;$i<$listnumday;$i++) {
                           if ($this->check_in_day($time,$i)) $postperday[$i]++;
                      }

               }
               $list = "";
               for ($i=0;$i<$listnumday;$i++) {
                    $data['numpost'] = $postperday[$i];
                    $data['stt'] = $i+1;
                    $data['date'] = $this->dayago($i);
                    $list .= $this->html_row_view($data);
               }
               $check_mem = $DB->query("SELECT * FROM ".$conf['perfix']."user WHERE user_id='{$user_id}'");
               $mem = $DB->fetch_row($check_mem);
               $nd['name'] = $mem["username"];
               $nd['totals'] = $mem["num_post"];
               $nd['nd'] = $list;
               $nd['tittle'] = "User post History for {$listnumday} days";
               $this->output .= $this->html_view($nd);
      }

      function check_in_day($date,$n=0) {
               $time1_today = mktime(0,0,1,date("m"),date("d"),date("Y"));
               $time2_today = mktime(23,59,59,date("m"),date("d"),date("Y"));
               $time1day = 86400;
               $time1 = $time1_today - ($n*$time1day);
               $time2 = $time2_today - ($n*$time1day);
               if (($date>$time1)&&($date<=$time2)) $ok=1;
               else $ok=0;
               return $ok;
      }

      function dayago($n=0) {
               $time_today = mktime(12,0,0,date("m"),date("d"),date("Y"));
               $time1day = 86400;
               $time = $time_today - ($n*$time1day);
               $date = date("l, <b>F j</b>, Y",$time);
               return $date;
      }

      function do_Manage(){
               global $func,$DB,$conf;
               if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
               $n=$conf["record"];
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog ");
               $nd["total_cat"] = $DB->num_rows($query);
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."news where isdisplay=1 ");
               $nd["total_news"] = $DB->num_rows($query);
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."news where isdisplay =0 ");
               $nd["total_news1"] = $DB->num_rows($query);
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."user ");
               $nd["total_user"] = $DB->num_rows($query);
               $query = $DB->query("SELECT max(num_post) FROM ".$conf['perfix']."user ");
               if ($row =$DB->fetch_row($query))  $nd["num_top"] = $row["max(num_post)"];
               $sql = "SELECT * FROM ".$conf['perfix']."user where num_post =".$nd["num_top"];
               $query = $DB->query($sql);
               $nd["top_poster"]="";
               while ($row = $DB->fetch_row($query)){
                      $user_id = $row["user_id"];
                      $username = $row["username"];
                      $nd["top_poster"].= $username.",";
               }
               $nd["top_poster"] = substr($nd["top_poster"],0,-1);
               $where="";
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."user ");
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
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."user LIMIT $start,$n");
               while ($data=$DB->fetch_row($query)) {
                      $data['stt'] = $stt+(($p-1)*$n);
                      $user_id = $data["user_id"];
                      $username = $data["username"];
                      $data['date_post'] = $func->makedate($data["date_post"]);
                      $tmp = explode ("/",$data["date_post"]);
                      $startday = mktime(12,0,0,$tmp[1],$tmp[0],$tmp[2]);
                      $today = mktime(12,0,0,date("m"),date("d"),date("Y"));
                      $num_day = @round(($today-$startday)/(60*60*24));
                      if ($num_day==0) $num_day =1;
                      $data['average'] ="<font color =red>".@round($data['num_post']/$num_day)."</font> news/day";
                      $list .= $this->html_row($data);
                      $stt++;
               }
               $nd['nd'] = $list;
               $nd['average'] = 0;
               $nd['tittle'] = "Statistic";
               $this->output .= $this->html_manage($nd);
               $this->output .= $nav."<br>";
      }

      function html_manage($data){
               return<<<EOF
                        <br><table width="90%" border="0" cellspacing="2" cellpadding="2" align="center">
                            <tr>
                               <td width="22%"><strong>Total catalog : </strong></td>
                               <td width="28%">{$data['total_cat']}</td>
                               <td width="16%"><strong>Total user post: </strong></td>
                               <td width="34%">{$data['total_user']}</td>
                            </tr>
                            <tr>
                               <td><strong>Total news active:</strong></td>
                               <td>{$data['total_news']}</td>
                               <td><strong>Top poster :</strong></td>
                               <td>{$data['top_poster']} ({$data['num_top']}) </td>
                            </tr>
                            <tr>
                               <td><strong>Total news watting active:</strong></td>
                               <td>{$data['total_news1']}</td>
                               <td>&nbsp;</td>
                               <td>&nbsp;</td>
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
                               <form action="index.php?act=statistic" method="post" name="manage" id="manage">
                                  <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                     <tr>
                                         <td width="10%" class="row_tittle">STT</td>
                                         <td width="30%" class="row_tittle">User Post</td>
                                         <td width="20%" class="row_tittle">Number posts</td>
                                         <td width="20%" class="row_tittle">Date begin post</td>
                                         <td width="20%" class="row_tittle">Average</td>
                                     </tr>
                                     {$data['nd']}
                                     <tr>
                                         <td width="7%" class="row_tittle">&nbsp;</td>
                                         <td colspan="4" class="row_tittle" align=left>&nbsp;</td>
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
                             <td class="row" align=left>&nbsp;&nbsp;<a href="?act=statistic&sub=view&id={$data['user_id']}"><b>{$data['username']}</b></a></td>
                             <td class="row" align="center">{$data['num_post']}</td>
                             <td class="row" align="center">{$data['date_post']}</td>
                             <td class="row">{$data['average']}</td>
                          </tr>
EOF;
        }
        function html_view($data){
                 return<<<EOF
                          <br><table width="80%" border="0" cellspacing="2" cellpadding="2" align="center">
                             <tr>
                                 <td width="22%"><strong>User Post : </strong></td>
                                 <td width="28%">{$data['name']}</td>
                                 <td width="16%"><strong>Total news post: </strong></td>
                                 <td width="34%">{$data['totals']}</td>
                             </tr>
                          </table><br>
                          <table width="80%"  border="0" align="center" cellpadding="0" cellspacing="0">
                             <tr>
                                <td><table width="100%"  border="0" cellspacing="0" cellpadding="0">
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
                                          <td width="10%" class="row_tittle">#</td>
                                          <td width="50%" class="row_tittle">Date</td>
                                          <td width="40%" class="row_tittle">Number posts </td>
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
                          <div style="padding:5px 5px 5px 5px"><a href="?act=statistic"> <b>&laquo; Back to Main Statistic</b></a></div><br>
EOF;
        }
        function html_row_view($data){
                 return<<<EOF
                          <tr>
                            <td class="row1">{$data['stt']}</td>
                            <td class="row" align="right" style="padding-right:20px">&nbsp;{$data['date']}</td>
                            <td class="row" align="center"><b>{$data['numpost']}</b></td>
                          </tr>
EOF;
        }
}
?>