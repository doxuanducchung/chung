<?php
/*================================================================================*\
||       Name code NDM - This code developed from another source                # ||
|| # Copyright � 2006 by Duc Manh - CHF  15/07/06                               # ||
|| # Warning - About copyright - Ban quyen                                      # ||
|| # Co tham khao Ma nguon mo suu tam tren Internet: TreToday, Nuke, SoSo,...   # ||
\*================================================================================*/

function Check_Sub($cid){
         global $DB;
         $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE parentid={$cid}");
         if ($scat=$DB->fetch_row($query)) return 1;
         else return 0;
}

function get_hot_title(){
         global $func,$DB,$conf;
         $HTML = "";
         $htitle = "";
         $hlink = "";
         $stt = 0;
         $ndmrand = rand(0,10)*2;
         $sql = "SELECT * FROM ".$conf['perfix']."focus WHERE cat_id=0 ORDER BY newsid DESC LIMIT {$ndmrand},15";
         $result = $DB->query($sql);
         $numh = $DB->num_rows($result);
         while ($news = $DB->fetch_row($result)){
                $newsid = $news["newsid"];
                $hlink .= "'?cmd=act:news|newsid:{$newsid}'";
                $sql1 = "SELECT title FROM ".$conf['perfix']."news WHERE newsid=$newsid";
                $resultNews = $DB->query($sql1);
                if ($news1 = $DB->fetch_row($resultNews)){
                    $newstitle = str_replace('&quot;','"',$news1["title"]);
                    $htitle .= "'{$newstitle}'";
                }
                $stt++;
                if ($stt < $numh){
                    $htitle .= ",";
                    $hlink .= ",";
                }
         }
         $HTML .= "<SCRIPT language=\"javascript\" type=\"text/javascript\">var theSummaries = new Array($htitle);var theSiteLinks = new Array($hlink);</script><script type=\"text/javascript\" src=\"js/tickerhead.js\"></script>";
         return $HTML;
}

function get_focus($cat_id){     // Tin tieu diem noi bat
         global $func,$DB,$conf;
         $HTML ="";
         if (!empty($cat_id)) $where = "where cat_id='{$cat_id}'";
         else  $where = "where cat_id=0";
         $sql= "select * from ".$conf['perfix']."focus {$where} order by newsid DESC";
         $result = $DB->query($sql);
         if ($row = $DB->fetch_row($result)){
             $newsidhot = $row["newsid"];
             $sql ="select * from ".$conf['perfix']."news where newsid=$newsidhot";
             $resultNews=$DB->query ($sql);
             if($news=$DB->fetch_row($resultNews)){
                $title = $news["title"];
                $short = $func->HTML($news["short"]);
                $source = $func->HTML($news["source"]);
                $adddate = $news["adddate"];
                if (!empty($news['picture'])) {
                    if ( (!strstr($news['picture'],"http://"))){
                          $folder = explode("-",$adddate);
                          $path = $conf['rooturl']."images/news/".$folder[0]."/".$folder[1]."/".$folder[2]."/";
                          $src = $path.$news['picture'];
                    } else
                          $src=$news['picture'];
                } else $src="images/nophoto.jpg";
                $pic = "<a href=\"?cmd=act:news|newsid:{$newsidhot}\" onMouseOver=\"(window.status='{$title}'); return true\"><img src=\"{$src}\" align=\"left\" width=\"150\" border=\"0\" style=\"margin:4 4 0 0\"></a>" ;
             }
             $HTML = "<table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                        <tr>
                            <td height=\"35\" valign=\"top\" background=\"images/latest.gif\" width=\"415\"></td>
                        </tr>
                        <tr>
                            <td><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
                               <tr>
                                 <td style=\"padding-top:5px\" align=\"left\" onMouseOver=\"(window.status='{$title}'); return true\"><div align=\"justify\">{$pic}<a href=\"?cmd=act:news|newsid:{$news["newsid"]}\" onMouseOver=\"(window.status='{$title}'); return true\"><b>{$title}</b></a><br>
                                     {$short}<div align=\"right\"><a href=\"?cmd=act:news|newsid:{$newsidhot}\"><img src=\"images/b_pre1.gif\" width=\"47\" height=\"9\" border=\"0\"></a></div></div></td>
                               </tr>
                               </table>
                            </td>
                         </tr>
                       </table><img src=\"images/spacer.gif\" width=\"1\" height=\"5\"><br>";

        } else  $HTML ="";
        return $HTML;
}

