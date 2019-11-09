<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

$act_func=new func();
class func{

      function HTML($text=""){
               //$t = addslashes($t);
               $text = nl2br($text);
               $text = str_replace("[url]http://","[url]",$text);
               $text = str_replace("[url=http://","[url=",$text);
               //$text = preg_replace("/(http.*:\/\/.+)\s/U", "<a href=\"$1\">$1</a> ", $text);
               $text = preg_replace('/(\[b\])(.+?)(\[\/b\])/', '<b>\\2</b>',$text);
               $text = preg_replace('/(\[i\])(.+?)(\[\/i\])/', '<i>\\2</i>',$text);
               $text = preg_replace('/(\[u\])(.+?)(\[\/u\])/', "<u>\\2</u>", $text);
               $text = preg_replace('/(\[color=(.+?)\])(.+?)(\[\/color\])/', '<font color=\\2>\\3</font>',$text);
               $text = preg_replace('/(\[email\])(.+?)(\[\/email\])/', "<a href=\"mailto:\\2\">\\2 </a>", $text);
               $text = preg_replace('/(\[email=(.+?)\])(.+?)(\[\/email\])/', "<a href=\"mailto:\\2\">\\3</a>", $text);
               $text = preg_replace('/(\[url\])(.+?)(\[\/url\])/', "<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);
               $text = preg_replace('/(\[url=\])(.+?)(\[\/url\])/', "<a href=\"http://\\2\" target=\"_blank\">\\2</a>", $text);
               $text = preg_replace('/(\[url=(.+?)\])(.+?)(\[\/url\])/', "<a href=\"http://\\2\" target=\"_blank\">\\3</a>", $text);
               $text = stripslashes($text);
               $text = str_replace("!!!!", "!", $text);
               $text = str_replace( "[img]", "<img align=right width=150 src=news_images/"  , $text );
               $text = str_replace( "[/img]", " />"  , $text );
               return $text;
      }

      function txt_HTML($t=""){
               //$t = addslashes($t);
               $t = preg_replace("/&(?!#[0-9]+;)/s", '&amp;', $t );
               $t = str_replace( "<", "&lt;"  , $t );
               $t = str_replace( ">", "&gt;"  , $t );
               $t = str_replace( '"', "&quot;", $t );
               $t = str_replace( "'", '&#039;', $t );
               return $t;
      }

      function txt_unHTML($t=""){
               $t = stripslashes($t);
               //$t = nl2br($t);
               $t = preg_replace("/&(?!#[0-9]+;)/s", '&amp;', $t );
               $t = str_replace( "<", "&lt;"  , $t );
               $t = str_replace( ">", "&gt;"  , $t );
               $t = str_replace( '"', "&quot;", $t );
               $t = str_replace( "'", '&#039;', $t );
               return $t;
      }

      function Upload($data){
               // Upload
               $path = $data['path'].$data['dir'];
               $max_size = 10000000;
               $err = "";
               $image = $data['file'];
               $ptime = $data['timepost'];
               $re['type'] = strtolower(substr($image['name'],strrpos($image['name'],".")+1));
               $image['name'] = $ptime.".".$re['type'];
               $w = $data['w'];
               $ato = $data['ato'];
               $ico = $data['ico'];
               if ($image['size']>0) {
                   if ($image['size']>$max_size) $err .= "File h&#236;nh qu&#225; l&#7899;n :(";
                   if ($data['type']=="hinh") {
                            if ($data['thum']==1){
                                $link_file = $path."/".$image['name'];
                                $this->Save($image['tmp_name'],$link_file,$ato);
                                if (!empty($w)){
                                     $link_file1= $path."/thumb_".$image['name'];
                                     $this->thum($image['tmp_name'],$link_file1,$w);
                                }
                                if (!empty($ico)){
                                     $link_file2= $path."/icon_".$image['name'];
                                     $this->pico($image['tmp_name'],$link_file2,$ico);
                                }
                            }
                   } else $err .= "- &#272;&#7883;nh d&#7841;ng c&#7911;a File kh&#244;ng h&#7907;p l&#7879; !";
              }
              if (empty($err)) {
                  $link_file = $path."/".$image['name'];
                  $re['link'] = $image['name'];
              }
              $re['err'] = $err;
              return $re;
     }

     function pico($imgfile="",$path,$ico){
              $img['format']=ereg_replace(".*\.(.*)$","\\1",$path);
              $img['format']=strtoupper($img['format']);
              if ($img['format']=="JPG" || $img['format']=="JPEG") {
                  $img['src'] = imagecreatefromjpeg($imgfile);
              }
              if ($img['format']=="GIF") {
                  $img['src'] = imagecreatefromgif($imgfile);
              }
              $img['pic_w'] = imagesx($img['src']);
              $img['pic_h'] = imagesy($img['src']);
              $pic_h = $img['pic_h'];
              $pic_w = $img['pic_w'];
              if ($img['pic_w'] > $ico){
                  $pic_w  = $ico;
                  $pic_h  = ($ico/$img['pic_w'])*$img['pic_h'];
              }
              $pic_h  = 60;  // Mac dinh chieu cao cua anh icons chi la 55 bang rong cho no dep hiehie
              $img['des'] = imagecreatetruecolor($pic_w,$pic_h);
              $balck = imagecolorallocate($img['des'],000,000,000);
              imagefill($img['des'],1,1,$balck);
              imagecopyresized($img['des'],$img['src'], 0,0,0,0,$pic_w,$pic_h,$img['pic_w'],$img['pic_h']);
              if ($img['format']=="JPG" || $img['format']=="JPEG") {
                    imagejpeg($img['des'],$path,100);
              }
              if ($img['format']=="GIF") {
                    imagegif($img['des'],$path,100);
              }
     }

     function thum($imgfile="",$path,$w){
              $img['format']=ereg_replace(".*\.(.*)$","\\1",$path);
              $img['format']=strtoupper($img['format']);
              if ($img['format']=="JPG" || $img['format']=="JPEG") {
                  $img['src'] = imagecreatefromjpeg($imgfile);
              }
              if ($img['format']=="GIF") {
                  $img['src'] = imagecreatefromgif($imgfile);
              }
              $img['old_w'] = imagesx($img['src']);
              $img['old_h'] = imagesy($img['src']);
              $new_h = $img['old_h'];
              $new_w = $img['old_w'];
              if ($img['old_w'] > $w){
                  $new_w  = $w;
                  $new_h  = ($w/$img['old_w'])*$img['old_h'];
              }
              if ( $w != 200) $new_h = 96;  // Mac dinh chieu cao cua anh thumbnails chi la 100 bang rong cho no dep hiehie
              $img['des'] = imagecreatetruecolor($new_w,$new_h);
              $balck = imagecolorallocate($img['des'],000,000,000);
              imagefill($img['des'],1,1,$balck);
              imagecopyresized($img['des'],$img['src'], 0,0,0,0,$new_w,$new_h,$img['old_w'],$img['old_h']);
              if ($img['format']=="JPG" || $img['format']=="JPEG") {
                    imagejpeg($img['des'],$path,100);
              }
              if ($img['format']=="GIF") {
                    imagegif($img['des'],$path,100);
              }
     }

     function Save($imgfile="",$path,$ato) {
              $gd_version = 2;
              $img['format']=ereg_replace(".*\.(.*)$","\\1",$path);
              $img['format']=strtoupper($img['format']);
              if ($img['format']=="JPG" || $img['format']=="JPEG") {
                  $img['src'] = imagecreatefromjpeg($imgfile);
              }
              if ($img['format']=="GIF") {
                  $img['src'] = imagecreatefromgif($imgfile);
              }
              $img['old_w'] = imagesx($img['src']);
              $img['old_h'] = imagesy($img['src']);
              $old_h = $img['old_h'];
              $old_w = $img['old_w'];
              if ($img['old_w'] > $ato){
                  $old_w  = $ato;
                  $old_h  = ($ato/$img['old_w'])*$img['old_h'];
              }
              if ($gd_version==2) {
                  $img['des'] = imagecreatetruecolor($old_w,$old_h);
                  $balck = imagecolorallocate($img['des'],000,000,000);
                  imagefill($img['des'],1,1,$balck);
                  imagecopyresampled($img['des'],$img['src'], 0, 0, 0, 0, $old_w,$old_h,$img['old_w'],$img['old_h']);
              }
              if ($gd_version==1) {
                  $img['des'] = imagecreatetruecolor($old_w,$old_h);
                  $white = imagecolorallocate($img['des'],255,255,255);
                  imagefill($img['des'],1,1,$white);
                  imagecopyresized($img['des'],$img['src'], 0, 0, 0, 0, $old_w,$old_h,$img['old_w'],$img['old_h']);
              }
              if ($img['format']=="JPG" || $img['format']=="JPEG") {
                    imagejpeg($img['des'],$path,100);
              }
              if ($img['format']=="GIF") {
                    imagegif($img['des'],$path,100);
              }
     }

     function makedate($text) {
              $tmp = explode ("-",$text);
              return $tmp[2]."/".$tmp[1]."/".$tmp[0];
     }

     function makedatetoMySQL($text) {
              $tmp = explode ("/",$text);
              return $tmp[2]."-".$tmp[1]."-".$tmp[0];
     }

     function tablelockup ($tenbang,$tencot,$dieukien,$vitri){
              global $DB;
              isset($ketqua);
              $resultTB = mysql_query("select * from ".$conf['perfix']."$tenbang where $tencot='".$dieukien."'");
              if ($data=$DB->fetch_row($resultTB)){
                  $ketqua =$data[$vitri];
              }else
                  $ketqua ='';
              return $ketqua;
     }

     function Link($t=""){
              global $conf;
              if ($conf['encode_link']) { // Encode the URL by NDM
                  $t = trim($t);
                  $code = base64_encode($t);
                  $code = substr($code,5,strlen($code)-7).substr($code,0,5).substr($code,strlen($code)-2);
                  $code = substr($code,0,3).substr($code,6,strlen($code)-8).substr($code,3,3).substr($code,strlen($code)-2);
              } else $code = trim($t);
              return $code;
     }

     function paginate($numRows, $maxRows, $pageNum=0, $pageVar="page", $class="pagelink"){
              global $lang;
              $navigation = "";
              // get total pages
              $totalPages = ceil($numRows/$maxRows);
              // develop query string minus page vars
              $queryString = "";
              if (!empty($_SERVER['QUERY_STRING'])) {
                  $params = explode("&", $_SERVER['QUERY_STRING']);
                  $newParams = array();
                  foreach ($params as $param) {
                           if (stristr($param, $pageVar."=") == false) {
                               array_push($newParams, $param);
                           }
                  }
                  if (count($newParams) != 0) {
                      $queryString = "&" . htmlentities(implode("&", $newParams));
                  }
              }
              // get current page
              $currentPage = $_SERVER['PHP_SELF'];
              // build page navigation
              if($totalPages> 1){
                 $navigation = "<span class=\"pagecur\">".$totalPages." Page(s) </span>&nbsp;";
                 $upper_limit = $pageNum + 5;
                 $lower_limit = $pageNum - 5;
                 if ($pageNum > 0) { // Show if not first page
                     if(($pageNum - 4)>0){
                        $first = sprintf("%s?".$pageVar."=%d%s", $currentPage, 1, $queryString);
                        $navigation .= "&nbsp;<a href='".$first."' class='".$class."'><font color=\"red\">&laquo;</font></a>";
                     }
                     $prev = sprintf("%s?".$pageVar."=%d%s", $currentPage, max(0, $pageNum - 1), $queryString);
                     $navigation .= "&nbsp;<a href='".$prev."' class='".$class."'><font color=\"red\">&lt;</font></a>";
                 } // Show if not first page
                 // get in between pages
                 for($i = 1; $i <= $totalPages; $i++){
                     $pageNo = $i;
                     if($i==$pageNum){
                        $navigation .= "&nbsp;<span class=\"pagecur\">".$pageNo."</span>";
                     } elseif($i!==$pageNum && $i<$upper_limit && $i>$lower_limit){
                              $noLink = sprintf("%s?".$pageVar."=%d%s", $currentPage, $i, $queryString);
                              $navigation .= "&nbsp;<a href='".$noLink."' class='".$class."'>".$pageNo."</a>";
                           } elseif(($i - $lower_limit)==0){
                                     $navigation .=  "&hellip;";
                                 }
                 }  // Het for
                 if (($pageNum+1) < $totalPages) { // Show if not last page
                      $next = sprintf("%s?".$pageVar."=%d%s", $currentPage, min($totalPages, $pageNum + 1), $queryString);
                      $navigation .= "&nbsp;<a href='".$next."' class='".$class."'><font color=\"red\">&gt;</font></a> ";
                      if(($pageNum + 4)<$totalPages){
                          $last = sprintf("%s?".$pageVar."=%d%s", $currentPage, $totalPages, $queryString);
                          $navigation .= "&nbsp;<a href='".$last."' class='".$class."'><font color=\"red\">&raquo;</font></a>";
                      }
                } // Show if not last page
             } // end if total pages is greater than one
             return $navigation;
        }

        function makeDbInfo($new="",$prevArray) {
                 global $DB,$NDM;
                 if (!is_array($new)){
                      $msg = "Error !";
                 }
                 if (count($new) < 1){
                     return "";
                     exit;
                 }
                 $newConfig =array();
                 // add old config vars not in $new array
                 if(is_array($prevArray)){
                    foreach($prevArray as $key => $value) {
                            if($new[$key]!==$prevArray[$key]){
                               $newConfig[$key] = $value;
                            }
                    }
                 }
                 // build new config vars from $new array
                 if(is_array($new)){
                    foreach($new as $key => $value) {
                            $newConfig[$key] = $value;
                    }
                 }
                 // serialise the array for DB storage bas64 encode to stop serialize bug
                 foreach($newConfig as $key => $value) {
                         $value = str_replace(array("\'","'"),"&#39;",$value);
                         $newConfigBase64[base64_encode($key)] = base64_encode($value);
                 }
                 $configText = serialize($newConfigBase64);
                 // see if database config exists
                 return $configText;
        }

        function fetchDbInfo($configText) {
                 $base64Encoded = unserialize($configText);
                 foreach($base64Encoded as $key => $value){
                         $base64Decoded[base64_decode($key)] = stripslashes(base64_decode($value));
                 }
                 return $base64Decoded;
        }

        function Get_Stats(){
                 global $DB,$conf;
                 $query = $DB->query("select visit from ".$conf['perfix']."counter");
                 if ($rows = $DB->fetch_row($query)){
                     $stats['totals'] = $rows['visit'];
                 }
                 return $stats;
        }
}// End class
?>