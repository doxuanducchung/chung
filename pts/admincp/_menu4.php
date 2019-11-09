<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/
if (!session_is_registered("admin")) header("Location: index.php");

$act=new func_focus($sub);

class func_focus{
      var $html="";
      var $output="";
      var $base_url="";

      function func_focus($sub){
               global $func,$DB;
               switch ($sub) {
                       case 'add': $this->do_Add(); break;
                       case 'del': $this->do_Del(); break;
                       default: $this->do_Manage(); break;
              }
              echo $this->output;
      }

      function list_subcat($cat_id){
               global $func,$DB,$conf;
               $output="";
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid={$cat_id} ORDER BY cat_order ASC");
               while ($cat=$DB->fetch_row($query)) {
                      $output.=$cat["catalogid"].",";
                      $output.=$this->list_subcat($cat['catalogid']);
               }
               return $output;
      }

      function do_Add(){
               global $func,$DB,$conf;
               if ((isset($_GET['cat_id'])) && (is_numeric($_GET['cat_id']))) $cat_id=$_GET['cat_id'];
               $ok=0;
               $qr="";
               if (isset($_POST["addfocus"])) $key=$_POST["addfocus"] ;
               for ($i=0;$i<count($key);$i++) {
                    $ok=1;
                    $qr .= " OR newsid='{$key[$i]}' ";
               }
               if ($ok){
                   $sql="SELECT * FROM ".$conf['perfix']."news WHERE newsid=-1".$qr;
                   $query = $DB->query($sql);
                   while ($data=$DB->fetch_row($query)){
                          $newsid = $data["newsid"] ;
                          $title =  $data["title"] ;
                          $date =  $data["adddate"] ;
                          // Kiem tra xem News nay da duoc add Focus chua
                          $queryF = "SELECT * FROM ".$conf['perfix']."menu4 WHERE cat_id='{$cat_id}' AND newsid ='{$newsid}'";
                          $resultF = $DB->query($queryF) ;
                          if ($DB->num_rows($resultF)==0){
                              $insert_sql ="INSERT INTO ".$conf['perfix']."menu4 (id,cat_id,newsid) VALUES ('','{$cat_id}','{$newsid}')";
                              $DB->query($insert_sql);
                              $mess = "Add focus successfull";
                          } else {
                              $mess = "News On Focus! Add Focus Existed";
                          }
                   }
                   $ext="";
                   if (!empty ($cat_id)) $ext ="&cat_id=".$cat_id;
                   $url= "?act=menu4&sub=manage".$ext;
                   $this->output .= $this->html_ann($url,$mess);
               }else
                   $this->do_Manage();
      }

      function do_Del(){
               global $func,$DB,$conf;
               if ((isset($_GET['cat_id'])) && (is_numeric($_GET['cat_id']))) $cat_id=$_GET['cat_id']; else $cat_id=0;
               if ((isset($_GET['id'])) && (is_numeric($_GET['id']))) $id=$_GET['id']; else $id=0;
               $ok=0;
               $qr="";
               if ($id!=0) {
                   $ok=1;
                   $qr = " OR newsid='{$id}'";
               }
               if (isset($_POST["delfocus"])) $key=$_POST["delfocus"] ;
               for ($i=0;$i<count($key);$i++) {
                    $ok=1;
                    $qr .= " OR newsid='{$key[$i]}' ";
               }
               if ($ok){
                   $query = "DELETE FROM ".$conf['perfix']."menu4 WHERE cat_id=".$cat_id." AND newsid=-1".$qr;
                   if ($DB->query($query)){
                       $mess = "Delete Focus Successfull";
                   } else
                       $mess = "Focus Not Found !";
                   $ext="";
                   if (!empty ($cat_id)) $ext ="&cat_id=".$cat_id;
                   $url= "?act=menu4&sub=manage".$ext;
                   $this->output .= $this->html_ann($url,$mess);
               }else
                   $this->do_Manage();
      }