function get_list_focus1($cat_id){
         global $func,$DB,$conf;
         $ok=0;
         $html_row = "";
         $sql ="select * from ".$conf['perfix']."focus where cat_id='{$cat_id}' order by newsid DESC LIMIT 0,10";
         $result=$DB->query ($sql);
         if ($num = $DB->num_rows($result)){
             $ok=1;
             $i=0;
             while ($row=$DB->fetch_row($result)){
                    $i++;
                    $newsid=$row['newsid'];
                    $resultnews = $DB->query("select * from ".$conf['perfix']."news where newsid ='{$newsid}'");
                    if ($news=$DB->fetch_row($resultnews)){
                        $title = $news['title'];
                        $adddatec = $news["adddate"];
                        if (!empty($news['picture'])) {
                             if ( (!strstr($news['picture'],"http://"))){
                                    $folderc = explode("-",$adddatec);
                                    $pathc = $conf['rooturl']."images/news/".$folderc[0]."/".$folderc[1]."/".$folderc[2]."/icon_";
                                    $src = $pathc.$news['picture'];
                             } else
                                    $src = $news['picture'];
                        } else $src="images/nophoto.jpg";
                        $data['pic']="<a href= \"?cmd=act:news|newsid:{$news["newsid"]}\" onMouseOver=\"(window.status='{$title}'); return true\"><img src=".$src." align='left' width='55' height='55' style='border: 1px #DFDFDF solid; margin:2px 5px 0px 0px'></a>";
                        $data['title'] ="<a href =\"?cmd=act:news|newsid:{$news["newsid"]}\" style=\"color:#FFFFFF\" onMouseOver=\"(window.status='{$title}'); return true\">".$title."</a>";
                        if ($i%2!=0) $html_row.="<tr><td height='25' align='left' onMouseOver=\"(window.status='{$title}'); return true\" bgcolor='#686868'>".$data['pic'].$data['title']."</td></tr>";
                        else $html_row.="<tr><td align='left' onMouseOver=\"(window.status='{$title}'); return true\" bgcolor='#242424'><div align='justify'>".$data['pic'].$data['title']."</div></td></tr>";
                    }
              }
         }else  $html_row.='<tr><td><font color="#ffffff">Ch&#432;a c&#7853;p nh&#7853;t tin ti&#234;u &#273;i&#7875;m</font></td></tr>';
         $HTML ="";
         $HTML .=<<<EOF
                    <table width="100%" border="0" cellspacing="1" cellpadding="1" bgcolor="#242424">
                      <tr>
                         <td align="center" bgcolor="#81AD00" height="25" class="fontmenu">Ti&#234;u &#272;i&#7875;m</td>
                      </tr>
                      {$html_row}
                    </table>

EOF;
         return $HTML ;
}


