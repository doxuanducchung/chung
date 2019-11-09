<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright © 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

class func{

      function HTML($t=""){
               //$t = addslashes($t);
               $text = nl2br($t);
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
	  function smHTML($t=""){
               //$t = addslashes($t);
               $text = nl2br($t);
               $text = str_replace("[url]http://","[url]",$text);
               $text = str_replace("[url=http://","[url=",$text);
               //$text = preg_replace("/(http.*:\/\/.+)\s/U", "<a href=\"$1\">$1</a> ", $text);
               $text = preg_replace('/(\[b\])(.+?)(\[\/b\])/', '<b>\\2</b>',$text);
			   $text = preg_replace('/(\[sm\])(.+?)(\[\/sm\])/', '<img border=0 src=/pts/smiles/\\2.gif>',$text);
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

      function makedate($text) {
               $tmp = explode ("-",$text);
               return $tmp[2]."/".$tmp[1]."/".$tmp[0];
      }

      function tablelockup($tenbang,$tencot,$dieukien,$vitri){
               global $conf;
               isset($ketqua);
               $resultTB = mysql_query("select * from ".$conf['perfix']."$tenbang where $tencot ='$dieukien'");
               if ($data=mysql_fetch_row($resultTB) ){
                   $ketqua =$data[$vitri];
               }else
                   $ketqua ='';
               return $ketqua;
       }

       function Get_Input($t){
                global $conf;
                if ($conf['encode_link']) {
                    $in_arr = array();
                    $code=trim($t);
                    $code = substr($code,0,3).substr($code,strlen($code)-5,3).substr($code,3,strlen($code)-8).substr($code,strlen($code)-2);
                    $code = substr($code,strlen($code)-7,5).substr($code,0,strlen($code)-7).substr($code,strlen($code)-2);
                    $code = base64_decode($code);
                } else $code=trim($t);
                $cmd_arr = explode("|",$code);
                foreach ($cmd_arr as $value) {
                         if (!empty($value)) {
                             $k = trim(substr($value,0,strpos($value,":")));
                             $v = trim(substr($value,strpos($value,":")+1));
                             $in_arr[$k] = $v;
                         }
                }
                if (is_array($_POST)){
                     while( list($k, $v) = each($_POST) ){
                            if ( is_array($_POST[$k]) ){
                                 while( list($k2, $v2) = each($_POST[$k]) ){
                                        $in_arr[ $this->clean_key($k) ][ $this->clean_key($k2) ] = $this->clean_value($v2);
                                 }
                            } else {
                                 $in_arr[ $this->clean_key($k) ] = $this->clean_value($v);
                            }
                     }
                }
                return $in_arr;
       }

       function Location() {
                global $input;
                $txt = "";
                while( list($k,$v) = each($input) ) {
                       if ( (!empty($k)) && (!empty($v)) )   $txt .= $k.":".$v."|";
                }
                return $txt;
       }

       function clean_key($key) {
                if ($key == ""){
                    return "";
                }
                $key = preg_replace( "/\.\./"           , ""  , $key );
                $key = preg_replace( "/\_\_(.+?)\_\_/"  , ""  , $key );
                $key = preg_replace( "/^([\w\.\-\_]+)$/", "$1", $key );
                return $key;
       }

       function clean_value($val) {
                if ($val == ""){
                    return "";
                }
                $val = str_replace( "&#032;", " ", $val );
                $val = str_replace( "&"            , "&amp;"         , $val );
                $val = str_replace( "<!--"         , "&#60;&#33;--"  , $val );
                $val = str_replace( "-->"          , "--&#62;"       , $val );
                $val = preg_replace( "/<script/i"  , "&#60;script"   , $val );
                $val = str_replace( ">"            , "&gt;"          , $val );
                $val = str_replace( "<"            , "&lt;"          , $val );
                $val = str_replace( "\""           , "&quot;"        , $val );
                $val = preg_replace( "/\n/"        , "<br />"        , $val ); // Convert literal newlines
                $val = preg_replace( "/\\\$/"      , "&#036;"        , $val );
                $val = preg_replace( "/\r/"        , ""              , $val ); // Remove literal carriage returns
                $val = str_replace( "!"            , "&#33;"         , $val );
                $val = str_replace( "'"            , "&#39;"         , $val ); // IMPORTANT: It helps to increase sql query safety.
                // Ensure unicode chars are OK
                $val = preg_replace("/&amp;#([0-9]+);/s", "&#\\1;", $val );
                // Swop user inputted backslashes
                // $val = preg_replace( "/\\\(?!&amp;#|\?#)/", "&#092;", $val );
                return $val;
       }

       function Link($t=""){
                global $conf;
                if ($conf['encode_link']) { // Encode the URL
                    $t = trim($t);
                    $code = base64_encode($t);
                    $code = substr($code,5,strlen($code)-7).substr($code,0,5).substr($code,strlen($code)-2);
                    $code = substr($code,0,3).substr($code,6,strlen($code)-8).substr($code,3,3).substr($code,strlen($code)-2);
                } else $code = trim($t);
                return $code;
       }

       function List_SubCat($cat_id){
                global $func,$DB,$conf;
                $perfix = $conf['perfix'];
                $output="";
                $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid={$cat_id}");
                while ($cat=$DB->fetch_row($query)) {
                       $output.=$cat["catalogid"].",";
                       $output.=$this->List_SubCat($cat['catalogid']);
                }
                return $output;
       }

       function paginate($numRows, $maxRows, $pageNum=1, $pageVar="page", $tieudeVar="", $idVar="",$class="pagelink"){
                global $conf;
				$navigation = "";
                // Lay total pages
                $totalPages = ceil($numRows/$maxRows);
                // develop query string minus page vars
                $queryString = "";
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $params = explode("&", $_SERVER['QUERY_STRING']);
                    $newParams = array();
                    foreach ($params as $param) {
                             if (stristr($param, $pageVar) == false) {
                                 array_push($newParams, $param);
                             }
                    }
                    if (count($newParams) != 0) {
                        $queryString = "&" . htmlentities(implode("&", $newParams));
                    }
                }
                // get current page
                $currentPage = $_SERVER['PHP_SELF'];
                //print "currentPage = ".$currentPage."<br>";
                // build page navigation
                if ($totalPages> 1){
                    $navigation = "";
                    $upper_limit = $pageNum + 3;
                    $lower_limit = $pageNum - 2;
                    if ($pageNum > 1) { // Show if not first page
                        if(($pageNum - 2)>0){
                            $first = sprintf("%s?".$pageVar."=%d%s", $currentPage, 1, $queryString);
							if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$first."' class='".$class."'>First</a>";
							if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tutorials-category/".$tieudeVar."/".$idVar."/page-1/' class='".$class."'>First</a>";
							
							
							}
                            $prev = sprintf("%s?".$pageVar."=%d%s", $currentPage, max(0, $pageNum - 1), $queryString);
							
                            if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$prev."' class='".$class."'>Pre page</a>";
							if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tutorials-category/".$tieudeVar."/".$idVar."/page-".max(0, $pageNum - 1)."/' class='".$class."'>Pre</a>";
                        } // Show if not first page
                        // get in between pages
                        for($i = 1; $i < $totalPages+1; $i++){
                            $pageNo = $i;
                            if($i==$pageNum){
                               $navigation .= "&nbsp;<span class=\"current\">".$pageNo."</span>";
                            } elseif($i!==$pageNum && $i<$upper_limit && $i>$lower_limit){
                               $noLink = sprintf("%s?".$pageVar."=%d%s", $currentPage, $i, $queryString);
                               if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$noLink."' class='".$class."'>".$pageNo."</a>";
							   if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tutorials-category/".$tieudeVar."/".$idVar."/page-".$i."/' class='".$class."'>".$pageNo."</a>";
                               } elseif(($i - $lower_limit)==0){
                                         $navigation .=  "&nbsp;<span class=\"current\">&hellip;</span>";
                               }
                        }
                        if (($pageNum) < $totalPages) { // Show if not last page
                             $next = sprintf("%s?".$pageVar."=%d%s", $currentPage, min($totalPages, $pageNum + 1), $queryString);
                             if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$next."' class='".$class."'>Next page</a> ";
							 if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tutorials-category/".$tieudeVar."/".$idVar."/page-".min($totalPages, $pageNum + 1)."/' class='".$class."'>Next</a> ";
                             if(($pageNum + 1)<$totalPages){
                                 $last = sprintf("%s?".$pageVar."=%d%s", $currentPage, $totalPages, $queryString);
                                 if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$last."' class='".$class."'>Last page</a>";
								 if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tutorials-category/".$tieudeVar."/".$idVar."/page-".$totalPages."/' class='".$class."'>Last</a>";
                             }
                        } // Show if not last page
                 } // end if total pages is greater than one
                 return $navigation;
       }
	   
	   
	   function paginatetopic($numRows, $maxRows, $pageNum=1, $pageVar="page", $tieudeVar="", $idVar="",$class="pagelink"){
                global $conf;
				$navigation = "";
                // Lay total pages
                $totalPages = ceil($numRows/$maxRows);
                // develop query string minus page vars
                $queryString = "";
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $params = explode("&", $_SERVER['QUERY_STRING']);
                    $newParams = array();
                    foreach ($params as $param) {
                             if (stristr($param, $pageVar) == false) {
                                 array_push($newParams, $param);
                             }
                    }
                    if (count($newParams) != 0) {
                        $queryString = "&" . htmlentities(implode("&", $newParams));
                    }
                }
                // get current page
                $currentPage = $_SERVER['PHP_SELF'];
                //print "currentPage = ".$currentPage."<br>";
                // build page navigation
                if ($totalPages> 1){
                    $navigation = "";
                    $upper_limit = $pageNum + 3;
                    $lower_limit = $pageNum - 2;
                    if ($pageNum > 1) { // Show if not first page
                        if(($pageNum - 2)>0){
                            $first = sprintf("%s?".$pageVar."=%d%s", $currentPage, 1, $queryString);
							if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$first."' class='".$class."'>First</a>";
							if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics-detail/".$tieudeVar."/".$idVar."/page-1/' class='".$class."'>First</a>";
							
							
							}
                            $prev = sprintf("%s?".$pageVar."=%d%s", $currentPage, max(0, $pageNum - 1), $queryString);
							
                            if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$prev."' class='".$class."'>Pre page</a>";
							if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics-detail/".$tieudeVar."/".$idVar."/page-".max(0, $pageNum - 1)."/' class='".$class."'>Pre</a>";
                        } // Show if not first page
                        // get in between pages
                        for($i = 1; $i < $totalPages+1; $i++){
                            $pageNo = $i;
                            if($i==$pageNum){
                               $navigation .= "&nbsp;<span class=\"current\">".$pageNo."</span>";
                            } elseif($i!==$pageNum && $i<$upper_limit && $i>$lower_limit){
                               $noLink = sprintf("%s?".$pageVar."=%d%s", $currentPage, $i, $queryString);
                               if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$noLink."' class='".$class."'>".$pageNo."</a>";
							   if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics-detail/".$tieudeVar."/".$idVar."/page-".$i."/' class='".$class."'>".$pageNo."</a>";
                               } elseif(($i - $lower_limit)==0){
                                         $navigation .=  "&nbsp;<span class=\"current\">&hellip;</span>";
                               }
                        }
                        if (($pageNum) < $totalPages) { // Show if not last page
                             $next = sprintf("%s?".$pageVar."=%d%s", $currentPage, min($totalPages, $pageNum + 1), $queryString);
                             if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$next."' class='".$class."'>Next page</a> ";
							 if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics-detail/".$tieudeVar."/".$idVar."/page-".min($totalPages, $pageNum + 1)."/' class='".$class."'>Next</a> ";
                             if(($pageNum + 1)<$totalPages){
                                 $last = sprintf("%s?".$pageVar."=%d%s", $currentPage, $totalPages, $queryString);
                                 if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$last."' class='".$class."'>Last page</a>";
								 if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics-detail/".$tieudeVar."/".$idVar."/page-".$totalPages."/' class='".$class."'>Last</a>";
                             }
                        } // Show if not last page
                 } // end if total pages is greater than one
                 return $navigation;
       }
	   
	   
	   