      function do_Manage(){
               global $func,$DB,$conf;
               if ((isset($_GET['p'])) && (is_numeric($_GET['p']))) $p=$_GET['p']; else $p=1;
               if ((isset($_GET['p1'])) && (is_numeric($_GET['p1']))) $p1=$_GET['p1']; else $p1=1;
               if ((isset($_GET['cat_id'])) && (is_numeric($_GET['cat_id']))) $cat_id=$_GET['cat_id'];
               if (isset($_POST["selCatalog"])) $cat_id=$_POST["selCatalog"] ;
               $ext="";
               if (!empty($cat_id)){
                   $where_focus = "where cat_id='{$cat_id}'";
                   $a_cat_id = $this->list_subCat($cat_id);
                   $a_cat_id = str_replace(",","','",$a_cat_id);
                   $where_news =" where isdisplay=1 AND catalogid in ('$cat_id','".$a_cat_id."') ";
                   $ext="&cat_id=".$cat_id ;
               } else {
                   $where_focus = "";
                   $where_news = " where isdisplay=1 ";
               }
               // List All Focus
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."menu4 $where_focus order by newsid,id DESC");
               $totals_news1 = $DB->num_rows($query);
               $n=$conf["record"];
               $num_pages1 = ceil($totals_news1/$n) ;
               if ($p1 > $num_pages1) $p1=$num_pages1;
               if ($p1 < 1 ) $p1=1;
               $start1 = ($p1-1) * $n;
               $nav = "<center><div align=\"justify\" style=\"width:90%\">&nbsp;Page : ";
               for ($i1=1; $i1<$num_pages1+1; $i1++ ) {
                    if ($i1==$p1) $nav.=" <font color=\"#FF6600\">[{$i1}]</font> ";
                    else $nav.="[<a href='?act=menu4&sub=manage{$ext}&p1={$i1}'>$i1</a>] ";
               }
               $nav .= "</div><center>";
               $list = "";
               $stt=0;
               $sql= "SELECT * FROM ".$conf['perfix']."menu4 $where_focus order by newsid DESC LIMIT $start1,$n";
               $query = $DB->query($sql);
               while ($data=$DB->fetch_row($query)) {
                      $data['stt'] = $stt;
                      // Lay thong tin Title, Adddate for News
                      $queryf = $DB->query("SELECT title,adddate,viewnum,picture FROM ".$conf['perfix']."news WHERE newsid=".$data['newsid']);
                      $fcdata = $DB->fetch_row($queryf);
                      $data['title']= $fcdata['title'];
                      $data['viewnum']= $fcdata['viewnum'];
                      if (empty($fcdata['picture'])) $data['picture'] = "<i>No Image</i>";
                      else {
                            $src = $fcdata['picture'];
                            $des = $fcdata['pic_des'];
                            $data['picture']="<img onclick=\"javascript: popupImage('{$src}','','{$des}');\" src=\"images/photo.gif\" style=\"cursor:hand\" title=\"View Picture\">";
                      }
                      $data['date'] = $func->makedate($fcdata['adddate']);
                      $list .= $this->html_row1($data);
                      $stt++;
               }
               // Show All Catalog
               $nd['cat_name'] = "";
               $nd['option'] = "";
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid=0 ORDER BY cat_order ASC");
               while ($row=$DB->fetch_row($query)){
                      if ($cat_id==$row["catalogid"]){
                          $nd['option'].='<option value="'.$row["catalogid"].'" selected>&nbsp;'.$row["catalogname"]."</option>";
                          $nd['cat_name'] = $row["catalogname"];
                      } else
                          $nd['option'].='<option value="'.$row["catalogid"].'">&nbsp;'.$row["catalogname"]."</option>";
                      $result = $DB->query ("select * from ".$conf['perfix']."catalog where parentid=".$row["catalogid"]." ORDER BY cat_order ASC");
                      while ($row1=$DB->fetch_row($result)){
                             if ($cat_id==$row1["catalogid"]){
                                 $nd['option'].='<option value="'.$row1["catalogid"].'" selected>'."&nbsp;--- ".$row1["catalogname"]."</option>";
                                 $nd['cat_name'] = $row1["catalogname"];
                             } else
                                 $nd['option'].='<option value="'.$row1["catalogid"].'">'."&nbsp;--- ".$row1["catalogname"]."</option>";
                      }
               }
               // End List_Focus - Begin List News On This Catalog
               if (!empty($nd['cat_name'])) $nd['cat_name'] = " For Catalog : ".$nd['cat_name'];
               else $nd['cat_name'] = " For System";
               $nd['title'] = "List All Focus".$nd['cat_name'];
               $nd['nd'] = $list;
               $nd['num'] = $stt+2;
               $nd['cat_id'] = $cat_id;
               $this->output .= $this->html_nav1($nd);
               $this->output .= $nav."<br>";