function get_list_focus($cat_id){
         global $func,$DB,$conf;
         $ok=0;
         $html_row = "";
         $sql ="select * from ".$conf['perfix']."focus where cat_id='{$cat_id}' order by newsid DESC LIMIT 0,5";
         $result=$DB->query ($sql);
         if ($num = $DB->num_rows($result)){
		 
		 
		 $html_row.="<div class=\"box-item\">
	<div class=\"list-item-header fl\">
		<div class=\"fl\"><img src=\"{$conf['thumucroot']}images/folder-activeleft2.gif\" alt=\"\"></div>
		<div class=\"folder-active2 fl\">.:: Tiêu điểm nổi bật ::.
		</div>
		<div class=\"fl\"><img src=\"{$conf['thumucroot']}images/folder-activeright2.gif\" alt=\"\"></div>
		<div class=\"fr\"><img src=\"{$conf['thumucroot']}images/folder-topright.gif\" alt=\"\"></div>
	</div>
	<div class=\"fl\">
	<div class=\"box-middle1 list-item5 fl\">";
		 
		 
             $ok=1;
             $i=0;
             while ($row=$DB->fetch_row($result)){
                    $i++;
                    $newsid=$row['newsid'];
                    $resultnews = $DB->query("select * from ".$conf['perfix']."news where newsid ='{$newsid}'");
                    if ($news=$DB->fetch_row($resultnews)){
						$newsid = $news['newsid'];
                        $title = $news['title'];
						$link = $func->seolinknews(mahoa($title),$newsid);
                        $adddatec = $news["adddate"];
                        if (!empty($news['picture'])) {
                             if ( (!strstr($news['picture'],"http://"))){
                                    $folderc = explode("-",$adddatec);
                                    $pathc = $conf['rooturl']."images/news/".$folderc[0]."/".$folderc[1]."/".$folderc[2]."/icon_";
                                    $srcicon = $pathc.$news['picture'];
                             } else
                                    $srcicon = $news['picture'];
                        }
						if ($i % 2 != 0) {$bg1 = "#FFFFFF"; $bg2 = "#FFFFFF"; $bg3 = "#FFFFFF";}
						else {$bg1 = "#ccc"; $bg2 = "#ccc";$bg3 = "#f5f5f7";}
                        $html_row.="<div class=\"list-item5-content fl\" style=\"BORDER-TOP: 1px solid {$bg1}; BORDER-BOTTOM: 1px solid {$bg2}; BACKGROUND: {$bg3}\">	
						<a href=\"{$link}\"><img src=\"{$srcicon}\" class=\"fr\" alt=\"\" onerror=\"loadErrorImage(this,'{$conf['thumucroot']}images/noimg.jpg');\"></a>	
						<a href=\"{$link}\">{$title}</a></div>";
                    }
              }

		 $html_row.="</ul>
					</div>
					</div>
					<div class=\"fl\">
					<div class=\"fl\"><img src=\"{$conf['thumucroot']}images/box-bottomleft1.gif\" alt=\"\"></div>
					<div class=\"box-bottomcenter1 fl\">&nbsp;</div>
					<div class=\"fl\"><img src=\"{$conf['thumucroot']}images/box-bottomright1.gif\" alt=\"\"></div>
					</div>
					</div>
					";
		         }
         $HTML ="";
         $HTML .=<<<EOF
                    
                      {$html_row}

EOF;
         return $HTML ;
}

function get_related_news($info_id){
         global $func,$DB,$conf;
         $cat_id =$func->tablelockup("news","newsid",$info_id,1);
         $html_row = "";
         $sql ="select * from ".$conf['perfix']."news where catalogid ='{$cat_id}' and newsid <>'{$info_id}'and isdisplay=1 order by viewnum DESC LIMIT 0,10";
         $result=$DB->query ($sql);
         $num = $DB->num_rows($result);
         $i=0;
         while ($news=$DB->fetch_row($result)){
                $i++;
                $cat_id = $news["catalogid"];
                $title = $news["title"];
                $adddatern = $news["adddate"];
                if (!empty($news['picture'])) {
                    if ( (!strstr($news['picture'],"http://"))){
                          $folderrn = explode("-",$adddatern);
                          $pathrn = $conf['rooturl']."images/news/".$folderrn[0]."/".$folderrn[1]."/".$folderrn[2]."/icon_";
                          $src = $pathrn.$news['picture'];
                    } else
                          $src = $news['picture'];
                } else $src = "images/nophoto.jpg";
                $data['pic']="<a href= \"?cmd=act:news|newsid:{$news["newsid"]}\" onMouseOver=\"(window.status='{$title}'); return true\"><img src=".$src." align=left width=55 height=55 style='border: 1px #DFDFDF solid'></a>";
                $data['title'] ="<a href =\"?cmd=act:news|newsid:{$news["newsid"]}\" style=\"color:#FFFFFF\" onMouseOver=\"(window.status='{$title}'); return true\">".$title."</a>";
                if ($i%2!=0) $html_row.="<tr><td bgcolor=\"#4C6B87\" height=\"25\" onMouseOver=\"(window.status='{$title}'); return true\">".$data['pic'].$data['title']."</td></tr>";
                else  $html_row.="<tr><td bgcolor=\"#2F5375\" height=\"25\" onMouseOver=\"(window.status='{$title}'); return true\">".$data['pic'].$data['title']."</td></tr>";
         }
         $HTML ="";
         $HTML .=<<<EOF
                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                         <td align="center" bgcolor="#01172C" height="25" class="fontmenu">&nbsp;Theo d&#242;ng s&#7921; ki&#7879;n</td>
                      </tr>
                      <tr>
                         <td><table width="100%" border="0" cellspacing="1" cellpadding="1">
                                 {$html_row}
                         </table></td>
                      </tr>
                    </table>
EOF;
         return $HTML ;
}

