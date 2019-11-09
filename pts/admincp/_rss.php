<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act = new func_rss();
class func_rss{
      var $output="";
      var $base_url="";

      function func_rss(){
               global $func,$DB;
               $this->do_Manage();
               echo $this->output;
      }

      function do_Manage (){
               global $func,$DB,$conf;
               $mess = $rsstitle = "";
               if (isset($_GET["submit"])){
                   $pubDate = date("D, d M Y h:i:s",time())." GMT";
                   if (isset($_POST["hotnews"])){
                             $rsstitle = "Hotnews CHF Media - Chf.com.vn";
                             $cat_id = 0;
                             $file = "../rss/home.rss";
                   }
                   if (isset($_POST["viet_nam"])){
                             $rsstitle = "Vi&#7879;t Nam - CHF Media";
                             $cat_id = 1;
                             $file = "../rss/viet_nam.rss";
                   }
                   if (isset($_POST["the_gioi"])){
                             $rsstitle = "Th&#7871; Gi&#7899;i - CHF Media";
                             $cat_id = 2;
                             $file = "../rss/the_gioi.rss";
                   }
                   if (isset($_POST["the_thao"])){
                             $rsstitle = "Th&#7875; Thao - CHF Media";
                             $cat_id = 3;
                             $file = "../rss/the_thao.rss";
                   }
                   if (isset($_POST["kinh_doanh"])){
                             $rsstitle = "Kinh Doanh - CHF Media";
                             $cat_id = 4;
                             $file = "../rss/kinh_doanh.rss";
                   }
                   if (isset($_POST["phap_luat"])){
                             $rsstitle = "Ph&#225;p Lu&#7853;t - CHF Media";
                             $cat_id = 25;
                             $file = "../rss/phap_luat.rss";
                   }
                   if (isset($_POST["van_hoa"])){
                             $rsstitle = "V&#259;n H&#243;a - CHF Media";
                             $cat_id = 15;
                             $file = "../rss/van_hoa.rss";
                   }
                   if (isset($_POST["khoa_hoc"])){
                             $rsstitle = "Khoa H&#7885;c - CHF Media";
                             $cat_id = 34;
                             $file = "../rss/khoa_hoc.rss";
                   }
                   if (isset($_POST["doi_song"])){
                             $rsstitle = "&#272;&#7901;i S&#7889;ng CHF Media";
                             $cat_id = 7;
                             $file = "../rss/doi_song.rss";
                   }
                   if (isset($_POST["giai_tri"])){
                             $rsstitle = "Gi&#7843;i Tr&#237; - CHF Media";
                             $cat_id = 8;
                             $file = "../rss/giai_tri.rss";
                   }
                   if (isset($_POST["suc_manh_so"])){
                             $rsstitle = "S&#7913;c M&#7841;nh S&#7889; - CHF Media";
                             $cat_id = 9;
                             $file = "../rss/suc_manh_so.rss";
                   }
                   if (isset($_POST["goc_anh"])){
                             $rsstitle = "G&#243;c &#7842;nh - CHF Media";
                             $cat_id = 43;
                             $file = "../rss/goc_anh.rss";
                   }
                   if (isset($_POST["thoi_trang"])){
                             $rsstitle = "Th&#7901;i Trang - CHF Media";
                             $cat_id = 12;
                             $file = "../rss/thoi_trang.rss";
                   }
                   if (isset($_POST["oto_xe_may"])){
                             $rsstitle = "&#212;t&#244; Xe M&#225;y - CHF Media";
                             $cat_id = 11;
                             $file = "../rss/oto_xe_may.rss";
                   }
                   if (isset($_POST["sea_game"])){
                             $rsstitle = "SEA Games 24";
                             $cat_id = 13;
                             $file = "../rss/sea_game.rss";
                   }
                   $file_string = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n<rss version=\"2.0\">\n   <channel>\n";
                   // Export Rss Hotnews
                   $file_string.= "\t<title>".$rsstitle."</title>\n";
                   $file_string.= "\t<description>".$rsstitle." - T&#7901; b&#225;o &#273;i&#7879;n t&#7917; c&#243; nhi&#7873;u &#273;&#7897;c gi&#7843; nh&#7845;t Vi&#7879;t Nam</description>\n";
                   $file_string.= "\t<link>http://www.chf.com.vn</link>\n";
                   $file_string.= "\t<copyright>CHF Media - Chf.com.vn</copyright>\n";
                   $file_string.= "\t<generator>CHF Media:http://www.chf.com.vn/rss</generator>\n";
                   $file_string.= "\t<pubDate>".$pubDate."</pubDate>\n";
                   $file_string.= "\t<lastBuildDate>".$pubDate."</lastBuildDate>\n\n";

                   // Lay du lieu Hotnews
                   $sql = "select * from ".$conf['perfix']."focus where cat_id='{$cat_id}' order by newsid DESC limit 20";
                   $result = $DB->query($sql);
                   while ($row = $DB->fetch_row($result)){
                       $newsidhot = $row["newsid"];
                       $sql ="select * from ".$conf['perfix']."news where newsid=$newsidhot";
                       $resultNews=$DB->query ($sql);
                       if ($news=$DB->fetch_row($resultNews)){
                           $title = $news["title"];
                           $short = $func->HTML($news["short"]);
                           $timepost = gmdate("D, d M Y h:i:s",$news["timepost"])." GMT";
                           // Export noi dung Hotnews
                           $file_string .= "\t\t<item>\n";
                           $file_string .= "\t\t   <title>".$title."</title>\n";
                           $file_string .= "\t\t   <description>".$short."</description>\n";
                           $file_string .= "\t\t   <link>http://www.chf.com.vn/?cmd=act:news|newsid:".$newsidhot."</link>\n";
                           $file_string .= "\t\t   <pubDate>".$timepost."</pubDate>\n";
                           $file_string .= "\t\t</item>\n\n";
                       }
                   }
                   $file_string .= "\n   </channel>\n</rss>";
                   @chmod($file,0777);
                   if ( $fh = fopen( $file, 'w' ) ){
                        fputs($fh, $file_string, strlen($file_string) );
                        fclose($fh);
                        $mess = "C&#7853;p nh&#7853;t th&#224;nh c&#244;ng RSS m&#7899;i ! ( ".$pubDate." )";
                   } else {
                        $mess = "Kh&#244;ng th&#7875; ghi th&#244;ng tin v&#224;o File RSS";
                   }
                   @chmod($file,0644);
               }
               $data['err'] = $mess;
               $data['f_tittle'] = "Manage & Export RSS For Category";
               $this->output.=$this->html_rss($data);
      }