               // List All News on today
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."news $where_news ");
               $totals_news = $DB->num_rows($query);
               $n = $conf["record"];
               $num_pages = ceil($totals_news/$n);
               if ($p > $num_pages) $p=$num_pages;
               if ($p < 1 ) $p=1;
               $start = ($p-1) * $n;
               $nav = "<center><div align=\"justify\" style=\"width:90%\"> <b>Page : </b>";
               for ($i=1; $i<$num_pages+1; $i++ ) {
                    if ($i==$p) $nav.=" <font color=\"#FF6600\">[{$i}]</font> ";
                    else $nav.="[<a href='?act=menu4&sub=manage{$ext}&p={$i}'>$i</a>] ";
               }
               $nav .= "</div></center>";
               $list = "";
               $stt=0;
               $query = $DB->query("SELECT * FROM ".$conf['perfix']."news $where_news ORDER BY newsid DESC LIMIT $start,$n");
               while ($data=$DB->fetch_row($query)) {
                      $data['stt'] = $stt;
                      $data['date']= $func->makedate($data['adddate']);
                      if (empty($data['picture'])) $data['picture'] = "<i>No Image</i>";
                      else {
                            $src = $data['picture'];
                            $des = $data['pic_des'];
                            $data['picture']="<img onclick=\"javascript: popupImage('{$src}','','{$des}');\" src=\"images/photo.gif\" style=\"cursor:hand\" title=\"View Picture\">";
                      }
                      $list .= $this->html_row($data);
                      $stt++;
               }
               $nd['title'] = "List All News In System";
               $nd['nd'] = $list;
               $nd['cat_id'] = $cat_id;
               $nd['num'] = $stt+2;
               $this->output .= $this->html_nav($nd);
               $this->output .= $nav."<br>";
      }

      function html_nav($data){
               return<<<EOF
                       <script language="javascript">
                               function checkall_news(num){
                                        for ( i=0;i < document.manage.elements.length ; i++ ){
                                              if ( document.manage.all_news.checked==true ){
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
                                     <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['title']}</td>
                                     <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                  </tr>
                              </table></td>
                           </tr>
                           <tr>
                              <td bgcolor="#FFFFFF" class="main_table" align=center>
                              <form action="?act=menu4&cat_id={$data['cat_id']}&sub=add" method="post" name="manage" id="manage">
                                  <table width="100%"  border="0" cellspacing="1" cellpadding="1">
                                     <tr>
                                        <td width="10%" class="row_tittle">Select</td>
                                        <td width="55%" class="row_tittle">News Title</td>
                                        <td width="10%" class="row_tittle">Hits</td>
                                        <td width="10%" class="row_tittle">Picture</td>
                                        <td width="15%" class="row_tittle">Date Post</td>
                                     </tr>
                                     {$data['nd']}
                                     <tr>
                                        <td width="10%" class="row_tittle"><input type="checkbox" name="all_news" onclick="javascript:checkall_news({$data['num']});"></td>
                                        <td colspan="4" class="row_tittle" align="left" style="padding-left:5px"><input type="submit" name="btnAdd" value="Add Seleted Focus"></td>
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
                              <td class="row"><input name="addfocus[]" type="checkbox" value="{$data['newsid']}"></td>
                              <td class="row" align="left" style="padding-left:5px"><a href="?act=news&sub=edit&id={$data['newsid']}">{$data['title']}</a></td>
                              <td class="row">{$data['viewnum']}</td>
                             <td class="row">{$data['picture']}</td>
                              <td class="row">{$data['date']}</td>
                           </tr>
EOF;
        }

        function html_nav1($data){
                 return<<<EOF
                          <script language="javascript">
                                  function checkall1(num){
                                           for ( i=0;i < document.manage1.elements.length ; i++ ){
                                                 if ( document.manage1.all1.checked==true ){
                                                      document.manage1.elements[i].checked = true;
                                                 } else {
                                                      document.manage1.elements[i].checked  = false;
                                                 }
                                           }
                                  }
                          </script>
                          <br><table width="90%" border="0" align="center" cellpadding="0" cellspacing="0">
                              <form action="?act=menu4&sub=manage" method="post" name="myform">
                                  <tr>
                                      <td><strong>Select Catalog : </strong> &nbsp;
                                          <select name="selCatalog" onChange="document.myform.submit()">
                                              <option value="0">----- All Catalog -----</option>
                                              {$data['option']}
                                          </select>
                                      </td>
                                  </tr>
                                  <tr><td>&nbsp;</td></tr>
                                      </form>
                                      <tr><td>&nbsp;</td></tr>
                                      <tr>
                                          <td><table width="100%" border="0" cellspacing="0" cellpadding="0">
                                              <tr>
                                                 <td width="6" align="left"><img src="images/nav_topleft.gif" width="6" height="27"></td>
                                                 <td background="images/navtop_bg.gif" class="nav_tittle" align=left valign=middle>{$data['title']}</td>
                                                 <td width="6" align="right"><img src="images/nav_topright.gif" width="6" height="27"></td>
                                              </tr>
                                          </table></td>
                                      </tr>
                                      <tr>
                                          <td bgcolor="#FFFFFF" class="main_table" align=center>
                                          <form action="?act=menu4&sub=del&cat_id={$data['cat_id']}" method="post" name="manage1" id="manage1">
                                              <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                                 <tr>
                                                     <td width="10%" class="row_tittle">Select</td>
                                                     <td width="55%" class="row_tittle">News Title</td>
                                                     <td width="10%" class="row_tittle">Hits</td>
                                                     <td width="10%" class="row_tittle">Picture</td>
                                                     <td width="15%" class="row_tittle">Date Post</td>
                                                 </tr>
                                                 {$data['nd']}
                                                 <tr>
                                                     <td width="10%" class="row_tittle"><input type="checkbox" name="all1" onclick="javascript:checkall1({$data['num']});"></td>
                                                     <td colspan="4" class="row_tittle" align="left" style="padding-left:5px"><input type="submit" name="btnAdd" value="Delete Seleted Focus"></td>
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

        function html_row1($data){
                 return<<<EOF
                            <tr>
                                <td class="row"><input name="delfocus[]" type="checkbox" value="{$data['newsid']}"></td>
                                <td class="row" align="left" style="padding-left:5px">{$data['title']}</td>
                                <td class="row">{$data['viewnum']}</td>
                                <td class="row">{$data['picture']}</td>
                                <td class="row">{$data['date']}</td>
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
                                              <td width="100%" height="40" align="center" valign="middle"><font color="red">{$mess}</font></td>
                                          </tr>
                                       </table></td>
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
} // End Class
?>