function html_row ($data){
         $HTML = "<tr><td onMouseOver=\"(window.status='{$title}'); return true\">".$data['pic']."&nbsp;".$data['title']."</td></tr>";
         return $HTML;
}

function poll($catid){
         global $func,$DB,$conf;
         $ok=0;
         $query = "select * from ".$conf['perfix']."poll WHERE ht='1' AND (incat LIKE '%|{$catid}|%' OR incat='all') ORDER BY poll_id DESC LIMIT 0,1";
         $result = $DB->query ($query) ;
         if ($row =$DB->fetch_row($result)){
             $ok=1;
             $p_id = $row["poll_id"];
             $p_name = $row["poll_name"];
             $query = "select * from ".$conf['perfix']."poll_option where poll_id=$p_id";
             $result = $DB->query ($query);
             $option_row="";
             while ($data=$DB->fetch_row($result)) {
                    $option_row.="<tr><td><input name=\"option_id\" type=\"radio\" value=\"{$data['option_id']}\" align=absmiddle>&nbsp;<font color='#f3f3f3'>".$data['option_name']."</font></td></tr>";
             }
         }
         $plink = "act:poll";
         $HTML ="";
         $HTML .=<<<EOF
                    <table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#242424">
                      <tr>
                         <td align="center" bgcolor="#81AD00" height="25" class="fontmenu">Th&#259;m d&#242; &#253; ki&#7871;n</td>
                      </tr>
                      <tr>
                         <td bgcolor="#353535"><form action="?cmd={$plink}" method="post">
                             <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                <tr>
                                   <td class="fontmenu"><strong>{$p_name}</strong></td>
                                </tr>
                                {$option_row}
                                <tr>
                                   <td align="center"><input name="p_id" type="hidden" value="{$p_id}">
                                       <input class="button" name="btnPoll" type="submit" value="Ch&#7885;n">&nbsp;&nbsp;
                                       <input class="button" name="btnSeePoll" type="submit" value="K&#7871;t qu&#7843;">
                                   </td>
                                </tr>
                                <tr>
                                   <td height="5"></td>
                                </tr>
                             </table>
                          </form></td>
                        </tr>
                      </table><br>
EOF;
         if ($ok) print $HTML;
         else   print "";
}

function option_row ($data){
         $HTML ='';
         return $HTML;
}