	 function seolinkmain($tieudeVar="", $idVar=1){
				global $conf;
                $seolink_main = "";
				if ($conf['seo_link'] == 'yes') $seolink_main.= "".$conf['thumucroot']."tutorials-category/".$tieudeVar."/".$idVar."/";
                if ($conf['seo_link'] == 'no') $seolink_main.= "?cmd=act:main|cat:".$idVar."";
                
                 return $seolink_main;
       }
	   function seolinknews($tieudeVar="", $idVar=1){
				global $conf;
                $seolink_news = "";
				if ($conf['seo_link'] == 'yes') $seolink_news.= "".$conf['thumucroot']."tutorials-detail/".$idVar."/".$tieudeVar.".html";
                if ($conf['seo_link'] == 'no') $seolink_news.= "?cmd=act:news|newsid:".$idVar."";
                
                 return $seolink_news;
       }
	   
	   function seolinktopic($tieudeVar="", $idVar=1){
				global $conf;
                $seolink_topic = "";
				if ($conf['seo_link'] == 'yes') $seolink_topic.= "".$conf['thumucroot']."topics-detail/".$tieudeVar."/".$idVar."/";
                if ($conf['seo_link'] == 'no') $seolink_topic.= "?cmd=act:event|eventid:".$idVar."";
                
                 return $seolink_topic;
       }
	   