      function html_rss($data){
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
                              <form action="?act=rss&submit=ok" method="post">
                                <table width="100%" border="0" cellspacing="2" cellpadding="2" align="center">
                                  <tr>
                                     <td colspan=2 align="center" height="20"><font color=red>{$data['err']}</font></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="hotnews" value=" Hotnews - Trang Ch&#7911;" style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="sea_game" value=" SEA Games 24 " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="viet_nam" value=" Vi&#7879;t Nam " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="the_gioi" value=" Th&#7871; Gi&#7899;i " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="the_thao" value=" Th&#7875; Thao " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="kinh_doanh" value=" Kinh Doanh " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="phap_luat" value=" Ph&#225;p Lu&#7853;t " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="van_hoa" value=" V&#259;n H&#243;a " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="khoa_hoc" value=" Khoa H&#7885;c " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="doi_song" value=" &#272;&#7901;i S&#7889;ng " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="giai_tri" value=" Gi&#7843;i Tr&#237; " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="suc_manh_so" value=" S&#7913;c M&#7841;nh S&#7889; " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="goc_anh" value=" G&#243;c &#7842;nh " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="thoi_trang" value=" Th&#7901;i Trang " style="width:250px"></td>
                                  </tr>
                                  <tr>
                                     <td colspan="2"><input type="submit" name="oto_xe_may" value=" &#212;t&#244; Xe M&#225;y " style="width:250px"></td>
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