function get_trangchu1($catid) {
         global $func,$DB,$conf;
         $querylogo = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE (incat LIKE '%|{$catid}|%' OR incat='all') AND vitri='trangchu1' AND logo_order!=0 ORDER BY logo_order ASC,logo_id DESC LIMIT 0,3");
         while ($lgleft = $DB->fetch_row($querylogo)) {
                $logosrc=$conf['rooturl'].$conf['banner'].$lgleft['img'].".".$lgleft['type'];
				$idlogo = $lgleft['logo_id'];
				if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$idlogo;
				if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$idlogo.".html";
                if ($lgleft['type']=="swf") {
                    $html_logo ='<DIV class="box-item large-logo12"><div class=box-item>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="300" height="122">
                                 <param name="movie" value="'.$logosrc.'"><param name="quality" value="high">
                                 <embed src="'.$logosrc.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300" height="122"></embed>
                                 </object></div></DIV>';
                }else{
                    $html_logo ="<DIV class=\"box-item large-logo12\">
<div class=box-item><a href=\"{$link}\" target=\"_blank\" title=\"{$lgleft['title']}\"><img style=\"width: 288px; height: 93px; border: 1px solid #ccc; padding: 5px; margin-bottom: 2px\" src=\"{$logosrc}\"></a></div></DIV>";
                }
                echo $html_logo;
         }
         $HTML = $html_logo;
         return $HTML ;
}
function get_newsmain1($catid) {
         global $func,$DB,$conf;
         $querylogonewsmain = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE (incat LIKE '%|{$catid}|%' OR incat='all') AND vitri='newsmain1' AND logo_order!=0 ORDER BY logo_order ASC,logo_id DESC LIMIT 0,2");
         while ($lgnewsmain1 = $DB->fetch_row($querylogonewsmain)) {
                $logosrc=$conf['rooturl'].$conf['banner'].$lgnewsmain1['img'].".".$lgnewsmain1['type'];
				$idlogo = $lgnewsmain1['logo_id'];
				if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$idlogo;
				if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$idlogo.".html";
                if ($lgnewsmain1['type']=="swf") {
                    $html_logo ='<DIV class="box-item large-logo12"><div class=box-item>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="300" height="140">
                                 <param name="movie" value="'.$logosrc.'"><param name="quality" value="high">
                                 <embed src="'.$logosrc.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300" height="140"></embed>
                                 </object></div></DIV>';
                }else{
                    $html_logo ="<DIV class=\"box-item large-logo12\">
<div class=box-item><a href=\"{$link}\" target=\"_blank\" title=\"{$lgnewsmain1['title']}\"><img style=\"width: 288px; border: 1px solid #ccc; padding: 5px;\" src=\"{$logosrc}\"></a></div></DIV>";
                }
                echo $html_logo;
         }
         $HTML = $html_logo;
         return $HTML ;
}
function get_newsmain2($catid) {
         global $func,$DB,$conf;
         $querylogonewsmain = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE (incat LIKE '%|{$catid}|%' OR incat='all') AND vitri='newsmain2' AND logo_order!=0 ORDER BY logo_order ASC,logo_id DESC LIMIT 0,2");
         while ($lgnewsmain1 = $DB->fetch_row($querylogonewsmain)) {
                $logosrc=$conf['rooturl'].$conf['banner'].$lgnewsmain1['img'].".".$lgnewsmain1['type'];
				$idlogo = $lgnewsmain1['logo_id'];
				if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$idlogo;
				if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$idlogo.".html";
                if ($lgnewsmain1['type']=="swf") {
                    $html_logo ='<DIV class="box-item large-logo12"><div class=box-item>
					<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="300" height="140">
                                 <param name="movie" value="'.$logosrc.'"><param name="quality" value="high">
                                 <embed src="'.$logosrc.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300" height="140"></embed>
                                 </object></div></DIV>';
                }else{
                    $html_logo ="<DIV class=\"box-item large-logo12\">
<div class=box-item><a href=\"{$link}\" target=\"_blank\" title=\"{$lgnewsmain1['title']}\"><img style=\"width: 288px; border: 1px solid #ccc; padding: 5px;\" src=\"{$logosrc}\"></a></div></DIV>";
                }
                echo $html_logo;
         }
         $HTML = $html_logo;
         return $HTML ;
}
function get_trangchu2($catid) {
         global $func,$DB,$conf;
         $queryrighttop = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE (incat LIKE '%|{$catid}|%' OR incat='all') AND vitri='trangchu2' AND logo_order!=0 ORDER BY RAND() DESC LIMIT  0,1");
         while ($righttop = $DB->fetch_row($queryrighttop)) {
                $logosrc=$conf['rooturl'].$conf['banner'].$righttop['img'].".".$righttop['type'];
				$idlogo = $righttop['logo_id'];
				if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$idlogo;
				if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$idlogo.".html";
                if ($righttop['type']=="swf") {
                    $html_logo ='<div class="box-item"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="300">
                                 <param name="movie" value="'.$logosrc.'"><param name="quality" value="high">
                                 <embed src="'.$logosrc.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300"></embed>
                                 </object></div>';
                }else{
                    $html_logo ="<div class=\"box-item\"><a href=\"{$link}\" target=\"_blank\" title=\"{$righttop['title']}\"><img style=\"width: 288px; height: 128px; border: 1px solid #ccc; padding: 5px;\" src=\"{$logosrc}\"></a></div>";
                }
                echo $html_logo;
         }
         $HTML = $html_logo;
         return $HTML ;
}
function get_trangchu3($catid) {
         global $func,$DB,$conf;
         $queryrighttop = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE (incat LIKE '%|{$catid}|%' OR incat='all') AND vitri='trangchu3' AND logo_order!=0 ORDER BY RAND() DESC LIMIT  0,1");
         while ($righttop = $DB->fetch_row($queryrighttop)) {
                $logosrc=$conf['rooturl'].$conf['banner'].$righttop['img'].".".$righttop['type'];
                $idlogo = $righttop['logo_id'];
				if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$idlogo;
				if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$idlogo.".html";
                if ($righttop['type']=="swf") {
                    $html_logo ='<div class="box-item"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="300">
                                 <param name="movie" value="'.$logosrc.'"><param name="quality" value="high">
                                 <embed src="'.$logosrc.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300"></embed>
                                 </object></div>';
                }else{
                    $html_logo ="<div class=\"box-item\"><a href=\"{$link}\" target=\"_blank\" title=\"{$righttop['title']}\"><img style=\"width: 288px; height: 128px; border: 1px solid #ccc; padding: 5px;\" src=\"{$logosrc}\"></a></div>";
                }
                echo $html_logo;
         }
         $HTML = $html_logo;
         return $HTML ;
}
function get_trangchu4($catid) {
         global $func,$DB,$conf;
         $queryrighttop = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE (incat LIKE '%|{$catid}|%' OR incat='all') AND vitri='trangchu4' AND logo_order!=0 ORDER BY RAND() DESC LIMIT  0,1");
         while ($righttop = $DB->fetch_row($queryrighttop)) {
                $logosrc=$conf['rooturl'].$conf['banner'].$righttop['img'].".".$righttop['type'];
                $idlogo = $righttop['logo_id'];
				if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$idlogo;
				if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$idlogo.".html";
                if ($righttop['type']=="swf") {
                    $html_logo ='<div class="box-item"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="300">
                                 <param name="movie" value="'.$logosrc.'"><param name="quality" value="high">
                                 <embed src="'.$logosrc.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="300"></embed>
                                 </object></div>';
                }else{
                    $html_logo ="<div class=\"box-item\"><a href=\"{$link}\" target=\"_blank\" title=\"{$righttop['title']}\"><img style=\"width: 288px; height: 128px; border: 1px solid #ccc; padding: 5px;\" src=\"{$logosrc}\"></a></div>";
                }
                echo $html_logo;
         }
         $HTML = $html_logo;
         return $HTML ;
}