	   function seolinkbanin($tieudeVar="", $idVar=1){
				global $conf;
                $seolink_banin = "";
				if ($conf['seo_link'] == 'yes') $seolink_banin.= "".$conf['thumucroot']."print/".$idVar."/".$tieudeVar.".html";
                if ($conf['seo_link'] == 'no') $seolink_banin.= "".$conf['thumucroot']."sources/print.php?newsid=".$idVar."";
                
                 return $seolink_banin;
       }
	   function seolinkbanner($idVar=1){
				global $conf;
                $seolink_banner = "";
				if ($conf['seo_link'] == 'yes') $seolink_banner.= "/lien-ket-quang-cao-trao-doi-banner/".$idVar."/vip.html";
                if ($conf['seo_link'] == 'no') $seolink_banner.= "click.php?bid=".$idVar."";
                
                 return $seolink_banner;
       }
	   function seolinktag($tieudeVar=""){
				global $conf;
                $seolink_tag = "";
				if ($conf['seo_link'] == 'yes') $seolink_tag.= "".$conf['thumucroot']."tag/".$tieudeVar.".html";
                if ($conf['seo_link'] == 'no') $seolink_tag.= "?cmd=act:tag|tag:".$tieudeVar."";
                
                 return $seolink_tag;
       }
	   
	   
	   function totalnewstag($tagVar=""){
				global $conf;
                $totalnews_tag = "";
				$sql_tag = mysql_query("select * from ".$conf['perfix']."news where (isdisplay!=0 and ((tag1 = '$tagVar') or (tag2 = '$tagVar') or (tag3 = '$tagVar'))) order by newsid DESC");
				$totalnews_tag.= mysql_num_rows($sql_tag);
                 return $totalnews_tag;
       }
	   