function get_lienket($catid) {
         global $func,$DB,$conf;
         $queryright = $DB->query("SELECT * FROM ".$conf['perfix']."banner WHERE (incat LIKE '%|{$catid}|%' OR incat='all') AND vitri='right' AND logo_order!=0 ORDER BY logo_order ASC,logo_id DESC");
         while ($lgright = $DB->fetch_row($queryright)) {
                $logosrc=$conf['rooturl'].$conf['banner'].$lgright['img'].".".$lgright['type'];
				$idlogo = $lgright['logo_id'];
				if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$idlogo;
				if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$idlogo.".html";
                if ($lgright['type']=="swf") {
                    $html_logo ='<div class="fl"><a href="click.php?bid='.$lgright["logo_id"].'" target="_blank" title="'.$lgright["title"].'"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="180">
                                 <param name="movie" value="'.$logosrc.'"><param name="quality" value="high">
                                 <embed src="'.$logosrc.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="180"></embed>
                                 </object></a></div>';
                }else{
                    $html_logo ="<div class=\"fl\"><a href=\"{$link}\" target=\"_blank\" title=\"{$lgright['title']}\"><img style=\"width: 168px; border: 1px solid #ccc; padding: 4px; margin-bottom: 5px\" src=\"{$logosrc}\"></a></div>";
               }
               echo $html_logo;
         }
         $HTML = $html_logo;
         return $HTML ;
}

function banner_row ($img){
         $HTML ='<tr><td align=center>'.$img.'</td></tr>';
         return $HTML;
}


function get_header1() {
         global $func,$DB,$conf;
         $query = "SELECT * FROM ".$conf['perfix']."banner WHERE vitri='header1' AND logo_order!=0 ORDER BY logo_id DESC LIMIT 0,1";
         $result = $DB->query($query);
         if ($row = $DB->fetch_row($result)){
             $id = $row['logo_id'];
             $title = $row['title'];
             $type = $row ["type"];
             if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$id;
			 if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$id.".html";
             $src = $conf['rooturl'].$conf['banner'].$row['img'].".".$type;
             if ($type =="swf"){
                 $html_img ='<div class="halfbanner fl"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="362" height="92">
                             <param name="movie" value="'.$src.'"><param name="quality" value="high">
                             <embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="362" height="92"></embed>
                             </object></div>';
             }else{
                 $html_img ="<div class=\"halfbanner fl\"><a href=\"{$link }\" target=\"_blank\"  title=\"{$title}\"><img style=\"width: 354px; height:80px; border: 1px solid #ccc; padding: 5px;\" src=\"{$src}\"></a></div>";
             }
          }
          $HTML = $html_img;
          return $HTML ;
}

function get_header2() {
         global $func,$DB,$conf;
         $query = "SELECT * FROM ".$conf['perfix']."banner WHERE vitri='header2' AND logo_order!=0 ORDER BY logo_id DESC LIMIT 0,1";
         $result = $DB->query($query);
        if ($row = $DB->fetch_row($result)){
             $id = $row['logo_id'];
             $title = $row['title'];
             $type = $row ["type"];
             if ($conf['seo_link'] == 'no') $link = "click.php?bid=".$id;
			 if ($conf['seo_link'] == 'yes') $link = "".$conf['thumucroot']."ads-banner/".$id.".html";
             $src = $conf['rooturl'].$conf['banner'].$row['img'].".".$type;
             if ($type =="swf"){
                 $html_img ='<div class="halfbanner fr"><object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="362" height="92">
                             <param name="movie" value="'.$src.'"><param name="quality" value="high">
                             <embed src="'.$src.'" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" width="362" height="92"></embed>
                             </object></div>';
             }else{
                 $html_img ="<div class=\"halfbanner fr\"><a href=\"{$link }\" target=\"_blank\"  title=\"{$title}\"><img style=\"width: 354px; height:80px; border: 1px solid #ccc; padding: 5px;\" src=\"{$src}\"></a></div>";
             }
          }
          $HTML = $html_img;
          return $HTML ;
}


function get_event() {
         global $func,$DB,$conf;
         $querye = $DB->query("SELECT * FROM ".$conf['perfix']."events WHERE active=1 ORDER BY eventid DESC LIMIT 3");
         if ($DB->num_rows($querye)){
             $html_event = "<br><table width=\"100%\" border=\"0\" cellspacing=\"1\" cellpadding=\"0\" bgcolor=\"#242424\">";
             $html_event .= "<tr><td align=\"center\" bgcolor=\"#81AD00\" height=\"25\" class=\"fontmenu\"><a href=\"?cmd=act:event\" onMouseOver=\"(window.status=' S&#7921; Ki&#7879;n'); return true\" title=\"Xem chi ti&#7871;t\" class=\"sidenav\">S&#7921; Ki&#7879;n</a></td></tr>";
             $bgs = 1;
             while ($rowe = $DB->fetch_row($querye)) {
                    $eventid = $rowe['eventid'];
                    $eventt = $rowe['eventtitle'];
                    $linke = "?cmd=act:event|eventid:".$eventid;
                    if (!empty($rowe['eventpic'])){
                         $eventp1 = gmdate("Y",$rowe["eventpost"] + 7*3600);
                         $eventp2 = gmdate("m",$rowe["eventpost"] + 7*3600);
                         $src = $conf['rooturl']."images/event/".$eventp1."/".$eventp2."/thumb_".$rowe['eventpic'];
                         $html_img = "<img border=\"0\" src=\"{$src}\" width=\"100\" height=\"100\" style=\"border: 1px #C6C6C6 solid\"><br>";
                    }else{
                         $html_img = "";
                    }
                    if ($bgs % 2 != 0) $bg = "#EEEEEE";
                    else $bg = "#FFFFFF";
                    $html_event .= "<tr><td bgcolor=\"{$bg}\" align=\"center\" style=\"padding: 5 0 5 0\" onMouseOver=\"(window.status='{$eventt}'); return true\"><a href=\"{$linke}\" onMouseOver=\"(window.status='{$eventt}'); return true\" title=\"Xem chi ti&#7871;t\">".$html_img.$eventt."</a></td></tr>";
                    $bgs++;
             }
             $html_event .= "</table><br>";
             echo $html_event;
             $HTML = $html_event;
         } else
             $HTML = "";
         return $HTML ;
}