	   function paginatetopicl($numRows, $maxRows, $pageNum=1, $pageVar="page",$class="pagelink"){
                global $conf;
				$navigation = "";
                // Lay total pages
                $totalPages = ceil($numRows/$maxRows);
                // develop query string minus page vars
                $queryString = "";
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $params = explode("&", $_SERVER['QUERY_STRING']);
                    $newParams = array();
                    foreach ($params as $param) {
                             if (stristr($param, $pageVar) == false) {
                                 array_push($newParams, $param);
                             }
                    }
                    if (count($newParams) != 0) {
                        $queryString = "&" . htmlentities(implode("&", $newParams));
                    }
                }
                // get current page
                $currentPage = $_SERVER['PHP_SELF'];
                //print "currentPage = ".$currentPage."<br>";
                // build page navigation
                if ($totalPages> 1){
                    $navigation = "";
                    $upper_limit = $pageNum + 3;
                    $lower_limit = $pageNum - 2;
                    if ($pageNum > 1) { // Show if not first page
                        if(($pageNum - 2)>0){
                            $first = sprintf("%s?".$pageVar."=%d%s", $currentPage, 1, $queryString);
							if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$first."' class='".$class."'>First</a>";
		                    if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics/page-1/' class='".$class."'>First</a>";
							
							
							}
                            $prev = sprintf("%s?".$pageVar."=%d%s", $currentPage, max(0, $pageNum - 1), $queryString);
							
                            if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$prev."' class='".$class."'>Pre page</a>";
							if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics/page-".max(0, $pageNum - 1)."/' class='".$class."'>Pre</a>";
                        } // Show if not first page
                        // get in between pages
                        for($i = 1; $i < $totalPages+1; $i++){
                            $pageNo = $i;
                            if($i==$pageNum){
                               $navigation .= "&nbsp;<span class=\"current\">".$pageNo."</span>";
                            } elseif($i!==$pageNum && $i<$upper_limit && $i>$lower_limit){
                               $noLink = sprintf("%s?".$pageVar."=%d%s", $currentPage, $i, $queryString);
                               if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$noLink."' class='".$class."'>".$pageNo."</a>";
							   if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics/page-".$i."/' class='".$class."'>".$pageNo."</a>";
                               } elseif(($i - $lower_limit)==0){
                                         $navigation .=  "&nbsp;<span class=\"current\">&hellip;</span>";
                               }
                        }
                        if (($pageNum) < $totalPages) { // Show if not last page
                             $next = sprintf("%s?".$pageVar."=%d%s", $currentPage, min($totalPages, $pageNum + 1), $queryString);
                             if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$next."' class='".$class."'>Next page</a> ";
							 if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics/page-".min($totalPages, $pageNum + 1)."/' class='".$class."'>Next</a> ";
                             if(($pageNum + 1)<$totalPages){
                                 $last = sprintf("%s?".$pageVar."=%d%s", $currentPage, $totalPages, $queryString);
                                 if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$last."' class='".$class."'>Last page</a>";
								 if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."topics/page-".$totalPages."/' class='".$class."'>Last</a>";
                             }
                        } // Show if not last page
                 } // end if total pages is greater than one
                 return $navigation;
       }
	   
	   
	   function paginatetag($numRows, $maxRows, $pageNum=1, $pageVar="page", $tieudeVar="",$class="pagelink"){
                global $conf;
				$navigation = "";
                // Lay total pages
                $totalPages = ceil($numRows/$maxRows);
                // develop query string minus page vars
                $queryString = "";
                if (!empty($_SERVER['QUERY_STRING'])) {
                    $params = explode("&", $_SERVER['QUERY_STRING']);
                    $newParams = array();
                    foreach ($params as $param) {
                             if (stristr($param, $pageVar) == false) {
                                 array_push($newParams, $param);
                             }
                    }
                    if (count($newParams) != 0) {
                        $queryString = "&" . htmlentities(implode("&", $newParams));
                    }
                }
                // get current page
                $currentPage = $_SERVER['PHP_SELF'];
                //print "currentPage = ".$currentPage."<br>";
                // build page navigation
                if ($totalPages> 1){
                    $navigation = "";
                    $upper_limit = $pageNum + 3;
                    $lower_limit = $pageNum - 2;
                    if ($pageNum > 1) { // Show if not first page
                        if(($pageNum - 2)>0){
                            $first = sprintf("%s?".$pageVar."=%d%s", $currentPage, 1, $queryString);
							if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$first."' class='".$class."'>First</a>";
							if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tag/".$tieudeVar."/page-1/' class='".$class."'>First</a>";
							
							
							}
                            $prev = sprintf("%s?".$pageVar."=%d%s", $currentPage, max(0, $pageNum - 1), $queryString);
							
                            if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$prev."' class='".$class."'>Pre page</a>";
							if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tag/".$tieudeVar."/page-".max(0, $pageNum - 1)."/' class='".$class."'>Pre</a>";
                        } // Show if not first page
                        // get in between pages
                        for($i = 1; $i < $totalPages+1; $i++){
                            $pageNo = $i;
                            if($i==$pageNum){
                               $navigation .= "&nbsp;<span class=\"current\">".$pageNo."</span>";
                            } elseif($i!==$pageNum && $i<$upper_limit && $i>$lower_limit){
                               $noLink = sprintf("%s?".$pageVar."=%d%s", $currentPage, $i, $queryString);
                               if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$noLink."' class='".$class."'>".$pageNo."</a>";
							   if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tag/".$tieudeVar."/page-".$i."/' class='".$class."'>".$pageNo."</a>";
                               } elseif(($i - $lower_limit)==0){
                                         $navigation .=  "&nbsp;<span class=\"current\">&hellip;</span>";
                               }
                        }
                        if (($pageNum) < $totalPages) { // Show if not last page
                             $next = sprintf("%s?".$pageVar."=%d%s", $currentPage, min($totalPages, $pageNum + 1), $queryString);
                             if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$next."' class='".$class."'>Next page</a> ";
							 if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tag/".$tieudeVar."/page-".min($totalPages, $pageNum + 1)."/' class='".$class."'>Next</a> ";
                             if(($pageNum + 1)<$totalPages){
                                 $last = sprintf("%s?".$pageVar."=%d%s", $currentPage, $totalPages, $queryString);
                                 if ($conf['seo_link'] == 'no') $navigation .= "&nbsp;<a href='".$last."' class='".$class."'>Last page</a>";
								 if ($conf['seo_link'] == 'yes') $navigation .= "&nbsp;<a href='".$conf['thumucroot']."tag/".$tieudeVar."/page-".$totalPages."/' class='".$class."'>Last</a>";
                             }
                        } // Show if not last page
                 } // end if total pages is greater than one
                 return $navigation;
       }
	   
	    
	   
}


?>