function show_addon_left() {
         global $func,$DB,$conf;
         // Bat dau Radio
         $HTML .=<<<EOF
                    <br><table width="100%" border="0" cellspacing="1" cellpadding="0" bgcolor="#242424">
                         <tr>
                            <td align="center" bgcolor="#81AD00" height="25" class="fontmenu">VN Radio News</td>
                         </tr>
                         <tr>
                            <td bgcolor="#EEEEEE">
                                <table width="100%" border="0" cellspacing="1" cellpadding="2">
                                   <tr>
                                      <td width="100%" align=left><font color="#111111"><font color="AA0000"><b><u>Ghi ch&#250;:</u></b></font> B&#7841;n c&#7847;n c&#224;i <b><a href="downloads/RealPlayer10GOLD.exe">RealPlayer</a></b> khi nghe nh&#7919;ng &#273;&#224;i Radio &#7903; d&#432;&#7899;i </font></td>
                                   </tr>
                                   <tr>
                                      <td width="100%" align=left bgcolor="#FFFFFF">
                                          <table width="100%" border="0" cellspacing="1" cellpadding="1">
                                             <tr>
                                                <td width="40" align="right" valign="middle" style="padding-right:4px"><img src="images/radio1.gif" width="38" height="11" border="0"></td>
                                                <td width="90" height="20"><font color="#5E5F61">(Vietnam)</font>
                                                    <br><a href="mms://210.245.0.62/vov1" target="_blank">VOV1</a>&nbsp;&nbsp;&nbsp;<a href="mms://210.245.0.62/vov2" target="_blank">VOV2</a>
                                                    <br><a href="mms://210.245.0.62/vov3" target="_blank">VOV3</a>&nbsp;&nbsp;&nbsp;<a href="mms://210.245.0.62/vov6" target="_blank">VOV6</a>
                                                </td>
                                             </tr>
                                             <tr>
                                                <td align="right" valign="middle" style="padding-right:4px"><img src="images/radio4.gif" width="38" height="11" border="0"></td>
                                                <td height="20"><font color="#5E5F61">(France)<br><a href="http://www.tv-radio.com/ondemand/rfi/mere/vietnamien/info/vietnamien_1500-1600-20k.asx" target="_blank">7:00</a></font></td>
                                             </tr>
                                             <tr>
                                                <td align="right" valign="middle" style="padding-right:4px"><img src="images/radio5.gif" width="38" height="11" border="0"></td>
                                                <td height="20"><font color="#5E5F61">(Japan) <a href="http://www.nhk.or.jp/rj/ram/en/vietnamese.ram" target="_blank">9:00</a></font></td>
                                             </tr>
                                             <tr>
                                                <td align="right" valign="middle" style="padding-right:4px"><img src="images/radio2.gif" width="38" height="11" border="0"></td>
                                                <td height="20"><font color="#5E5F61">(England)<br><a href="http://www.bbc.co.uk/vietnamese/rams/viet1430.ram" target="_blank">14:30</a>
                                                    &nbsp;&nbsp;&nbsp;<a href="http://www.bbc.co.uk/vietnamese/rams/viet2300.ram" target="_blank">23:00</a></font></td>
                                             </tr>
                                             <tr>
                                                <td align="right" valign="middle" style="padding-right:4px"><img src="images/radio3.gif" width="38" height="11" border="0"></td>
                                                <td height="20"><font color="#5E5F61">(Australia)<br><a href="http://www.vietfun.com/chat/news02.ram" target="_blank">13:30</a></font></td>
                                             </tr>
                                           </table>
                                         </td>
                                       </tr>
                                   </table>
                                 </td>
                              </tr>
                           </table><br>
EOF;
                            // Het radio
         echo $HTML;
}
function getsm(){
				global $conf;
				$i = 1;
				while ($i<=10) {
				$i ++;
				$seolink_tag = "<IMG onclick='grin(\"[sm]{$i}[/sm]\")' src=\"/demo/smiles/{$i}.gif\">";
				echo $sm;
				}
				$smHTML = $sm;
				return $smHTML;
                
       }
	 function get_subcat($catid) {
         global $func,$DB,$conf;
         $query = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE catalogid = $catid");
         
		 if ($cat_q= $DB->fetch_row($query))
		 {
                $catalogid=$cat_q['catalogid'];
				$catalogtype=$cat_q['type'];
				$catalogparent=$cat_q['parentid'];
				if ($catalogtype ==1) {
					$query1 = $DB->query("SELECT * FROM ".$conf['perfix']."catalog WHERE catalogid = $catalogparent");
					
					if ($cat_q1= $DB->fetch_row($query1))
					{	
						$html_id = $cat_q1["catalogid"];
						$html_name = "{$html_id}";
					}
				}
				
                
         }
         $HTML = $html_name;
         return $HTML ;
}

?>